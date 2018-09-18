<?php

namespace MageSuite\Opengraph\DataProviders;

class CategoryOpengraphImage extends TagProvider implements TagProviderInterface
{
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

        $this->addImageTag();

        return $this->tags;
    }

    private function addImageTag()
    {
        $category = $this->registry->registry('current_category');

        $opengraphImage = $category->getOgImage() ?? null;

        if(!$opengraphImage){
            return;
        }

        $imageUrl = $category->getImageUrl('og_image');

        if(!$imageUrl){
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $categoryData = array_filter($category->getData());
        $title = $categoryData['og_title'] ?? $categoryData['meta_title'] ?? $categoryData['name'] ?? null;

        if(!$title){
            return;
        }

        $tag = $this->tagFactory->getTag('image:alt', $title);
        $this->addTag($tag);
    }
}