<?php

namespace MageSuite\Opengraph\Service;

class TagsCollector
{
    protected $dataProviders;

    public function __construct(array $dataProviders)
    {
        $this->dataProviders = $dataProviders;
    }

    public function getTags($pageType = null)
    {
        if(empty($pageType) or !isset($this->dataProviders[$pageType])){
            $pageType = \MageSuite\Opengraph\Helper\PageType::DEFAULT_PAGE_TYPE;
        }

        $dataProviders = $this->sortProviders($this->dataProviders[$pageType]);

        $tags = [];

        foreach($dataProviders as $dataProvider){
            $dataProviderClass = $dataProvider['class'];

            if(!is_object($dataProviderClass) or !$dataProviderClass instanceof \MageSuite\Opengraph\DataProviders\TagProviderInterface){
                continue;
            }

            $tags = $this->mergeTags($tags, $dataProviderClass->getTags());
        }

        return $tags;
    }

    private function sortProviders($dataProviders)
    {
        usort($dataProviders, function ($a, $b)
        {
            $aSortOrder = $a['sortOrder'] ?? 0;
            $bSortOrder = $b['sortOrder'] ?? 0;

            return ($aSortOrder <=> $bSortOrder);
        });

        return $dataProviders;
    }

    private function mergeTags($currentTags, $newTags)
    {
        if(empty($currentTags)){
            return $newTags;
        }

        return array_replace($currentTags, array_filter($newTags));
    }


}