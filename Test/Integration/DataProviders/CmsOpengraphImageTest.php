<?php

namespace MageSuite\Opengraph\Test\Integration\DataProviders;

class CmsOpengraphImageTest extends \PHPUnit\Framework\TestCase
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
        $page = $this->pageRepository->getById('page_with_og_tags');

        $dataProvider = $this->objectManager->create(
            \MageSuite\Opengraph\DataProviders\CmsOpengraphImage::class,
            ['page' => $page]
        );

        $tags = $dataProvider->getTags();

        $this->assertContains('image.png', $tags['og:image']);
        $this->assertEquals('image/png', $tags['og:image:type']);
    }
}
