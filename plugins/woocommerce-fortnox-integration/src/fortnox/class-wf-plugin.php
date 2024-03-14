<?php

namespace src\fortnox;

if ( !defined( 'ABSPATH' ) ) die();

use src\admin_views\WF_General_Settings_View;
use src\admin_views\WF_Automation_Settings_View;
use src\admin_views\WF_Accounting_Settings_View;
use src\admin_views\WF_Order_Settings_View;
use src\admin_views\WF_Product_Settings_View;
use src\admin_views\WF_Shipping_Settings_View;
use src\admin_views\WF_Bulk_Settings_View;
use src\admin_views\WF_Advanced_Settings_View;
use src\fortnox\api\WF_Orders;
use src\fortnox\api\WF_Products;
use Exception;
use src\wetail\admin\WF_Admin_Settings;

class WF_Plugin {

	const TEXTDOMAIN = "woocommerce-fortnox-integration";


    /**
     * Set sequential order
     *
     * @param $order_id
     * @param $post
     */
	public static function set_sequential_order_number( $order_id, $post ){
		if( ! get_option( 'fortnox_order_number_prefix'  ) )
			return;

		if( is_array( $post ) || is_null( $post ) || ( 'shop_order' === $post->post_type && 'auto-draft' !== $post->post_status ) ) {
			$order_id = is_a( $order_id, "WC_Order" ) ? $order_id->get_id() : $order_id;
			$wc_order = wc_get_order( $order_id);
			$order_number = WF_Utils::get_order_meta_compat( $order_id, '_order_number' );
            $wc_order->update_meta_data( '_order_number', get_option( 'fortnox_order_number_prefix' ) . $order_number );
            $wc_order->save();
		}
	}

    /**
     * Get sequential order number
     *
     * @param $order_number
     * @param \WC_Order $order
     * @return mixed|string
     */
	public static function get_sequential_order_number( $order_number, $order ){
		if( $order instanceof \WC_Subscription ){
            return $order_number;
        }

		if( WF_Utils::get_order_meta_compat( $order->get_id(), '_order_number_formatted' ) ){
            return WF_Utils::get_order_meta_compat( $order->get_id(), '_order_number_formatted' );
        }

		if( ! get_option( 'fortnox_order_number_prefix'  ) ){
            return $order_number;
        }

		return get_option( 'fortnox_order_number_prefix' ) . $order_number;
	}

    /**
     * Get plugin path
     *
     * @param string $path
     * @return string
     */
	public static function get_path( $path = '' ){
		return plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . ltrim( $path, '/' );
	}

    /**
     * Get plugin URL
     *
     * @param string $path
     * @return string
     */
	public static function get_url( $path = '' ){
		return plugins_url( $path, dirname( dirname( __FILE__ ) ) );
	}

	/**
	 * Load textdomain
	 *
	 * @hook 'plugins_loaded'
	 */
	public static function load_text_domain(){
		$locale = ( is_admin()  && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale() );
		load_textdomain( SLUG, PATH . '/languages/' . NAME . '-' . $locale . '.mo' );
		load_plugin_textdomain( SLUG, false, NAME . '/languages' );
	}

	/**
	 * Add settings
	 *
	 * @hook 'admin_init'
	 */
	public static function add_settings(){

        WF_General_Settings_View::add_settings();
        WF_Automation_Settings_View::add_settings();
		WF_Accounting_Settings_View::add_settings();
		WF_Order_Settings_View::add_settings();
        WF_Shipping_Settings_View::add_settings();
        WF_Product_Settings_View::add_settings();
		WF_Bulk_Settings_View::add_settings();
		WF_Advanced_Settings_View::add_settings();
	}

	/**
	 * Add settings page
	 *
	 * @hook 'admin_menu'
	 */
	public static function add_settings_page(){
        $page = WF_Admin_Settings::add_page( [
			'slug'  => "fortnox",
			'title' => __( "Settings for the WooCommerce - Fortnox integration", self::TEXTDOMAIN ),
			'menu'  => __( "Fortnox", self::TEXTDOMAIN )
		] );

		add_action( "admin_print_footer_scripts-". $page, [__CLASS__,'render_popup_for_fortnox_sync_orders_date_range'] );
	}

