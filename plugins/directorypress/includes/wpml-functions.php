<?php

function directorypress_wpml_translation_notification_string() {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress && defined('WPML_ST_VERSION')) {
		echo '<p class="description">';
		_e('Set translation status as completed on translation page', 'DIRECTORYPRESS');
		echo '</p>';
	}
}

global $directorypress_wpml_dependent_options;
$directorypress_wpml_dependent_options[] = 'directorypress_listing_contact_form_7';
$directorypress_wpml_dependent_options[] = 'directorypress_directory_title';
function directorypress_wpml_supported_option_id($option) {
	global $directorypress_wpml_dependent_options, $sitepress;

	if (in_array($option, $directorypress_wpml_dependent_options))
		if (function_exists('wpml_object_id_filter') && $sitepress)
			if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE)
				if (get_option($option.'_'.ICL_LANGUAGE_CODE) !== false)
					return $option.'_'.ICL_LANGUAGE_CODE;

	return $option;
}

function directorypress_wpml_supported_settings($option) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	return get_option(directorypress_wpml_supported_option_id($option));
}

function directorypress_wpml_supported_settings_description() {
	global $sitepress;
	return ((function_exists('wpml_object_id_filter') && $sitepress) ? sprintf(__('%s This option is WPML supported, set seperate value for each language.', 'DIRECTORYPRESS'), '<br /><img src="'.DIRECTORYPRESS_RESOURCES_URL . 'images/multilang.png" /><br />') : '');
}