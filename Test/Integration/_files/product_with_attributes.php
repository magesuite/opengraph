<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$eavConfig = $objectManager->create(\Magento\Eav\Model\Config::class);
$installer = $objectManager->create(\Magento\Catalog\Setup\CategorySetup::class);

$brand = $objectManager->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(600)
    ->setStoreId(0)
    ->setUrlKey('/mark/nike.html')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('Nike')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png');
$brandRepository = $objectManager->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);

$brand = $objectManager->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(700)
    ->setStoreId(0)
    ->setUrlKey('/mark/adidas.html')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('Adidas')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png');
$brandRepository = $objectManager->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);

$brandAttribute = $eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'brand');
$brandAttributeOptions = [];
foreach($brandAttribute->getSource()->getAllOptions(false) AS $key => $option){
    $brandAttributeOptions[$option['label']] = $option['value'];
}

$product = $objectManager->create('Magento\Catalog\Model\Product');

$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(557)
    ->setAttributeSetId(4)
    ->setName('Product with attributes')
    ->setSku('product_with_attributes')
    ->setPrice(10)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setCanSaveCustomOptions(true)
    ->setBrand($brandAttributeOptions['Adidas'])
    ->setSpecialFromDate('2018-01-01 01:01:02')
    ->setSpecialToDate('2028-01-01 01:11:02')
    ->setSpecialPrice(5)
    ->save();
$product->reindex();
$product->priceReindexCallback();

$category = $objectManager->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(555)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Super Category')
    ->setParentId(2)
    ->setPath('1/2/333')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setMetaDescription('Meta description')
    ->setPostedProducts([
        557 => 8
    ])
    ->save()
    ->reindex();