	public static function render_popup_for_fortnox_sync_orders_date_range(){
		?><section id="fortnox-popups"><?php
		include FORTNOX_PLUGIN_PATH . "/assets/templates/admin/settings/template-popup-fortnox-order-sync.php";
		include FORTNOX_PLUGIN_PATH . "/assets/templates/admin/settings/template-popup-fortnox-product-sync.php";
		?></section><?php
	}

	/**
	 * Add admin scripts
	 */
	public static function add_admin_scripts(){
		wp_enqueue_script( 'mustache', self::get_url( 'assets/scripts/mustache.js' ) );
		wp_enqueue_script( 'jquery-ui-datepicker' );

		if ( is_woo_active() ) {
			wp_register_style( 'jquery-ui-style', WC()->plugin_url() . '/assets/css/jquery-ui/jquery-ui.min.css', array(), WC()->version );
		}

		wp_enqueue_style( 'jquery-ui-style'  );
		wp_enqueue_style( 'woocommerce_admin_styles'  );
		wp_enqueue_script( 'fortnox', self::get_url( 'assets/scripts/admin.js'  ), [ 'jquery', 'mustache', 'jquery-ui-datepicker' ] );

		wp_enqueue_script( 'jquery-tiptip'  );

		$l10n = [
			'Start'                                       => __( "Start", WF_Plugin::TEXTDOMAIN ),
			'Cancel'                                      => __( "Cancel", WF_Plugin::TEXTDOMAIN ),
			'Creating new...'                             => __( "Creating new...", WF_Plugin::TEXTDOMAIN ),
			'Error'                                       => __( "Error", WF_Plugin::TEXTDOMAIN ),
			'Tracking old task.'                          => __( "Tracking old task.", WF_Plugin::TEXTDOMAIN ),
			'Interrupted'                                 => __( "Interrupted", WF_Plugin::TEXTDOMAIN ),
			'Please set ranges'                           => __( "Please set ranges", WF_Plugin::TEXTDOMAIN ),
			'Processing Product ID'                       => __( "Processing Product ID", WF_Plugin::TEXTDOMAIN ),
			'Process done'                                => __( "Process done", WF_Plugin::TEXTDOMAIN ),
			'Process started'                             => __( "Process started", WF_Plugin::TEXTDOMAIN ),
			'Error occurred querying backend with status' => __( "Error occurred querying backend with status",
				WF_Plugin::TEXTDOMAIN ),
		];
		wp_localize_script( 'fortnox', 'fortnox_l10n', $l10n );

        wp_localize_script( 'fortnox', 'order_settings', [
            'fortnox_has_warehouse_module'  => get_option( 'fortnox_has_warehouse_module' ),
        ] );
		wp_enqueue_style( 'fortnox', self::get_url( 'assets/styles/fortnox-admin.css'  ) );
        wp_enqueue_style( 'font-awesome', self::get_url( 'assets/font-awesome/css/font-awesome.min.css'  ) );
	}

	public static function add_scripts_for_my_account(){
		wp_enqueue_script( 'my-account', self::get_url( 'assets/scripts/my-account.js'  ), array(), false, true );
		wp_enqueue_style( 'my-account', self::get_url( 'assets/styles/my-account.css'  ) );
	}

    /**
     * An array helper
     *
     * @param $array
     * @param $insert
     * @param $at
     * @return array
     */
	public static function array_insert( $array, $insert, $at ){
		$insert = ( array ) $insert;
		$left = array_slice( $array, 0, $at );
		$right = array_slice( $array, $at, count( $array ) );

		return $left + $insert + $right;
	}

    /**
     * Add orders table columns
     *
     * @param $columns
     * @return array
     */
	public static function add_orders_table_columns( $columns = [] ){
		$columns['fortnox'] = "Fortnox";
		return $columns;
	}

