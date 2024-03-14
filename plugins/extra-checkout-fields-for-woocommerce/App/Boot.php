<?php

namespace ECFFW\App;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Controllers\Activator;
use ECFFW\App\Controllers\Assets;
use ECFFW\App\Controllers\Deactivator;
use ECFFW\App\Controllers\Admin\Settings;
use ECFFW\App\Controllers\Frontend\Checkout;
use ECFFW\App\Helpers\CheckCompatible;
use ECFFW\App\Helpers\WooCommerce;
use ECFFW\App\Views\Admin\Orders;
use ECFFW\App\Views\Frontend\FormField;
use ECFFW\App\Views\Frontend\OrderDetails;

class Boot
{
    /**
     * Boot constructor.
     */
    public function __construct()
    {
        new Activator();
        new Deactivator();
        $this->initHooks();
    }

    /**
     * Initialize all the plugin related hooks.
     */
    public function initHooks()
    {
        if (WooCommerce::isActive()) {
            add_action('plugins_loaded', array($this, 'initPlugin'));
        } else {
            WooCommerce::missingNotice();
        }

        add_action('init', array($this, 'loadTextdomain'));
    }

    /**
     * Initialize plugin.
     * This will run only if woocommerce is activated.
     */
    public function initPlugin()
    {
        new Assets();

        if (is_admin()) {
            new Settings();
            new Orders();
        }

        if ((!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON')) {
            new Checkout();
            new FormField();
            new OrderDetails();
        }
    }

    /**
     * Initialize plugin internationalization.
     * For plugin translation.
     */
    public function loadTextdomain() 
    {
        load_plugin_textdomain('extra-checkout-fields-for-woocommerce', false, dirname(plugin_basename(ECFFW_PLUGIN_FILE) ) . '/i18n/languages');
    }
}
