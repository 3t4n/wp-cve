<?php

namespace ECFFW\App\Helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WooCommerce
{
    /**
     * Check the WooCommerce is active or not.
     * @return bool
     */
    public static function isActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('woocommerce/woocommerce.php', $active_plugins, false) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }

    /**
     * Display Woocommerce Missing Notice.
     */
    public static function missingNotice()
    {
        add_action('admin_notices', function () {
            ?>
                <div class="notice notice-error">
                    <p> 
                        <strong><?php _e(ECFFW_PLUGIN_NAME, 'extra-checkout-fields-for-woocommerce'); ?></strong> 
                        <?php _e("requires the", 'extra-checkout-fields-for-woocommerce'); ?>
                        <a href="https://wordpress.org/plugins/woocommerce" target="_blank">WooCommerce</a>
                        <?php _e("plugin to be installed and active.", 'extra-checkout-fields-for-woocommerce'); ?>
                    </p>
                </div>
            <?php
        }, 1);
    }
}
