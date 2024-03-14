<?php
/**
 * Plugin Name: Frontend Dashicons
 * Plugin URI: https://fatpony.me/plugins/frontend-dashicons-plugin/
 * Description: Load the Dashicons icon font on the front-end of your site.
 * Version: 1.0.2
 * Author: Erica Franz
 * Author URI: https://fatpony.me/
 * License: MIT
 */

defined( 'ABSPATH' ) or die( 'Dashing through the snow, on a great white thunderbird!' );

add_action( 'wp_enqueue_scripts', 'dashicons_enqueue_frontend_dashicons' );

function dashicons_enqueue_frontend_dashicons() {

    wp_enqueue_style( 'dashicons' );

}
