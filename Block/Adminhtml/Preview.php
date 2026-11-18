<?php

namespace MageSuite\Opengraph\Block\Adminhtml;

class Preview extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \MageSuite\Opengraph\Helper\PageType
     */
    protected $pageType;

    /**
     * @var \MageSuite\Opengraph\Service\TagsCollector
     */
    protected $tagsCollector;

    /**
     * @var string|null
     */
    protected $pageTypeValue;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \MageSuite\Opengraph\Helper\PageType $pageType
     * @param \MageSuite\Opengraph\Service\TagsCollector $tagsCollector
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \MageSuite\Opengraph\Helper\PageType $pageType,
        \MageSuite\Opengraph\Service\TagsCollector $tagsCollector,
        array $data = []
    ) {
        parent::__construct($context, array_replace($data, ['template' => 'preview.phtml']));

        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->pageType = $pageType;
        $this->tagsCollector = $tagsCollector;
    }

    /**
     * Collect tags for the page being previewed.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tagsCollector->getTags($this->getPageType());
    }

    /**
     * Resolve the page URL for the selected preview store.
     *
     * @param string $value
     * @return string
     */
    public function prepareUrl($value)
    {
        $storeId = $this->getRequest()->getParam('store') ?? $this->storeManager->getDefaultStoreView()->getId();

        $pageType = $this->getPageType();

        if ($pageType == 'product') {
            $product = $this->registry->registry('current_product');

            return $this->replaceStoreUrl($product->getProductUrl(), $storeId);

        } elseif ($pageType == 'category') {
            $categoryId = $this->getRequest()->getParam('id') ?? null;

            if (!$categoryId) {
                return $value;
            }

            $category = $this->categoryRepository->get($categoryId, $storeId);

            if ($category->getLevel() < 2) {
                return $this->storeManager->getStore($storeId)->getBaseUrl();
            }

            return $this->replaceStoreUrl($category->setStoreId($storeId)->getUrl(), $storeId);

        } elseif ($pageType == 'cms') {
            $page = $this->registry->registry('cms_page');

            return $this->storeManager->getStore($storeId)->getBaseUrl() . $page->getIdentifier();
        }

        return $value;
    }

    /**
     * Cache the page type for subsequent tag and URL lookups.
     *
     * @return string
     */
    protected function getPageType()
    {
        if (!$this->pageTypeValue) {
            $this->pageTypeValue = $this->pageType->getPageType();
        }

        return $this->pageTypeValue;
    }

    /**
     * Replace the current store's base URL with the preview store's base URL.
     *
     * @param string $url
     * @param int|string $storeId
     * @return string
     */
    protected function replaceStoreUrl($url, $storeId)
    {
        return str_replace(
            $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB),
            $this->storeManager->getStore($storeId)->getBaseUrl(),
            $url
        );
    }
}
