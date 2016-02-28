<?php

namespace Skudler\Connect\Model;

include_once dirname(__DIR__ ). '/lib/autoload.php';

use Skudler\SkudlerAPI;

/**
 * Connect Config model
 */
class SkudlerEvent implements \Magento\Framework\Option\ArrayInterface {

    protected $skudler;
    protected $siteId;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $apiKey         = $scopeConfig->getValue('skudler_connect/api/key');
        $token          = $scopeConfig->getValue('skudler_connect/api/token');
        $this->siteId   = $scopeConfig->getValue('skudler_connect/api/skudler_site');

        $this->skudler = new SkudlerAPI($apiKey, $token);
    }

    protected function getEvents()
    {
        $call = $this->skudler->getEvents($this->siteId);

        return $call;
    }

    public function toOptionArray()
    {
        $eventsToOptions = array();
        $events = $this->getEvents();

        if(is_array($events)) {
            foreach ($events as $event) {
                $eventsToOptions[] = array(
                    'value' => $event->_id,
                    'label' => $event->name,
                );
            }
        }

        return $eventsToOptions;
    }

}