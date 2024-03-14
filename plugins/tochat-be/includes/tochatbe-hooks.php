<?php
defined( 'ABSPATH' ) || exit;

/**
 * tochatbe_about_message_by_page()
 */
add_filter( 'tochatbe_about_message', 'tochatbe_about_message_by_page', 10, 1 );