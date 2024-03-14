<?php

defined( 'ABSPATH' ) || exit;

class Kitpack_Lite_Icons {

	public function add_font_irani( $font ) {
		$json_url = plugin_dir_url( __DIR__ ) . 'assets/data/config.json';
		$icons_css_url = plugin_dir_url( __DIR__ ) . 'assets/css/modules/kitpack-lite-irani-icons.css';
		//echo $json_url;
		$font['ikpeicons'] = array(
			'name'          => 'ikpeicons',
			'label'         => esc_html__( 'Kitpack Icon', 'kitpack-lite' ),
			'url'           => $icons_css_url,
			'enqueue'       => false,
			'prefix'        => 'ikpe-',
			'displayPrefix' => '',
			'labelIcon'     => 'ikpe-kp',
			'ver'           => '5.3.0',
			'fetchJson'     => $json_url,

		);

		return $font;
	}

	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function jet_add_custom_icons_tab( $tabs = array() ) {
		$json_url = plugin_dir_url( __DIR__ ) . '/assets/data/config1.json';
		
		$tabs['my-custom-icons'] = array(
			'name'          => 'my-custom-icons',
			'label'         => esc_html__( 'My Custom Icons', 'kitpack-lite' ),
			'labelIcon'     => 'fas fa-user',
			'prefix'        => 'mdi-',
			'displayPrefix' => 'mdi',
			'url'           => 'https://cdn.materialdesignicons.com/4.5.95/css/materialdesignicons.min.css',
			//'icons'         => $new_icons,
			'fetchJson'     => $json_url,
			'ver'           => '1.0.0',
		);

		return $tabs;
	}
}