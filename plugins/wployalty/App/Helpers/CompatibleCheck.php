<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die;

class CompatibleCheck
{
    /**
     * initial check
     * @param bool $active_check
     * @return bool
     */
    function init_check($active_check = false)
    {
        $status = true;
        if (!$this->isEnvironmentCompatible()) {
            if ($active_check) {
                exit(esc_html(WLR_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum PHP version of ', 'wp-loyalty-rules') . WLR_MINIMUM_PHP_VERSION));
            }
            $status = false;
        }
        if (!$this->isWordPressCompatible()) {
            if ($active_check) {
                exit(esc_html(WLR_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum WordPress version of ', 'wp-loyalty-rules') . WLR_MINIMUM_WP_VERSION));
            }
            $status = false;
        }
        if (!$this->isWoocommerceActive()) {
            if ($active_check) {
                exit(esc_html(__('WooCommerce should be installed and activated in order to use', 'wp-loyalty-rules') . WLR_PLUGIN_NAME));
            }
            $status = false;
        }
        if (!$this->isWooCompatible()) {
            if ($active_check) {
                exit(esc_html(WLR_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum Woocommerce version of ', 'wp-loyalty-rules') . WLR_MINIMUM_WC_VERSION));
            }
            $status = false;
        }
        return $status;
    }

    protected function isEnvironmentCompatible()
    {
        return version_compare(PHP_VERSION, WLR_MINIMUM_PHP_VERSION, '>=');
    }

    public function isWordPressCompatible()
    {
        if (!WLR_MINIMUM_WP_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare(get_bloginfo('version'), WLR_MINIMUM_WP_VERSION, '>=');
        }
        return $is_compatible;
    }

    function isWoocommerceActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('woocommerce/woocommerce.php', $active_plugins, false) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }

    function isWooCompatible()
    {
        $woo_version = $this->woo_version();
        if (!WLR_MINIMUM_WC_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare($woo_version, WLR_MINIMUM_WC_VERSION, '>=');
        }
        return $is_compatible;
    }

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

    /**
     * Admin Message for display any requirement missing
     * @return void
     */
    public function inactiveNotice()
    {
        $message = $this->getCompatibleContent();
        echo '<div class="error"><p><strong>' . esc_html($message) . '</strong></p></div>';
    }

    public function getCompatibleContent()
    {
        $message = '';
        if (!$this->isEnvironmentCompatible()) {
            $message = WLR_PLUGIN_NAME . __(' requires minimum PHP version of ', 'wp-loyalty-rules') . WLR_MINIMUM_PHP_VERSION;
        } elseif (!$this->isWordPressCompatible()) {
            $message = WLR_PLUGIN_NAME . __(' requires minimum WordPress version of ', 'wp-loyalty-rules') . WLR_MINIMUM_WP_VERSION;
        } elseif (!$this->isWoocommerceActive()) {
            $message = __('WooCommerce should be installed and activated in order to use ', 'wp-loyalty-rules') . WLR_PLUGIN_NAME;
        } elseif (!$this->isWooCompatible()) {
            $message = WLR_PLUGIN_NAME . __(' requires minimum Woocommerce version of ', 'wp-loyalty-rules') . WLR_MINIMUM_WC_VERSION;
        }
        return $message;
    }
}
