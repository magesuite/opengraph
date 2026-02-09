<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CmsTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager;
    protected ?\Magento\Cms\Api\PageRepositoryInterface $pageRepository;

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
        $this->itReturnsDefaultTags();
        $this->itReturnsOpengraphTags();
    }

    protected function itReturnsDefaultTags(): void
    {
        $page = $this->pageRepository->getById('page_without_og_tags');

        $dataProvider = $this->objectManager->create(
            \MageSuite\Opengraph\DataProviders\Cms::class,
            ['page' => $page]
        );

        $tags = $dataProvider->getTags();

        $this->assertEquals('Page without og tags', $tags['og:title']);
        $this->assertEquals('Meta description', $tags['og:description']);
        $this->assertEquals('article', $tags['og:type']);
    }

    protected function itReturnsOpengraphTags(): void
    {
        $page = $this->pageRepository->getById('page_with_og_tags');

        $dataProvider = $this->objectManager->create(
            \MageSuite\Opengraph\DataProviders\Cms::class,
            ['page' => $page]
        );

        $tags = $dataProvider->getTags();

        $this->assertEquals('Og Title', $tags['og:title']);
        $this->assertEquals('Og Description', $tags['og:description']);
        $this->assertEquals('article', $tags['og:type']);
    }
}
