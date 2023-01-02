<?php

namespace MageSuite\Opengraph\Service\Processor;

class UploadImage
{
    protected \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory;
    protected \Magento\Framework\App\Filesystem\DirectoryList $directoryList;
    protected \Magento\Framework\Filesystem $filesystem;
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;
    protected \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider;
    protected \Magento\Framework\App\RequestInterface $request;

    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider,
        \Magento\Framework\App\RequestInterface $request,
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->cmsImageUrlProvider = $cmsImageUrlProvider;
        $this->request = $request;
    }

    public function processUpload($imageName, $path)
    {
        $file = $this->request->getFiles($imageName);

        if (!isset($file['name'])) {
            $result = ['error' => __('Image file has been not uploaded'), 'errorcode' => __('Image file has been not uploaded')];
            return $result;
        }

        $uploader = $this->uploaderFactory->create(['fileId' => $imageName]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg']);
        $uploader->setAllowRenameFiles(false);
        $uploader->setFilesDispersion(false);

        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath($path);

        $result = $uploader->save($path);

        $imagePath = $uploader->getUploadedFileName();

        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->cmsImageUrlProvider->getImageUrl($imagePath, $path);
        $result['name'] = $result['file'];

        return $result;
    }
}
