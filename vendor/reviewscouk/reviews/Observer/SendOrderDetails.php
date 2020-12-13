<?php

namespace Reviewscouk\Reviews\Observer;

use Magento\Framework as Framework;
use Reviewscouk\Reviews as Reviews;
use Magento\Catalog as Catalog;
use Magento\ConfigurableProduct as ConfigurableProduct;
use Magento\Store as Store;

class SendOrderDetails implements Framework\Event\ObserverInterface
{

    private $configHelper;
    private $apiModel;
    private $productModel;
    private $imageHelper;
    private $configProductModel;

    public function __construct(
        Reviews\Helper\Config $config,
        Reviews\Model\Api $api,
        Catalog\Model\Product $product,
        Catalog\Helper\Image $image,
        ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurable
    ) {
        $this->configHelper = $config;
        $this->apiModel = $api;
        $this->productModel = $product;
        $this->imageHelper = $image;
        $this->configProductModel = $configurable;
    }

    public function execute(Framework\Event\Observer $observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();
        $this->dispatchNotification($order);
    }

    public function dispatchNotification($order)
    {
        try {
            $magento_store_id = $order->getStoreId();

            if ($this->configHelper->getStoreId($magento_store_id) && $this->configHelper->getApiKey($magento_store_id) && $this->configHelper->isMerchantReviewsEnabled($magento_store_id)) {

                $name = $order->getCustomerName();
                if ($name == 'Guest') {
                    $name = $order->getBillingAddress()->getFirstName();
                }

                $merchantResponse = $this->apiModel->apiPost('merchant/invitation', [
                    'source'       => 'magento',
                    'name'         => $name,
                    'email'        => $order->getCustomerEmail(),
                    'order_id'     => $order->getRealOrderId(),
                    'country_code' => $order->getShippingAddress()->getCountryId(),
                ], $magento_store_id);
                $this->apiModel->addStatusMessage($merchantResponse, "Merchant Review Invitation");
            }

            if ($this->configHelper->getStoreId($magento_store_id) && $this->configHelper->getApiKey($magento_store_id) && $this->configHelper->isProductReviewsEnabled($magento_store_id)) {
                $items = $order->getAllVisibleItems();
                $p = array();
                foreach ($items as $item) {
                    
                    if ($this->configHelper->isUsingGroupSkus($magento_store_id)) {
                        // If product is part of a configurable product, use the configurable product details.
                        if ($item->getProduct()->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                            $productId = $item->getProduct()->getId();
                            $item = $this->productModel->load($productId);
                        }
                    }
                    $imageUrl = $this->imageHelper->init($item, 'product_page_image_large')->getUrl();
                    $p[] = [
                        'image' => $imageUrl,
                        'id' => $item->getId(),
                        'sku' => $item->getSku(),
                        'name' => $item->getName(),
                        'pageUrl' => $item->getProductUrl()
                    ];
                }

                $name = $order->getCustomerName();
                if ($name == 'Guest') {
                    $name = $order->getBillingAddress()->getFirstName();
                }
                
                $productResponse = $this->apiModel->apiPost('product/invitation', [
                    'source'       => 'magento',
                    'name'         => $name,
                    'email'        => $order->getCustomerEmail(),
                    'order_id'     => $order->getRealOrderId(),
                    'country_code' => $order->getShippingAddress()->getCountryId(),
                    'products'     => $p
                ], $magento_store_id);

                $this->apiModel->addStatusMessage($productResponse, "Product Review Invitation");

            }
        } catch (\Exception $e) {
        }
    }
}
