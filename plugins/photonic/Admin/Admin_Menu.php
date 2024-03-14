<?php

namespace Photonic_Plugin\Admin;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Options\Defaults;

class Admin_Menu {
	private $file;
	private $core;

	public function __construct($file, $core) {
		$this->file = $file;
		$this->core = $core;

		$this->register_settings();

		add_action('admin_enqueue_scripts', [&$this, 'enqueue']);
	}

	public function settings() {
		require_once 'Options_Manager.php';
		$options_manager = new Options_Manager($this->file, $this->core);
		$options_manager->init();
		$options_manager->render('Settings');
	}

	public function getting_started() {
		require_once 'Getting_Started.php';
		$getting_started = Getting_Started::get_instance();
		$getting_started->render('Getting Started');
	}

	public function authentication() {
		require_once 'Authentication.php';
		$auth_page = Authentication::get_instance();
		$auth_page->render('Authentication');
	}

	public function shortcode() {
		require_once 'Shortcode_Replace.php';
		$gutenberg = Shortcode_Replace::get_instance();
		$gutenberg->render('Replace the Gallery Shortcode');
	}

	public function helpers() {
		require_once 'Helper.php';
		$helper_page = new Helper();
		$helper_page->render('Helpers');
	}

	/**
	 * Registers settings for the Settings API. Though this applies to the Options_Manager, the settings are required here.
	 * The Options_Manager class is big and heavy, so we don't load it on all Photonic admin pages.
	 * But since saving options calls WP's native options.php, when options.php is loaded without registering settings,
	 * it triggers an "Error: options page not found" error.
	 *
	 * So, the definition of the settings is done here, since this file is always loaded in the admin page.
	 */
	public function register_settings() {
		$pages = Defaults::get_options_pages();
		foreach ($pages as $page) {
			register_setting('photonic_options-' . $page, 'photonic_options', [&$this, 'validate_options']);
		}
	}

	/**
	 * A wrapper for the <code>validate_options</code> function in Options_Manager. This is required because of the
	 * <code>register_settings</code> call (which is a run-time call requiring a validation function) running without initiating
	 * the Options_Manager at compile-time.
	 *
	 * @param $options
	 */
	public function validate_options($options) {
		require_once 'Options_Manager.php';
		$options_manager = new Options_Manager($this->file, $this->core);
		return $options_manager->validate_options($options);
	}

	public function enqueue($hook) {
		$prefix = ['toplevel_page_', 'photonic_page_'];
		$mod_hook = str_replace($prefix, '', $hook);
		if ('photonic-options-manager' === $mod_hook) {
			wp_enqueue_style('photonic-admin-css', PHOTONIC_URL . 'include/css/admin/admin.css', ['wp-color-picker'], Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin.css'));
			global $photonic_options;
			$js_array = [
				'category' => sanitize_text_field(isset($photonic_options) && isset($photonic_options['last-set-section']) ? $photonic_options['last-set-section'] : 'generic-settings'),
			];
			wp_enqueue_script('photonic-options-js', PHOTONIC_URL . 'include/js/admin/options-manager.js', ['jquery', 'wp-color-picker'], Photonic::get_version(PHOTONIC_PATH . '/include/js/admin/options-manager.js'), true);
			wp_localize_script('photonic-options-js', 'Photonic_Options_JS', $js_array);
		}
		elseif (in_array($mod_hook, ['photonic-helpers', 'photonic-auth', 'photonic-shortcode-replace'], true)) {
			wp_enqueue_script('photonic-admin-js', PHOTONIC_URL . 'include/js/admin/helpers.js', ['jquery'], Photonic::get_version(PHOTONIC_PATH . '/include/js/admin/helpers.js'), true);
			wp_enqueue_style('photonic-admin-css', PHOTONIC_URL . 'include/css/admin/admin.css', [], Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin.css'));

			$js_array = [
				'obtain_token' => esc_attr__('Step 2: Obtain Token', 'photonic')
			];
			wp_localize_script('photonic-admin-js', 'Photonic_Admin_JS', $js_array);
		}
		elseif (in_array($mod_hook, ['photonic-getting-started'], true)) {
			wp_enqueue_style('photonic-admin-css', PHOTONIC_URL . 'include/css/admin/admin.css', [], Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin.css'));
		}
	}
}
