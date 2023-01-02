<?php

namespace MageSuite\Opengraph\Helper;

class PageType extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DEFAULT_PAGE_TYPE = 'default';

    protected $allowedRouteNames = ['cms', 'catalog'];

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->registry = $registry;
    }

    public function getPageType()
    {
        $routeName = $this->request->getRouteName();

        if (!in_array($routeName, $this->allowedRouteNames)) {
            return self::DEFAULT_PAGE_TYPE;
        }

        $product = $this->registry->registry('product');

        if ($product) {
            return 'product';
        }

        $category = $this->registry->registry('current_category');

        if ($category) {
            return 'category';
        }

        return $routeName;
    }
}
