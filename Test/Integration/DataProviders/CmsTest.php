<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CmsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->pageRepository = $this->objectManager->get(\Magento\Cms\Api\PageRepositoryInterface::class);
    }

    public static function pagesFixture() {
        include __DIR__ . '/../_files/pages.php';
    }

    public static function pagesFixtureRollback()
    {
        include __DIR__ . '/../_files/pages_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture pagesFixture
     */
    public function testItReturnsCorrectTags()
    {
        $this->itReturnsDefaultTags();
        $this->itReturnsOpengraphTags();
    }

    private function itReturnsDefaultTags()
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

    private function itReturnsOpengraphTags()
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