    /**
     * Print orders table column content
     *
     * @param $column_name
     * @param $post_id
     */
	public static function print_orders_table_column_content( $column_name, $post_id ){
		if( 'fortnox' != $column_name )
			return;
        $order_id = false;
        if ( is_int( $post_id ) ) {
            $order_id = $post_id;
        }
        elseif ( 'Automattic\WooCommerce\Admin\Overrides\Order' === get_class( $post_id ) )  {
            $order_id = $post_id->get_id();
        }
		$nonce = wp_create_nonce( 'fortnox_woocommerce'  );

		print '<span class="fortnox-order-row-actions">';

		print '<a href="#" class="button wetail-button wetail-icon-repeat syncOrderToFortnox" data-order-id="' . $order_id . '" data-nonce="' . $nonce . '" title="Sync order to Fortnox"></a> ';

		$synced = self::is_order_synced( $order_id );
		$display = 1 == $synced ? '' : 'wetail-hidden';

		$order = wc_get_order( $post_id );

		if ( ! $order->meta_exists( 'fortnox_invoice_number'  ) ) {
			$display = "wetail-hidden";
        }

		print '<a href="#" class="button wetail-button wetail-icon-repeat sendInvoiceToCustomer ' . $display . '" data-order-id="' . $order_id . '" data-nonce="' . $nonce . '" title="Send invoice to customer" ><i class="fa fa-send" aria-hidden="true"></i></a>';

        if( self::order_has_notices( $order_id ) && ! $synced ){
            print '<span class="exclamation" title="' . __( "Order is not synchronized. Order has warnings.", WF_Plugin::TEXTDOMAIN ) . '" </span>';
        }
        else{
            print '<span class="wetail-fortnox-status ' . ( 1 == $synced ? 'wetail-icon-check' : 'wetail-icon-cross'  ) . '" title="' . ( 1 == $synced ? __( "Order has synchronized", WF_Plugin::TEXTDOMAIN ) : __( "Order has not synchronized", WF_Plugin::TEXTDOMAIN ) ) . '"></span>';
        }

		print '<span class="spinner fortnox-spinner"></span>';

		print '</span>';
	}

    /**
     * @param $actions
     * @param \WC_Order $order
     * @return mixed
     */
	public static function add_action_to_order_row_my_account( $actions, $order ) {

		if ( $order->meta_exists( 'fortnox_invoice_number'  ) ) :
			$actions['fortnox-my-account-send-invoice'] = array(
				'url'  => '#',
				'name' => __( 'Send invoice', WF_Plugin::TEXTDOMAIN )
			);
			$actions['hidden_order_number'] = array(
				'url'  => '#',
				'name' => $order->get_id()
			);
		endif;
		return $actions;
	}

    /**
     * Add products table columns
     *
     * @param array
     * @return array $columns
     */
	public static function add_products_table_columns( $columns = [] )
	{
		$columns['fortnox'] = "Fortnox";

		return $columns;
	}

	/**
	 * Print products table column content
	 *
	 * @param $column_name
	 * @param $post_id
	 */
	public static function print_products_table_column_content( $column_name, $post_id )
	{
		if( "fortnox" != $column_name )
			return;

		$nonce = wp_create_nonce( "fortnox_woocommerce" );

		print '<a href="#" class="button wetail-button wetail-icon-repeat syncProductToFortnox" data-product-id="' . $post_id . '" data-nonce="' . $nonce . '" title="Sync product to Fortnox"></a> ';

		$synced = self::is_product_synced( $post_id );

		print '<span class="wetail-fortnox-status ' . ( 1 == $synced ? 'wetail-icon-check' : 'wetail-icon-cross'  ) . '" title="' . ( 1 == $synced ? __( "Product has synchronized", WF_Plugin::TEXTDOMAIN ) : __( "Product has not syncronized", WF_Plugin::TEXTDOMAIN ) ) . '"></span>';
		print '<span class="spinner fortnox-spinner"></span>';
	}

	/**
	 * Check if order is synced
	 *
	 * @param $order_id
	 * @return bool
	 */
	public static function is_order_synced( $order_id )
	{
		return WF_Orders::is_synced( $order_id );
	}

    /**
     * Check if order has notices
     *
     * @param $order_id
     * @return bool
     */
    public static function order_has_notices( $order_id )
    {
        return WF_Orders::has_notices( $order_id );
    }

