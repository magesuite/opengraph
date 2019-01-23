<?php

namespace MageSuite\Opengraph\Model\Source;

use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;


class Type implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    protected $types = ['article', 'website'];

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
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
