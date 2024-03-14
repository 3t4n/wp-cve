<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://eversionsystems.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Duplicate_Billing_Address
 * @subpackage Woocommerce_Duplicate_Billing_Address/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Duplicate_Billing_Address
 * @subpackage Woocommerce_Duplicate_Billing_Address/admin
 * @author     Andrew Schultz <contact@eversionsystems.com>
 */
class Woocommerce_Duplicate_Billing_Address_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-duplicate-billing-address-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $woocommerce;
		$screen = get_current_screen();
		
		// load the script only for the user edit page
		if( $screen->id == 'user-edit' || $screen->id == 'profile' ) {
			$args = array( 'woo_version' => $woocommerce->version );
			wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-duplicate-billing-address-admin.js', array( 'jquery' ), self::script_version_id(), true );
			wp_localize_script( $this->plugin_name, 'dup_address', $args );
			wp_enqueue_script( $this->plugin_name );
		}
			
	}
	
	/**
	 * Display a checkbox for duplicating the billing address.
	 *
	 * @since    1.0.0
	 */
	public function display_address_duplicate_checkbox() {
		?>
		<table class="form-table">
			<tr>
				<th><label for="duplicate_billing_address">Duplicate Address</label></th>
				<td>
					<input type="checkbox" id="duplicate_billing_address" name="duplicate_billing_address" value="yes">Copy billing to shipping address
				</td>
			</tr>
		</table>
		<?php
	}
	
	/**
	 * Add checkbox for enabling duplication of address on ordering.
	 *
	 * When an order is set to pending the address will duplicate to the shipping address.
	 *
	 * @since	1.16
	 */
	public function add_address_duplicate_on_order_checkbox( $settings ) {
		$billing_checkbox = array(
				'title'   => __( 'Duplicate billing to shipping address', $this->plugin_name ),
				'desc'    => __( 'Copy the billing address to the shipping address when an order is placed', $this->plugin_name ),
				'id'      => 'woocommerce_billing_to_shipping_address',
				'default' => 'no',
				'type'    => 'checkbox',
			);
		
		// When adding the billing checkbox array using array_splice it adds each array element as a new index
		// To get around just insert a single value and update later with an array value.
		$empty_array = 'test';
		
		// insert array element after the default address combobox
		// don't place it after the store notice as there is more jQuery code that tries to auto expand the closest <tr> element
		// which causes the store notice textbox to not open anymore.
		$default_address_key = self::search_array_for_id( 'woocommerce_default_customer_address', $settings );
		
		if( $default_address_key ) {
			$new_index = $default_address_key + 1;
			array_splice( $settings, $new_index, 0, $empty_array );
			$settings[$default_address_key] = $billing_checkbox;
		}

		return $settings;
	}
	
	/**
	 * Get index of search term in array.
	 *
	 * @since	1.16
	 */
	function search_array_for_id( $id, $array ) {
	   foreach ($array as $key => $val) {
		   if ( $val['id'] === $id ) {
			   return $key;
		   }
		}
	   
		return null;
	}
	
	public function script_version_id() {
		if ( WP_DEBUG )
			return time();
		return $this->version;
	}

}
