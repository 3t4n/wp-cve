<?php

namespace Woo_MP;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use YeEasyAdminNotices\V1\AdminNotice;

defined( 'ABSPATH' ) || die;

/**
 * Initialization.
 */
class Woo_MP {

    /**
     * Initialize the plugin.
     *
     * @return void
     */
    public function init() {
        $this->define_constants();
        $this->init_hooks();
        $this->init_ajax_hooks();
    }

    /**
     * Define constants.
     *
     * @return void
     */
    private function define_constants() {
        define( 'WOO_MP_PAYMENT_PROCESSOR', str_replace( '_', '-', get_option( 'woo_mp_payment_processor' ) ) );
        define( 'WOO_MP_CONFIG_HELP', 'If you need help, you can find instructions <a href="https://wordpress.org/plugins/woo-mp/#installation" target="_blank">here</a>.' );
        define( 'WOO_MP_SETTINGS_URL', admin_url( 'admin.php?page=wc-settings&tab=manual_payment' ) );
        define( 'WOO_MP_UPGRADE_URL', 'https://www.woo-mp.com/#section-pricing' );
    }

    /**
     * Register hooks.
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'admin_init', [ new Update_Routines(), 'run_routines' ] );
        add_action( 'in_admin_header', [ $this, 'setup_notice' ] );
        add_filter( 'plugin_action_links_' . WOO_MP_BASENAME, [ $this, 'add_action_links' ] );
        add_action( 'in_plugin_update_message-' . WOO_MP_BASENAME, [ new Upgrade_Notices(), 'output_upgrade_notice' ], 10, 2 );
        add_action( 'add_meta_boxes_shop_order', [ Controllers\Payment_Meta_Box_Controller::class, 'add_meta_box' ] );
        add_action( 'add_meta_boxes_woocommerce_page_wc-orders', [ Controllers\Payment_Meta_Box_Controller::class, 'add_meta_box' ] );
        add_filter( 'woo_mp_payments_meta_box_title', [ new Controllers\Rating_Request_Controller(), 'append_rating_request' ] );
        add_action( 'before_woocommerce_init', [ $this, 'declare_wc_feature_compat' ] );
        add_filter( 'woocommerce_get_settings_pages', [ Settings_Page::class, 'get_pages' ] );
    }

    /**
     * Register AJAX routes.
     *
     * @return void
     */
    private function init_ajax_hooks() {
        add_action( 'wp_ajax_woo_mp_process_transaction', [ new Controllers\Transaction_Controller(), 'process_transaction' ] );
        add_action( 'wp_ajax_woo_mp_get_unpaid_order_balance', [ new Controllers\Charge_Amount_Autofill_Controller(), 'get_unpaid_order_balance' ] );
        add_action( 'wp_ajax_woo_mp_rated', [ new Controllers\Rating_Request_Controller(), 'woo_mp_rated' ] );
    }

    /**
     * Display a welcome notice.
     *
     * @return void
     */
    public function setup_notice() {
        if ( ! Payment_Gateways::get_active_id() ) {
            AdminNotice::create( 'woo_mp_welcome' )
                ->persistentlyDismissible()
                ->info( html_entity_decode( wp_kses_post( sprintf(
                    'To get started with WooCommerce Manual Payment, ' .
                    '<a href="%s">select a payment gateway</a> and fill out your API keys.' .
                    " Once that's done, you'll be able to process payments directly from the " .
                    '<strong>Payments</strong> section at the bottom of the <strong>Edit&nbsp;order</strong> screen. %s',
                    WOO_MP_SETTINGS_URL,
                    WOO_MP_CONFIG_HELP
                ) ) ) )
                ->show();
        }
    }

    /**
     * Add action links to the plugins page.
     *
     * @param  array $links The action links.
     * @return array        The updated action links.
     */
    public function add_action_links( $links ) {
        if ( ! self::is_pro() ) {
            array_unshift( $links, sprintf( '<a href="%s" target="_blank">Upgrade</a>', WOO_MP_UPGRADE_URL ) );
        }

        array_unshift( $links, sprintf( '<a href="%s">Settings</a>', WOO_MP_SETTINGS_URL ) );

        return $links;
    }

    /**
     * Declare WooCommerce feature compatibilities.
     *
     * @param  string|null $plugin_basename The plugin basename.
     * @return void
     */
    public static function declare_wc_feature_compat( $plugin_basename = null ) {
        if (
            is_callable( [ FeaturesUtil::class, 'declare_compatibility' ] ) &&
            version_compare( WC_VERSION, '7.4.0-beta.1', '>=' )
        ) {
            FeaturesUtil::declare_compatibility( 'custom_order_tables', $plugin_basename ?: WOO_MP_BASENAME, true );
        }
    }

    /**
     * Check whether WooCommerce Manual Payment Pro Extension is installed and active.
     *
     * @return bool Whether Pro is available.
     */
    public static function is_pro() {
        return class_exists( \Woo_MP_Pro\Woo_MP_Pro::class, false );
    }

}
