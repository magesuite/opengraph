<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CategoryOpengraphImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \MageSuite\Opengraph\DataProviders\CategoryOpengraphImage
     */
    private $categoryOpengraphImageProvider;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->categoryRepository = $this->objectManager->get(\Magento\Catalog\Api\CategoryRepositoryInterface::class);

        $this->categoryOpengraphImageProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\CategoryOpengraphImage::class);
    }

    public static function categoriesFixture()
    {
        include __DIR__ . '/../_files/categories.php';
    }

    public static function categoriesFixtureRollback()
    {
        include __DIR__ . '/../_files/categories_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture categoriesFixture
     */
    public function testItReturnsCorrectTags()
    {
        $category = $this->categoryRepository->get(334);
        $this->registry->register('current_category', $category);

        $tags = $this->categoryOpengraphImageProvider->getTags();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains('og_image.png', $tags['og:image']);
        $this->assertEquals('image/png', $tags['og:image:type']);
    }
}
