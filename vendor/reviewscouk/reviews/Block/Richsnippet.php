<?php

namespace Reviewscouk\Reviews\Block;

use Magento\Backend as Backend;
use Magento\Directory as Directory;
use Magento\Framework as Framework;
use Reviewscouk\Reviews as Reviews;

class Richsnippet extends Framework\View\Element\Template
{
    private $dataHelper;
    private $configHelper;
    private $registry;
    private $store;

    protected $currency;

    public function __construct(
        Reviews\Helper\Config $config,
        Reviews\Helper\Data $dataHelper,
        Framework\View\Element\Template\Context $context,
        Framework\Registry $registry,
        Backend\Block\Template\Context $backend,

        Directory\Model\Currency $currency,

        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->configHelper = $config;
        $this->dataHelper   = $dataHelper;
        $this->registry     = $registry;
        $this->currency     = $currency;

        $this->store = $this->_storeManager->getStore();
    }

    public function autoRichSnippet()
    {
        $merchant_enabled = $this->configHelper->isMerchantRichSnippetsEnabled($this->store->getId());
        $product_enabled  = $this->configHelper->isProductRichSnippetsEnabled($this->store->getId());

        $current_product = $this->registry->registry('current_product');

        if ($current_product && $product_enabled) {

            $sku = $this->dataHelper->getProductSkus($current_product);

            $product = [
                'availability'  => $this->availability($current_product->getData('quantity_and_stock_status')['is_in_stock']),
                'price'         => $current_product->getData('price'),
                'priceCurrency' => $this->store->getDefaultCurrencyCode(),
            ];

            return $this->getRichSnippet($sku, $product);

        } else if ($merchant_enabled) {
            return $this->getRichSnippet();
        }
        return '';
    }

    public function getRichSnippet($sku = null, $product = null)
    {
        if (isset($sku) && is_array($sku)) {
            $sku = implode(';', $sku);
        }

        $region    = $this->configHelper->getRegion($this->store->getId());
        $storeName = $this->configHelper->getStoreId($this->store->getId());
        $url       = $region == 'us' ? 'https://widget.reviews.io/rich-snippet/dist.js' : 'https://widget.reviews.co.uk/rich-snippet/dist.js';

        $output = '<script src="' . $url . '"></script>';
        $output .= '<script>
        richSnippet({

            store: "' . $storeName . '",
            sku:"' . $sku . '",
            data:{
                type:"Offer",
                availability: "' . (isset($product['availability']) ? $product['availability'] : null) . '",
                price: "' . (isset($product['price']) ? $product['price'] : null) . '",
                priceCurrency: "' . (isset($product['priceCurrency']) ? $product['priceCurrency'] : null) . '"
            }

        })</script>';

        return $output;
    }

    /**
     * Get Availability for Rich Shippets
     * @param $availability -- boolean
     *
     * @return string
     */
    private function availability($availability = true)
    {
        if ($availability == false) {
            return 'http://schema.org/OutOfStock';
        }

        return 'http://schema.org/InStock';
    }
}
