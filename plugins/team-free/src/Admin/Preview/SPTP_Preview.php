<?php
/**
 * The admin preview.
 *
 * @link        https://smartpostshow.com/
 * @since      2.1.4
 *
 * @package    WP_Team_free
 * @subpackage WP_Team_free/admin
 */

namespace ShapedPlugin\WPTeam\Admin\Preview;

use ShapedPlugin\WPTeam\Frontend\Helper;
use ShapedPlugin\WPTeam\Frontend\Frontend;

/**
 * SPTP Preview class
 */
class SPTP_Preview {

	/**
	 * Script and style suffix
	 *
	 * @since 2.1.4
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.1.4
	 */
	public function __construct() {
		$this->sp_team_preview_action();
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function sp_team_preview_action() {
		// admin Preview.
		add_action( 'wp_ajax_sptp_preview_meta_box', array( $this, 'sp_team_backend_preview' ) );
	}


	/**
	 * Function Team Backed preview.
	 *
	 * @since 3.2.5
	 */
	public function sp_team_backend_preview() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spf_metabox_nonce' ) ) {
			return;
		}
		$setting = array();
		$data    = ! empty( $_POST['data'] ) ?
			wp_unslash( $_POST['data'] ) // phpcs:ignore
		: '';
		parse_str( $data, $setting );
		$setting = array_map( 'wp_kses_post_deep', $setting );
		// Shortcode id.
		$generator_ids = $setting['post_ID'];
		// Preset Layouts.
		$layout                = $setting['_sptp_generator_layout'];
		$settings              = $setting['_sptp_generator'];
		$preview_section_title = $setting['post_title'];
		// Dynamic style load.
		$dynamic_style = Frontend::load_dynamic_style( $generator_ids, $layout, $settings );
		echo '<style id="team_free_dynamic_css' . $generator_ids . '">' . $dynamic_style['dynamic_css'] . '</style>';//phpcs:ignore
		Helper::sptp_html_show( $generator_ids, $layout, $settings, $preview_section_title, false );
		die();
	}
}
