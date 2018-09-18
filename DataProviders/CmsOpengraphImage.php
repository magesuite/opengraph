<?php

namespace MageSuite\Opengraph\DataProviders;

class CmsOpengraphImage extends TagProvider implements TagProviderInterface
{
    /**
     * @var \Magento\Cms\Api\Data\PageInterface
     */
    protected $page;

    /**
     * @var \MageSuite\Opengraph\Service\CmsImageUrlProvider
     */
    protected $cmsImageUrlProvider;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    protected $tags = [];

    public function __construct(
        \Magento\Cms\Api\Data\PageInterface $page,
        \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory

    ) {
        $this->page = $page;
        $this->cmsImageUrlProvider = $cmsImageUrlProvider;
        $this->tagFactory = $tagFactory;
    }

    public function getTags()
    {
        if(!$this->page->getIdentifier()){
            return [];
        }

        $pageData = array_filter($this->page->getData());

        $this->addImageTag($pageData);

        return $this->tags;
    }

    private function addImageTag($pageData)
    {
        $opengraphImage = $pageData['og_image'] ?? null;

        if(!$opengraphImage){
            return;
        }

        $imageUrl = $this->cmsImageUrlProvider->getImageUrl($opengraphImage);

        if(!$imageUrl){
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $title = $pageData['og_title'] ?? $pageData['meta_title'] ?? $pageData['title'] ?? null;

        if(!$title){
            return;
        }

        $tag = $this->tagFactory->getTag('image:alt', $title);
        $this->addTag($tag);
    }
}