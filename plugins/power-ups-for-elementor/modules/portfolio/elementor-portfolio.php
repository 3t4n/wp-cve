<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Custom Post Types
 */
require ('portfolio_custom-post-types.php');
/*
 * Shortcodes
 */
require ('portfolio_shortcodes.php');
/*
 * Elementor
 */
require ('elementor/extend-elementor.php');