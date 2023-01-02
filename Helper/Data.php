<?php

namespace MageSuite\Opengraph\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected \Magento\Cms\Api\Data\PageInterface $page;
    protected \MageSuite\Opengraph\Helper\Configuration $configuration;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MageSuite\Opengraph\Helper\Configuration $configuration,
        \Magento\Cms\Api\Data\PageInterface $page,
    ) {
        parent::__construct($context);
        $this->configuration = $configuration;
        $this->page = $page;
    }

    public function isHomePage()
    {
        $homepageIdentifier = $this->configuration->getHomepageIdentifier();
        if (!$homepageIdentifier) {
            return false;
        }

        $currentPageIdentifier = $this->page->getIdentifier();
        if (!$currentPageIdentifier) {
            return false;
        }

        return $homepageIdentifier == $currentPageIdentifier;
    }
}