	/**
	 * Check if product is synced
	 *
	 * @param $product_id
     * @return bool
	 */
	public static function is_product_synced( $product_id )
	{
		return WF_Products::is_synced( $product_id );
	}

	/**
	 * Add Fortnox meta boxes to Edit Product and Order views
	 */
	public static function add_meta_boxes()
	{
		add_meta_box(
			'fortnox_product_meta_box',
			__( "Fortnox", WF_Plugin::TEXTDOMAIN ),
			[ __CLASS__, 'render_product_meta_box' ],
			"product",
			"side",
			"high"
		);

		add_meta_box(
			'fortnox_order_meta_box',
			__( "Fortnox", WF_Plugin::TEXTDOMAIN ),
			[ __CLASS__, 'render_order_meta_box' ],
			"shop_order",
			"side",
			"high"
		);
	}

	/**
	 * Render Product meta box
	 */
	public static function render_product_meta_box()
	{
		print '<p><label><input type="checkbox" name="fortnox_sync_product" ' . checked( get_option( 'fortnox_auto_sync_products'  ), '1', false ) . '> ' . __( "Sync changes to Fortnox", WF_Plugin::TEXTDOMAIN ) . '</label></p>';
	}

	/**
	 * Render Order meta box
	 */
	public static function render_order_meta_box()
	{
		print '<p><label><input type="checkbox" name="fortnox_sync_order" ' . checked( get_option( 'fortnox_auto_sync_orders'  ), '1', false ) . '> ' . __( "Sync changes to Fortnox", WF_Plugin::TEXTDOMAIN ) . '</label></p>';
	}

	# Sync changes to Fortnox ---
	public static function sync_changes_to_fortnox( $post_id )
	{

		if ( isset( $_POST['post_type'] ) ) {

			# Sync Product
			if( get_post_type( $post_id ) ==  'product' && ! empty( $_POST['fortnox_sync_product'] ) ) {

				# Check whether the post is revision
				if( get_post_status( $post_id ) !== 'publish' ) return;

				try {
					WF_Products::sync( $_POST['ID'], $sync_stock = true );
				}
				catch( \Exception $error ) {
					// Silently fail
				}

			# Sync Order
			} else if ( get_post_type( $post_id ) ==  'shop_order' && ! empty( $_POST['fortnox_sync_order'] ) ) {

				# Check whether the post is auto-draft
				if( get_post_status( $post_id ) == 'auto-draft'  ) return;

				try {
					WF_Orders::sync( $_POST['ID'] );
				}
				catch( \Exception $error ) {
					// Silanetly fail
				}
			}
		}
	}

	public static function show_organization_number_form_field( $address_fields ) {
		$billing = $address_fields['billing'];
		$res = array_slice( $billing, 0, 3, true ) +
		array( 'billing_company_number' => array(
				'label'        => __( 'Organization registration number', WF_Plugin::TEXTDOMAIN ),
				'class'        => array( 'form-row-wide'  ),
				'priority'     => 31
		) ) +
		array_slice( $billing, 3, count( $billing ) - 3, true );
		$address_fields['billing'] = $res;

		return $address_fields;
	}

    /**
     * @since 4.4.3
     */
    public static function save_billing_company_number( $order_id ){
        #TODO only save if needed
        if ( isset( $_POST['_billing_company_number'] ) ) {
            $billing_company_number = sanitize_text_field( $_POST['_billing_company_number'] );
            $wc_order = wc_get_order( $order_id );
            $wc_order->update_meta_data( '_billing_company_number', $billing_company_number );
            $wc_order->save();
        }
    }

    /**
     * @param \WC_Order $order
     */
	public static function custom_checkout_field_display_admin_order_meta( $order ){
        woocommerce_form_field( '_billing_company_number', array(
            'type'          => 'text',
            'label'         => __( 'Organization registration number', WF_Plugin::TEXTDOMAIN ),
            'placeholder'   => __( 'Organization registration number', WF_Plugin::TEXTDOMAIN ),
            'required'      => true,
            'clear'         => true,
        ), WF_Utils::get_order_meta_compat( $order->get_id(), '_billing_company_number' ) );
	}
}
