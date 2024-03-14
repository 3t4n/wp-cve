<?php
/*
 * Add Dokan functions to Multiple Packages
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

// Check if WooCommerce is active
if ( class_exists( 'WooCommerce' ) && function_exists('dokan') ) {

	if ( class_exists( 'BE_Multiple_Packages_Dokan' ) ) return;

	class BE_Multiple_Packages_Dokan {

		/**
		 * Cloning is forbidden. Will deactivate prior 'instances' users are running
		 *
		 * @since 4.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cloning this class could cause catastrophic disasters!', 'bolder-multi-package-woo' ), '1.1' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 4.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Unserializing is forbidden!', 'bolder-multi-package-woo' ), '1.1' );
		}

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		function __construct() {
			// modify the necessary settings values through hooks and filters
			add_filter( 'be_packages_settings_type', array( $this, 'add_type_modification' ), 10, 1 );
			add_filter( 'be_packages_generate_packages', array( $this, 'generate_packages' ), 10, 3 );
			add_filter( 'woocommerce_shipping_package_name', array( $this, 'update_package_titles' ), 10, 3 );
		}


		/**
		 * add_type_modification function.
		 *
		 * @access public
		 * @param array $package (default: array())
		 * @return array
		 */
		function add_type_modification( $separation_types ) {
			$separation_types['dokan-vendors'] = __( 'Dokan Vendors', 'bolder-multi-package-woo' );

			return $separation_types;
		}


		/**
		 * generate_packages function.
		 *
		 * @access public
		 * @param array $packages, string $split_type, array $cart_items
		 * @return null
		 */
		function generate_packages( $packages, $split_type, $cart_items ) {
			// only process Dokan configurations
			if( sanitize_title( $split_type ) !== 'dokan-vendors' )
				return $packages;

			// Setup items by vendor array
			$items_by_vendor = array();
			foreach( $cart_items as $item ) {
		        if( ! $item['data']->needs_shipping() )
		        	continue;

		        // get vendor ID
				$vendor = dokan_get_vendor_by_product( $item['data'] );
				$vendor_id = $vendor->id;
				if( ! isset( $items_by_vendor[ $vendor_id ] ) )
					$items_by_vendor[ $vendor_id ] = array();

				$items_by_vendor[ $vendor_id ][] = $item;
			}

		    // Put inside packages
		    foreach( $items_by_vendor as $vendor_id => $items ) {

				if ( count( $items ) ) {
			        $packages[ $vendor_id ] = array(
			            'contents' => $items,
			            'contents_cost' => array_sum( wp_list_pluck( $items, 'line_total' ) ),
			            'applied_coupons' => WC()->cart->applied_coupons,
			            'destination' => array(
			                'country' => WC()->customer->get_shipping_country(),
			                'state' => WC()->customer->get_shipping_state(),
			                'postcode' => WC()->customer->get_shipping_postcode(),
			                'city' => WC()->customer->get_shipping_city(),
			                'address' => WC()->customer->get_shipping_address(),
			                'address_2' => WC()->customer->get_shipping_address_2()
			            )
			        );
			    }
			}

			return $packages;
		}


		/**
		 * update_package_titles function.
		 *
		 * @access public
		 * @param string $current_title, int $pkg_id, array $items
		 * @return string
		 */
		function update_package_titles( $current_title, $pkg_id, $package ) {
			// only update if Dokan separation enabled
	    	$split_type = sanitize_text_field( get_option( 'multi_packages_type' ) );
	    	if( $split_type !== 'dokan-vendors' )
	    		return $current_title;
		 
		    // exit if package error
		    if( ! isset( $package['contents'] ) || ! is_array( $package['contents'] ) ) return $current_title;
		 
			// find vendor information
			$vendor_id = intval( $pkg_id );
			if( $vendor_id > 0 ) {
				// get vendor profile
				$store_info = dokan_get_store_info( $vendor_id );

				if ( ! empty( $store_info['store_name'] ) )
	                $current_title = $store_info['store_name'];

	        }

		    return $current_title;
		         
		}

	}

	new BE_Multiple_Packages_Dokan();

}

?>