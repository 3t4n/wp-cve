<?php

/* EXIT IF FILE IS CALLED DIRECTLY */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* FOR SCRIPT CONTROL */

add_action( 'admin_enqueue_scripts', 'iprm_load_backend_scripts' );
add_action( 'wp_enqueue_scripts', 'iprm_load_frontend_scripts' );

function iprm_load_backend_scripts() {
	wp_enqueue_style( 'iprm-styles', plugin_dir_url( __FILE__ ) . 'css/plugin-styles.css' );
	wp_enqueue_script( 'irpm_tables', plugin_dir_url( __FILE__ ) . '/js/irpm_tables.js' );
	wp_enqueue_script( 'irpm_tables_sorter', plugin_dir_url( __FILE__ ) . '/js/sortable.js' );
}
function iprm_load_frontend_scripts() {
	global $post;
	if ( has_shortcode( $post->post_content, 'iprm' ) ) {
		wp_enqueue_style( 'iprm-styles', plugin_dir_url( __FILE__ ) . 'css/plugin-styles.css' );
		wp_enqueue_script( 'irpm_tables', plugin_dir_url( __FILE__ ) . '/js/irpm_tables.js' );
		wp_enqueue_script( 'irpm_tables_sorter', plugin_dir_url( __FILE__ ) . '/js/sortable.js' );
	}
}
