<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$product = $objectManager->create('Magento\Catalog\Model\Product');
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(555)
    ->setAttributeSetId(4)
    ->setName('Product without og tags')
    ->setSku('product_without_og_tags')
    ->setPrice(10)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setCanSaveCustomOptions(true)
    ->setDescription('<p>Description</p>')
    ->setImage('image.png')
    ->save();
$product->reindex();
$product->priceReindexCallback();

$product = $objectManager->create('Magento\Catalog\Model\Product');
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(556)
    ->setAttributeSetId(4)
    ->setName('Product with og tags')
    ->setSku('product_with_og_tags')
    ->setPrice(10)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setCanSaveCustomOptions(true)
    ->setMetaTitle('Meta Title')
    ->setDescription('<p>Description</p>')
    ->setImage('image.png')
    ->setOgTitle('Og Title')
    ->setOgDescription('Og Description')
    ->setOgImage('og_image.png')
    ->setOgType('article')
    ->save();
$product->reindex();
$product->priceReindexCallback();