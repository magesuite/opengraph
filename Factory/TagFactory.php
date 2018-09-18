<?php

namespace MageSuite\Opengraph\Factory;

class TagFactory implements TagFactoryInterface
{
    /**
     * @var \MageSuite\Opengraph\Model\TagFactory
     */
    protected $tagFactory;

    public function __construct(\MageSuite\Opengraph\Model\TagFactory $tagFactory)
    {
        $this->tagFactory = $tagFactory;
    }

    public function getTag($name, $value)
    {
        $tag = $this->tagFactory->create();

        if(!$name or !$value){
            return $tag;
        }

        $tag->setName($name);
        $tag->setValue($value);

        return $tag;
    }
}
