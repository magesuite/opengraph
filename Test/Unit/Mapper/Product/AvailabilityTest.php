<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Unit\Mapper\Product;

class AvailabilityTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('getAvailabilityCases')]
    public function testItMapsSalableStatusToAvailability(bool $isSalable, string $expectedAvailability): void
    {
        $product = $this->createMock(\Magento\Catalog\Model\Product::class);
        $product->method('isSalable')->willReturn($isSalable);

        $this->assertSame($expectedAvailability, (new \MageSuite\Opengraph\Mapper\Product\Availability())->getTagValue($product));
    }

    public static function getAvailabilityCases(): array
    {
        return [
            'salable_product' => [true, 'in stock'],
            'not_salable_product' => [false, 'out of stock']
        ];
    }
}
