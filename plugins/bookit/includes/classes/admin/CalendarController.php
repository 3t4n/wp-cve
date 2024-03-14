<?php

namespace Bookit\Classes\Admin;

use Bookit\Classes\Database\Categories;

use Bookit\Classes\Nonces;
use Bookit\Classes\Template;
use Bookit\Classes\Translations;

class CalendarController {

	/**
	 * Enqueue Styles & Scripts
	 * @param $settings
	 */
	public static function enqueue_styles_scripts() {
		wp_enqueue_script( 'bookit-app', BOOKIT_URL . 'assets/dist/dashboard/js/app.js', array(), BOOKIT_VERSION );
		wp_enqueue_style( 'bookit-app', BOOKIT_URL . 'assets/dist/dashboard/css/app.css', array(), intval( get_option( 'bookit_styles_version', 1 ) ) );

		$translations = array_merge( Translations::get_admin_translations(), Translations::get_addon_translations() );

		$ajax_data = array(
			'ajax_url'     => admin_url( 'admin-ajax.php' ),
			'translations' => $translations,
			'nonces'       => Nonces::get_admin_nonces(),
			'pro_disabled' => bookit_pro_features_disabled(), //todo remove
			'has_feedback' => DashboardController::has_feedback(),
		);

		wp_localize_script( 'bookit-app', 'bookit_window', $ajax_data );
	}

	/**
	 * Render Bookit Calendar
	 */
	public static function render() {
		self::enqueue_styles_scripts();

		$data               = AppointmentsController::get_form_data();
		$data['language']   = substr( get_bloginfo( 'language' ), 0, 2 );
		$data['categories'] = Categories::get_all();

		return Template::load_template( 'dashboard/bookit-calendar', $data, true );
	}
}
