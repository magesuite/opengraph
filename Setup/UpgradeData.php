<?php

namespace MageSuite\Opengraph\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
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
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;

        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
    }

    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'og_type',
                'source_model',
                \MageSuite\Opengraph\Model\Entity\Attribute\Source\Type::class
            );

            $this->eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'og_type',
                'source_model',
                \MageSuite\Opengraph\Model\Entity\Attribute\Source\Type::class
            );
        }
    }
}