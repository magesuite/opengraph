<?php

namespace MageSuite\Opengraph\Test\Unit\Factory;

class TagFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactory
     */
    private $tagFactory;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->tagFactory = $this->objectManager->get(\MageSuite\Opengraph\Factory\TagFactory::class);
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