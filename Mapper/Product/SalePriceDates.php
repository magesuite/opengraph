<?php

namespace MageSuite\Opengraph\Mapper\Product;

class SalePriceDates extends AbstractItem
{
    public function getTagValue($product)
    {
        if(!$this->hasSpecialPrice($product)){
            return null;
        }

        return [
            'start' => $product->getSpecialFromDate(),
            'end' => $product->getSpecialToDate()
        ];
    }
}
