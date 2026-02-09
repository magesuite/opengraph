<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CategoryOpengraphImageTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager;
    protected ?\Magento\Framework\Registry $registry;
    protected ?\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;
    protected ?\MageSuite\Opengraph\DataProviders\CategoryOpengraphImage $categoryOpengraphImageProvider;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->categoryRepository = $this->objectManager->get(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
        $this->categoryOpengraphImageProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\CategoryOpengraphImage::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Opengraph::Test/Integration/_files/categories.php
     */
    public function testItReturnsCorrectTags(): void
    {
        $category = $this->categoryRepository->get(334);
        $this->registry->register('current_category', $category);

        $tags = $this->categoryOpengraphImageProvider->getTags();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains('og_image.png', $tags['og:image']);
        $this->assertEquals('image/png', $tags['og:image:type']);
    }
}
