<?php

namespace OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin;

interface HookablePluginDependant extends \OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Set Plugin.
     *
     * @param AbstractPlugin $plugin Plugin.
     *
     * @return null
     */
    public function set_plugin(\OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin $plugin);
    /**
     * Get plugin.
     *
     * @return AbstractPlugin.
     */
    public function get_plugin();
}
