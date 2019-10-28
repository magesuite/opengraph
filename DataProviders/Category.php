<?php

namespace MageSuite\Opengraph\DataProviders;

class Category extends TagProvider implements TagProviderInterface
{
    const DEFAULT_CATEGORY_OPENGRAPH_TYPE = 'website';

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
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory

    ) {
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

        $categoryData = array_filter($category->getData());

        $this->addTitleTag($categoryData);
        $this->addDescriptionTag($categoryData);
        $this->addTypeTag($categoryData);
        $this->addUrlTag();

        return $this->tags;
    }

    private function addTitleTag($categoryData)
    {
        $title = $categoryData['og_title'] ?? $categoryData['meta_title'] ?? $categoryData['name'] ?? null;

        if (!$title) {
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function addDescriptionTag($categoryData)
    {
        $description = $categoryData['og_description'] ?? $categoryData['meta_description'] ?? $categoryData['description'] ?? null;

        if (!$description) {
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function addTypeTag($categoryData)
    {
        $type = $categoryData['og_type'] ?? self::DEFAULT_CATEGORY_OPENGRAPH_TYPE;

        $tag = $this->tagFactory->getTag('type', $type);

        $this->addTag($tag);
    }

    private function addUrlTag()
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