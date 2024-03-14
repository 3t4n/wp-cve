<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXMTZC_Enqueue_Scripts
{

	/*
	* MXMTZC_Enqueue_Scripts
	*/
	public function __construct()
	{
	}

	/*
	* Registration of styles and scripts
	*/
	public static function mxmtzc_register()
	{

		// register scripts and styles
		add_action('admin_enqueue_scripts', array('MXMTZC_Enqueue_Scripts', 'mxmtzc_enqueue'));
	}

	public static function mxmtzc_enqueue()
	{

		wp_enqueue_style('mxmtzc_font_awesome', MXMTZC_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css');

		wp_enqueue_style('mxmtzc_admin_style', MXMTZC_PLUGIN_URL . 'includes/admin/assets/css/style.css', array('mxmtzc_font_awesome'), MXMTZC_PLUGIN_VERSION, 'all');

		wp_enqueue_script('mxmtzc_admin_script', MXMTZC_PLUGIN_URL . 'includes/admin/assets/js/script.js', array('jquery'), MXMTZC_PLUGIN_VERSION, false);

		wp_localize_script('mxmtzc_admin_script', 'mxmtzc_admin_localize', [

			'ajax_url'   => admin_url('admin-ajax.php'),
			'nonce'      => wp_create_nonce('mxmtzc_nonce_request_admin'),

		]);

		wp_enqueue_media();

		wp_enqueue_script('mxmtzc_image-upload', MXMTZC_PLUGIN_URL . 'includes/admin/assets/js/image-upload.js', ['jquery'], MXMTZC_PLUGIN_VERSION, false);
	}
}
