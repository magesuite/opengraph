<?php
namespace MageSuite\Opengraph\Observer;

class BeforeCmsPageSave implements \Magento\Framework\Event\ObserverInterface
{
    protected \Magento\Framework\App\RequestInterface $request;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pageObject = $observer->getObject();

        $params = $this->request->getParams();
        if ($params && (!isset($params['og_image']) || !$params['og_image'])) {
            $pageObject->setData('og_image', null);
            return;
        }

        $cmsImageData = $pageObject->getData('og_image');

        if ($cmsImageData && isset($cmsImageData[0]['name'])) {
            $pageObject->setData('og_image', $cmsImageData[0]['name']);
        }
    }
}
