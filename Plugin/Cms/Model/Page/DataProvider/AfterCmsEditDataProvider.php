<?php
namespace MageSuite\Opengraph\Plugin\Cms\Model\Page\DataProvider;

class AfterCmsEditDataProvider
{
    /**
     * @var \MageSuite\Opengraph\Service\Admin\CmsImageDataProvider
     */
    protected $cmsImageDataProvider;

    public function __construct(\MageSuite\Opengraph\Service\Admin\CmsImageDataProvider $cmsImageDataProvider)
    {
        $this->cmsImageDataProvider = $cmsImageDataProvider;
    }

    public function afterGetData(\Magento\Cms\Model\Page\DataProvider $subject, $result)
    {
        if (!$result) {
            return $result;
        }

        $pageData = reset($result);

        if (!isset($pageData['og_image'])) {
            return $result;
        }

        $imageDataArray = $this->cmsImageDataProvider->getImageData($pageData['og_image'], \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH);

        $result[$pageData['page_id']]['og_image'] = $imageDataArray;

        return $result;
    }
}
