<?php

namespace Fluid7\Address\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Framework\App\ObjectManager;

class Main extends Template
{
    protected $scopeConfig;
    protected $logo;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Logo $logo,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->logo = $logo;
    }

    public function ldJson()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $json = [ "@context" => "https://schema.org", "@type" => "LocalBusiness" ];
        $json['name'] = $this->getValue('name');
        $json['image'] = $this->logo->getLogoSrc();
        $json['@id'] = '';
        $json['url'] = $storeManager->getStore()->getBaseUrl();
        $json['telephone'] = $this->getValue('phone');
        $json['address']['@type'] = "PostalAddress";

        $street = [];
        $street['street_line1'] = $this->getValue('street_line1');
        $street['street_line2'] = $this->getValue('street_line2');
        $street = array_diff($street, array(''));

        $json['address']['streetAddress'] = implode(", ", $street);
        $json['address']['addressLocality'] = $this->getValue('city');
        $json['address']['postalCode'] = $this->getValue('postcode');
        $json['address']['addressCountry'] = $this->getValue('country_id');
        $json['address'] = array_diff($json['address'], array(''));

        /*
         * Price Range
         */
        $objectManager = ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('catalog_product_index_price');
        $min = $connection->fetchOne("SELECT MIN(price) as price_min FROM " . $tableName . " WHERE price > 0;");
        $max = $connection->fetchOne("SELECT MAX(price) as price_max FROM " . $tableName . " WHERE price > 0;");
        $currency = $objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface');
        $currency_code = $currency->getCurrency()->getCurrencyCode();

        $json['priceRange'] = $min . " - " . $max . "" . $currency_code;

        return '<script type="application/ld+json">' . json_encode($json) . '</script>';
    }
    public function localAddress()
    {
        $address = [];
        $address['name'] = $this->getValue('name');
        $address['street_line1'] = $this->getValue('street_line1');
        $address['street_line2'] = $this->getValue('street_line2');
        $address['city'] = $this->getValue('city');
        $address['postcode'] = $this->getValue('postcode');
        $address = array_diff($address, array(''));

        return  implode("<br />", $address);
    }

    public function getValue($name)
    {
        return $this->scopeConfig->getValue(
            'general/store_information/' . $name,
            ScopeInterface::SCOPE_STORE
        );
    }
}