<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\Controller\Adminhtml\Image;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class UploadTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager;
    protected ?\MageSuite\Opengraph\Service\Processor\UploadImage $uploadProcessor;
    protected ?\Magento\Framework\Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->uploadProcessor = $this->objectManager->create(\MageSuite\Opengraph\Service\Processor\UploadImage::class);
        $this->filesystem = $this->objectManager->create(\Magento\Framework\Filesystem::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_Opengraph::Test/Integration/_files/cms_image.php
     */
    public function testItUploadFileCorrectly(): void
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
     * @magentoDataFixture MageSuite_Opengraph::Test/Integration/_files/cms_image.php
     */
    public function testUploadWithWrongData(): void
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
}
