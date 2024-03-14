<?php
/**
 * Shipping address selection methods
 *
 * @package Fish and Ships
 * @since 1.4.13
 */
 
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Fish_n_Ships_Shipping' ) ) {
	
	class Fish_n_Ships_Shipping {
				
		/**
		 * Constructor.
		 *
		 * @since 1.4.13
		 */
		public function __construct() {
			
			add_filter('wc_fns_get_selection_methods', array ( $this, 'wc_fns_get_selection_methods_fn' ) , 20, 1);
						
		}

		/**
		 * Filter to get all selection methods
		 *
		 * @since 1.4.13
		 *
		 * @param $methods (array) maybe incomming  a pair method-id / method-name array
		 *
		 * @return $methods (array) a pair method-id / method-name array
		 *
		 */
		function wc_fns_get_selection_methods_fn($methods = array()) {

			$scope_all     = array ('normal', 'extra');

			$methods['in-regions']           = array('onlypro' => true, 'group' => 'Shipping address', 'scope' => $scope_all, 'label' =>  _x('In zone regions', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['not-in-regions']       = array('onlypro' => true, 'group' => 'Shipping address', 'scope' => $scope_all, 'label' =>  _x('NOT In zone regions', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['in-postal-codes']      = array('onlypro' => true, 'group' => 'Shipping address', 'scope' => $scope_all, 'label' =>  _x('In ZIP/postcodes', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['not-in-postal-codes']  = array('onlypro' => true, 'group' => 'Shipping address', 'scope' => $scope_all, 'label' =>  _x('NOT In ZIP/postcodes', 'shorted, select-by conditional', 'fish-and-ships'));

			return $methods;
		}
	}
	
	global $Fish_n_Ships_Shipping;
	$Fish_n_Ships_Shipping = new Fish_n_Ships_Shipping();
}

