<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Model\Source;

class Type implements \Magento\Framework\Data\OptionSourceInterface
{
    protected ?array $options = null;

    protected array $types = ['article', 'product', 'website'];

    public function toOptionArray(): array
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $options = [];

        foreach ($this->types as $type) {
            $options[] = [
                'label' => $type,
                'value' => $type,
            ];
        }

        $this->options = $options;

        return $this->options;
    }
}
