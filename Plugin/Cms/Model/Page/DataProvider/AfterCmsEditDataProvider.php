<?php
namespace MageSuite\Opengraph\Plugin\Cms\Model\Page\DataProvider;

class AfterCmsEditDataProvider
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \MageSuite\Opengraph\Service\Admin\CmsImageDataProvider
     */
    protected $cmsImageDataProvider;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\Opengraph\Service\Admin\CmsImageDataProvider $cmsImageDataProvider
    ) {
        $this->request = $request;
        $this->cmsImageDataProvider = $cmsImageDataProvider;
    }

    public function afterGetData(\Magento\Cms\Model\Page\DataProvider $subject, $result)
    {
        if (!$result) {
            return $result;
        }

        $currentPageId = $this->request->getParam('page_id', 0);

        if (!$currentPageId || !isset($result[$currentPageId])) {
            return $result;
        }

        $pageData = $result[$currentPageId];

        if (!isset($pageData['og_image'])) {
            return $result;
        }

        $imageDataArray = $this->cmsImageDataProvider->getImageData($pageData['og_image'], \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH);

        $result[$pageData['page_id']]['og_image'] = $imageDataArray;

        return $result;
    }
}
