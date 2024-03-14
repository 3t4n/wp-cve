<?php

class TWL_Page_In_Page_Admin extends TWL_Page_In_Page {
	
	private static $instance = null;

	public function __construct() {
		add_action('admin_menu', array($this, 'adminMenu'));
	}

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public static function init() {
		return self::getInstance();
	}

	public function adminMenu() {
		// plugin settings page
        add_options_page('Admin Settings', 'Page-In-Page Plugin', 'manage_options', 'wp-pip-admin-settings', array($this, 'settingsHTML'));
	}
	
	public function settingsHTML() {
		$settings = $this->settings();

		if (isset($_POST['wp-pip-admin-settings-save']) && $_POST['wp-pip-admin-settings-save'] === "Agnes") {
			$settings = $this->settingsSave();
		}

		$errors = $this->getErrors();
		$successes = $this->getSuccesses();
		include TWL_PIP_TEMPLATES . '/admin-settings.php';
	}

	private function settingsSave() {
		$settings = $_POST;
		if (!wp_verify_nonce($settings['twl_pip_nonce'], TWL_PIP_ROOT)) {
			$this->addError("Could verify your request.");
			return $settings;
		}

		$settable = array(
			'facebook' => array('page_id', 'app_id', 'app_secret', 'alert_sdk_errors'),
			'twitter' => array('screen_name', 'customer_key', 'customer_secret', 'access_token', 'access_token_secret', 'alert_sdk_errors'),
		);

		// are settings present
		if (empty($settings['facebook']) || empty($settings['twitter'])) {
			$this->addError("Could not find required settings variables");
			return $settings;
		}

		// are submitted keys only the ones required?
		if (array_diff($settable['facebook'], array_keys($settings['facebook'])) ||
			array_diff($settable['twitter'], array_keys($settings['twitter'])))
		{
			$this->addError("Invalid parameters submitted");
			return $settings;
		}

		TWL_PIP_Config::add('facebook-settings', $settings['facebook']);
		TWL_PIP_Config::add('twitter-settings', $settings['twitter']);

		if (isset($settings['cache_feeds']) && is_numeric($settings['cache_feeds']) && $settings['cache_feeds'] > 0) {
			TWL_PIP_Config::add('cache_feeds', $settings['cache_feeds']);
		}

		$this->addSuccess('Settings saved.');
		return $settings;
	}

	public function settings() {
		return array(
			'facebook' => TWL_PIP_Config::option('facebook-settings', array()),
			'twitter' => TWL_PIP_Config::option('twitter-settings', array()),
			'cache_feeds' => TWL_PIP_Config::option('cache_feeds'),
		);
	}

}