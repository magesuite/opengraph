<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CategoryTest extends \PHPUnit\Framework\TestCase
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
     * @var \MageSuite\Opengraph\DataProviders\Category
     */
    private $categoryProvider;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->categoryRepository = $this->objectManager->get(\Magento\Catalog\Api\CategoryRepositoryInterface::class);

        $this->categoryProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\Category::class);
    }

    public static function categoriesFixture() {
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
        $this->itReturnsDefaultTags();
        $this->itReturnsOpengraphTags();
    }

    private function itReturnsDefaultTags()
    {
        $category = $this->categoryRepository->get(333);

        if($this->registry->registry('current_category')){
            $this->registry->unregister('current_category');
        }
        $this->registry->register('current_category', $category);

        $tags = $this->categoryProvider->getTags();

        $this->assertEquals('Category without og tags', $tags['og:title']);
        $this->assertEquals('Meta description', $tags['og:description']);
        $this->assertEquals('website', $tags['og:type']);
    }

    private function itReturnsOpengraphTags()
    {
        $category = $this->categoryRepository->get(334);

        if($this->registry->registry('current_category')){
            $this->registry->unregister('current_category');
        }
        $this->registry->register('current_category', $category);

        $tags = $this->categoryProvider->getTags();

        $this->assertEquals('Og Title', $tags['og:title']);
        $this->assertEquals('Og Description', $tags['og:description']);
        $this->assertEquals('article', $tags['og:type']);
    }
}
