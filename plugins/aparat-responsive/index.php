<?php

	/*
		Plugin Name: Aparat responsive
		Plugin URI: https://wordpress.org/plugins/aparat-responsive/
		Description: نمایش آپارات به صورت کاملا رسپانسیو در نوشته های وردپرس
		Version: 1.3
		Author: mahmood khosravi
		Author URI: https://iraweb.ir
		
	*/

	function aparat($atts) {
		extract( shortcode_atts( array(
			'id' => '',
			'style' => 'margin: 15px 1px 10px 1px;'
		), $atts ) );
	return "<style>.h_iframe-aparat_embed_frame{position:relative;} .h_iframe-aparat_embed_frame .ratio {display:block;width:100%;height:auto;} .h_iframe-aparat_embed_frame iframe {position:absolute;top:0;left:0;width:100%; height:100%;}.author_plug{position: absolute;text-indent: -9999px;opacity: 0;font-size: 0;}</style><div class='h_iframe-aparat_embed_frame' style='{$style}'> <span style='display: block;padding-top: 57%'></span><iframe src='https://www.aparat.com/video/video/embed/videohash/{$id}/vt/frame' allowFullScreen='true' webkitallowfullscreen='true' mozallowfullscreen='true'></iframe></div>";
	}
	add_shortcode( 'aparat', 'aparat' );

	function aparat_editor_btn($buttons) {
		array_push($buttons, "separator", "aparat_shortcode");
		return $buttons;
	}
	add_filter('mce_buttons', 'aparat_editor_btn', 0);

	function aparat_shortcode_register($plugin_array)	{
		$plugin_array['aparat_shortcode'] = plugins_url('tinyMCE/editor_plugin.js', __FILE__);
		return $plugin_array;
	}
	add_filter('mce_external_plugins', "aparat_shortcode_register");
	
?>