<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Register the categories
Plugin::$instance->elements_manager->add_category(
	'audio-and-video-player-cat',
	array(
		'title' => __( 'Audio and Video Player', 'codepeople-media-player' ),
		'icon'  => 'eicon-play',
	),
	2 // position
);
