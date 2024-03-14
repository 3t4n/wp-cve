<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://eversionsystems.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Duplicate_Billing_Address
 * @subpackage Woocommerce_Duplicate_Billing_Address/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Duplicate_Billing_Address
 * @subpackage Woocommerce_Duplicate_Billing_Address/public
 * @author     Andrew Schultz <contact@eversionsystems.com>
 */
class Woocommerce_Duplicate_Billing_Address_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The WooCommerce billing/shipping fields.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $billing_fields    The array of billing fields in WooCommerce without billing prefix.
	 */
	private $checkout_fields;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->checkout_fields = array( 'first_name',
									'last_name',
									'address_1',
									'address_2',
									'city',
									'state',
									'postcode',
									'country' 
								);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-duplicate-billing-address-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-duplicate-billing-address-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Display a button to copy billing to shipping address on my-account page.
	 *
	 * @since    1.0.0
	 */
	public function my_account_copy_billing_to_shipping() {
		global $wp_query;

		// Don't display the button when we are editting an address
		if( empty( $wp_query->query['edit-address'] ) ) {
			$checkout = WC_Checkout::instance();
			$billing_fields = $checkout->get_checkout_fields( 'billing' );
			// Possible to get billing fields based on country...
			// WC()->countries->get_address_fields( $checkout->get_value( 'billing_country' ), 'billing_' )
			?>
			<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
				<input type="submit" class="button" value="Copy Billing to Shipping Address">
				<input name="action" value="duplicate_billing_address_action" type="hidden">
				<input type="hidden" name="billing_fields" value="<?php echo htmlentities( json_encode( $billing_fields ) ); ?>">
				<?php echo wp_nonce_field('duplicate_billing_address', '_mynonce'); ?>
			</form>
			<?php
		}
	}
	
	/**
	 * Copy the billing address to the shipping address on front end.
	 *
	 * Seems to be an issue with calling the wc_add_notice functions sometimes it says it doesn't exist.
	 *
	 * @since	1.2.0
	 */
	function my_account_duplicate_billing_address() {
		if ( !isset($_POST['_mynonce']) || !wp_verify_nonce($_POST['_mynonce'], 'duplicate_billing_address')) {
			wp_die();
		}

		global $woocommerce;
		
		// Magic quotes needs to be switched off in php.ini as it causes forward slashed to be added to the variables
		// https://stackoverflow.com/questions/2496455/why-are-post-variables-getting-escaped-in-php
		$billing_fields = json_decode( stripslashes( $_POST['billing_fields'] ), true );

		$user_id = get_current_user_id();
		$all_meta_for_user = get_user_meta( $user_id );

		foreach( $billing_fields as $field_key => $billing_field ) {
			$field_key = sanitize_text_field( $field_key );
			$field_name = substr( $field_key, strpos( $field_key, '_' ) );
			update_user_meta( $user_id, 'shipping' . $field_name, $all_meta_for_user['billing' . $field_name][0] );
		}
		
		// issue with function not being available in some scenarios
		if( function_exists( 'wc_add_notice' ) )
			wc_add_notice( __( 'Billing address copied to shipping address successfully.', 'woocommerce' ) );
		
		if( version_compare( $woocommerce->version, '2.6.0', '>=' ) )
			wp_safe_redirect( wc_get_endpoint_url( 'edit-address', '', get_permalink( wc_get_page_id( 'myaccount' ) ) ) );
		else
			wp_safe_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );

		exit;
	}
	
	/**
	 * Copy billing to shipping address.
	 *
	 * When an order is processed duplicate the billing to shipping address.
	 *
	 * @since	1.16
	 */
	function order_duplicate_billing_address( $order_id, $posted_data, $order ) {
		$copy_address = get_option( 'woocommerce_billing_to_shipping_address' );
		
		if( $copy_address ) {
			$user = $order->get_user();

			$billing_fields = $order->get_address( 'billing' );
			$shipping_fields = $order->get_address( 'shipping' );
			
			// Ensure user is not a guest
			if( $user ) {
				// If any custom billing fields have been added we will pick them up by looping over all the billing order fields
				foreach( $billing_fields as $key => $value  ) {
					if( isset( $shipping_fields[$key] ) ) {
						update_user_meta( $user->ID, 'shipping_' . $key, $value );
					}
				}
			}
		}
	}
	
	/**
	 * Set the WooCommerce My Account hook.
	 *
	 * Based on the WooCommerce version use the appropriate hook.
	 *
	 * @since	1.2.0
	 */
	function set_woocommerce_my_account_hook() {
		global $woocommerce;
		
		if( version_compare( $woocommerce->version, '2.6.0', '>=' ) )
			add_action('woocommerce_after_edit_account_address_form', array( $this, 'my_account_copy_billing_to_shipping' ) );
		else 
			// Use old hook with WooCommerce less than version 2.6 which doesn't have tabbed my-account page
			add_action('woocommerce_after_my_account', array( $this, 'my_account_copy_billing_to_shipping' ) );
	}

}
