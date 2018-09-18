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
    private $categoryRepository;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    )
    {
        $this->registry = $registry;
        $this->categoryRepository = $categoryRepository;
    }

    public function getTagValue($product)
    {
        $currentCategory = $this->registry->registry('current_category');

        if($currentCategory and $currentCategory->getId()){
            return $currentCategory->getName();
        }

        $productCategories = $product->getAvailableInCategories();

        if(empty($productCategories) or !is_array($productCategories)) {
            return null;
        }

        $categoryId = $productCategories[0];

        $category = $this->categoryRepository->get($categoryId);

        return $category->getName();
    }
}
