<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend_Scripts Class.
 */
class Scripts {

	/**
	 * Register/queue frontend scripts.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function load_scripts() {
		global $post;
		
		$wphr_scripts = new \WPHR\HR_MANAGER\Scripts();
		$wphr_scripts->register_scripts();
		$wphr_scripts->register_styles();
		//$wphr_scripts->register_scripts();
		$wphr_scripts->enqueue_scripts();

        wp_enqueue_media();
        wp_enqueue_style( 'jquery-ui', WPHR_ASSETS . '/vendor/jquery-ui/jquery-ui-1.9.1.custom.css' );
		wp_enqueue_style('wphr-fontawesome');
		wp_enqueue_style('wphr-fullcalendar');
		wp_enqueue_style( 'front-end-style', WPHR_HR_FRONTEND_ASSETS . '/css/frontend.css', false, WPHR_HR_FRONTEND_VERSION, false );
        wp_enqueue_script('wphr-fullcalendar');
        wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), false, false, 1 );

        $wphr = wphr();
        $hrm = new \WPHR\HR_MANAGER\HRM\Human_Resource( $wphr );
        $hrm->admin_scripts( 'wphr-manager_page_wphr-hr-employee' );

        add_action( 'wp_footer', 'wphr_include_popup_markup' );

        $country = \WPHR\HR_MANAGER\Countries::instance();
        wp_localize_script( 'wphr-script', 'wpHrCountries', $country->load_country_states() );

        do_action( 'wphr_hr_frontend_load_script' );

	}
}
