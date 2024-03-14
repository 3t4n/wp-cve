<?php
/**
 * Plugin Name: WP SEO Structured Data Schema
 * Plugin URI: https://wpsemplugins.com/
 * Description: Comprehensive JSON-LD based Structured Data solution for WordPress for adding schema for organizations, businesses, blog posts, ratings & more.
 * Version: 2.7.10
 * Author: WPSEMPlugins
 * Author URI: https://wpsemplugins.com/
 * Text Domain: wp-seo-structured-data-schema
 * Domain Path: /languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html.
 */
if (! defined('ABSPATH')) {
	exit;
}
if (! defined('JSON_UNESCAPED_SLASHES')) {
	define('JSON_UNESCAPED_SLASHES', 64);
}
if (! defined('JSON_PRETTY_PRINT')) {
	define('JSON_PRETTY_PRINT', 128);
}
if (! defined('JSON_UNESCAPED_UNICODE')) {
	define('JSON_UNESCAPED_UNICODE', 256);
}

define('KCSEO_WP_SCHEMA_VERSION', '2.7.10');
define('KCSEO_WP_SCHEMA_SLUG', 'wp-seo-structured-data-schema');
define('KCSEO_WP_SCHEMA_PATH', dirname(__FILE__));
define('KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME', plugin_basename(__FILE__));
define('KCSEO_WP_SCHEMA_URL', plugins_url('', __FILE__));
define('KCSEO_WP_SCHEMA_LANGUAGE_PATH', dirname(plugin_basename(__FILE__)) . '/languages');

require 'lib/init.php';
register_uninstall_hook(__FILE__, 'KCSEO_uninstall');

if (! function_exists('KCSEO_uninstall')) {
	function KCSEO_uninstall() {
		$settings = get_option(kcseo()->options['main_settings']);
		if (! empty($settings['delete-data'])) {
			$schemaFields = KcSeoOptions::getSchemaTypes();

			$args  = [
				'post_type'      => ['page', 'post'],
				'posts_per_page' => '-1',
			];
			$pages = new WP_Query($args);
			if ($pages->have_posts()) {
				while ($pages->have_posts()) {
					$pages->the_post();
					foreach ($schemaFields as $schemaID => $schema) {
						delete_post_meta(get_the_ID(), '_schema_' . $schemaID);
					}
				}
				wp_reset_postdata();
			}
		}
	}
}
