<?php

namespace MageSuite\Opengraph\DataProviders;

class Category extends TagProvider implements TagProviderInterface
{
    const DEFAULT_CATEGORY_OPENGRAPH_TYPE = 'website';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    protected $tags = [];

    public function __construct(
        \Magento\Framework\Registry $registry,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory

    ) {
        $this->registry = $registry;
        $this->tagFactory = $tagFactory;
    }

    public function getTags()
    {
        $category = $this->registry->registry('current_category');

        if(!$category or !$category->getId()){
            return [];
        }

        $categoryData = array_filter($category->getData());

        $this->addTitleTag($categoryData);
        $this->addDescriptionTag($categoryData);
        $this->addTypeTag($categoryData);

        return $this->tags;
    }

    private function addTitleTag($categoryData)
    {
        $title = $categoryData['og_title'] ?? $categoryData['meta_title'] ?? $categoryData['name'] ?? null;

        if(!$title){
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function addDescriptionTag($categoryData)
    {
        $description = $categoryData['og_description'] ?? $categoryData['meta_description'] ?? null;

        if(!$description){
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function addTypeTag($categoryData)
    {
        $type = $categoryData['og_type'] ?? self::DEFAULT_CATEGORY_OPENGRAPH_TYPE;

        $tag = $this->tagFactory->getTag('type', $type);

        $this->addTag($tag);
    }
}