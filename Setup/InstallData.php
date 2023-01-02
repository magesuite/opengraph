<?php

namespace MageSuite\Opengraph\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetupInterface;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;
        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $this->addCategoryAttributes();
        $this->addProductAttributeGroup();
        $this->addProductAttributes();
    }

    private function addCategoryAttributes()
    {
        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'og_title')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'og_title',
                [
                    'type' => 'varchar',
                    'label' => 'Open Graph title',
                    'input' => 'text',
                    'visible' => true,
                    'required' => false,
                    'sort_order' => 10,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Social Media',
                    'note' => 'If empty, value from "Meta title" field will be used. If this field is also empty, category name will be used.'
                ]
            );
        }

        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'og_image')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'og_image',
                [
                    'type' => 'varchar',
                    'label' => 'Open Graph image',
                    'backend' => \Magento\Catalog\Model\Category\Attribute\Backend\Image::class,
                    'input' => 'image',
                    'visible' => true,
                    'required' => false,
                    'sort_order' => 20,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Social Media',
                    'note' => 'If empty, image from "Image Teaser" field will be used.'
                ]
            );
        }

        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'og_description')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'og_description',
                [
                    'type' => 'text',
                    'label' => 'Open Graph Description',
                    'input' => 'textarea',
                    'visible' => true,
                    'required' => false,
                    'sort_order' => 30,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Social Media',
                    'note' => 'If empty, value from "Meta description" field will be used.'
                ]
            );
        }

        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'og_type')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'og_type',
                [
                    'type' => 'varchar',
                    'label' => 'Open Graph Type',
                    'input' => 'select',
                    'source' => \MageSuite\Opengraph\Model\Entity\Attribute\Source\Type::class,
                    'visible' => true,
                    'required' => false,
                    'sort_order' => 40,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Social Media',
                    'note' => 'If empty, "website" value will be used.'
                ]
            );
        }
    }

    private function addProductAttributeGroup()
    {
        $setIds = $this->eavSetup->getAllAttributeSetIds(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE);

        foreach ($setIds as $setId) {
            $searchGroupSortOrder = $this->eavSetup->getAttributeGroup(
                \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,
                $setId,
                'Search Engine Optimization',
                'sort_order'
            );

            $socialMediaGroup = $this->eavSetup->getAttributeGroup(
                \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,
                $setId,
                'Social Media'
            );

            if (!$socialMediaGroup) {
                $this->eavSetup->addAttributeGroup(
                    \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,
                    $setId,
                    'Social Media',
                    $searchGroupSortOrder + 1
                );
            }
        }
    }

    private function addProductAttributes()
    {
        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'og_title')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'og_title',
                [
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'type' => 'varchar',
                    'unique' => false,
                    'group' => 'Social Media',
                    'label' => 'Open Graph Title',
                    'input' => 'text',
                    'default' => false,
                    'required' => false,
                    'sort_order' => 10,
                    'user_defined' => 1,
                    'searchable' => false,
                    'filterable' => false,
                    'filterable_in_search' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'note' => 'If empty, value from "Meta title" field will be used. If this field is also empty, product name will be used.'
                ]
            );
        }

        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'og_description')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'og_description',
                [
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'type' => 'text',
                    'unique' => false,
                    'group' => 'Social Media',
                    'label' => 'Open Graph Description',
                    'input' => 'text',
                    'default' => false,
                    'required' => false,
                    'sort_order' => 20,
                    'user_defined' => 1,
                    'searchable' => false,
                    'filterable' => false,
                    'filterable_in_search' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'note' => 'If empty, value from "Meta description" field will be used. If this field is also empty, "Short Description" will be used.',
                ]
            );
        }

        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'og_image')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'og_image',
                [
                    'type' => 'varchar',
                    'label' => 'Open Graph Image',
                    'input' => 'media_image',
                    'frontend' => \Magento\Catalog\Model\Product\Attribute\Frontend\Image::class,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'filterable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'sort_order' => 30,
                    'required' => false,
                    'note' => 'If empty, base product image will be used.'
                ]
            );
        }

        if (!$this->eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'og_type')) {
            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'og_type',
                [
                    'type' => 'varchar',
                    'label' => 'Open Graph Type',
                    'input' => 'select',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'source' => \MageSuite\Opengraph\Model\Entity\Attribute\Source\Type::class,
                    'filterable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'sort_order' => 40,
                    'required' => false,
                    'group' => 'Social Media',
                    'note' => 'If empty, "product" value will be used.'
                ]
            );
        }
    }
}
