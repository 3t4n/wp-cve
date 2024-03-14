<?php

namespace Elementor;

// Create profile card category into elementor.
if ( ! function_exists( 'tfsel_init_category' ) ) {

	function tfsel_init_category() {
		Plugin::instance()->elements_manager->add_category(
			'touch-fashion-slider',
			array(
				'title' => esc_html__( 'Touch Fashino Slider Pro', TFS_EL_DOMAIN ),
				'icon'  => 'font',
			),
			1
		);
	}
}
add_action( 'elementor/init', 'Elementor\tfsel_init_category' );

