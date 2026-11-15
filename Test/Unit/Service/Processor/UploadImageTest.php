<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Unit\Service\Processor;

class UploadImageTest extends \PHPUnit\Framework\TestCase
{
    public function testItBuildsPreviewUrlFromRelativePath(): void
    {
        $mediaPath = '/var/www/html/pub/media/';
        $relativePath = \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH;

        $uploader = $this->getMockBuilder(\Magento\MediaStorage\Model\File\Uploader::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'setAllowedExtensions',
                'setAllowRenameFiles',
                'setFilesDispersion',
                'save',
                'getUploadedFileName'
            ])
            ->getMock();
        $uploader->method('save')->with($mediaPath . $relativePath)->willReturn([
            'tmp_name' => '/tmp/phpabc',
            'path' => $mediaPath . $relativePath,
            'file' => 'image.jpg'
        ]);
        $uploader->method('getUploadedFileName')->willReturn('image.jpg');

        $uploaderFactory = $this->createMock(\Magento\MediaStorage\Model\File\UploaderFactory::class);
        $uploaderFactory->method('create')->willReturn($uploader);

        $mediaDirectory = $this->createMock(\Magento\Framework\Filesystem\Directory\ReadInterface::class);
        $mediaDirectory->method('getAbsolutePath')->with($relativePath)->willReturn($mediaPath . $relativePath);
        $filesystem = $this->createMock(\Magento\Framework\Filesystem::class);
        $filesystem->method('getDirectoryRead')->willReturn($mediaDirectory);

        $store = $this->createMock(\Magento\Store\Model\Store::class);
        $store->method('getBaseUrl')->willReturn('https://example.com/media/');
        $storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);

        $request = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $request->method('getFiles')->with('og_image')->willReturn(['name' => 'image.jpg']);

        $processor = new \MageSuite\Opengraph\Service\Processor\UploadImage(
            $uploaderFactory,
            $this->createMock(\Magento\Framework\App\Filesystem\DirectoryList::class),
            $filesystem,
            $storeManager,
            new \MageSuite\Opengraph\Service\CmsImageUrlProvider($storeManager),
            $request
        );

        $result = $processor->processUpload('og_image', $relativePath);

        $this->assertSame('https://example.com/media/opengraph/cms/image.jpg', $result['url']);
    }
}
