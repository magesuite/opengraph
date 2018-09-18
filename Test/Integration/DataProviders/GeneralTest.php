<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class GeneralTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Opengraph\DataProviders\General
     */
    private $dataProvider;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->dataProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\General::class);
        $this->pageConfig = $this->objectManager->get(\Magento\Framework\View\Page\Config::class);
    }


    public function testItReturnsCorrectTags()
    {
        $this->pageConfig->getTitle()->set('title');
        $this->pageConfig->setDescription('description');

        $tags = $this->dataProvider->getTags();

        $this->assertEquals('title', $tags['og:title']);
        $this->assertEquals('description', $tags['og:description']);
        $this->assertContains('images/logo.svg', $tags['og:image']);
        $this->assertEquals('http://localhost/index.php/', $tags['og:url']);
        $this->assertEquals('en_US', $tags['og:locale']);
    }
}