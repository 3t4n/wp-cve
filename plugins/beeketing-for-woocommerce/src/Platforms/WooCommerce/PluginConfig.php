<?php

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce;

/**
 * @property string appJs
 * @property string $pluginName
 * @property string pluginAdminUrl
 * @property string pluginDirName
 * @property string pluginBaseName
 */
class PluginConfig
{
    private $appJs;
    private $pluginName;
    private $pluginAdminUrl;
    private $pluginDirName;
    private $pluginBaseName;

    /**
     * PluginConfig constructor.
     * @param $appJs
     * @param $namePlugin
     * @param $pluginAdminUrl
     * @param $pluginDirName
     * @param $pluginBaseName
     */
    public function __construct($appJs, $namePlugin, $pluginAdminUrl, $pluginDirName, $pluginBaseName)
    {

        $this->appJs = $appJs;
        $this->pluginName = $namePlugin;
        $this->pluginAdminUrl = $pluginAdminUrl;
        $this->pluginDirName = $pluginDirName;
        $this->pluginBaseName = $pluginBaseName;
    }

    /**
     * @return string
     */
    public function getAppJs()
    {
        return $this->appJs;
    }

    /**
     * @param string $appJs
     */
    public function setAppJs($appJs)
    {
        $this->appJs = $appJs;
    }

    /**
     * @return string
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * @param string $pluginName
     */
    public function setPluginName($pluginName)
    {
        $this->pluginName = $pluginName;
    }

    /**
     * @return string
     */
    public function getPluginAdminUrl()
    {
        return $this->pluginAdminUrl;
    }

    /**
     * @param string $pluginAdminUrl
     */
    public function setPluginAdminUrl($pluginAdminUrl)
    {
        $this->pluginAdminUrl = $pluginAdminUrl;
    }

    /**
     * @return string
     */
    public function getPluginDirName()
    {
        return $this->pluginDirName;
    }

    /**
     * @param string $pluginDirName
     */
    public function setPluginDirName($pluginDirName)
    {
        $this->pluginDirName = $pluginDirName;
    }

    /**
     * @return string
     */
    public function getPluginBaseName()
    {
        return $this->pluginBaseName;
    }

    /**
     * @param string $pluginBaseName
     */
    public function setPluginBaseName($pluginBaseName)
    {
        $this->pluginBaseName = $pluginBaseName;
    }
}