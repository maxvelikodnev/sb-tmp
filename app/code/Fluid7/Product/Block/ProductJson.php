<?php

namespace Fluid7\Product\Block;

use Exception;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\App\Request\Http;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Registry;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollection;
use Magento\Review\Model\Review;
use Magento\Review\Model\ReviewFactory;
use Magento\Search\Helper\Data as SearchHelper;
use Mageplaza\Seo\Helper\Data as HelperData;
use Mageplaza\Seo\Model\Config\Source\PriceValidUntil;

class ProductJson extends Template
{
    private $request;
    private $helperData;
    private $stockItemRepository;
    private $registry;
    private $reviewFactory;
    private $productFactory;
    private $messageManager;
    private $stockState;
    private $_searchHelper;
    private $_priceHelper;
    private $_dateTime;
    private $_timeZoneInterface;
    private $_reviewCollection;
    private $_moduleManager;

    public function __construct(
        Http $request,
        HelperData $helpData,
        StockItemRepository $stockItemRepository,
        Registry $registry,
        ReviewFactory $reviewFactory,
        UrlInterface $urlBuilder,
        ProductFactory $productFactory,
        ManagerInterface $messageManager,
        StockRegistryInterface $stockState,
        SearchHelper $searchHelper,
        PriceHelper $priceHelper,
        Manager $eventManager,
        DateTime $dateTime,
        TimezoneInterface $timeZoneInterface,
        ReviewCollection $reviewCollection,
        ModuleManager $moduleManager,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->request = $request;
        $this->helperData = $helpData;
        $this->stockItemRepository = $stockItemRepository;
        $this->registry = $registry;
        $this->reviewFactory = $reviewFactory;
        $this->_urlBuilder = $urlBuilder;
        $this->productFactory = $productFactory;
        $this->messageManager = $messageManager;
        $this->stockState = $stockState;
        $this->_searchHelper = $searchHelper;
        $this->_priceHelper = $priceHelper;
        $this->_eventManager = $eventManager;
        $this->_dateTime = $dateTime;
        $this->_timeZoneInterface = $timeZoneInterface;
        $this->_reviewCollection = $reviewCollection;
        $this->_moduleManager = $moduleManager;
    }

