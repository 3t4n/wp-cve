<?php
/*
Plugin Name: WP-TAB Tableau Public Viz Block
Description: An easy way to embed Tableau Public Vizualizations into a WordPress page with basic options.
Version: 1.3
Author: wptab
Author URI: https://wordpress.org/plugins/wptab-tableau-public-viz-block/
License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Enqueue the public scripts
add_action('wp_enqueue_scripts','wptab_tableau_public_init');

function wptab_tableau_public_init() {
  //Include Tableau JS to allow the Viz to be rendered. If min does not work comment this line and uncomment line 24
  wp_enqueue_script( 'tableau-js', 'https://public.tableau.com/javascripts/api/tableau-2.min.js', false);

	//Include Tableau JS to allow the Viz to be rendered. min script uses document write which is blocked by browsers, hence use the normal js
  //wp_enqueue_script( 'tableau-js', 'https://public.tableau.com/javascripts/api/tableau-2.1.1.js', false);

	//Enqueue the public js
	wp_enqueue_script( 'tableau-init-viz-js', plugin_dir_url( __FILE__ ) .'js/wptab-tableau-public-viz-render.js',array('jquery'), true);
}
// load the js to support blocks
function loadwptabTableauPublicVizFiles() {
  wp_enqueue_script(
    'wptab-tableau-public-viz',
    plugin_dir_url(__FILE__) . 'js/wptab-tableau-public-viz-embed-block.js',
    array('wp-blocks', 'wp-editor',  'wp-plugins', 'wp-edit-post', 'wp-element' ),
    true
  );
}

add_action('enqueue_block_editor_assets', 'loadwptabTableauPublicVizFiles');