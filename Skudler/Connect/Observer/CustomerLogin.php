<?php

namespace Skudler\Connect\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject as Object;

class CustomerLogin extends SkudlerObserver implements ObserverInterface {

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(
            $this->config->getValue('skudler_connect/api/enable') && // Module enable
            $this->config->getValue('skudler_events/login/event_login_enable')// Event enable
        ) {

            $eventId = $this->config->getValue('skudler_events/login/event_login_id');

            $customer = $observer->getEvent()->getCustomer();

            $info = array(
                'email'     => $customer->getEmail(),
                'firstname' => $customer->getFirstname(),
                'lastname'  => $customer->getLastname()
            );

            @$this->api->addSubscription($eventId, $info);
        }
    }

}