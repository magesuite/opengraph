<?php

namespace MageSuite\Opengraph\Test\Integration\Helper;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Opengraph\Helper\Configuration
     */
    private $configuration;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->configuration = $this->objectManager->get(\MageSuite\Opengraph\Helper\Configuration::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store facebook/opengraph/fb_app_id fb_test
     */
    public function testItReturnCorrectFbAppId()
    {
        $fbApp = $this->configuration->getFbAppId();

        $this->assertEquals('fb_test', $fbApp);
    }
}
