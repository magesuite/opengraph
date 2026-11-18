<?php

namespace MageSuite\Opengraph\Test\Unit\Model;

class TagTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\Opengraph\Model\Tag
     */
    private $tag;

    public function setUp(): void
    {
        $this->tag = new \MageSuite\Opengraph\Model\Tag();
    }

    public function testItConvertTag()
    {
        $tag = $this->tag;
        $tag->setName('name');
        $this->assertEquals('og:name', $tag->getOpengraphName());
    }
}
