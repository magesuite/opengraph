<?php

$registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$categoriesIds = [333,334];

foreach($categoriesIds as $categoryId){
    $category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
    $category->load($categoryId);

    if ($category->getId()) {
        $category->delete();
    }
}