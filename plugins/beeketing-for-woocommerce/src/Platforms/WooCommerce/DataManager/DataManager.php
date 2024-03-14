<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/12/18
 * Time: 12:57 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager;


use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\ResourceManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data\ResourceManager;

class DataManager
{

    /**
     * @var SettingManager
     */
    public $settingManager;

    /**
     * @var ResourceManagerAbstract
     */
    public $resourceManager;

    /**
     * DataManager constructor.
     */
    public function __construct()
    {
        $this->settingManager = new SettingManager();
        $this->resourceManager = new ResourceManager();
    }

    /**
     * Get plugin version
     * @return string
     */
    public function getPluginVersion()
    {
        return BEEKETINGWOOCOMMERCE_VERSION;
    }

}