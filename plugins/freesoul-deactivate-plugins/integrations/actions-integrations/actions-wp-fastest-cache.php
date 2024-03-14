<?php
/**
 * WP Fastest Cache actions to be added to the Actions settings page.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$actions = array(
    'main_file'    => 'wp-fastest-cache/wpFastestCache.php',
    'is_active'    => defined( 'WPFC_WP_CONTENT_BASENAME' ),
    'ajax_actions' => array(
        'wpfc_delete_cache'              => array( 'description' => esc_html__( 'delete cache', 'freesoul-deactivate-plugins' ) ),
        'wpfc_delete_cache_and_minified' => array( 'description' => esc_html__( 'delete cache and minified', 'freesoul-deactivate-plugins' ) ),
    ),
);