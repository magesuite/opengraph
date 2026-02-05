<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CmsOpengraphImageTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\Framework\App\ObjectManager $objectManager;
    protected \Magento\Cms\Api\PageRepositoryInterface $pageRepository;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->pageRepository = $this->objectManager->get(\Magento\Cms\Api\PageRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Opengraph::Test/Integration/_files/pages.php
     */
    public function testItReturnsCorrectTags(): void
    {
        $page = $this->pageRepository->getById('page_with_og_tags');

        $dataProvider = $this->objectManager->create(
            \MageSuite\Opengraph\DataProviders\CmsOpengraphImage::class,
            ['page' => $page]
        );

        $tags = $dataProvider->getTags();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains('image.png', $tags['og:image']);
        $this->assertEquals('image/png', $tags['og:image:type']);
    }
}
