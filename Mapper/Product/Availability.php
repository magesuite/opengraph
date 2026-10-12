<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Mapper\Product;

class Availability
{
    public const IN_STOCK = 'in stock';
    public const OUT_OF_STOCK = 'out of stock';

    public function getTagValue(\Magento\Catalog\Model\Product $product): string
    {
        return $product->isSalable() ? self::IN_STOCK : self::OUT_OF_STOCK;
    }
}
