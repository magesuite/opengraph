<?php

/** @var $page \Magento\Cms\Model\Page */

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Page without og tags')
    ->setIdentifier('page_without_og_tags')
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title1</h1>')
    ->setMetaDescription('Meta description')
    ->setPageLayout('1column')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Page with og tags')
    ->setIdentifier('page_with_og_tags')
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title1</h1>')
    ->setMetaDescription('Meta description')
    ->setPageLayout('1column')
    ->setOgTitle('Og Title')
    ->setOgDescription('Og Description')
    ->setOgType('article')
    ->setOgImage('image.png')
    ->save();