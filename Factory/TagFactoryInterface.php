<?php

namespace MageSuite\Opengraph\Factory;

interface TagFactoryInterface
{

    /**
     * Returns Tag object
     *
     * @param string $name
     * @param string $value
     * @return \MageSuite\Opengraph\Model\Tag|null
     */
    public function getTag($name, $value);
}
