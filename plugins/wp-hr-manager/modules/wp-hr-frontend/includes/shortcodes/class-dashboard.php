<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

/**
 */
class Dashboard {

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
        wphr_get_js_template( WPHR_HRM_JS_TMPL . '/new-leave-request.php', 'wphr-new-leave-req' );
        wphr_get_js_template( WPHR_HRM_JS_TMPL . '/leave-days.php', 'wphr-leave-days' );

		wphr_hr_frontend_get_template('dashboard/dashboard.php');
	}

}
