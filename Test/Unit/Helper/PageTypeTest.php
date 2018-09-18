<?php

namespace MageSuite\Opengraph\Test\Unit\Helper;

class PageTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Opengraph\Helper\PageType
     */
    private $pageType;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->pageType = $this->objectManager->get(\MageSuite\Opengraph\Helper\PageType::class);
        $this->request = $this->objectManager->get(\Magento\Framework\App\Request\Http::class);
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);

        $this->objectManager->get(\Magento\Framework\App\State::class)->setAreaCode('frontend');
    }

    public function testItReturnsDefaultType()
    {
        $pageType = $this->pageType->getPageType();

        $this->assertEquals(\MageSuite\Opengraph\Helper\PageType::DEFAULT_PAGE_TYPE, $pageType);
    }

    public function testItReturnsSpecificType()
    {
        $this->request->setRouteName('cms');

        $pageType = $this->pageType->getPageType();
        $this->assertEquals('cms', $pageType);

        $this->request->setRouteName('catalog');
        $this->registry->register('current_category', 'dummy');

        $pageType = $this->pageType->getPageType();
        $this->assertEquals('category', $pageType);

        $this->registry->register('product', 'dummy');

        $pageType = $this->pageType->getPageType();
        $this->assertEquals('product', $pageType);

        $this->request->setRouteName(null);
    }
}