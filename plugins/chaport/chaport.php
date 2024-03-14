<?php

/**
 * Plugin Name: WP Live Chat + Chatbots Plugin for WordPress – Chaport
 * Description: Modern live chat plugin for WordPress. Powerful features: multi-channel, chatbots, customization, etc. Free plan. Unlimited chats & websites.
 * Version: 1.1.5
 * Author: Chaport
 * Author URI: https://www.chaport.com/
 * Text Domain: chaport
 * Domain Path: /languages
 * License: MIT
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(dirname(__FILE__) . '/includes/models/chaport_app_id.php');
require_once(dirname(__FILE__) . '/includes/models/chaport_installation_code.php');
require_once(dirname(__FILE__) . '/includes/renderers/chaport_installation_code_renderer.php');
require_once(dirname(__FILE__) . '/includes/renderers/chaport_app_id_renderer.php');

return ChaportPlugin::bootstrap();

final class ChaportPlugin {
	// Minimum required version of Wordpress for this plugin to work
	const WP_MAJOR = 2;
	const WP_MINOR = 8;

	private static $instance; // singleton
	public static function bootstrap() {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() { // constructable via ChaportPlugin::bootstrap()
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		add_action('admin_enqueue_scripts', array($this, 'handle_admin_enqueue_scripts') );
		add_action('admin_menu', array($this, 'handle_admin_menu'));
		add_action('admin_init', array($this, 'handle_admin_init'));
		add_action('wp_footer', array($this, 'render_chaport_code'));

		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'handle_plugin_actions'));
	}

	public function wp_version_is_compatible() {
		global $wp_version;
		$version = array_map('intval', explode('.', $wp_version));
		return $version[0] > self::WP_MAJOR || ($version[0] === self::WP_MAJOR && $version[1] >= self::WP_MINOR);
	}

	public function load_textdomain() {
		load_plugin_textdomain('chaport', false, basename(dirname(__FILE__)) . '/languages');
	}

	public function handle_admin_enqueue_scripts($hook) {
		// Include styles _only_ on Chaport Settings page
		if ($hook === 'settings_page_chaport') {
			wp_enqueue_style('chaport', plugin_dir_url(__FILE__) . 'assets/css/style.css');
			wp_enqueue_script('chaport', plugin_dir_url(__FILE__) . 'assets/js/toggle.js' );
		}
	}

	public function handle_admin_menu() {
		add_options_page(
			__('Chaport Settings', 'chaport'), // $page_title
			__('Chaport', 'chaport'), // $menu_title
			'manage_options', // $capability
			'chaport', // $menu_slug
			array($this, 'render_settings_page') // $function (callback)
		);
	}

	public function handle_admin_init() {
		register_setting('chaport_options', 'chaport_options');
		// register_setting('chaport_options', 'chaport_options', array($this, 'sanitize_options'));

		add_settings_section(
			'chaport_general_settings', // $id
			__('Chaport Settings', 'chaport'), // $title
			array($this, 'render_chaport_general_settings'), // $callback
			'chaport' // $page
		);

		add_settings_field(
			'chaport_installation_type_field', // $id
			__('Installation type', 'chaport'), // $title
			array($this, 'render_installation_type_field'), // $callback
			'chaport', // $page
			'chaport_general_settings' //$section
		);

		add_settings_field(
			'chaport_app_id_field', // $id
			__('App ID', 'chaport'), // $title
			array($this, 'render_app_id_field'), // $callback
			'chaport', // $page
			'chaport_general_settings' //$section
		);

		add_settings_field(
			'chaport_app_installation_code_field', // $id
			__('Installation code', 'chaport'), // $title
			array($this, 'render_installation_code_field'), // $callback
			'chaport', // $page
			'chaport_general_settings' //$section
		);
	}

	public function handle_plugin_actions($links) {
		// Build and escape the URL.
		$url = esc_url(add_query_arg(
			'page',
			'chaport',
			get_admin_url() . 'admin.php'
		));
		// Create the link.
		$settings_link = "<a href='$url'>" . __('Settings') . '</a>';
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

	public function get_options() {
		$options = get_option('chaport_options', array());
		$sanitized = array();
		$sanitized['app_id'] = isset($options['app_id']) ? trim($options['app_id']) : '';
		$sanitized['installation_code'] = isset($options['installation_code']) ? trim($options['installation_code']) : '';
		$sanitized['installation_type'] = isset($options['installation_type']) && $options['installation_type'] === 'installationCode' ? 'installationCode' : 'appId';
		return $sanitized;
	}

	public function render_chaport_general_settings() {
		$status_message = __('Not configured.', 'chaport'); // Default status message
		$status_class = 'chaport-status-warning'; // Default status class

		$options = $this->get_options();
		if (!isset($options['app_id'])) {
			$options['app_id'] = '';
		}
		if (!isset($options['installation_code'])) {
			$options['installation_code'] = '';
		}
		if (!isset($options['installation_type'])) {
			$options['installation_type'] = 'appId';
		}

		if (!empty($options['app_id']) && $options['installation_type'] === 'appId') {
			if (ChaportAppId::isValid($options['app_id'])) {
				$status_message = __('Configured.', 'chaport');
				$status_class = 'chaport-status-ok';
			} else {
				$status_message = __('Error. Invalid App ID.', 'chaport');
				$status_class = 'chaport-status-error';
			}
		} elseif (!empty($options['installation_code']) && $options['installation_type'] === 'installationCode') {
			if (ChaportInstallationCode::isValid($options['installation_code'])) {
				$status_message = __('Configured.', 'chaport');
				$status_class = 'chaport-status-ok';
			} else {
				$status_message = __('Error. Invalid Installation Code.', 'chaport');
				$status_class = 'chaport-status-error';
			}
		}

		require(dirname(__FILE__) . '/includes/snippets/chaport_status_snippet.php');
	}

	public function render_app_id_field() {
		$options = $this->get_options();

		echo "<input id='chaport_app_id_field' name='chaport_options[app_id]' size='40' type='text' value='" . esc_attr($options['app_id']) . "' />";
	}

	public function render_installation_code_field() {
		$options = $this->get_options();

		echo "<textarea id='chaport_app_installation_code_field' name='chaport_options[installation_code]' rows='10' cols='60'>";
		echo $options['installation_code'];
		echo "</textarea>";
	}

	public function render_installation_type_field() {
		$options = $this->get_options();
		$input_array = array(
			'appId' => array(
				'class' => 'chaport-default chaport-left',
				'id' => 'chaport_default_app_id',
				'value' => 'appId',
				'onclick' => 'ChooseAppId()',
				'label' => 'Default'
			),
			'installationCode' => array(
				'class' => 'chaport-default chaport-right',
				'id' => 'chaport_default_installation_code',
				'value' => 'installationCode',
				'onclick' => 'ChooseInstallationCode()',
				'label' => 'Installation code'
			)
		);

		if ($options['installation_type'] !== 'installationCode') {
			$options['installation_type'] = 'appId';
		}

		echo "<div class='switch-chaport' id='chaport_installation_type_field'>\n";
		foreach ($input_array as $value) {
			$input = "<input type='radio' name='chaport_options[installation_type]' class='" . $value['class'] . "' id='" . $value['id'] . "' value='" . $value['value'] . "' onclick='" . $value['onclick'] . "'";
			if ($options['installation_type'] === $value['value']) {
				$input = $input . " checked";
			}
			$input = $input . ">\n";
			echo $input;
			echo "<label for='" . $value['id'] . "' class='btn'>" . __($value['label'], 'chaport') . "</label>\n";
		};
		echo "</div>";
	}

	public function render_settings_page() {
		if (!current_user_can('manage_options')) {
			wp_die(__("You don't have access to this page"));
		}

		require(dirname(__FILE__) . '/includes/snippets/chaport_settings_snippet.php');
	}

	public function render_chaport_code() {
		// ignore requests to widgets.php for legacy widgets
		if (isset($_GET['legacy-widget-preview'])) {
			return;
		}
		if (!$this->wp_version_is_compatible() || is_feed() || is_robots() || is_trackback() || is_embed()) {
			return;
		}

		$options = $this->get_options();
		$app_id = $options['app_id'];
		$installation_code = $options['installation_code'];
		$options['installation_type'] = $options['installation_type'];
		$user_settings = wp_get_current_user();

		if (!empty($app_id) && ChaportAppId::isValid($app_id) && ($options['installation_type'] === 'appId')) {
			$renderer = new ChaportAppIdRenderer(ChaportAppId::fromString($app_id));
		} elseif(!empty($installation_code) && ChaportInstallationCode::isValid($installation_code) && ($options['installation_type'] === 'installationCode')){
			$renderer = new ChaportInstallationCodeRenderer(ChaportInstallationCode::fromString($installation_code));
		} else {
			return;
		}

		if (!empty($user_settings->user_email)) {
			$renderer->setUserEmail($user_settings->user_email);
		}
		if (!empty($user_settings->display_name)) {
			$renderer->setUserName($user_settings->display_name);
		}

		$renderer->render();
	}
}
