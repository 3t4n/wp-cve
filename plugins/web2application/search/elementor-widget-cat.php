<?php
// Web2Engage

function add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'Web2Engage',
		[
			'title' => esc_html__( 'Web2Engage', 'web2application' ),
			'icon' => 'fa fa-plug',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );