<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Model\Entity\Attribute\Source;

class Type extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function __construct(
        protected \MageSuite\Opengraph\Model\Source\Type $typeSource
    ) {}

    public function getAllOptions(): array
    {
        $emptyOption = [
            'label' => ' ',
            'value' => ''
        ];

        return array_merge([$emptyOption], $this->typeSource->toOptionArray());
    }
}
