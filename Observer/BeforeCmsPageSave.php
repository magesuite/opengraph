<?php
namespace MageSuite\Opengraph\Observer;

class BeforeCmsPageSave implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pageObject = $observer->getObject();

        $cmsImageData = $pageObject->getData('og_image');

        if ($cmsImageData && isset($cmsImageData[0]['name'])) {
            $pageObject->setData('og_image', $cmsImageData[0]['name']);
        }
    }
}