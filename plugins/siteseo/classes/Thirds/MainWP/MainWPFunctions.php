<?php
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * Return settings
 *
 * @return array
 */
function siteseo_return_settings() {
	$settings = array();

	$settings['siteseo_activated'] = get_option('siteseo_activated');
	$settings['siteseo_titles_option_name'] = get_option('siteseo_titles_option_name');
	$settings['siteseo_social_option_name'] = get_option('siteseo_social_option_name');
	$settings['siteseo_google_analytics_option_name'] = get_option('siteseo_google_analytics_option_name');
	$settings['siteseo_instant_indexing_option_name'] = get_option('siteseo_instant_indexing_option_name');
	$settings['siteseo_advanced_option_name'] = get_option('siteseo_advanced_option_name');
	$settings['siteseo_xml_sitemap_option_name'] = get_option('siteseo_xml_sitemap_option_name');
	$settings['siteseo_pro_option_name'] = get_option('siteseo_pro_option_name');
	$settings['siteseo_pro_mu_option_name'] = get_option('siteseo_pro_mu_option_name');
	$settings['siteseo_pro_license_key'] = get_option('siteseo_pro_license_key');
	$settings['siteseo_pro_license_status'] = get_option('siteseo_pro_license_status');
	$settings['siteseo_bot_option_name'] = get_option('siteseo_bot_option_name');
	$settings['siteseo_toggle'] = get_option('siteseo_toggle');
	$settings['siteseo_google_analytics_lock_option_name'] = get_option('siteseo_google_analytics_lock_option_name');
	$settings['siteseo_tools_option_name'] = get_option('siteseo_tools_option_name');
	$settings['siteseo_dashboard_option_name'] = get_option('siteseo_dashboard_option_name');

	return $settings;
}

/**
 * Siteseo do import settings
 *
 * @param  array $settings
 *
 * @return void
 */
function siteseo_do_import_settings( $settings ) {
	if (false !== $settings['siteseo_activated']) {
		update_option('siteseo_activated', $settings['siteseo_activated'], false);
	}
	if (false !== $settings['siteseo_titles_option_name']) {
		update_option('siteseo_titles_option_name', $settings['siteseo_titles_option_name'], false);
	}
	if (false !== $settings['siteseo_social_option_name']) {
		update_option('siteseo_social_option_name', $settings['siteseo_social_option_name'], false);
	}
	if (false !== $settings['siteseo_google_analytics_option_name']) {
		update_option('siteseo_google_analytics_option_name', $settings['siteseo_google_analytics_option_name'], false);
	}
	if (false !== $settings['siteseo_advanced_option_name']) {
		update_option('siteseo_advanced_option_name', $settings['siteseo_advanced_option_name'], false);
	}
	if (false !== $settings['siteseo_xml_sitemap_option_name']) {
		update_option('siteseo_xml_sitemap_option_name', $settings['siteseo_xml_sitemap_option_name'], false);
	}
	if (false !== $settings['siteseo_pro_option_name']) {
		update_option('siteseo_pro_option_name', $settings['siteseo_pro_option_name'], false);
	}
	if (false !== $settings['siteseo_pro_mu_option_name']) {
		update_option('siteseo_pro_mu_option_name', $settings['siteseo_pro_mu_option_name'], false);
	}
	if (false !== $settings['siteseo_pro_license_key']) {
		update_option('siteseo_pro_license_key', $settings['siteseo_pro_license_key'], false);
	}
	if (false !== $settings['siteseo_pro_license_status']) {
		update_option('siteseo_pro_license_status', $settings['siteseo_pro_license_status'], false);
	}
	if (false !== $settings['siteseo_bot_option_name']) {
		update_option('siteseo_bot_option_name', $settings['siteseo_bot_option_name'], false);
	}
	if (false !== $settings['siteseo_toggle']) {
		update_option('siteseo_toggle', $settings['siteseo_toggle'], false);
	}
	if (false !== $settings['siteseo_google_analytics_lock_option_name']) {
		update_option('siteseo_google_analytics_lock_option_name', $settings['siteseo_google_analytics_lock_option_name'], false);
	}
	if (false !== $settings['siteseo_tools_option_name']) {
		update_option('siteseo_tools_option_name', $settings['siteseo_tools_option_name'], false);
	}
	if (false !== $settings['siteseo_instant_indexing_option_name']) {
		update_option('siteseo_instant_indexing_option_name', $settings['siteseo_instant_indexing_option_name'], false);
	}
}

/**
 * Save settings for given option
 *
 * @param  array  $settings The settings to be saved.
 * @param  string $option The option name.
 *
 * @return void
 */
function siteseo_mainwp_save_settings( $settings, $option ) {
	update_option( $option, $settings );
}

/**
 * Flush rewrite rules.
 *
 * @return void
 */
function siteseo_flush_rewrite_rules() {
	flush_rewrite_rules( false );
}