<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\DataProviders;

class ProductAdditional extends TagProvider implements TagProviderInterface
{
    public function __construct(
        protected \Magento\Framework\Registry $registry,
        protected \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        protected \MageSuite\Opengraph\Mapper\Product $productMapper,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected $tags = []
    ) {}

    public function getTags()
    {
        $product = $this->registry->registry('product');

        if (!$product || !$product->getId()) {
            return [];
        }

        $origStoreId = $product->getStoreId();
        $this->checkAndUpdateStoreId($product);

        $items = $this->productMapper->getItems($product);

        foreach ($items as $name => $value) {
            $tag = $this->tagFactory->getTag($name, $value);
            $this->addProductTag($tag);
        }

        $product->setStoreId($origStoreId);

        return $this->tags;
    }

    private function checkAndUpdateStoreId(\Magento\Catalog\Api\Data\ProductInterface $product): void
    {
        if ($product->getTypeId() === \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE || $product->getStoreId()) {
            return;
        }

        $product->setStoreId((int) $this->storeManager->getDefaultStoreView()?->getId());
    }
}
