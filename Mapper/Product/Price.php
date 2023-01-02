<?php

namespace MageSuite\Opengraph\Mapper\Product;

class Price extends AbstractItem
{
    public function getTagValue($product)
    {
        $return = [
            'currency' => $this->getCurrency(),
            'amount' => $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\RegularPrice::PRICE_CODE)->getAmount()->getValue()
        ];

        $productType = $product->getTypeId();

        if ($productType != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE || $productType != \Magento\Bundle\Model\Product\Type::TYPE_CODE) {
            return $this->formatPrice($return);
        }

        $simpleProducts = $product->getTypeInstance()->getUsedProducts($product, 'price');
        foreach ($simpleProducts as $simpleProduct) {
            $price = $simpleProduct->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\RegularPrice::PRICE_CODE)->getAmount()->getValue();
            $return['amount'] = max($return['amount'], $price);
        }

        return $this->formatPrice($return);
    }
}
