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

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->dataProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\General::class);
        $this->pageConfig = $this->objectManager->get(\Magento\Framework\View\Page\Config::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store facebook/opengraph/default_image default/test.jpeg
     * @magentoConfigFixture current_store general/store_information/name store_name
     */
    public function testItReturnsCorrectTags()
    {
        $this->pageConfig->getTitle()->set('title');
        $this->pageConfig->setDescription('description');

        $tags = $this->dataProvider->getTags();

        $this->assertEquals('title', $tags['og:title']);
        $this->assertEquals('description', $tags['og:description']);
        $this->assertContains('default/test.jpeg', $tags['og:image']);
        $this->assertEquals('image/jpeg', $tags['og:image:type']);
        $this->assertEquals('store_name', $tags['og:image:alt']);
        $this->assertEquals('http://localhost/index.php/', $tags['og:url']);
        $this->assertEquals('en_US', $tags['og:locale']);
    }
}
