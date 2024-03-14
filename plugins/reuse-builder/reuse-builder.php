<?php
/*
 * Plugin Name: Reuse Builder
 * Plugin URI: https://wordpress.org/plugins/reuse-builder/
 * Description: A Simple but powerful Post Type, Metabox, Taxonomy, Term meta and Template builder
 * Version: 1.7
 * Author: redq
 * Author URI: http://redq.io
 * Requires at least: 4.7
 * Tested up to: 5.5.1
 *
 * Text Domain: reuse-builder
 *
 * Copyright: © 2017 redqteam.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

class Reuse_Builder
{
	/**
	 * @var null
	 */
	protected static $_instance = null;
	/**
	 * @create instance on self
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		$this->reuse_builder_bootstrap();
		$this->reuse_builder_autoload();

		add_action('plugins_loaded', array($this, 'reuse_builder_language_textdomain'), 1);
	}

	public function reuse_builder_bootstrap()
	{
		define('REUSE_BUILDER_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
		define('REUSE_BUILDER_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)),	basename(__FILE__))));
		define('REUSE_BUILDER_FILE', dirname(__FILE__));
		define('REUSE_BUILDER_CSS', REUSE_BUILDER_URL . '/assets/dist/css/');
		define('REUSE_BUILDER_JS',  REUSE_BUILDER_URL . '/assets/dist/js/');
		define('REUSE_BUILDER_JS_VENDOR',  REUSE_BUILDER_URL . '/assets/dist/ven/');
		define('REUSE_BUILDER_JS_REUSE_FORM',  REUSE_BUILDER_URL . '/assets/dist/reuse-form/');
		define('REUSE_BUILDER_IMG',  REUSE_BUILDER_URL . '/assets/dist/vendor/img/');
		define('REUSE_BUILDER_INCLUDE', REUSE_BUILDER_DIR . '/includes/');
		define('REUSE_BUILDER_TEMPLATE_PATH', plugin_dir_path(__FILE__) . 'templates/');
		define('REUSE_BUILDER_SHORTCODE_PATH', plugin_dir_path(__FILE__) . 'shortcodes/');
	}

	/**
	 * Get the plugin textdomain for multilingual.
	 * @return null
	 */
	public function reuse_builder_language_textdomain()
	{
		load_plugin_textdomain('reuse-builder', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	public function reuse_builder_autoload()
	{
		require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
		require_once(REUSE_BUILDER_INCLUDE . DIRECTORY_SEPARATOR . 'reuse-template-functions.php');
		$classNames = array(
			'Reuse_Builder_ICONS_Provider',
			'Reuse_Builder_Listing',
			'Reuse_Builder_Admin_Scripts',
			'Reuse_Builder_Save_Meta',
			'Reuse_Builder_Shortcode',
			'Reuse_Builder_Admin_Menu',
			'Reuse_Builder_Generate_Term_Meta',
			'Reuse_Builder_Template_Loader',
			'Reuse_Builder_Frontend_Style',
			'Reuse_Generate_Term_Meta',
			'RestAPISupport',
			'Ajax_Handler',
		);
		foreach ($classNames as $className) {
			$dynamicName = "Reuse\\Builder\\" . $className;
			if (class_exists($dynamicName)) {
				new $dynamicName();
			}
		}
	}

	/**
	 * Get the template path.
	 * @return string
	 */
	public function template_path()
	{
		return apply_filters('reuse_builder_template_path', 'reuse-templates/');
	}


	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path()
	{
		return untrailingslashit(plugin_dir_path(__FILE__));
	}
}

function reuse_builder()
{
	return Reuse_Builder::instance();
}
$GLOBALS['reuse-builder'] = reuse_builder();

register_activation_hook(__FILE__, 'reuseb_activation_init_func');
function reuseb_activation_init_func()
{
	// Add the admin notice notifier during plugin activation. Default set to false.
	add_option('reactive_builder_admin_notices', false);
	$webpack_public_path = get_option('webpack_public_path_url', true);
	if ($webpack_public_path == '' || $webpack_public_path == '1') {
		update_option('webpack_public_path_url', REUSE_BUILDER_JS_REUSE_FORM);
	}
}
