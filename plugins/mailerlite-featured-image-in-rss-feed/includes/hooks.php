<?php
/**
 * Hooks
 *
 * @package     MailerLiteFIRSS\Hooks
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main hooks for extending the feed content
 */
add_filter( 'the_excerpt_rss', 'mailerlite_firss_add_image_to_content', PHP_INT_MAX, 1 );
add_filter( 'the_content_feed', 'mailerlite_firss_add_image_to_content', PHP_INT_MAX, 1 );