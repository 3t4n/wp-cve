<?php

function register_web_fonts_provider($provider_class_name) {
	Web_Fonts::register_web_fonts_provider($provider_class_name);
}

function web_fonts_prepare_font_item($provider_key, $font_id, $font_name, $font_family, $font_preview = 'The Quick Brown Fox Jumped Over The Lazy Dog', $font_selectors = array(), $additional_styles = array()) {
	return array(
		'family' => $font_family,
		'id' => $font_id,
		'name' => $font_name,
		'preview' => $font_preview,
		'provider' => $provider_key,
		'selectors' => (array)$font_selectors,
		'additional_styles' => (array)$additional_styles
	);
}

function web_fonts_prepare_selector_item($provider_key, $selector_id, $selector_tag, $fallback_fontstack, $font) {
	return array(
		'fallback' => $fallback_fontstack,
		'font' => $font,
		'id' => $selector_id,
		'tag' => $selector_tag,
		'provider' => $provider_key,
	);
}

function web_fonts_get_last_saved_stylesheet_time() {
	return apply_filters('web_fonts_get_last_saved_stylesheet_time', Web_Fonts::get_last_saved_stylesheet_timestamp());
}
