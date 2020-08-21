<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class ProductAdditionalTest extends \PHPUnit\Framework\TestCase
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
     * @var \MageSuite\Opengraph\DataProviders\ProductAdditional
     */
    private $productAdditionalProvider;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);

        $this->productAdditionalProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\ProductAdditional::class);
    }

    public static function productWithAttributesFixture() {
        include __DIR__ . '/../_files/product_with_attributes.php';
    }

    public static function productWithAttributesFixtureRollback() {
        include __DIR__ . '/../_files/product_with_attributes_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productWithAttributesFixture
     */
    public function testItReturnsCorrectTags()
    {
        $product = $this->productRepository->get('product_with_attributes');

        if($this->registry->registry('product')){
            $this->registry->unregister('product');
        }
        $this->registry->register('product', $product);

        $tags = $this->productAdditionalProvider->getTags();

        $this->assertEquals('instock', $tags['product:availability']);
        $this->assertEquals('new', $tags['product:condition']);
        $this->assertEquals('USD', $tags['product:price:currency']);
        $this->assertEquals('10.0000', $tags['product:price:amount']);
        $this->assertEquals('USD', $tags['product:sale_price:currency']);
        $this->assertEquals('5.0000', $tags['product:sale_price:amount']);
        $this->assertEquals('Adidas', $tags['product:brand']);
        $this->assertEquals('2018-01-01 01:01:02', $tags['product:sale_price_dates:start']);
        $this->assertEquals('2028-01-01 01:11:02', $tags['product:sale_price_dates:end']);

    }
}
