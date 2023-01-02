<?php

namespace MageSuite\Opengraph\Mapper\Product;

class Category
{
    /**
     * @var \Magento\Framework\Registry $registry
     */
    protected $registry;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {
        $this->registry = $registry;
        $this->categoryRepository = $categoryRepository;
    }

    public function getTagValue($product)
    {
        $currentCategory = $this->registry->registry('current_category');

        if ($currentCategory && $currentCategory->getId()) {
            return $currentCategory->getName();
        }

        $productCategories = $product->getAvailableInCategories();

        if (empty($productCategories) || !is_array($productCategories)) {
            return null;
        }

        $categoryId = $productCategories[0];

        $category = $this->categoryRepository->get($categoryId);

        return $category->getName();
    }
}
