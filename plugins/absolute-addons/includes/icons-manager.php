<?php

namespace AbsoluteAddons;

defined( 'ABSPATH' ) || die();

class Icons_Manager {

	public static function init() {
		add_filter( 'elementor/icons_manager/native', [ __CLASS__, 'add_absp_icons_tab' ] );
	}

	public static function add_absp_icons_tab( $tabs ) {
		$tabs['absp-icons'] = [
			'name'          => 'fa-absp-icons',
			'label'         => __( 'Absolute Addons Icons', 'absolute-addons' ),
			'url'           => ABSOLUTE_ADDONS_URL . '/assets/dist/css/libraries/absp-icons/css/absolute-icons.css',
			'enqueue'       => [ ABSOLUTE_ADDONS_URL . '/assets/dist/css/libraries/absp-icons/css/absolute-icons.css' ],
			'prefix'        => 'absp-',
			'displayPrefix' => 'absp',
			'labelIcon'     => 'absp-absolute',
			'ver'           => ABSOLUTE_ADDONS_VERSION,
			'fetchJson'     => ABSOLUTE_ADDONS_URL . 'assets/dist/css/libraries/absp-icons/absp-icons.json?v=' . ABSOLUTE_ADDONS_VERSION,
			'native'        => true,
		];
		return $tabs;
	}
}

Icons_Manager::init();
