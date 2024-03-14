<?php

defined( 'ABSPATH' ) or die(':)');


function video_popup_tinymce_add_locale($locales) {
    $locales ['video-popup-tinymce-langs'] = plugin_dir_path ( __FILE__ ) . 'languages.php';
    return $locales;
}
add_filter('mce_external_languages', 'video_popup_tinymce_add_locale');


function video_popup_add_tinymce_button($buttons) {
	array_push($buttons, 'video_popup_tinymce');
	return $buttons;
}
add_filter('mce_buttons', 'video_popup_add_tinymce_button');


function video_popup_register_tinymce_js($plugin_array) {
	$plugin_array['video_popup_tinymce'] = plugins_url( '/js/video-popup-tinymce.js', __FILE__);
	return $plugin_array;
}
add_filter('mce_external_plugins', 'video_popup_register_tinymce_js');


function video_popup_tinymce_button_icon(){
	wp_enqueue_style( 'video-popup-tinymce-style', plugins_url('/css/tinymce.css', __FILE__), array(), time(), "all"  );
}
add_action('admin_enqueue_scripts', 'video_popup_tinymce_button_icon');


function video_popup_tinymce_editor_style() {
	if( !get_option('vp_gs_op_editor_style') ){
		add_editor_style( plugins_url( '/css/editor-style.css', __FILE__ ) );
	}
}
add_action( 'admin_init', 'video_popup_tinymce_editor_style' );