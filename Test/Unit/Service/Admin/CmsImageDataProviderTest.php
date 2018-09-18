<?php
namespace MageSuite\Opengraph\Test\Unit\Service\Admin;

class CmsImageDataProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\Opengraph\Service\Admin\CmsImageDataProvider
     */
    protected $imageTeaserDataProvider;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->imageTeaserDataProvider = $this->objectManager->create(\MageSuite\Opengraph\Service\Admin\CmsImageDataProvider::class);

        $this->filesystem = $this->objectManager->create(\Magento\Framework\Filesystem::class);
    }
    
    public function testItReturnsImageDataCorrectly()
    {
        $response = $this->imageTeaserDataProvider->getImageData('magento_image.jpg', \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH);

        $this->assertArrayHasKey('url', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);

        $expectedPath = 'http://localhost/pub/media/' . \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH . 'magento_image.jpg';
        $this->assertEquals($expectedPath, $response[0]['url']);
        $this->assertEquals('magento_image.jpg', $response[0]['name']);
    }
}