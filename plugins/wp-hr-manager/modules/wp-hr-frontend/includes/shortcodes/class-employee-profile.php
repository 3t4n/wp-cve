<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

/**
 */
class Employee_Profile {

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
     * @since  1.0.1 Check proper permissions before render shortcodes
	 *
	 * @return  void
	 */
	public static function output( $atts ) {
        if ( ! ( current_user_can( wphr_hr_get_employee_role() ) || current_user_can( 'manage_options' ) ) ) {
            wp_die( __( 'You do not have permission to view this page', 'wp-hr-frontend' ) );
        }

		wphr_hr_frontend_get_template('employee-profile/employee-profile.php');
	}


    /**
     * Output shortcode wphr-hr-my-profile.
     *
     * @param array $atts
     *
     * @since  1.0.0
     * @since  1.0.1 Check proper permissions before render shortcodes
     *
     * @return  void
     */
    public static function output_my_profile( $atts ) {
        if ( ! ( current_user_can( wphr_hr_get_employee_role() ) || current_user_can( 'manage_options' ) ) ) {
            wp_die( __( 'You do not have permission to view this page', 'wp-hr-frontend' ) );
        }

        wphr_hr_frontend_get_template('employee-profile/my-profile.php');
    }
}
