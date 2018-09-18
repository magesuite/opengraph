<?php

namespace MageSuite\Opengraph\Mapper\Product;

class Availability
{
    public function getTagValue($productData)
    {
        $isInStock = $productData['quantity_and_stock_status']['is_in_stock'] ?? false;
        $availability = $isInStock ? 'instock' : 'oos';

        return $availability;
    }
}
