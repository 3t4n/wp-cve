<?php

/**
 * Assets.
 */
namespace DhlVendor\WpDesk\WooCommerce\ShippingMethod;

/**
 * Class SettingsField
 * @package WpDesk\WooCommerce\ShippingMethod
 */
class Assets
{
    public function enqueue($base_url, $suffix = '', $scripts_version = '')
    {
        \wp_register_style('fs_boxes_css', \trailingslashit($base_url) . 'assets/css/style.css', array(), $scripts_version);
        \wp_enqueue_style('fs_boxes_css');
        \wp_enqueue_script('fs_boxes', \trailingslashit($base_url) . 'assets/js/settings-field-boxes.js', array(), $scripts_version);
    }
}
