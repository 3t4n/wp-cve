<?php 
/**
 * Bootstrap Blocks for WP Editor Metaboxes.
 *
 * @version 1.1.2
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2019-03-21
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function load_mod_metaboxes_bootstrap_metaboxes()
{
	GutenbergBootstrap::AddModule('metaboxes',array(
		'name' => 'Metaboxes',	
		'version'=>'1.1.2'
	));

	function init_mod_metaboxes_bootstrap_metaboxes()
	{
		register_meta('post', 'gtb_hide_title', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'boolean'
		));

		register_meta('post', 'gtb_wrap_title', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'boolean'
		));

		register_meta('post', 'gtb_class_title', array(
			'show_in_rest' => true,
			'single' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'string'
		));

		register_meta('post', 'gtb_remove_headerfooter', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'boolean'
		));
	}

	add_action('gtb_init','init_mod_metaboxes_bootstrap_metaboxes');
}

add_action('gtb_bootstrap_modules','load_mod_metaboxes_bootstrap_metaboxes');

