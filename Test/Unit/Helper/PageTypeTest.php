<?php

namespace MageSuite\Opengraph\Test\Unit\Helper;

class PageTypeTest extends \PHPUnit\Framework\TestCase
{
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

    public function setUp(): void
    {
        $this->request = $this->createStub(\Magento\Framework\App\Request\Http::class);
        $this->registry = new \Magento\Framework\Registry();
        $this->pageType = new \MageSuite\Opengraph\Helper\PageType(
            $this->createStub(\Magento\Framework\App\Helper\Context::class),
            $this->request,
            $this->registry
        );
    }

    public function testItReturnsDefaultType()
    {
        $pageType = $this->pageType->getPageType();

        $this->assertEquals(\MageSuite\Opengraph\Helper\PageType::DEFAULT_PAGE_TYPE, $pageType);
    }

    public function testItReturnsSpecificType()
    {
        $this->request->method('getRouteName')->willReturnOnConsecutiveCalls('cms', 'catalog', 'catalog');

        $pageType = $this->pageType->getPageType();
        $this->assertEquals('cms', $pageType);

        $this->registry->register('current_category', 'dummy');

        $pageType = $this->pageType->getPageType();
        $this->assertEquals('category', $pageType);

        $this->registry->register('product', 'dummy');

        $pageType = $this->pageType->getPageType();
        $this->assertEquals('product', $pageType);
    }
}
