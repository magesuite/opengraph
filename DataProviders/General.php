<?php

namespace MageSuite\Opengraph\DataProviders;

class General extends TagProvider implements TagProviderInterface
{
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
     * @var \MageSuite\Opengraph\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    protected $tags = [];

    public function __construct(
        \Magento\Framework\View\Page\Config $pageConfig,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Locale\Resolver $localeResolver,
        \Magento\Theme\Block\Html\Header\Logo $logoBlock,
        \MageSuite\Opengraph\Helper\Configuration $configuration,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory

    ) {
        $this->pageConfig = $pageConfig;
        $this->urlBuilder = $urlBuilder;
        $this->localeResolver = $localeResolver;
        $this->logoBlock = $logoBlock;
        $this->configuration = $configuration;
        $this->tagFactory = $tagFactory;
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

        if(!$fbAppIdTag) {
            return;
        }

        $this->tags['fb:app_id'] = $fbAppIdTag;
    }

    private function addTitleTag()
    {
        $title = $this->pageConfig->getTitle()->get();

        if(!$title){
            return;
        }

        $tag = $this->tagFactory->getTag('title', $title);

        $this->addTag($tag);
    }

    private function addDescriptionTag()
    {
        $description = $this->pageConfig->getDescription();

        if(!$description){
            return;
        }

        $tag = $this->tagFactory->getTag('description', $description);

        $this->addTag($tag);
    }

    private function addImageTag()
    {
        $logoSrc = $this->logoBlock->getLogoSrc();

        if(!$logoSrc){
            return;
        }

        $tag = $this->tagFactory->getTag('image', $logoSrc);

        $this->addTag($tag);

        $logoAlt = $this->logoBlock->getLogoAlt();

        if(!$logoAlt){
            return;
        }

        $tag = $this->tagFactory->getTag('image:alt', $logoAlt);

        $this->addTag($tag);
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

        if(!$locale){
            return;
        }

        $tag = $this->tagFactory->getTag('locale', $locale);

        $this->addTag($tag);
    }
}