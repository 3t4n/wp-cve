<?php

/**
 * Fired during plugin activation
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/includes
 * @author     brandiD <tech@thebrandid.com>
 */
class Social_Proof_Slider_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-social-proof-slider-admin.php';

		Social_Proof_Slider_Admin::new_cpt_testimonials();

		flush_rewrite_rules();

	}

}
