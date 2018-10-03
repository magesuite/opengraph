<?php

/** @var \Magento\Framework\Registry $registry */
$registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Product');
$product->load(557);
if ($product->getId()) {
    $product->delete();
}

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(555);
if ($category->getId()) {
    $category->delete();
}

$brandIds = [888, 889];
foreach ($brandIds as $brandId) {
    $brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
    $brand->load($brandId);
    if ($brand->getId()) {
        $brand->delete();
    }
}
