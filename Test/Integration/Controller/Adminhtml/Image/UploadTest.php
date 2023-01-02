<?php
namespace MageSuite\Opengraph\Test\Integration\Controller\Adminhtml\Image;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class UploadTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\Opengraph\Service\Processor\UploadImage
     */
    protected $uploadProcessor;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->uploadProcessor = $this->objectManager->create(\MageSuite\Opengraph\Service\Processor\UploadImage::class);

        $this->filesystem = $this->objectManager->create(\Magento\Framework\Filesystem::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture moveCmsImageToTmp
     */
    public function testItUploadFileCorrectly()
    {
        $_FILES = [  // phpcs:ignore
            'og_image' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];
        $fileParameters = new \Laminas\Stdlib\Parameters();
        $fileParameters->set('og_image', $_FILES['og_image']);  // phpcs:ignore
        $this->getRequest()->setFiles($fileParameters);
        $this->dispatch('backend/opengraph/image/upload');

        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertTrue(isset($response['name']));

        $path = $this->filesystem
                ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
                ->getAbsolutePath() . \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH . $response['name'];
        $fileExist = file_exists($path);
        $this->assertTrue($fileExist);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture moveCmsImageToTmp
     */
    public function testUploadWithWrongData()
    {
        $_FILES = [ // phpcs:ignore
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../d/_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];
        $fileParameters = new \Laminas\Stdlib\Parameters();
        $fileParameters->set('brand_icon', $_FILES['brand_icon']);  // phpcs:ignore
        $this->getRequest()->setFiles($fileParameters);
        $this->dispatch('backend/opengraph/image/upload');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertTrue(isset($response['error']));
    }

    public static function moveCmsImageToTmp()
    {
        include __DIR__.'/../../../_files/cms_image.php';
    }
}
