<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\DataProviders;

interface TagsPostProcessorInterface
{
    public function process(array $tags, ?string $pageType): array;
}