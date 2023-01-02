<?php
namespace MageSuite\Opengraph\Service\Admin;

class CmsImageDataProvider
{
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;
    protected \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider;
    protected \Magento\Downloadable\Helper\File $fileHelper;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider,
        \Magento\Downloadable\Helper\File $fileHelper
    ) {
        $this->storeManager = $storeManager;
        $this->cmsImageUrlProvider = $cmsImageUrlProvider;
        $this->fileHelper = $fileHelper;
    }

    /**
     * @param string $imageName
     * @param string $path
     * @return array
     */
    public function getImageData($imageName, $path)
    {
        if (is_array($imageName)) {
            $imageName = $imageName[0]['name'] ?? null;
        }

        if (empty($imageName)) {
            return [];
        }

        $url = $this->cmsImageUrlProvider->getImageUrl($imageName, $path);

        $file = $path . $imageName;
        $size = $this->fileHelper->ensureFileInFilesystem($file) ? $this->fileHelper->getFileSize($file) : 0;

        $imageData = [
            [
                'url' => $url,
                'name' => $imageName,
                'size' => $size
            ]
        ];

        return $imageData;
    }
}
