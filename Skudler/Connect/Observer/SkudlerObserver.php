<?php

namespace Skudler\Connect\Observer;

include_once dirname(__DIR__ ). '/lib/autoload.php';

use Skudler\SkudlerAPI;

class SkudlerObserver {

    protected $config;
    protected $api;


    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $config)
    {
        $this->config = $config;
        $apiKey = $this->config->getValue('skudler_connect/api/key');
        $token  = $this->config->getValue('skudler_connect/api/token');

        $this->api = new SkudlerAPI($apiKey, $token);
    }

    protected function getProducts($items)
    {
        $products = array();

        foreach($items as $item) {

            $product = $this->getProductDescThumb($item->getProductId());

            $name = $this->getProductFullName($item);

            $products[] = array(
                'name'          => $name,
                'description'   => $product['shortDescription'],
                'qty'           => $item->getQty(),
                'price'         => $item->getPrice(),
                'thumbnail'     => $product['thumbnail']
            );
        }

        return $products;
    }

    protected function getProductDescThumb($productId)
    {
        $product = Mage::getModel('catalog/product')->load($productId);

        return array(
            'description'   => $product->getShortDescription(),
            'thumbnail'     => $product->getImageUrl(),
        );
    }

    protected function getProductFullName($item)
    {
        $name    = $item->getName();
        $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());

        if($options['attributes_info']) {
            foreach ($options['attributes_info'] as $opt){
                $name .= ', ' . $opt['label'] . ': ' . $opt['value'];
            }
        }
        return $name;
    }

}