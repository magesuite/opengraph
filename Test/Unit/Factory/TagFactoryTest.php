<?php

namespace MageSuite\Opengraph\Test\Unit\Factory;

class TagFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\Opengraph\Factory\TagFactory
     */
    private $tagFactory;

    public function setUp(): void
    {
        $tagFactory = $this->createMock(\MageSuite\Opengraph\Model\TagFactory::class);
        $tagFactory->expects($this->once())->method('create')->willReturn(new \MageSuite\Opengraph\Model\Tag());

        $this->tagFactory = new \MageSuite\Opengraph\Factory\TagFactory($tagFactory);
    }

    public function testItReturnsTag()
    {
        $tag = $this->tagFactory->getTag('name', 'value');

        $this->assertEquals('name', $tag->getName());
        $this->assertEquals('value', $tag->getValue());
    }

    public function testItDoesntSetTagWithEmptyValue()
    {
        $tag = $this->tagFactory->getTag('name', null);

        $this->assertEmpty($tag->getName());
        $this->assertEmpty($tag->getValue());
    }

    public function testItSetEmptyTagIfAddEvenIfValueIsEmptyFlagExists()
    {
        $tag = $this->tagFactory->getTag('name', null, true);

        $this->assertEquals('name', $tag->getName());
        $this->assertEmpty($tag->getValue());
    }
}
