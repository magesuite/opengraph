<?php
namespace MageSuite\Opengraph\Service;

class CmsImageUrlProvider
{
    const OPENGRAPH_CMS_IMAGE_PATH = 'opengraph/cms/';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    public function getImageUrl($image = null, $path = null)
    {
        if (!$image) {
            return '';
        }

        $path = $path ?? self::OPENGRAPH_CMS_IMAGE_PATH;

        $mediaUrl = $this->storeManager
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return sprintf('%s' . $path . '%s', $mediaUrl, $image);
    }
}