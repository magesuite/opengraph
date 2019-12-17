<?php

namespace MageSuite\Opengraph\DataProviders;

class TagProvider implements TagProviderInterface
{
    protected $tags = [];

    public function getTags()
    {
        return $this->tags;
    }

    protected function addTag(\MageSuite\Opengraph\Model\Tag $tag)
    {
        if (!$tag->getName()) {
            return;
        }

        $this->tags[$tag->getOpengraphName()] = $tag->getValue();
    }

    protected function addProductTag(\MageSuite\Opengraph\Model\Tag $tag)
    {
        if (!$tag->getName()) {
            return;
        }

        $this->tags[$tag->getOpengraphProductName()] = $tag->getValue();
    }
}
