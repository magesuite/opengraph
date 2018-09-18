<?php

namespace MageSuite\Opengraph\DataProviders;

class ProductAdditional extends TagProvider implements TagProviderInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    /**
     * @var \MageSuite\Opengraph\Mapper\Product
     */
    protected $productMapper;

    protected $tags = [];

    public function __construct(
        \Magento\Framework\Registry $registry,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        \MageSuite\Opengraph\Mapper\Product $productMapper
    )
    {
        $this->registry = $registry;
        $this->tagFactory = $tagFactory;
        $this->productMapper = $productMapper;
    }

    public function getTags()
    {
        $product = $this->registry->registry('product');

        if (!$product or !$product->getId()) {
            return [];
        }

        $items = $this->productMapper->getItems($product);

        foreach($items as $name => $value){
            $tag = $this->tagFactory->getTag($name, $value);
            $this->addProductTag($tag);
        }

        return $this->tags;
    }
}