<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var $mediaConfig \Magento\Catalog\Model\Product\Media\Config */
$mediaConfig = $objectManager->get(\Magento\Catalog\Model\Product\Media\Config::class);

/** @var $mediaDirectory \Magento\Framework\Filesystem\Directory\WriteInterface */
$mediaDirectory = $objectManager
    ->get(\Magento\Framework\Filesystem::class)
    ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

$images = [
    'magento_image.jpg',
    'og_image.png'
];

foreach ($images as $fileName) {
    $targetDirPath = $mediaConfig->getBaseMediaPath() . str_replace('/', DIRECTORY_SEPARATOR, '/'.$fileName[0].'/'.$fileName[1].'/');
    $targetTmpDirPath = $mediaConfig->getBaseTmpMediaPath() . str_replace('/', DIRECTORY_SEPARATOR, '/'.$fileName[0].'/'.$fileName[1].'/');

    $mediaDirectory->create($targetDirPath);
    $mediaDirectory->create($targetTmpDirPath);

    $targetTmpFilePath = $mediaDirectory->getAbsolutePath() . DIRECTORY_SEPARATOR . $targetTmpDirPath . DIRECTORY_SEPARATOR . $fileName;

    copy(__DIR__ . '/' . $fileName, $targetTmpFilePath);
}
