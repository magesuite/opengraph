<?php

namespace MageSuite\Opengraph\DataProviders;

class Cms extends TagProvider implements TagProviderInterface
{
    const DEFAULT_TYPE = 'article';
    const HOMEPAGE_TYPE = 'website';

    /**
     * @var \Magento\Cms\Api\Data\PageInterface
     */
    protected $page;

    /**
     * @var \MageSuite\Opengraph\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    protected $tags = [];

    public function __construct(
        \Magento\Cms\Api\Data\PageInterface $page,
        \MageSuite\Opengraph\Helper\Data $dataHelper,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory

    ) {
        $this->page = $page;
        $this->dataHelper = $dataHelper;
        $this->tagFactory = $tagFactory;
    }

    public function getTags()
    {
        if(!$this->page->getIdentifier()){
            return [];
        }

        $pageData = array_filter($this->page->getData());

        $this->addTitleTag($pageData);
        $this->addDescriptionTag($pageData);
        $this->addTypeTag($pageData);

        return $this->tags;
    }

    private function addTitleTag($pageData)
    {
        $title = $pageData['og_title'] ?? $pageData['meta_title'] ?? $pageData['title'] ?? null;

        if(!$title){
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function addDescriptionTag($pageData)
    {
        $description = $pageData['og_description'] ?? $pageData['meta_description'] ?? null;

        if(!$description){
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function addTypeTag($pageData)
    {
        $type = $pageData['og_type'] ?? null;

        if(!$type){
            $isHomepage = $this->dataHelper->isHomePage();
            $type = $isHomepage ? self::HOMEPAGE_TYPE : self::DEFAULT_TYPE;
        }

        $tag = $this->tagFactory->getTag('type', $type);

        $this->addTag($tag);
    }
}