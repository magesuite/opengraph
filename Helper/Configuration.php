<?php

namespace MageSuite\Opengraph\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const FACEBOOK_OPENGRAPH_PATH = 'facebook/opengraph';

    private $config;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled()
    {
        $config = $this->getConfig();

        return (boolean) $config['is_enabled'];
    }

    public function getFbAppId()
    {
        $config = $this->getConfig();

        if(!isset($config['fb_app_id'])){
            return null;
        }

        return $config['fb_app_id'];
    }

    private function getConfig()
    {
        if(!$this->config){
            $this->config = $this->scopeConfig->getValue(self::FACEBOOK_OPENGRAPH_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->config;
    }
}
