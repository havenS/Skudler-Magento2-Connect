<?php

namespace Skudler\Connect\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject as Object;

class NewOrder extends SkudlerObserver implements ObserverInterface {

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(
            $this->config->getValue('skudler_connect/api/enable') && // Module enable
            $this->config->getValue('skudler_events/new_order/event_new_order_enable')// Event enable
        ) {

            $addCartEventId = $this->config->getValue('skudler_events/product_added/event_product_added_id');
            $eventId        = $this->config->getValue('skudler_events/new_order/event_new_order_id');
            $customer       = Mage::getSingleton('customer/session')->getCustomer();
            $items          = $observer->getEvent()->getOrder()->getAllVisibleItems();

            $info = array(
                'email'     => $customer->getEmail(),
                'firstname' => $customer->getFirstname(),
                'lastname'  => $customer->getLastname(),
                'products'  => $this->getProducts($items)
            );


            @$this->api->deleteSubscription($addCartEventId, $info);
            @$this->api->addSubscription($eventId, $info);
        }
    }

}