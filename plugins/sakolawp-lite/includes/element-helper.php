<?php
namespace Elementor;

function sakolawp_general_elementor_init(){
	Plugin::instance()->elements_manager->add_category(
		'sakolawp-general-category',
		[
			'title'  => 'Sakola WP',
			'icon' => 'font'
		],
		1
	);
}
add_action('elementor/init','Elementor\sakolawp_general_elementor_init');
