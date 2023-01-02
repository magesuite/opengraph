<?php

namespace MageSuite\Opengraph\DataProviders;

class Category extends TagProvider implements TagProviderInterface
{
    const DEFAULT_CATEGORY_OPENGRAPH_TYPE = 'website';

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    protected $tags = [];

    public function __construct(
        \Magento\Framework\View\Page\Config $pageConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory
    ) {
        $this->pageConfig = $pageConfig;
        $this->registry = $registry;
        $this->request = $request;
        $this->urlFinder = $urlFinder;
        $this->storeManager = $storeManager;
        $this->tagFactory = $tagFactory;
    }

    public function getTags()
    {
        $category = $this->registry->registry('current_category');

        if (!$category || !$category->getId()) {
            return [];
        }

        $this->addTitleTag($category);
        $this->addDescriptionTag($category);
        $this->addTypeTag($category);
        $this->addUrlTag();

        return $this->tags;
    }

    protected function addTitleTag($category)
    {
        $pageConfigTitle = !empty($this->pageConfig->getTitle()->get()) ? $this->pageConfig->getTitle()->get() : null;
        $categoryData = array_filter($category->getData());
        $title = $categoryData['og_title']
            ?? $categoryData['meta_title']
            ?? $pageConfigTitle
            ?? $categoryData['name']
            ?? null;

        if (!$title) {
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    protected function addDescriptionTag($category)
    {
        $pageConfigDescription = !empty($this->pageConfig->getDescription()) ? $this->pageConfig->getDescription() : null;
        $categoryData = array_filter($category->getData());
        $description = $categoryData['og_description']
            ?? $categoryData['meta_description']
            ?? $pageConfigDescription
            ?? $categoryData['description']
            ?? null;

        if (!$description) {
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    protected function addTypeTag($category)
    {
        $categoryData = array_filter($category->getData());

        $type = $categoryData['og_type'] ?? self::DEFAULT_CATEGORY_OPENGRAPH_TYPE;

        $tag = $this->tagFactory->getTag('type', $type);

        $this->addTag($tag);
    }

    protected function addUrlTag()
    {
        $urlRewrite = $this->urlFinder->findOneByData([
            'target_path' => trim($this->request->getPathInfo(), '/'),
            'store_id' => $this->storeManager->getStore()->getId()
        ]);

        if (!$urlRewrite) {
            return;
        }

        $currentUrl = $this->storeManager->getStore()->getBaseUrl() . $urlRewrite->getRequestPath();

        $tag = $this->tagFactory->getTag('url', $currentUrl);

        $this->addTag($tag);
    }
}
