<?php

/**
 * Plugin Name: EazyGrid for Elementor
 * Description: Easiest Photo Grid Gallery Plugin for Elementor
 * Author URI:  https://eazyplugins.com/eazygrid/
 * Plugin URI:  https://eazyplugins.com/eazygrid/
 * Version:     1.5.1
 * Author:      EazyPlugins
 * Text Domain: eazygrid-elementor
 * Domain Path: /i18n
 * Elementor tested up to: 3.5.3
 * Elementor Pro tested up to: 3.5.2
 */

namespace EazyGrid\Elementor;


use EazyGrid\Elementor\Classes\Assets_Manager;
use EazyGrid\Elementor\Classes\Dashboard;
use EazyGrid\Elementor\Classes\Icons_Manager;
use EazyGrid\Elementor\Classes\Widgets_Manager;

if (!defined('ABSPATH')) {
	exit;
}

final class EazyGridElementor {

	/**
	 * @var mixed
	 */
	private static $instance = null;

	/**
	 * @var string
	 */
	private $namespace = '\EazyGrid\Elementor\Classes\\';

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->defines_constant();
		add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
	}

	public function defines_constant() {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		$version = (defined('EAZY_GRID_DEV') && true == EAZY_GRID_DEV) ? time() : get_plugin_data(__FILE__)['Version'];

		/**
		 * To DO
		 * Must remove random version defination
		 */
		define('EAZYGRIDELEMENTOR_VERSION', $version);
		define('EAZYGRIDELEMENTOR_PATH', plugin_dir_path(__FILE__));
		define('EAZYGRIDELEMENTOR_URL', plugin_dir_url(__FILE__));
		define('EAZYGRIDELEMENTOR_MINIMUM_ELEMENTOR_VERSION', '3.0.0');
		define('EAZYGRIDELEMENTOR_MINIMUM_PHP_VERSION', '7.0');
	}

	public function i18n() {
		load_plugin_textdomain(
			'eazygrid-elementor',
			false,
			dirname(plugin_basename(__FILE__)) . '/i18n/'
		);
	}

	public function on_plugins_loaded() {
		if ($this->is_compatible()) {
			add_action('elementor/init', [$this, 'init']);
			$this->run();
		}
	}

	public function is_compatible() {
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_elementor_plugin']);
			return false;
		}
		if (!version_compare(ELEMENTOR_VERSION, EAZYGRIDELEMENTOR_MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_eazygrid_elementor_minimum_elementor_version']);
			return false;
		}
		if (version_compare(PHP_VERSION, EAZYGRIDELEMENTOR_MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', [$this, 'admin_notice_eazygrid_elementor_minimum_php_version']);
			return false;
		}
		return true;
	}

	public function init() {

		$this->i18n();
		$this->include_files();
		$this->init_eazy_images();

		new Dashboard();

		do_action('eazygridElementor/init');
	}

	public function include_files() {
		include_once EAZYGRIDELEMENTOR_PATH . 'inc/functions.php';
		
		Assets_Manager::instance()->init();
		Icons_Manager::instance()->init();
		Widgets_Manager::instance()->init();
	}

	public function admin_notice_missing_elementor_plugin() {
		if (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {
			$notice_title = __('Activate Elementor', 'eazygrid-elementor');
			$notice_url   = wp_nonce_url('plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php');
		} else {
			$notice_title = __('Install Elementor', 'eazygrid-elementor');
			$notice_url   = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
		}

		printf(
			'<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>',
			sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__('%1$s requires %2$s to be installed and activated to function properly. %3$s', 'eazygrid-elementor'),
				'<strong>' . esc_html__('EazyGrid for Elementor', 'eazygrid-elementor') . '</strong>',
				'<strong>' . esc_html__('Elementor', 'eazygrid-elementor') . '</strong>',
				'<a href="' . esc_url($notice_url) . '">' . esc_html($notice_title) . '</a>'
			)
		);
	}

	public function admin_notice_eazygrid_elementor_minimum_elementor_version() {
		printf(
			'<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>',
			sprintf(
				esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'eazygrid-elementor'),
				'<strong>' . esc_html__('EazyGrid for Elementor', 'eazygrid-elementor') . '</strong>',
				'<strong>' . esc_html__('Elementor', 'eazygrid-elementor') . '</strong>',
				esc_html(EAZYGRIDELEMENTOR_MINIMUM_ELEMENTOR_VERSION)
			)
		);
	}

	public function admin_notice_eazygrid_elementor_minimum_php_version() {
		printf(
			'<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>',
			sprintf(
				esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'eazygrid-elementor'),
				'<strong>' . esc_html__('Elementor Test Extension', 'eazygrid-elementor') . '</strong>',
				'<strong>' . esc_html__('PHP', 'eazygrid-elementor') . '</strong>',
				esc_html(EAZYGRIDELEMENTOR_MINIMUM_PHP_VERSION)
			)
		);
	}

	/**
	 * @param $class_name
	 * @return null
	 */
	protected function autoload($class_name) {
		if (0 !== strpos($class_name, __NAMESPACE__)) {
			return;
		}

		$file_name = strtolower(
			str_replace(
				[__NAMESPACE__ . '\\', '_', '\\'], // replace namespace, underscrore & backslash
				['', '-', '/'],
				$class_name
			)
		);

		$file = EAZYGRIDELEMENTOR_PATH . '/' . $file_name . '.php';
		if (!class_exists($class_name) && is_readable($file)) {
			include_once $file;
		}
	}

	public function run() {
		spl_autoload_register([$this, 'autoload']);
	}

	public function init_eazy_images() {
		$eazyImagesEntry = (plugin_dir_path(__FILE__) . "EazyImages/instant-images.php");
		if (file_exists($eazyImagesEntry)) {
			$upload_dir = wp_upload_dir();
			define('INSTANT_IMAGES_TITLE', 'Eazy Images');
			define('INSTANT_IMAGES_VERSION', EAZYGRIDELEMENTOR_VERSION);
			define('INSTANT_IMAGES_NAME', 'eazy-images');
			define('INSTANT_IMAGES_UPLOAD_PATH', $upload_dir['basedir'] . '/eazy-images');
			define('INSTANT_IMAGES_UPLOAD_URL', $upload_dir['baseurl'] . '/eazy-images/');
			define('INSTANT_IMAGES_WPADMIN_URL', admin_url('upload.php?page=eazy-images'));

			include_once($eazyImagesEntry);
			remove_filter('admin_footer_text', 'instant_images_filter_admin_footer_text');
			add_filter('gettext', function ($translatedText, $unTranslatedText, $textDomain) {
				if ($textDomain == 'instant-images' && $unTranslatedText == 'Instant Images') {
					return "Eazy Images";
				}
				return $translatedText;
			}, 10, 3);
		}
	}
}
new EazyGridElementor();
