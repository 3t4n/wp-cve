<?php

function apyts_add_scripts() {

    // wp_enqueue_style('apyts-main-style', plugin_dir_url(__FILE__) . '/css/styles.css');

	// wp_enqueue_script('apyts-main-script', plugin_dir_url(__FILE__) . '/js/main.js');

	wp_enqueue_script('google', 'https://apis.google.com/js/platform.js');
	wp_enqueue_script('google');

}

add_action('wp_enqueue_scripts', 'apyts_add_scripts');