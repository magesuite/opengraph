<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CategoryTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\Framework\App\ObjectManager $objectManager;
    protected \Magento\Framework\Registry $registry;
    protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;
    protected \MageSuite\Opengraph\DataProviders\Category $categoryProvider;
    protected \Magento\Framework\View\Page\Config $pageConfig;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->categoryRepository = $this->objectManager->get(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
        $this->categoryProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\Category::class);
        $this->pageConfig = $this->objectManager->get(\Magento\Framework\View\Page\Config::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Opengraph::Test/Integration/_files/categories.php
     */
    public function testItReturnsCorrectTags(): void
    {
        $this->itReturnsDefaultTags();
        $this->itReturnsOpengraphTags();
        $this->itReturnsPageconfigTagsWhenNoMetatitleAndMetadescriptionTags();
    }

    protected function itReturnsDefaultTags(): void
    {
        $category = $this->categoryRepository->get(333);

        if ($this->registry->registry('current_category')) {
            $this->registry->unregister('current_category');
        }
        $this->registry->register('current_category', $category);

        $tags = $this->categoryProvider->getTags();

        $this->assertEquals('Category without og tags', $tags['og:title']);
        $this->assertEquals('Meta description', $tags['og:description']);
        $this->assertEquals('website', $tags['og:type']);
    }

    protected function itReturnsOpengraphTags(): void
    {
        $category = $this->categoryRepository->get(334);

        if ($this->registry->registry('current_category')) {
            $this->registry->unregister('current_category');
        }
        $this->registry->register('current_category', $category);

        $tags = $this->categoryProvider->getTags();

        $this->assertEquals('Og Title', $tags['og:title']);
        $this->assertEquals('Og Description', $tags['og:description']);
        $this->assertEquals('article', $tags['og:type']);
    }

    protected function itReturnsPageconfigTagsWhenNoMetatitleAndMetadescriptionTags(): void
    {
        $this->pageConfig->getTitle()->set('title');
        $this->pageConfig->setDescription('description');

        $category = $this->categoryRepository->get(335);

        if ($this->registry->registry('current_category')) {
            $this->registry->unregister('current_category');
        }
        $this->registry->register('current_category', $category);

        $tags = $this->categoryProvider->getTags();

        $this->assertEquals('title', $tags['og:title']);
        $this->assertEquals('description', $tags['og:description']);
    }
}
