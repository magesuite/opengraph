<?php

namespace MageSuite\Opengraph\Model;

class Tag
{
    const OPENGRAPH_PREFIX = 'og:';
    const PRODUCT_PREFIX = 'product:';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    public function __construct(
        protected \Magento\Framework\Escaper $escaper
    ){}

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $this->escaper->escapeHtml(trim($value ?? ''));
    }

    public function getOpengraphName()
    {
        return self::OPENGRAPH_PREFIX . $this->name;
    }

    public function getOpengraphProductName()
    {
        return self::PRODUCT_PREFIX . $this->name;
    }
}
