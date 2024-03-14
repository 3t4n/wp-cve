<?php
/*
Plugin Name: CookieCode
Plugin URI: https://cookiecode.nl/
Description: Automatically block tracking and analytical cookies until the visitor gives their consent.
Version: 2.4.4
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

require plugin_dir_path(__FILE__) . 'includes/options.php';

final class CookieCode_Plugin
{
	/**
	 * Constants
	 */
	const SCRIPT_URL = 'https://cdn.cookiecode.nl/dist/latest.js';
	const SCRIPT_HANDLE = 'cookiecode-script';
	const OPTIONS_VERSION = '2';

	/**
	 * Static property to hold our singleton instance
	 */
	private static $instance = false;

	/**
	 * Options helper class
	 */
	private $options;

	private function __construct()
	{
		$this->options = new CookieCode_Plugin_Options();

		// Listen for the activate event
		register_activation_hook(__FILE__, array($this, 'activated'));

		// Deactivation plugin
		register_deactivation_hook(__FILE__, array($this, 'deactivated'));

		if (is_admin()) {
			//Adding menu to WP admin
			add_action('admin_menu', array($this, 'load_admin_menu'));

			//Register settings
			add_action('admin_init', array($this, 'register_settings'));
		} else {
			add_action('wp_enqueue_scripts', array($this, 'add_script'));
			add_action('wp_head', array($this, 'insert_script'), $this->options->get_script_priority_option()); // Before 'print_emoji_detection_script' (priority 7)
			add_filter('script_loader_tag', array($this, 'script_add_options'), 10, 2);
		}

		add_action('admin_bar_menu', array($this, 'admin_bar_item'), 500);

		add_action('plugins_loaded', array($this, 'upgrade'), 100);
		add_action('plugins_loaded', array($this, 'apply_plugin_compatibility'), 100);
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return CookieCode_Plugin
	 */

	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new self;
		return self::$instance;
	}

	/**
	 * Function to call when plugin activated.
	 */
	function activated()
	{
		// Logic to execute when plugin activated.
		return;
	}

	/**
	 * Function to call when Plugin deactivated.
	 */
	function deactivated()
	{
		// Logic to execute when plugin deactivated.
		return;
	}

	/** 
	 * Function to add admin menu
	 */
	function load_admin_menu()
	{
		add_menu_page('CookieCode', __('CookieCode', 'cookiecode'), 'manage_options', 'CookieCode', array($this, 'render_settings_page'));
	}

	function render_select($field, $title, $options)
	{
		$currentValue = get_option($field);
?>
		<tr>
			<th scope="row"><?php _e($title, 'cookiecode'); ?></th>
			<td>
				<select name="<?php echo $field; ?>">
					<?php
					foreach ($options as $key => $value) {
						echo '<option value="' . $key . '"' . (($currentValue == $key) ? ' selected' : '') . '>' . $value . '</option>';
					}
					?>
				</select>
			</td>
		</tr>
	<?php
	}

	/**
	 * Function to display setting page
	 */
	function render_settings_page()
	{
	?>
		<div class="wrap">
			<h1><?php _e('CookieCode Settings', 'cookiecode'); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields('cookiecode'); ?>
				<?php do_settings_sections('cookiecode'); ?>
				<table class="form-table">
					<?php $this->render_select('cookiecode-lang', 'Language', $this->options->get_supported_languages()); ?>
					<?php $this->render_select('cookiecode-blocking-mode', 'Blocking mode', $this->options->get_supported_blocking_modes()); ?>
					<?php $this->render_select('cookiecode-disable', 'Disable script for', $this->options->get_supported_script_disable_options()); ?>
				</table>

				<details>
					<summary style="cursor: pointer">
						Advanced settings
					</summary>
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Script priority', 'cookiecode'); ?></th>
							<td>
								<input type="number" name="cookiecode-priority" class="small-text" value="<?php echo $this->options->get_script_priority_option(); ?>" />
								<p class="description" id="cookiecode-priority-description">Default value is 6.</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Additional settings', 'cookiecode'); ?></th>
							<td>
								<label>
									<input type="hidden" name="cookiecode-legacy-after" value="0" />
									<input name="cookiecode-legacy-after" type="checkbox" <?php checked('1', $this->options->get_script_legacy_after_option()); ?> value="1">
									Enable deprecated "cookiecode-settings" class
								</label>
							</td>
						</tr>
					</table>
				</details>

				<?php submit_button(); ?>
			</form>
		</div>
<?php
	}

	/**
	 * Function to allow plugin upgrades
	 */
	function upgrade()
	{
		$v = get_option('cookiecode-plugin-options-version', 1);
		if ($v != self::OPTIONS_VERSION) {
			update_option('cookiecode-plugin-options-version', self::OPTIONS_VERSION);
		}
	}

	/**
	 * Function to register plugin settings.
	 */
	function register_settings()
	{
		register_setting('cookiecode', 'cookiecode-disable');
		register_setting('cookiecode', 'cookiecode-lang');
		register_setting('cookiecode', 'cookiecode-blocking-mode');
		register_setting('cookiecode', 'cookiecode-priority');
		register_setting('cookiecode', 'cookiecode-legacy-after');
	}

	/**
	 * Function to add CookieCode script in every website frontend page.
	 */
	function add_script()
	{
		if ($this->is_disabled()) {
			return;
		}

		wp_register_script(self::SCRIPT_HANDLE, self::SCRIPT_URL, array(), NULL, false);
		wp_enqueue_script(self::SCRIPT_HANDLE);

		if ($this->options->get_script_legacy_after_option()) {
			wp_add_inline_script(self::SCRIPT_HANDLE, 'document.addEventListener(\'click\', function(event) {if (event.target.matches(\'.cookiecode-settings\') || event.target.matches(\'.praivacy-settings\')) {CookieCode.showSettings();}}, false);', 'after');
		}
	}

	/**
	 * Function to add CookieCode script in every website frontend page.
	 */
	function insert_script()
	{
		$wp_scripts = wp_scripts();
		$wp_scripts->print_scripts(self::SCRIPT_HANDLE);
	}

	/**
	 * Function to add a warning to the admin bar.
	 */
	function admin_bar_item(WP_Admin_Bar $admin_bar)
	{
		if ($this->is_disabled()) {

			$admin_bar->add_menu(array(
				'id'    => 'cookiecode-warning',
				'parent' => null,
				'group'  => null,
				'title' => '<span class="ab-icon dashicons-info-outline" style="margin-top: 2px;"></span><span class="ab-label">' . __('CookieCode is currently disabled', 'cookiecode') . '</span>',
				'meta' => array(
					'html' => '<style>#wpadminbar #wp-admin-bar-cookiecode-warning-content div { height: auto; white-space: normal; line-height: initial; }</style>'
				)
			));

			$admin_bar->add_menu(array(
				'id'    => 'cookiecode-warning-content',
				'parent' => 'cookiecode-warning',
				'title' => __('CookieCode is disabled while you are logged in. A website administrator can change this option on the CookieCode settings page.', 'cookiecode')
			));

			if (current_user_can('administrator')) {
				$admin_bar->add_group(array(
					'id'    => 'cookiecode-warning-admin-group',
					'parent' => 'cookiecode-warning',
					'meta' => array(
						'class' => 'ab-sub-secondary'
					)
				));

				$admin_bar->add_menu(array(
					'id'    => 'cookiecode-warning-admin-link',
					'parent' => 'cookiecode-warning-admin-group',
					'title' => __('Go to settings page', 'cookiecode'),
					'href'  => admin_url('admin.php?page=CookieCode')
				));
			}
		}
	}

	function script_compatibility_flags()
	{
		$flags = new stdClass();
		$theme = wp_get_theme()->get_template();

		if ($theme === 'uncode') {
			if (!property_exists($flags, 'bodyNullPatch'))
				$flags->bodyNullPatch = array();

			$flags->bodyNullPatch[] = array('id' => 'uncode-init-js');
		}

		return $flags;
	}

	function script_add_options($tag, $handle)
	{
		if (self::SCRIPT_HANDLE !== $handle)
			return $tag;

		list($script, $rest) = explode("\n", $tag, 2);

		// set language if not empty
		$cookiecode_lang = $this->options->get_language();
		if (!empty($cookiecode_lang)) {
			$script = str_replace('<script ', '<script data-cc:language="' . $cookiecode_lang . '" ', $script);
		}
		// set blockig mode if not empty
		$cookiecode_blocking_mode = $this->options->get_blocking_mode();
		if (!empty($cookiecode_blocking_mode)) {
			$script = str_replace('<script ', '<script data-cc:mode="' . $cookiecode_blocking_mode . '" ', $script);
		}

		$flags = $this->script_compatibility_flags();

		if ($flags) {
			$config = '<script type="application/json;cc-config">' . json_encode(array('flags' => $flags)) . '</script>';
			return $config . "\n" . $script . "\n" . $rest;
		}

		return $script . "\n" . $rest;
	}

	/**
	 * Check if script should be disabled
	 */
	function is_disabled()
	{
		switch ($this->options->get_script_disable_option()) {
			case "":
				return current_user_can('administrator');
			case "users":
				return is_user_logged_in();
			default:
				return false;
				//return is_user_logged_in() && $this->is_builder_active();
		}
	}

	/**
	 * Check if builder plugin is active
	 */
	function is_builder_active()
	{
		// elementor
		if (in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			return true;
		}
		// divi
		$diviNeedle = "/themes/Divi";
		$l = strlen($diviNeedle) * -1;
		$templateDirectory = get_template_directory();
		if (substr(strtolower($templateDirectory), $l) == strtolower($diviNeedle)) {
			return true;
		}
		return false;
	}

	function apply_plugin_compatibility()
	{
		// WP-Rocket
		if (defined('WP_ROCKET_VERSION')) {
			add_filter('rocket_exclude_defer_js', array($this, 'wp_rocket_exclude'));
			add_filter('rocket_minify_excluded_external_js', array($this, 'wp_rocket_exclude'));
		}

		// SGO
		if (defined('SiteGround_Optimizer\VERSION')) {
			add_filter('sgo_js_minify_exclude', array($this, 'sgo_exclude'));
			add_filter('sgo_js_async_exclude', array($this, 'sgo_exclude'));
			add_filter('sgo_javascript_combine_exclude', array($this, 'sgo_exclude'));
			add_filter('sgo_javascript_combine_excluded_external_paths', array($this, 'sgo_javascript_combine_excluded_external_paths'));
		}

		// Hummingbird
		if (defined('WPHB_VERSION')) {
			add_filter('wphb_minify_resource', array($this, 'hummingbird_exclude'), 10, 3);
			add_filter('wphb_combine_resource', array($this, 'hummingbird_exclude'), 10, 3);
			add_filter('wphb_minification_display_enqueued_file', array($this, 'hummingbird_exclude'), 10, 3);
		}
	}

	// WP-Rocket
	function wp_rocket_exclude($excluded = array())
	{
		$excluded[] = self::SCRIPT_URL;
		return $excluded;
	}

	// SGO
	function sgo_exclude($excluded  = array())
	{
		$excluded[] = self::SCRIPT_HANDLE;
		return $excluded;
	}

	function sgo_javascript_combine_excluded_external_paths($excluded  = array())
	{
		$excluded[] = self::SCRIPT_URL;
		return $excluded;
	}

	// Hummingbird
	function hummingbird_exclude($action, $handle, $type)
	{
		if (is_array($handle) && isset($handle['handle'])) {
			$handle = $handle['handle'];
		}

		if ('scripts' === $type && self::SCRIPT_HANDLE === $handle) {
			return false;
		}

		return $action;
	}
}

$CookieCode_Plugin = CookieCode_Plugin::getInstance();
