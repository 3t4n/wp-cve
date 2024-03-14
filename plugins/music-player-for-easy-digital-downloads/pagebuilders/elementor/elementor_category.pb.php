<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Register the categories
Plugin::$instance->elements_manager->add_category(
	'music-player-for-easy-digital-downloads-cat',
	array(
		'title' => 'Music Player For Easy Digital Downloads',
		'icon'  => 'fa fa-plug',
	),
	2 // position
);
