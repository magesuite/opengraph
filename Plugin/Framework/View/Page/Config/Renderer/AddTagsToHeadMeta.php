<?php

namespace MageSuite\Opengraph\Plugin\Framework\View\Page\Config\Renderer;

class AddTagsToHeadMeta
{
    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var \MageSuite\Opengraph\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\Opengraph\Helper\PageType
     */
    protected $pageType;

    /**
     * @var \MageSuite\Opengraph\Service\TagsCollector
     */
    protected $tagsCollector;

    public function __construct(
        \Magento\Framework\View\Page\Config $pageConfig,
        \MageSuite\Opengraph\Helper\Configuration $configuration,
        \MageSuite\Opengraph\Helper\PageType $pageType,
        \MageSuite\Opengraph\Service\TagsCollector $tagsCollector
    ) {
        $this->pageConfig = $pageConfig;
        $this->configuration = $configuration;
        $this->pageType = $pageType;
        $this->tagsCollector = $tagsCollector;
    }

    public function afterRenderMetadata(\Magento\Framework\View\Page\Config\Renderer $subject, $result)
    {
        if (!$this->configuration->isEnabled()) {
            return $result;
        }

        $pageType = $this->pageType->getPageType();

        $tags = $this->tagsCollector->getTags($pageType);

        if (empty($tags)) {
            return $result;
        }

        foreach ($tags as $name => $value) {
            if (empty($value)) {
                continue;
            }

            $metadataTemplate = $this->getMetadataTemplate($name);

            $value = strip_tags($value);

            $result .= str_replace(['%name', '%content'], [$name, $value], $metadataTemplate);
        }

        return $result;
    }

    protected function getMetadataTemplate($name)
    {
        return '<meta property="' . $name . '" content="%content"/>' . "\n";
    }
}
