<?php

namespace MageSuite\Opengraph\DataProviders;

class Cms extends TagProvider implements TagProviderInterface
{
    const DEFAULT_TYPE = 'article';
    const HOMEPAGE_TYPE = 'website';

    /**
     * @var \Magento\Cms\Api\Data\PageInterface
     */
    protected $page;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \MageSuite\Opengraph\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    protected $tags = [];

    public function __construct(
        \Magento\Cms\Api\Data\PageInterface $page,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository,
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\Opengraph\Helper\Data $dataHelper,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory
    ) {
        $this->page = $page;
        $this->pageRepository = $pageRepository;
        $this->request = $request;
        $this->dataHelper = $dataHelper;
        $this->tagFactory = $tagFactory;
    }

    public function getTags()
    {
        if (!$this->page->getIdentifier() && !$this->request->getParam('page_id')) {
            return [];
        }

        $pageData = $this->page->getData();
        if (empty($pageData)) {
            $page = $this->pageRepository->getById($this->request->getParam('page_id'));
            $pageData = $page->getData();
        }

        $pageData = array_filter($pageData);

        $this->addTitleTag($pageData);
        $this->addDescriptionTag($pageData);
        $this->addTypeTag($pageData);

        return $this->tags;
    }

    private function addTitleTag($pageData)
    {
        $title = $pageData['og_title'] ?? $pageData['meta_title'] ?? $pageData['title'] ?? null;

        if (!$title) {
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function addDescriptionTag($pageData)
    {
        $description = $pageData['og_description'] ?? $pageData['meta_description'] ?? null;

        if (!$description) {
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function addTypeTag($pageData)
    {
        $type = $pageData['og_type'] ?? null;

        if (!$type) {
            $isHomepage = $this->dataHelper->isHomePage();
            $type = $isHomepage ? self::HOMEPAGE_TYPE : self::DEFAULT_TYPE;
        }

        $tag = $this->tagFactory->getTag('type', $type);

        $this->addTag($tag);
    }
}
