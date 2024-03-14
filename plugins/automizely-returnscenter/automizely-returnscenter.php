<?php
/**
 * Plugin Name: AfterShip Returns – automated return, exchange, and refund management for WooCommerce
 * Description: Offer scalable, self-service returns, exchanges, warranties, and refunds while automating status notifications and integrating reverse logistics
 * Version: 1.0.14
 * Author: AfterShip
 * Author URI: https://www.returnscenter.com/
 * Copyright: © AfterShip
 * License: GPL2
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

define('AUTOMIZELY_RETURNSCENTER_VERSION', '1.0.14');
define('AUTOMIZELY_RETURNSCENTER_PATH', dirname(__FILE__));
define('AUTOMIZELY_RETURNSCENTER_URL', plugins_url() . '/' . basename(AUTOMIZELY_RETURNSCENTER_PATH));


class Automizely_ReturnsCenter
{
    /**
     * Instance of Automizely_ReturnsCenter_Actions.
     *
     * @var Automizely_ReturnsCenter_Actions
     */
    public $actions;

    /**
     * Plugin dir.
     *
     * @var string
     */
    public $plugin_dir;

    public function __construct()
    {
        $this->plugin_dir  = untrailingslashit(plugin_dir_path(__FILE__));

        // Include required files.
        $this->includes();

        // Check if woocommerce active.
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true) ) {
            add_filter('woocommerce_rest_api_get_rest_namespaces', array( $this, 'add_rest_api' ));
        }

        add_action('admin_enqueue_scripts', array($this, 'automizely_returnscenter_add_admin_css'));
        // Remove other plugins notice message for setting and landing page
        add_action('admin_enqueue_scripts', array( $this, 'returnscenter_admin_remove_notice_style' ));

        // register admin pages for the plugin
        add_action('admin_menu', array($this, 'automizely_returnscenter_connect_page'));

        // Translation-ready
        add_action('plugins_loaded', array($this, 'automizely_returnscenter_add_textdomain'));

        add_action('admin_init', array( $this, 'automizely_returnscenter_plugin_active' ));
        add_action('admin_footer', array($this, 'deactivate_modal'));

        // show plugin tip message when update version
        add_action('admin_notices', array( $this->actions, 'show_notices' ));

        add_filter('rest_shop_order_collection_params', array( $this->actions, 'add_collection_params' ), 10, 1);
        add_filter('rest_shop_coupon_collection_params', array( $this->actions, 'add_collection_params' ), 10, 1);
        add_filter('rest_product_collection_params', array( $this->actions, 'add_collection_params' ), 10, 1);
        add_filter('woocommerce_rest_orders_prepare_object_query', array( $this->actions, 'add_query' ), 10, 2);
        add_filter('woocommerce_rest_product_object_query', array( $this->actions, 'add_query' ), 10, 2);
        add_filter('woocommerce_rest_shop_coupon_object_query', array( $this->actions, 'add_query' ), 10, 2);
        add_filter('woocommerce_rest_customer_query', array( $this->actions, 'add_customer_query' ), 10, 2);

        // migrate order notes to order meta
        add_action( 'woocommerce_rest_insert_order_note', array( $this->actions, 'woocommerce_rest_insert_order_note' ), 10, 2 );
		add_action('woocommerce_order_note_added', array( $this->actions, 'woocommerce_order_note_added' ), 10, 2 );


        register_activation_hook(__FILE__, array( 'Automizely_ReturnsCenter', 'install' ));
        register_deactivation_hook(__FILE__, array( 'Automizely_ReturnsCenter', 'deactivation' ));
        register_uninstall_hook(__FILE__, array( 'Automizely_ReturnsCenter', 'deactivation' ));

        // show tip message duration: 7 days
        set_transient('wc-automizely-returnscenter-plugin' . AUTOMIZELY_RETURNSCENTER_VERSION, 'alive', 7 * 24 * 3600);
    }

    /**
     * Include required files.
     *
     * @since 1.4.0
     */
    private function includes()
    {
        include $this->plugin_dir . '/includes/class-returnscenter-actions.php';
        $this->actions = Automizely_ReturnsCenter_Actions::get_instance();
        include_once $this->plugin_dir . '/includes/api/returnscenter/v1/class-am-rc-rest-settings-controller.php';
    }

    /**
     * Description: Will add the backend CSS required for the display of automizely-returnscenter settings page.
     */
    public function automizely_returnscenter_add_admin_css()
    {
        wp_register_style('automizely-returnscenter-admin', plugins_url('assets/css/index.css', __FILE__), array(), '1.0');
        wp_enqueue_style('automizely-returnscenter-admin');
        wp_register_style('automizely-returnscenter-admin', plugins_url('assets/css/normalize.css', __FILE__), array(), '1.0');
        wp_enqueue_style('automizely-returnscenter-admin');
    }

    /**
     * Remove other plugins notice message for setting and landing page
     */
    public function returnscenter_admin_remove_notice_style()
    {
        $page_screen          = get_current_screen()->id;
        $screen_remove_notice = array(
            'toplevel_page_automizely-returnscenter-index',
        );

        if (current_user_can('manage_options') && in_array($page_screen, $screen_remove_notice) ) {
            echo '<style>.update-nag, .updated, .notice, #wpfooter, .error, .is-dismissible { display: none; }</style>';
        }
    }

    /**
     * Description: Will add the landing page into the Menu System of Wordpress
     * Parameters:  None
     */
    public function automizely_returnscenter_connect_page()
    {
        add_menu_page(
            "AfterShip Returns",
            "AfterShip Returns",
            'manage_options',
            'automizely-returnscenter-index',
            array($this, 'automizely_returnscenter_index'),
            AUTOMIZELY_RETURNSCENTER_URL . '/assets/images/sidebar-aftership-returns.svg'
        );
    }

    /**
     * Description:
     * Parameters:  None
     */
    public function automizely_returnscenter_index()
    {
        include_once AUTOMIZELY_RETURNSCENTER_PATH . '/views/returnscenter_landing_view.php';
    }

    /**
     * Description: --
     * Parameters:  None
     */
    public function automizely_returnscenter_add_textdomain()
    {
        load_plugin_textdomain('automizely_returnscenter', false, dirname(plugin_basename(__FILE__)) . '/lang/');
    }

    /**
     * Description: Called via admin_init action in Constructor
     *              Will redirect to the plugin page if the automizely_returnscenter_plugin_redirection is setup.
     *              Once redirection is pushed, the key is removed.
     * Return:      void
     **/
    function automizely_returnscenter_plugin_active()
    {
        if (get_option('automizely_returnscenter_plugin_redirection', false)) {
            delete_option('automizely_returnscenter_plugin_redirection');
            exit(wp_redirect("admin.php?page=automizely-returnscenter-index"));
        }
    }

    public function deactivate_modal()
    {
        if (current_user_can('manage_options') ) {
            global $pagenow;

            if ('plugins.php' !== $pagenow ) {
                return;
            }
        }
    }

    /**
     * activate
     */
    public static function install()
    {
        // We want to take the user to the Plugin Page on installation.
        add_option('automizely_returnscenter_plugin_redirection', true);
    }

    /**
     * Remove settings when plugin deactivation.
     **/
    public static function deactivation()
    {
        $legacy_options              = get_option('returnscenter_option_name') ? get_option('returnscenter_option_name') : array();
        $legacy_options['connected'] = false;
        update_option('returnscenter_option_name', $legacy_options);

        // Revoke ReturnsCenter plugin REST oauth key when user Deactivation | Delete plugin
        call_user_func(array( 'Automizely_ReturnsCenter_Actions', 'revoke_returnscenter_key' ));

        // If At all this was not removed already
        delete_option('automizely_returnscenter_plugin_redirection');
    }

    /**
     * Register REST API endpoints
     *
     * @param  array $controllers REST Controllers.
     * @return array
     */
    function add_rest_api( $controllers )
    {
        $controllers['wc/returnscenter/v1']['settings'] = 'AM_RC_REST_Settings_Controller';
        return $controllers;
    }

}


new Automizely_ReturnsCenter();
?>
