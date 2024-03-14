<?php

namespace XproElementorAddons\Module;

defined( 'ABSPATH' ) || die();

class Icons_Manager {


	public static function init() {
		add_filter( 'elementor/icons_manager/additional_tabs', array( __CLASS__, 'add_xpro_elementor_icons_tab' ) );
	}

	public static function add_xpro_elementor_icons_tab( $tabs ) {
		$tabs['xpro-icons'] = array(
			'name'          => 'xpro-icons',
			'label'         => __( 'Xpro Icons', 'xpro-elementor-addons' ),
			'url'           => XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
			'enqueue'       => array( XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css' ),
			'prefix'        => 'xi-',
			'displayPrefix' => 'xi',
			'labelIcon'     => 'xi xi-xpro',
			'ver'           => XPRO_ELEMENTOR_ADDONS_VERSION,
			'fetchJson'     => XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/xpro-icons/xpro-icons.json?v=' . XPRO_ELEMENTOR_ADDONS_VERSION,
			'native'        => false,
		);

		return $tabs;
	}
}

Icons_Manager::init();
