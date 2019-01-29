<?php

namespace MageSuite\Opengraph\DataProviders;

class Product extends TagProvider implements TagProviderInterface
{
    const DEFAULT_PRODUCT_OPENGRAPH_TYPE = 'product';
    const DEFAULT_PRODUCT_IMAGE_ID = 'product_base_image';
    const OPENGRAPH_PRODUCT_IMAGE_ID = 'og_image';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

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
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Image $imageHelper,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        \MageSuite\Opengraph\Helper\Mime $mimeHelper

    ) {
        $this->registry = $registry;
        $this->imageHelper = $imageHelper;
        $this->tagFactory = $tagFactory;
        $this->mimeHelper = $mimeHelper;
    }

    public function getTags()
    {
        $product = $this->registry->registry('product');

        if(!$product or !$product->getId()){
            return [];
        }

        $productData = array_filter($product->getData());

        $this->addTitleTag($productData);
        $this->addDescriptionTag($productData);
        $this->addImageTag($product);
        $this->addTypeTag($productData);
        $this->addUrlTag($product);

        return $this->tags;
    }

    private function addTitleTag($productData)
    {
        $title = $this->getProductTitle($productData);

        if(!$title){
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function getProductTitle($productData)
    {
        return $productData['og_title'] ?? $productData['meta_title'] ?? $productData['name'] ?? null;
    }

    private function addDescriptionTag($productData)
    {
        $description = $productData['og_description'] ?? $productData['meta_description'] ?? null;

        if(!$description){
            $description = $productData['short_description'] ?? $productData['description'] ?? null;

            if(!$description){
                return;
            }

            $description = $this->stripTags($description);
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function stripTags($content)
    {
        return trim(strip_tags($content));
    }

    private function addImageTag($product)
    {
        $ogImage = $product->getOgImage();
        $imageId = ($ogImage and $ogImage != 'no_selection') ? self::OPENGRAPH_PRODUCT_IMAGE_ID : self::DEFAULT_PRODUCT_IMAGE_ID;

        $image = $this->imageHelper
            ->init($product, $imageId);

        $imageUrl = $image->getUrl();

        if(!$imageUrl){
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $this->addAdditionalImageTags($image, $product);
    }

    protected function addAdditionalImageTags($image, $product)
    {
        $tag = $this->tagFactory->getTag('image:width', $image->getWidth());
        $this->addTag($tag);

        $tag = $this->tagFactory->getTag('image:height', $image->getHeight());
        $this->addTag($tag);

        $mimeType = $this->mimeHelper->getMimeType($image->getUrl());

        if($mimeType){
            $tag = $this->tagFactory->getTag('image:type', $mimeType);
            $this->addTag($tag);
        }

        $productData = array_filter($product->getData());
        $title = $this->getProductTitle($productData);

        if($title){
            $tag = $this->tagFactory->getTag('image:alt', $title);
            $this->addTag($tag);
        }

        return;
    }

    private function addTypeTag($productData)
    {
        $type = $productData['og_type'] ?? self::DEFAULT_PRODUCT_OPENGRAPH_TYPE;

        $tag = $this->tagFactory->getTag('type', $type);

        $this->addTag($tag);
    }

    private function addUrlTag($product)
    {
        $tag = $this->tagFactory->getTag('url', $product->getProductUrl());
        $this->addTag($tag);
    }

}