    public function getCurrentCategory()
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }

    public function getCurrentCategoryId()
    {
        return $this->getCurrentCategory()->getId();
    }

    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        return $collection;
    }

    public function ldJson()
    {
        return $this->showProductStructuredData();
    }

    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function showProductStructuredData()
    {
        if ($currentProduct = $this->getProduct()) {
            try {
                $productId = $currentProduct->getId() ?: $this->request->getParam('id');

                $product = $this->productFactory->create()->load($productId);
                $availability = $product->isAvailable() ? 'InStock' : 'OutOfStock';
                $stockItem = $this->stockState->getStockItem(
                    $product->getId(),
                    $product->getStore()->getWebsiteId()
                );
                $priceValidUntil = $currentProduct->getSpecialToDate();

                $modelValue = $product->getResource()
                    ->getAttribute($this->helperData->getRichsnippetsConfig('model_value'));

                if ($modelValue) {
                    $modelValue = $modelValue->getFrontend()->getValue($product);
                }

                $modelName = $this->helperData->getRichsnippetsConfig('model_name');

                $productStructuredData = [
                    '@context' => 'http://schema.org/',
                    '@type' => 'Product',
                    'name' => $currentProduct->getName(),
                    'description' => trim(strip_tags($currentProduct->getDescription())),
                    'sku' => $currentProduct->getSku(),
                    'url' => $currentProduct->getProductUrl(),
                    'image' => $this->getUrl('pub/media/catalog') . 'product' . $currentProduct->getImage(),
                    'offers' => [
                        '@type' => 'Offer',
                        'priceCurrency' => $this->_storeManager->getStore()->getCurrentCurrencyCode(),
                        'price' => $currentProduct->getPriceInfo()->getPrice('final_price')->getValue(),
                        'itemOffered' => $stockItem->getQty(),
                        'availability' => 'http://schema.org/' . $availability,
                        'url' => $currentProduct->getProductUrl()
                    ],
                    $modelName => $modelValue ?: $modelName
                ];
                $productStructuredData = $this->addProductStructuredDataByType(
                    $currentProduct->getTypeId(),
                    $currentProduct,
                    $productStructuredData
                );

                $priceValidType = $this->helperData->getRichsnippetsConfig('price_valid_until');
                if (!empty($priceValidUntil)) {
                    $productStructuredData['offers']['priceValidUntil'] = $priceValidUntil;
                } elseif ($priceValidType !== 'none') {
                    $time = $this->_dateTime->gmtTimestamp();

                    switch ($priceValidType) {
                        case PriceValidUntil::PLUS_7:
                            $time += 604800;
                            break;
                        case PriceValidUntil::PLUS_30:
                            $time += 2592000;
                            break;
                        case PriceValidUntil::PLUS_60:
                            $time += 5184000;
                            break;
                        case PriceValidUntil::PLUS_1_YEAR:
                            $time += 31536000;
                            break;
                        default:
                            $time = $this->helperData->getRichsnippetsConfig('price_valid_until_custom');
                            break;
                    }

                    $productStructuredData['offers']['priceValidUntil'] = $priceValidType === 'custom'
                        ? $time
                        : date('Y-m-d', $time);
                }

                if (!$this->_moduleManager->isEnabled('Mageplaza_Shopbybrand')) {
                    $brandValue = $product->getResource()
                        ->getAttribute($this->helperData->getRichsnippetsConfig('brand'));

                    if ($brandValue) {
                        $brandValue = $brandValue->getFrontend()->getValue($product);
                    }

                    $productStructuredData['brand']['@type'] = 'Thing';
                    $productStructuredData['brand']['name'] = $brandValue ?: 'Brand';
                }

                $collection = $this->_reviewCollection->create()
                    ->addStatusFilter(
                        Review::STATUS_APPROVED
                    )->addEntityFilter(
                        'product',
                        $product->getId()
                    )->setDateOrder();
                if ($collection->getData()) {
                    foreach ($collection->getData() as $review) {
                        $productStructuredData['review'][] = [
                            '@type' => 'Review',
                            'author' => $review['nickname']
                        ];
                    }
                } elseif ($this->helperData->getRichsnippetsConfig('aggregate_rating') === '1') {
                    $productStructuredData['review'][] = [
                        '@type' => 'Review',
                        'author' => $this->helperData->getRichsnippetsConfig('review_author')
                    ];
                }

                if ($this->getReviewCount()) {
                    $productStructuredData['aggregateRating']['@type'] = 'AggregateRating';
                    $productStructuredData['aggregateRating']['bestRating'] = 100;
                    $productStructuredData['aggregateRating']['worstRating'] = 0;
                    $productStructuredData['aggregateRating']['ratingValue'] = $this->getRatingSummary();
                    $productStructuredData['aggregateRating']['reviewCount'] = $this->getReviewCount();
                } elseif ($this->helperData->getRichsnippetsConfig('aggregate_rating')) {
                    $productStructuredData['aggregateRating']['@type'] = 'AggregateRating';
                    $productStructuredData['aggregateRating']['bestRating'] = 100;
                    $productStructuredData['aggregateRating']['worstRating'] = 0;
                    $productStructuredData['aggregateRating']['ratingValue'] = $this->helperData->getRichsnippetsConfig('rating_value');
                    $productStructuredData['aggregateRating']['reviewCount'] = $this->helperData->getRichsnippetsConfig('review_count');
                }

                $objectStructuredData = new DataObject(['mpdata' => $productStructuredData]);
                $this->_eventManager->dispatch(
                    'mp_seo_product_structured_data',
                    ['structured_data' => $objectStructuredData]
                );
                $productStructuredData = $objectStructuredData->getMpdata();

                return json_encode($productStructuredData, JSON_UNESCAPED_SLASHES);
            } catch (Exception $e) {
                $this->messageManager->addError(__('Can not add structured data'));
            }
        }
    }

    public function getBundleProductStructuredData($currentProduct, $productStructuredData)
    {
        $productStructuredData['offers']['@type'] = 'AggregateOffer';
        $productStructuredData['offers']['highPrice'] = $currentProduct->getPriceInfo()->getPrice('regular_price')->getMaximalPrice()->getValue();
        $productStructuredData['offers']['lowPrice'] = $currentProduct->getPriceInfo()->getPrice('regular_price')->getMinimalPrice()->getValue();
        unset($productStructuredData['offers']['price']);
        $offerData = [];
        $typeInstance = $currentProduct->getTypeInstance();
        $childProductCollection = $typeInstance->getSelectionsCollection(
            $typeInstance->getOptionsIds($currentProduct),
            $currentProduct
        );
        foreach ($childProductCollection as $child) {
            $imageUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . 'catalog/product' . $child->getImage();

            $offerData[] = [
                '@type' => 'Offer',
                'name' => $child->getName(),
                'price' => $this->_priceHelper->currency($child->getPrice(), false),
                'sku' => $child->getSku(),
                'image' => $imageUrl
            ];
        }
        if (!empty($offerData)) {
            $productStructuredData['offers']['offers'] = $offerData;
            $productStructuredData['offers']['offerCount'] = count($offerData);
        }

        return $productStructuredData;
    }

    public function getConfigurableProductStructuredData($currentProduct, $productStructuredData)
    {
        $productStructuredData['offers']['@type'] = 'AggregateOffer';
        $productStructuredData['offers']['highPrice'] = $currentProduct->getPriceInfo()->getPrice('regular_price')->getMaxRegularAmount()->getValue();
        $productStructuredData['offers']['lowPrice'] = $currentProduct->getPriceInfo()->getPrice('regular_price')->getMinRegularAmount()->getValue();
        $offerData = [];
        $typeInstance = $currentProduct->getTypeInstance();
        $childProductCollection = $typeInstance->getUsedProductCollection($currentProduct)->addAttributeToSelect('*');
        foreach ($childProductCollection as $child) {
            $imageUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . 'catalog/product' . $child->getImage();

            $offerData[] = [
                '@type' => 'Offer',
                'name' => $child->getName(),
                'price' => $this->_priceHelper->currency($child->getPrice(), false),
                'sku' => $child->getSku(),
                'image' => $imageUrl
            ];
        }
        if (!empty($offerData)) {
            $productStructuredData['offers']['offers'] = $offerData;
            $productStructuredData['offers']['offerCount'] = count($offerData);
        }

        return $productStructuredData;
    }

    public function getDownloadableProductStructuredData($currentProduct, $productStructuredData)
    {
        $productStructuredData['offers']['@type'] = 'AggregateOffer';

        $typeInstance = $currentProduct->getTypeInstance();
        $childProductCollection = $typeInstance->getLinks($currentProduct);
        $childrenPrice = [];
        foreach ($childProductCollection as $child) {
            $offerData[] = [
                '@type' => 'Offer',
                'name' => $child->getTitle(),
                'price' => $this->_priceHelper->currency($child->getPrice(), false)
            ];
            $childrenPrice[] = $this->_priceHelper->currency($child->getPrice(), false);
        }
        $productStructuredData['offers']['highPrice'] = array_sum($childrenPrice);
        $productStructuredData['offers']['lowPrice'] = min($childrenPrice);

        if (!empty($offerData)) {
            $productStructuredData['offers']['offers'] = $offerData;
            $productStructuredData['offers']['offerCount'] = count($offerData);
        }

        return $productStructuredData;
    }

    public function getGroupedProductStructuredData($currentProduct, $productStructuredData)
    {
        $productStructuredData['offers']['@type'] = 'AggregateOffer';
        $childrenPrice = [];
        $offerData = [];
        $typeInstance = $currentProduct->getTypeInstance();
        $childProductCollection = $typeInstance->getAssociatedProducts($currentProduct);
        foreach ($childProductCollection as $child) {
            $imageUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . 'catalog/product' . $child->getImage();

            $offerData[] = [
                '@type' => 'Offer',
                'name' => $child->getName(),
                'price' => $this->_priceHelper->currency($child->getPrice(), false),
                'sku' => $child->getSku(),
                'image' => $imageUrl
            ];
            $childrenPrice[] = $this->_priceHelper->currency($child->getPrice(), false);
        }

        $productStructuredData['offers']['highPrice'] = array_sum($childrenPrice);
        $productStructuredData['offers']['lowPrice'] = min($childrenPrice);
        unset($productStructuredData['offers']['price']);

        if (!empty($offerData)) {
            $productStructuredData['offers']['offers'] = $offerData;
            $productStructuredData['offers']['offerCount'] = count($offerData);
        }

        return $productStructuredData;
    }

    public function addProductStructuredDataByType($productType, $currentProduct, $productStructuredData)
    {
        switch ($productType) {
            case 'grouped':
                $productStructuredData = $this->getGroupedProductStructuredData(
                    $currentProduct,
                    $productStructuredData
                );
                break;
            case 'bundle':
                $productStructuredData = $this->getBundleProductStructuredData($currentProduct, $productStructuredData);
                break;
            case 'downloadable':
                $productStructuredData = $this->getDownloadableProductStructuredData(
                    $currentProduct,
                    $productStructuredData
                );
                break;
            case 'configurable':
                $productStructuredData = $this->getConfigurableProductStructuredData(
                    $currentProduct,
                    $productStructuredData
                );
                break;
        }

        return $productStructuredData;
    }
}
