<?php

namespace MageSuite\Opengraph\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const FACEBOOK_OPENGRAPH_PATH = 'facebook/opengraph';
    const STORE_NAME_PATH = 'general/store_information/name';
    const CMS_HOMEPAGE_PATH = 'web/default/cms_home_page';

    protected $config;

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

        if (!isset($config['fb_app_id'])) {
            return null;
        }

        return $config['fb_app_id'];
    }

    public function geDefaultImage()
    {
        $config = $this->getConfig();

        if (!isset($config['default_image'])) {
            return null;
        }

        return $config['default_image'];
    }

    public function getStoreName()
    {
        return $this->scopeConfig->getValue(self::STORE_NAME_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getHomepageIdentifier()
    {
        return $this->scopeConfig->getValue(self::CMS_HOMEPAGE_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    private function getConfig()
    {
        if (!$this->config) {
            $this->config = $this->scopeConfig->getValue(self::FACEBOOK_OPENGRAPH_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->config;
    }
}
