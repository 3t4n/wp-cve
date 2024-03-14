<?php
/**
 * Object to manage SEO related features, but not connected to Surfer directly.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO;

/**
 * Object to manage SEO related features, but not connected to Surfer directly.
 */
class Seo_Manager {

	/**
	 * Object constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'display_gsc_html_tag_in_head' ) );
	}

	/**
	 * Displays tag saved in configuration provided by GSC.
	 *
	 * @return void
	 */
	public function display_gsc_html_tag_in_head() {
		$allow_meta = array(
			'meta' => array(
				'name'    => array(),
				'content' => array(),
			),
		);

		$html_tag = SurferSeo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'surfer_gsc_meta_script', false );
		if ( false !== $html_tag ) {
			echo wp_kses( stripslashes( $html_tag ), $allow_meta );
		}
	}

}
