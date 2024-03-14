<?php
namespace SiteSeoElementorAddon;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Register_Controls {
	use \SiteSeoElementorAddon\Singleton;

	/**
	 * Initialize class
	 *
	 * @return  void
	 */
	private function _initialize() {
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
	}

	/**
	 * Register controls
	 *
	 * @return  void
	 */
	public function register_controls( $controls_manager ) {
		$controls_manager->register( new \SiteSeoElementorAddon\Controls\Social_Preview_Control() );
		$controls_manager->register( new \SiteSeoElementorAddon\Controls\Text_Letter_Counter_Control() );
		$controls_manager->register( new \SiteSeoElementorAddon\Controls\Content_Analysis_Control() );
		if ( is_plugin_active( 'siteseo-pro/siteseo-pro.php' ) ) {
			$controls_manager->register( new \SiteSeoElementorAddon\Controls\Google_Suggestions_Control() );
		}
	}
}
