<?php

/** @var \Magento\Framework\Registry $registry */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$pages = ['page_without_og_tags'];

/** @var $page \Magento\Cms\Model\Page */
foreach ($pages as $pageId) {
    $page = $objectManager->create('Magento\Cms\Model\Page');
    $page->load($pageId);

    if (!$page->getId()) {
        continue;
    }

    $page->delete();
}
