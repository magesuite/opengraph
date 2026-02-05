<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class ProductAdditionalTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\Framework\App\ObjectManager $objectManager;
    protected \Magento\Framework\Registry $registry;
    protected \Magento\Catalog\Api\ProductRepositoryInterface $productRepository;
    protected \MageSuite\Opengraph\DataProviders\ProductAdditional $productAdditionalProvider;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->productAdditionalProvider = $this->objectManager->get(\MageSuite\Opengraph\DataProviders\ProductAdditional::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Opengraph::Test/Integration/_files/product_with_attributes.php
     */
    public function testItReturnsCorrectTags(): void
    {
        $product = $this->productRepository->get('product_with_attributes');

        if ($this->registry->registry('product')) {
            $this->registry->unregister('product');
        }
        $this->registry->register('product', $product);

        $tags = $this->productAdditionalProvider->getTags();

        $this->assertEquals('instock', $tags['product:availability']);
        $this->assertEquals('new', $tags['product:condition']);
        $this->assertEquals('USD', $tags['product:price:currency']);
        $this->assertEquals(10, $tags['product:price:amount'], '', 0);
        $this->assertEquals('USD', $tags['product:sale_price:currency']);
        $this->assertEquals(5, $tags['product:sale_price:amount'], '', 0);
        $this->assertEquals('Adidas', $tags['product:brand']);
        $this->assertEquals('2018-01-01 01:01:02', $tags['product:sale_price_dates:start']);
        $this->assertEquals('2028-01-01 01:11:02', $tags['product:sale_price_dates:end']);
    }
}
