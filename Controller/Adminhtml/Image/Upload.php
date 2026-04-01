<?php

declare(strict_types=1);

namespace MageSuite\Opengraph\Controller\Adminhtml\Image;

class Upload extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Cms::page';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \MageSuite\Opengraph\Service\Processor\UploadImageFactory $uploadImage
    ) {
        parent::__construct($context);
    }

    public function execute() //phpcs:ignore
    {
        try {
            $result = $this->uploadImage->create()->processUpload('og_image', \MageSuite\Opengraph\Service\CmsImageUrlProvider::OPENGRAPH_CMS_IMAGE_PATH);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)->setData($result);
    }
}
