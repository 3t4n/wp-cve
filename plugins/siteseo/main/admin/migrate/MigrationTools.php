<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

function siteseo_migration_tool($plugin, $name) {
	$seo_title = 'SiteSEO';
	if (function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option()) {
		$seo_title = method_exists(siteseo_pro_get_service('OptionPro'), 'getWhiteLabelListTitle') && siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() ? siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() : 'SiteSEO';
	}

	$html = '<div id="' . esc_attr($plugin) . '-migration-tool" class="postbox section-tool">
		<div class="inside">
				<h3>' . sprintf(__('Import posts and terms (if available) metadata from %s', 'siteseo'), esc_html($name)) . '</h3>

				<p>' . __('By clicking Migrate, we\'ll import:', 'siteseo') . '</p>

				<ul>
					<li>' . __('Title tags', 'siteseo') . '</li>
					<li>' . __('Meta description', 'siteseo') . '</li>
					<li>' . __('Facebook Open Graph tags (title, description and image thumbnail)', 'siteseo') . '</li>';
	if ('premium-seo-pack' != $plugin) {
		$html .= '<li>' . __('Twitter tags (title, description and image thumbnail)', 'siteseo') . '</li>';
	}
	if ('wp-meta-seo' != $plugin && 'seo-ultimate' != $plugin) {
		$html .= '<li>' . __('Meta Robots (noindex, nofollow...)', 'siteseo') . '</li>';
	}
	if ('wp-meta-seo' != $plugin && 'seo-ultimate' != $plugin && 'slim-seo' != $plugin) {
		$html .= '<li>' . __('Canonical URL', 'siteseo') . '</li>';
	}
	if ('wp-meta-seo' != $plugin && 'seo-ultimate' != $plugin && 'squirrly' != $plugin && 'slim-seo' != $plugin) {
		$html .= '<li>' . __('Focus / target keywords', 'siteseo') . '</li>';
	}
	if ('wp-meta-seo' != $plugin && 'premium-seo-pack' != $plugin && 'seo-ultimate' != $plugin && 'squirrly' != $plugin && 'aio' != $plugin && 'slim-seo' != $plugin) {
		$html .= '<li>' . __('Primary category', 'siteseo') . '</li>';
	}
	if ('wpseo' == $plugin || 'platinum-seo' == $plugin || 'smart-crawl' == $plugin || 'seopressor' == $plugin || 'rk' == $plugin || 'seo-framework' == $plugin || 'aio' == $plugin) {
		$html .= '<li>' . __('Redirect URL', 'siteseo') . '</li>';
	}
	$html .= '</ul>

				<div class="siteseo-notice is-warning">
					<span class="dashicons dashicons-warning"></span>
					<p>
						' . sprintf(__('<strong>WARNING:</strong> Migration will delete / update all <strong>%1$s posts and terms metadata</strong>. Some dynamic variables will not be interpreted. We do <strong>NOT delete any %2$s data</strong>.', 'siteseo'), esc_html($seo_title), esc_html($name)) . '
					</p>
				</div>

				<button id="siteseo-' . esc_attr($plugin) . '-migrate" type="button" class="btn btnSecondary">
					' . __('Migrate now', 'siteseo') . '
				</button>

				<span class="spinner"></span>

				<div class="log"></div>
			</div>
		</div>';

	return $html;
}
