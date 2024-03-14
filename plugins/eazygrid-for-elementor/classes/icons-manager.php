<?php
namespace EazyGrid\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class Icons_Manager {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init() {
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'add_eazy_icons_tab' ] );
	}

	public function add_eazy_icons_tab( $tabs ) {
		$tabs['eazy-icons'] = [
			'name'          => 'eazy-icons',
			'label'         => __( 'Eazy Icons', 'eazygrid-elementor' ),
			'url'           => EAZYGRIDELEMENTOR_URL . 'assets/vendor/ezicon/style.min.css',
			'enqueue'       => [ EAZYGRIDELEMENTOR_URL . 'assets/vendor/ezicon/style.min.css' ],
			'prefix'        => 'ezicon-',
			'displayPrefix' => 'ezicon',
			'labelIcon'     => 'ezicon ezicon-eazyplugins',
			'ver'           => EAZYGRIDELEMENTOR_VERSION,
			'fetchJson'     => EAZYGRIDELEMENTOR_URL . 'assets/vendor/ezicon/fonts/ezicon.js?v=' . EAZYGRIDELEMENTOR_VERSION,
			'native'        => false,
		];
		return $tabs;
	}

}
