<?php

namespace MageSuite\Opengraph\Model\Entity\Attribute\Source;

class Type extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \MageSuite\Opengraph\Model\Source\Type
     */
    protected $typeSource;

    public function __construct(\MageSuite\Opengraph\Model\Source\Type $typeSource)
    {
        $this->typeSource = $typeSource;
    }

    public function getAllOptions()
    {
        return $this->typeSource->toOptionArray();
    }
}
