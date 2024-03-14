<?php
/**
 * @package   Cozipress
 */

require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozigo/cozigo-extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/features/cozipress-general.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozigo/features/cozigo-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/features/cozipress-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/features/cozipress-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozigo/features/cozigo-marquee.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/features/cozipress-service.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/features/cozipress-testimonial.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/features/cozipress-typography.php';

if ( ! function_exists( 'burger_companion_cozipress_frontpage_sections' ) ) :
	function burger_companion_cozipress_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozigo/sections/section-marquee.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/sections/section-service.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/sections/section-testimonial.php';
    }
	add_action( 'cozipress_sections', 'burger_companion_cozipress_frontpage_sections' );
endif;
