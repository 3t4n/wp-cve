<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

use WPHR\HR_MANAGER\HR\Frontend\Scripts;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Shortcodes class
 *
 * @version     0.1
 */
class Shortcodes {
	/**
	 * Init shortcodes.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		$shortcodes = array(
			'wp-hr-employee-list'    => __CLASS__ . '::employee_list',
			'wp-hr-employee-profile' => __CLASS__ . '::employee_profile',
            'wp-hr-dashboard'        => __CLASS__ . '::dashboard',
			'wp-hr-my-profile'       => __CLASS__ . '::my_profile',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			//echo $shortcode."==".$function."<br/>";
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string $function
	 * @param array $atts
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public static function shortcode_wrapper( $function, $atts = [], $wrapper_attr = [] ) {
		$wrapper = array(
			'class'  => 'wp-hr-frontend',
			'before' => null,
			'after'  => null
		);

		$wrapper  = wp_parse_args( $wrapper_attr, $wrapper );

        // enqueue styles and scripts
        Scripts::load_scripts();

		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		return ob_get_clean();
	}

	/**
	 * Employee list render shortcode
	 *
     * @since  1.0.0
	 * @since  1.0.1 Check proper permissions before render shortcodes
	 *
	 * @param mixed $atts
	 *
	 * @return string
	 */
	public static function employee_list( $atts ) {
        if ( ! ( current_user_can( wphr_hr_get_employee_role() ) || current_user_can( 'manage_options' ) ) ) {
            ?>
                <div class="error">
                    <p>
                        <?php _e( 'You do not have permission to see this page', 'wp-hr-frontend' ); ?>
                    </p>
                </div>
            <?php

            return;
        }

		return self::shortcode_wrapper( array( '\WPHR\HR_MANAGER\HR\Frontend\Employee_List', 'output' ), $atts );
	}

	/**
	 * Employee Profile render shortcode
	 *
	 * @param mixed $atts
	 *
	 * @since  1.0.0
     * @since  1.0.1 Check proper permissions before render shortcodes
	 *
	 * @return string
	 */
	public static function employee_profile( $atts ) {
        if ( ! ( current_user_can( wphr_hr_get_employee_role() ) || current_user_can( 'manage_options' ) ) ) {
            ?>
                <div class="error">
                    <p>
                        <?php _e( 'You do not have permission to see this page', 'wp-hr-frontend' ); ?>
                    </p>
                </div>
            <?php

            return;
        }

        if ( ! isset( $_GET['action'] ) || ! isset( $_GET['id'] ) || empty( $_GET['id'] ) ) {
            return self::shortcode_wrapper( array( '\WPHR\HR_MANAGER\HR\Frontend\Employee_Profile', 'output_my_profile' ), $atts );
        }


		return self::shortcode_wrapper( array( '\WPHR\HR_MANAGER\HR\Frontend\Employee_Profile', 'output' ), $atts );
	}

	/**
	 * HR dasboard render shortcode
	 *
	 * @param mixed $atts
	 *
	 * @since  1.0.0
     * @since  1.0.1 Check proper permissions before render shortcodes
	 *
	 * @return string
	 */
	public static function dashboard( $atts ) {
        if ( ! ( current_user_can( wphr_hr_get_employee_role() ) || current_user_can( 'manage_options' ) ) ) {
            ?>
                <div class="error">
                    <p>
                        <?php _e( 'You do not have permission to see this page', 'wp-hr-frontend' ); ?>
                    </p>
                </div>
            <?php

            return;
        }

		return self::shortcode_wrapper( array( '\WPHR\HR_MANAGER\HR\Frontend\Dashboard', 'output' ), $atts );
	}

    /**
     * Employee My Profile shortcode
     *
     * @param mixed $atts
     *
     * @since  1.0.0
     * @since  1.0.1 Check proper permissions before render shortcodes
     *
     * @return string
     */
    public static function my_profile( $atts ) {
        if ( ! ( current_user_can( wphr_hr_get_employee_role() ) || current_user_can( 'manage_options' ) ) ) {
            ?>
                <div class="error">
                    <p>
                        <?php _e( 'You do not have permission to see this page', 'wp-hr-frontend' ); ?>
                    </p>
                </div>
            <?php

            return;
        }

        return self::shortcode_wrapper( array( '\WPHR\HR_MANAGER\HR\Frontend\Employee_Profile', 'output_my_profile' ), $atts );
    }
}
