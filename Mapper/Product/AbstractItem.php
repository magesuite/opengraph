<?php

namespace MageSuite\Opengraph\Mapper\Product;

class AbstractItem
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    )
    {
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->priceCurrency = $priceCurrency;
    }

    public function getCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    public function hasSpecialPrice($product)
    {
        $regularPrice = $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\RegularPrice::PRICE_CODE)->getAmount()->getValue();
        $finalPrice = $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE)->getAmount()->getValue();

        return $finalPrice < $regularPrice;
    }

    public function formatPrice($result)
    {
        $result['amount'] = $this->priceCurrency->convertAndRound($result['amount'], null, null, \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION);

        return $result;
    }

}
