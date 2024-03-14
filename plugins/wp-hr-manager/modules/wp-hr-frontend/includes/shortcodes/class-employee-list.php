<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

/**
 * Employee List class
 */
class Employee_List {

	/**
	 * Get the shortcode content.
	 *
	 * @param array $atts
	 *
	 * @since  1.0.0
	 * 
	 * @return string
	 */
	public static function get( $atts ) {
		return \WPHR\HR_MANAGER\HR\Frontend\Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @param array $atts
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function output( $atts ) {
		wphr_hr_frontend_get_template('employee-list/employee-list.php');
	}

}
