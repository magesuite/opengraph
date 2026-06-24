<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Service;

class TagsCollector
{
    public function __construct(protected array $dataProviders, protected array $tagPostProcessors = [])
    {
    }

    public function getTags(?string $pageType = null): array
    {
        if (empty($pageType) || !isset($this->dataProviders[$pageType])) {
            $pageType = \MageSuite\Opengraph\Helper\PageType::DEFAULT_PAGE_TYPE;
        }

        $dataProviders = $this->sortProviders($this->dataProviders[$pageType]);

        $tags = [];

        foreach ($dataProviders as $dataProvider) {
            $dataProviderClass = $dataProvider['class'];

            if (!is_object($dataProviderClass) || !$dataProviderClass instanceof \MageSuite\Opengraph\DataProviders\TagProviderInterface) {
                continue;
            }

            $tags = $this->mergeTags($tags, $dataProviderClass->getTags());
        }

        foreach ($this->tagPostProcessors as $postProcessor) {
            if (!$postProcessor instanceof \MageSuite\Opengraph\DataProviders\TagsPostProcessorInterface) {
                continue;
            }

            $tags = $postProcessor->process($tags, $pageType);
        }

        return $tags;
    }

    protected function sortProviders(array $dataProviders): array
    {
        usort($dataProviders, function ($a, $b) {
            $aSortOrder = $a['sortOrder'] ?? 0;
            $bSortOrder = $b['sortOrder'] ?? 0;

            return ($aSortOrder <=> $bSortOrder);
        });

        return $dataProviders;
    }

    protected function mergeTags(array $currentTags, array $newTags): array
    {
        if (empty($currentTags)) {
            return $newTags;
        }

        return array_replace($currentTags, $newTags);
    }
}
