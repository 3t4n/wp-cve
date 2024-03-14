<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Helpers;
defined('ABSPATH') or die();

class CompatibleCheck
{
    /**
     * Compatability check
     *
     * @param $active_check
     * @return bool|void
     */
    function initCheck($active_check = false)
    {
        $status = true;
        if (!$this->isEnvironmentCompatible()) {
            if ($active_check) {
                exit(esc_html(WLL_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum PHP version of ', 'wp-loyalty-rules') . WLL_MINIMUM_PHP_VERSION));
            }
            $status = false;
        }
        if (!$this->isWordPressCompatible()) {
            if ($active_check) {
                exit(esc_html(WLL_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum Wordpress version of ', 'wp-loyalty-rules') . WLL_MINIMUM_WP_VERSION));
            }
            $status = false;
        }
        if (!$this->isWooCompatible()) {
            if ($active_check) {
                exit(esc_html(WLL_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum Woocommerce version of ', 'wp-loyalty-rules') . WLL_MINIMUM_WC_VERSION));
            }
            $status = false;
        }
        return $status;
    }

    /**
     * Checks PHP version
     * @return bool|int
     */
    protected function isEnvironmentCompatible()
    {
        return version_compare(PHP_VERSION, WLL_MINIMUM_PHP_VERSION, '>=');
    }

    /**
     * Checks Wordpress version
     * @return bool|int
     */
    public function isWordPressCompatible()
    {
        if (!WLL_MINIMUM_WP_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare(get_bloginfo('version'), WLL_MINIMUM_WP_VERSION, '>=');
        }
        return $is_compatible;
    }

    /**
     * Checks wordpress vesion
     * @return bool|int
     */
    function isWooCompatible()
    {
        $woo_version = $this->woo_version();
        if (!WLR_MINIMUM_WC_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare($woo_version, WLL_MINIMUM_WC_VERSION, '>=');
        }
        return $is_compatible;
    }

    /**
     * Getting woocommerce version
     * @return string
     */
    function woo_version()
    {
        require_once ABSPATH . '/wp-admin/includes/plugin.php';
        $plugin_folder = get_plugins('/woocommerce');
        $plugin_file = 'woocommerce.php';
        $wc_installed_version = '1.0.0';
        if (isset($plugin_folder[$plugin_file]['Version'])) {
            $wc_installed_version = $plugin_folder[$plugin_file]['Version'];
        }
        return $wc_installed_version;
    }

    function inactiveNotice()
    {
        $message = '';
        if (!$this->isEnvironmentCompatible()) {
            $message = WLL_PLUGIN_NAME . __(' is inactive. Because, it requires minimum PHP version of ', 'wp-loyalty-rules') . WLL_MINIMUM_PHP_VERSION;
        } elseif (!$this->isWordPressCompatible()) {
            $message = WLL_PLUGIN_NAME . __(' is inactive. Because, it requires minimum Wordpress version of ', 'wp-loyalty-rules') . WLL_MINIMUM_WP_VERSION;
        } elseif (!$this->isWoocommerceActive()) {
            $message = __('Woocommerce must installed and activated in-order to use ', 'wp-loyalty-rules') . WLL_PLUGIN_NAME;
        } elseif (!$this->isWooCompatible()) {
            $message = WLL_PLUGIN_NAME . __(' is inactive. Because, it requires minimum Woocommerce version of ', 'wp-loyalty-rules') . WLL_MINIMUM_WC_VERSION;
        }
        return '<div class="error"><p><strong>' . esc_html($message) . '</strong></p></div>';
    }

    function isWoocommerceActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('woocommerce/woocommerce.php', $active_plugins, false) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }
}