<?php

add_action('elementor/element/before_section_end', function( $section, $section_id, $args ) {
	if ( 'image' === $section->get_name() && 'section_image' === $section_id ) {
		$section->add_control(
			'ep_instagram_filters',
			[
				'label'        => __( 'Instagram Filter Plus!', 'elements-plus' ),
				'type'         => Elementor\Controls_Manager::SELECT,
				'default'      => '',
				'options'      => ep_instagram_filters(),
				'prefix_class' => 'ep-filter-',
				'label_block'  => true,
			]
		);
	}
}, 10, 3 );

function ep_instagram_filters() {
	$filters = array(
		'' => __( 'No Filter', 'elements-plus' ),
		'1977' => '1977',
		'aden' => 'Aden',
		'amaro' => 'Amaro',
		'ashby' => 'Ashby',
		'brannan' => 'Brannan',
		'brooklyn' => 'Brooklyn',
		'charmes' => 'Charmes',
		'clarendon' => 'Clarendon',
		'crema' => 'Crema',
		'dogpatch' => 'Dogpatch',
		'earlybird' => 'Earlybird',
		'gingham' => 'Gingham',
		'ginza' => 'Ginza',
		'hefe' => 'Hefe',
		'helena' => 'Helena',
		'hudson' => 'Hudson',
		'inkwell' => 'Inkwell',
		'kelvin' => 'Kelvin',
		'juno' => 'Kuno',
		'lark' => 'Lark',
		'lofi' => 'Lo-Fi',
		'ludwig' => 'Ludwig',
		'maven' => 'Maven',
		'mayfair' => 'Mayfair',
		'moon' => 'Moon',
		'nashville' => 'Nashville',
		'perpetua' => 'Perpetua',
		'poprocket' => 'Poprocket',
		'reyes' => 'Reyes',
		'rise' => 'Rise',
		'sierra' => 'Sierra',
		'skyline' => 'Skyline',
		'slumber' => 'Slumber',
		'stinson' => 'Stinson',
		'sutro' => 'Sutro',
		'toaster' => 'Toaster',
		'valencia' => 'Valencia',
		'vesper' => 'Vesper',
		'walden' => 'Walden',
		'willow' => 'Willow',
		'xpro-ii' => 'X-Pro II',
	);

	return $filters;
}
