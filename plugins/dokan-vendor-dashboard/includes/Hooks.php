<?php

namespace WeDevs\DokanVendorDashboard;

/**
 * Initiates necessary hooks.
 *
 * @since 1.0.0
 *
 * @return void
 */
class Hooks {
    public function __construct() {
        add_action( 'init', array( $this, 'setup_localization' ) );
        add_filter( 'dokan_settings_general_site_options', [ Settings::class, 'add_setting_to_enable_vendor_dashboard' ] );
        add_filter( 'dokan_get_edit_product_url', array( $this, 'update_edit_product_url' ), 10, 2 );
    }

    /**
     * Initialize plugin for localization.
     *
     * @uses load_plugin_textdomain()
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function setup_localization() {
        load_plugin_textdomain( 'dokan-vendor-dashboard', false, dirname( plugin_basename( DOKAN_VENDOR_DASHBOARD_FILE ) ) . '/languages/' );
    }

    /**
     * Changes product edit url for vendor dashboard.
     *
     * @param string     $url
     * @param WC_Product $product
     *
     * @return string $url
     */
    public function update_edit_product_url( $url, $product ) {
        $url =  dokan_get_navigation_url( 'products' ) . 'update/' . $product->get_id();

        return $url;
    }
}