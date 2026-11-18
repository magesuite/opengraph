<?php

namespace MageSuite\Opengraph\Test\Unit\Service\Admin;

class CmsImageDataProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testItReturnsImageDataCorrectly()
    {
        $imageName = 'magento_image.jpg';
        $path = \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH;
        $expectedUrl = 'http://localhost/media/' . $path . $imageName;
        $store = $this->createStub(\Magento\Store\Model\Store::class);
        $store->method('getBaseUrl')->willReturn('http://localhost/media/');
        $storeManager = $this->createStub(\Magento\Store\Model\StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);
        $fileHelper = $this->createMock(\Magento\Downloadable\Helper\File::class);
        $fileHelper->expects($this->once())
            ->method('ensureFileInFilesystem')
            ->with($path . $imageName)
            ->willReturn(false);
        $fileHelper->expects($this->never())->method('getFileSize');
        $imageDataProvider = new \MageSuite\Opengraph\Service\Admin\CmsImageDataProvider(
            $storeManager,
            new \MageSuite\Opengraph\Service\CmsImageUrlProvider($storeManager),
            $fileHelper
        );

        $response = $imageDataProvider->getImageData($imageName, $path);

        $this->assertSame([['url' => $expectedUrl, 'name' => $imageName, 'size' => 0]], $response);
    }
}
