<?php

namespace MageSuite\Opengraph\Service;

class TagsCollector
{
    /**
     * @var array
     */
    protected $dataProviders;

    public function __construct(array $dataProviders)
    {
        $this->dataProviders = $dataProviders;
    }

    public function getTags($pageType = null)
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

        return $tags;
    }

    protected function sortProviders($dataProviders)
    {
        usort($dataProviders, function ($a, $b) {
            $aSortOrder = $a['sortOrder'] ?? 0;
            $bSortOrder = $b['sortOrder'] ?? 0;

            return ($aSortOrder <=> $bSortOrder);
        });

        return $dataProviders;
    }

    protected function mergeTags($currentTags, $newTags)
    {
        if (empty($currentTags)) {
            return $newTags;
        }

        return array_replace($currentTags, $newTags);
    }
}
