<?php

namespace Skudler\Connect\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject as Object;

class ProductToCart extends SkudlerObserver implements ObserverInterface {

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(
            $this->config->getValue('skudler_connect/api/enable') && // Module enable
            $this->config->getValue('skudler_events/product_added/event_product_added_enable')// Event enable
        ) {

            $eventId = $this->config->getValue('skudler_events/product_added/event_product_added_id');
            $customer   = Mage::getSingleton('customer/session')->getCustomer();
            $items      = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();

            $info = array(
                'email'     => $customer->getEmail(),
                'firstname' => $customer->getFirstname(),
                'lastname'  => $customer->getLastname(),
                'products'  => $this->getProducts($items)
            );

            @$this->api->addSubscription($eventId, $info);
        }
    }

}