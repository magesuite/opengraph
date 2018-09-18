<?php
namespace MageSuite\Opengraph\Service\Admin;

class CmsImageDataProvider
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageSuite\Opengraph\Service\CmsImageUrlProvider
     */
    protected $cmsImageUrlProvider;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider
    )
    {
        $this->storeManager = $storeManager;
        $this->cmsImageUrlProvider = $cmsImageUrlProvider;
    }

    /**
     * @param string $imageName
     * @param string $path
     * @return array
     */
    public function getImageData($imageName, $path)
    {
        if(is_array($imageName)){
            $imageName = $imageName[0]['name'] ?? null;
        }

        if(empty($imageName)){
            return [];
        }

        $path = 'media/' . $path . $imageName;
        $size = file_exists($path) ? filesize($path) : 0;

        $url = $this->cmsImageUrlProvider->getImageUrl($imageName);

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