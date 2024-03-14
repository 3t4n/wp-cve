<?php

namespace DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin;

interface HookablePluginDependant extends \DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Set Plugin.
     *
     * @param AbstractPlugin $plugin Plugin.
     *
     * @return null
     */
    public function set_plugin(\DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin $plugin);
    /**
     * Get plugin.
     *
     * @return AbstractPlugin.
     */
    public function get_plugin();
}
