<?php

namespace MageSuite\Opengraph\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CMS_HOMEPAGE_PATH = 'web/default/cms_home_page';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Cms\Api\Data\PageInterface
     */
    protected $page;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Cms\Api\Data\PageInterface $page
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfig;
        $this->page = $page;
    }

    public function isHomePage()
    {
        $homepageIdentifier = $this->scopeConfig->getValue(self::CMS_HOMEPAGE_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(!$homepageIdentifier){
            return false;
        }

        $currentPageIdentifier = $this->page->getIdentifier();

        if(!$currentPageIdentifier){
            return false;
        }

        return $homepageIdentifier == $currentPageIdentifier;
    }
}
