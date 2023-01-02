<?php

namespace MageSuite\Opengraph\DataProviders;

class General extends TagProvider implements TagProviderInterface
{
    const DEFAULT_IMAGE_PATH = 'opengraph/store/default_image/';

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $localeResolver;

    /**
     * @var \Magento\Theme\Block\Html\Header\Logo
     */
    protected $logoBlock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageSuite\Opengraph\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    /**
     * @var \MageSuite\Opengraph\Helper\Mime
     */
    protected $mimeHelper;

    protected $tags = [];

    public function __construct(
        \Magento\Framework\View\Page\Config $pageConfig,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Locale\Resolver $localeResolver,
        \Magento\Theme\Block\Html\Header\Logo $logoBlock,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Opengraph\Helper\Configuration $configuration,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        \MageSuite\Opengraph\Helper\Mime $mimeHelper
    ) {
        $this->pageConfig = $pageConfig;
        $this->urlBuilder = $urlBuilder;
        $this->localeResolver = $localeResolver;
        $this->logoBlock = $logoBlock;
        $this->storeManager = $storeManager;
        $this->configuration = $configuration;
        $this->tagFactory = $tagFactory;
        $this->mimeHelper = $mimeHelper;
    }

    public function getTags()
    {
        $this->addFbAppIdTag();
        $this->addTitleTag();
        $this->addDescriptionTag();
        $this->addImageTag();
        $this->addUrlTag();
        $this->addLocaleTag();

        return $this->tags;
    }

    private function addFbAppIdTag()
    {
        $fbAppIdTag = $this->configuration->getFbAppId();

        if (!$fbAppIdTag) {
            return;
        }

        $this->tags['fb:app_id'] = $fbAppIdTag;
    }

    private function addTitleTag()
    {
        $title = $this->pageConfig->getTitle()->get();

        if (!$title) {
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function addDescriptionTag()
    {
        $description = $this->pageConfig->getDescription();

        if (!$description) {
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function addImageTag()
    {
        $imageUrl = $this->getImageTagUrl();

        if (!$imageUrl) {
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $mimeType = $this->mimeHelper->getMimeType($imageUrl);

        if ($mimeType) {
            $tag = $this->tagFactory->getTag('image:type', $mimeType);
            $this->addTag($tag);
        }

        $storeName = $this->configuration->getStoreName();

        if ($storeName) {
            $tag = $this->tagFactory->getTag('image:alt', $storeName);
            $this->addTag($tag);
        }
    }

    private function getImageTagUrl()
    {
        $defaultImage = $this->configuration->geDefaultImage();

        if ($defaultImage) {
            return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . self::DEFAULT_IMAGE_PATH . $defaultImage;
        }

        $logoSrc = $this->logoBlock->getLogoSrc();

        if ($logoSrc) {
            return $logoSrc;
        }

        return null;
    }

    private function addUrlTag()
    {
        $currentUrl = $this->urlBuilder->getUrl('', ['_current' => true]);

        $tag = $this->tagFactory->getTag('url', $currentUrl);

        $this->addTag($tag);
    }

    private function addLocaleTag()
    {
        $locale = $this->localeResolver->getLocale();

        if (!$locale) {
            return;
        }

        $tag = $this->tagFactory->getTag('locale', $locale);

        $this->addTag($tag);
    }
}
