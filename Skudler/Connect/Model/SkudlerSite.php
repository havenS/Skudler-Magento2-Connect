<?php

namespace Skudler\Connect\Model;

include_once dirname(__DIR__ ). '/lib/autoload.php';

use Skudler\SkudlerAPI;

/**
 * Connect Config model
 */
class SkudlerSite implements \Magento\Framework\Option\ArrayInterface {

    protected $skudler;
    protected $siteId;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $apiKey         = $scopeConfig->getValue('skudler_connect/api/key');
        $token          = $scopeConfig->getValue('skudler_connect/api/token');

        $this->skudler = new SkudlerAPI($apiKey, $token);

    }

    protected function getSites()
    {
        $call = $this->skudler->getSites();

        return $call;
    }

    public function toOptionArray()
    {
        $sitesToOptions = array();
        $sites = $this->getSites() || array();

        if(is_array($sites)) {
            foreach ($sites as $site) {
                $sitesToOptions[] = array(
                    'value' => $site->_id,
                    'label' => $site->name,
                );
            }
        }

        return $sitesToOptions;
    }

}