<?php

namespace MageSuite\Opengraph\DataProviders;

class CmsOpengraphImage extends TagProvider implements TagProviderInterface
{
    /**
     * @var \Magento\Cms\Api\Data\PageInterface
     */
    protected $page;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \MageSuite\Opengraph\Service\CmsImageUrlProvider
     */
    protected $cmsImageUrlProvider;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    /**
     * @var \MageSuite\Opengraph\Helper\Mime
     */
    protected $mimeHelper;

    protected $tags = [];

    public function __construct(
        \Magento\Cms\Api\Data\PageInterface $page,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository,
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        \MageSuite\Opengraph\Helper\Mime $mimeHelper
    ) {
        $this->page = $page;
        $this->pageRepository = $pageRepository;
        $this->request = $request;
        $this->cmsImageUrlProvider = $cmsImageUrlProvider;
        $this->tagFactory = $tagFactory;
        $this->mimeHelper = $mimeHelper;
    }

    public function getTags()
    {
        if (!$this->page->getIdentifier() && !$this->request->getParam('page_id')) {
            return [];
        }

        $pageData = $this->page->getData();
        if (empty($pageData)) {
            $page = $this->pageRepository->getById($this->request->getParam('page_id'));
            $pageData = $page->getData();
        }

        $pageData = array_filter($pageData);

        $this->addImageTag($pageData);

        return $this->tags;
    }

    private function addImageTag($pageData)
    {
        $opengraphImage = $pageData['og_image'] ?? $pageData['cms_image_teaser'] ?? null;

        if (!$opengraphImage) {
            return;
        }

        $imageUrl = $this->cmsImageUrlProvider->getImageUrl($opengraphImage);

        if (!$imageUrl) {
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $mimeType = $this->mimeHelper->getMimeType($imageUrl);

        if ($mimeType) {
            $tag = $this->tagFactory->getTag('image:type', $mimeType);
            $this->addTag($tag);
        }

        $title = $pageData['og_title'] ?? $pageData['meta_title'] ?? $pageData['title'] ?? null;

        if ($title) {
            $tag = $this->tagFactory->getTag('image:alt', $title);
            $this->addTag($tag);
        }
    }
}
