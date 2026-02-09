<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\Service;

class TagsCollectorTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\Opengraph\Service\TagsCollector $tagsCollector;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->tagsCollector = $this->objectManager->get(\MageSuite\Opengraph\Service\TagsCollector::class);
    }

    public function testItReturnsTags()
    {
        $tags = $this->tagsCollector->getTags();
        $this->assertEquals('http://localhost/index.php/', $tags['og:url']);
        $this->assertEquals('en_US', $tags['og:locale']);
    }
}
