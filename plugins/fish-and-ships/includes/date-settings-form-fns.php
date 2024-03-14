<?php
/**
 * Date selection methods (beta)
 *
 * @package Fish and Ships
 * @since 1.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Fish_n_Ships_Date' ) ) {
	
	class Fish_n_Ships_Date {
				
		/**
		 * Constructor.
		 *
		 * @since 1.4.4
		 */
		public function __construct() {
			
			add_filter('wc_fns_get_selection_methods', array ( $this, 'wc_fns_get_selection_methods_fn' ) , 20, 1);
						
		}

		/**
		 * Filter to get all selection methods
		 *
		 * @since 1.4.4
		 *
		 * @param $methods (array) maybe incomming  a pair method-id / method-name array
		 *
		 * @return $methods (array) a pair method-id / method-name array
		 *
		 */
		function wc_fns_get_selection_methods_fn($methods = array()) {

			$scope_all     = array ('normal', 'extra');

			$methods['date-weekday']      = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Day of the week', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['date-daymonth']     = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Day of the month', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['date-month']        = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Month', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['date-year']         = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Year', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['date-date']         = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Date', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['date-dayyear']      = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Day of the year', 'shorted, select-by conditional', 'fish-and-ships'));
			$methods['date-time']         = array('onlypro' => true, 'group' => 'Date & time', 'scope' => $scope_all, 'label' =>  _x('Time', 'shorted, select-by conditional', 'fish-and-ships'));
			return $methods;
		}


	}
	global $Fish_n_Ships_Date;
	$Fish_n_Ships_Date = new Fish_n_Ships_Date();
}

