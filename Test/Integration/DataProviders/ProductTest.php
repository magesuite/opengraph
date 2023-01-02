<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class ProductTest extends \PHPUnit\Framework\TestCase
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
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \MageSuite\Opengraph\DataProviders\Product
     */
    private $productProvider;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->productProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\Product::class);
        $this->pageConfig = $this->objectManager->get(\Magento\Framework\View\Page\Config::class);
    }

    public static function productsFixture()
    {
        include __DIR__ . '/../_files/products.php';
    }

    public static function productsFixtureRollback()
    {
        include __DIR__ . '/../_files/products_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productsFixture
     */
    public function testItReturnsCorrectTags()
    {
        $this->itReturnsDefaultTags();
        $this->itReturnsOpengraphTags();
        $this->itReturnsPageconfigTagsWhenNoMetatitleAndMetadescriptionTags();
    }

    private function itReturnsDefaultTags()
    {
        $product = $this->productRepository->get('product_without_og_tags');

        if ($this->registry->registry('product')) {
            $this->registry->unregister('product');
        }
        $this->registry->register('product', $product);

        $tags = $this->productProvider->getTags();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->assertEquals('Product without og tags', $tags['og:title']);
        $this->assertEquals('Description', $tags['og:description']);
        $this->$assertContains('magento_image.jpg', $tags['og:image']);
        $this->assertEquals('product', $tags['og:type']);
    }

    private function itReturnsOpengraphTags()
    {
        $product = $this->productRepository->get('product_with_og_tags');

        if ($this->registry->registry('product')) {
            $this->registry->unregister('product');
        }
        $this->registry->register('product', $product);

        $tags = $this->productProvider->getTags();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->assertEquals('Og Title', $tags['og:title']);
        $this->assertEquals('Og Description', $tags['og:description']);
        $this->$assertContains('og_image.png', $tags['og:image']);
        $this->assertEquals('article', $tags['og:type']);
    }

    private function itReturnsPageconfigTagsWhenNoMetatitleAndMetadescriptionTags()
    {
        $this->pageConfig->getTitle()->set('title');
        $this->pageConfig->setDescription('description');

        $product = $this->productRepository->get('product_without_og_tags');
        $product->setMetaTitle('');
        $product->setMetaDescription('');
        $product->save();

        if ($this->registry->registry('product')) {
            $this->registry->unregister('product');
        }
        $this->registry->register('product', $product);

        $tags = $this->productProvider->getTags();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->assertEquals('title', $tags['og:title']);
        $this->assertEquals('description', $tags['og:description']);
        $this->$assertContains('magento_image.jpg', $tags['og:image']);
        $this->assertEquals('product', $tags['og:type']);
    }
}
