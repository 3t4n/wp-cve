<?php
/**
 * Apocalypse Meow admin.
 *
 * Admin settings, menus, etc.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;

class admin {

	const EXTENSIONS = array(
		'date',
		'filter',
		'json',
		'pcre',
	);

	protected static $errors = array();
	protected static $version;
	protected static $remote_version;
	protected static $remote_home;

	// ---------------------------------------------------------------------
	// General
	// ---------------------------------------------------------------------

	/**
	 * Warnings
	 *
	 * @return bool True/false.
	 */
	public static function warnings() {
		global $pagenow;

		// Only show warnings to administrators, and only on relevant pages.
		if (
			! \current_user_can('manage_options') ||
			('plugins.php' !== $pagenow && false === static::current_screen())
		) {
			return true;
		}

		// Only warn about Intl if it appears to be needed.
		if (
			\function_exists('mb_check_encoding') &&
			! \function_exists('idn_to_ascii') &&
			(! \mb_check_encoding(\site_url(), 'ASCII') || (false !== \strpos(\site_url(), 'xn--')))
		) {
			static::$errors[] = \__('The recommended PHP extension Intl is missing; you will not be able to handle internationalized or unicode domains.', 'apocalypse-meow');
		}

		// All good!
		if (! \count(static::$errors)) {
			return true;
		}

		?>
		<div class="notice notice-error">
			<p><?php
			\printf(
				\__('Your server does not meet the requirements for running %s. You or your system administrator should take a look at the following:'),
				'<strong>Apocalypse Meow</strong>'
			);
			?><br>
			&nbsp;&nbsp;&bullet;&nbsp;&nbsp;<?php echo \implode('<br>&nbsp;&nbsp;&bullet;&nbsp;&nbsp;', static::$errors); ?></p>
		</div>
		<?php

		return false;
	}

	/**
	 * Fix Server Name
	 *
	 * WordPress generates its wp_mail() "from" address from
	 * $_SERVER['SERVER_NAME'], which doesn't always exist. This
	 * will generate something to use as a fallback for CLI
	 * instances, etc.
	 *
	 * @return void Nothing.
	 */
	public static function server_name() {
		if (! \array_key_exists('SERVER_NAME', $_SERVER)) {
			if (false === $_SERVER['SERVER_NAME'] = common\sanitize::hostname(\site_url(), false)) {
				$_SERVER['SERVER_NAME'] = 'localhost';
			}
		}
	}

	/**
	 * Update Notice
	 *
	 * This adds an update notice to the plugins page in cases where the
	 * plugin has been installed in Must-Use mode.
	 *
	 * @return void Nothing.
	 */
	public static function update_notice() {
		if (\MEOW_MUST_USE) {
			$screen = \get_current_screen();
			if (
				('plugins' === $screen->id) &&
				static::has_update()
			) {
				echo '<div class="notice notice-warning"><p>' . \sprintf(
					\__('%s %s has been released! Must-Use plugins must be updated manually, so click %s to download the new version.', 'apocalypse-meow'),
					'<em>Apocalypse Meow</em>',
					'<code>' . static::$remote_version . '</code>',
					'<a href="' . static::$remote_home . '" target="_blank" rel="noopener">' . \__('here', 'apocalypse-meow') . '</a>'
				) . '</p></div>';
			}
		}
	}

	/**
	 * Has Update?
	 *
	 * We need to query the API because WordPress won't check for
	 * updates if this plugin is installed as Must-Use.
	 *
	 * @return bool True/false.
	 */
	public static function has_update() {
		return (\version_compare(static::get_version(), static::get_remote_version()) < 0);
	}

	/**
	 * Get Plugin Version
	 *
	 * @return string Version.
	 */
	public static function get_version() {
		if (\is_null(static::$version)) {
			$plugin_data = \get_plugin_data(\MEOW_INDEX, false, false);
			if (isset($plugin_data['Version'])) {
				static::$version = $plugin_data['Version'];
			}
			else {
				static::$version = '0.0';
			}
		}

		return static::$version;
	}

	/**
	 * Get Remote Version
	 *
	 * @return string Version.
	 */
	public static function get_remote_version() {
		if (\is_null(static::$remote_version)) {
			require_once \trailingslashit(\ABSPATH) . 'wp-admin/includes/plugin.php';
			require_once \trailingslashit(\ABSPATH) . 'wp-admin/includes/plugin-install.php';

			$response = \plugins_api(
				'plugin_information',
				array('slug'=>'apocalypse-meow')
			);
			if (
				! \is_wp_error($response) &&
				\is_a($response, 'stdClass') &&
				isset($response->version)
			) {
				static::$remote_version = $response->version;
				static::$remote_home = $response->homepage;
			}
			else {
				static::$remote_version = '0.0';
			}
		}

		return static::$remote_version;
	}

	/**
	 * Localize
	 *
	 * @return void Nothing.
	 */
	public static function localize() {
		if (\MEOW_MUST_USE) {
			\load_muplugin_textdomain('apocalypse-meow', \basename(\MEOW_PLUGIN_DIR) . '/languages');
		}
		else {
			\load_plugin_textdomain('apocalypse-meow', false, \basename(\MEOW_PLUGIN_DIR) . '/languages');
		}
	}

	/**
	 * Current Screen
	 *
	 * The WP Current Screen function isn't ready soon enough
	 * for our needs, so we need to get creative.
	 *
	 * @return bool|string WH screen type or false.
	 */
	public static function current_screen() {
		// Obviously this needs to be an admin page.
		if (! \is_admin()) {
			return false;
		}

		// Could be a miscellaneous page.
		if (\array_key_exists('page', $_GET)) {
			if (\preg_match('/^meow\-/', $_GET['page'])) {
				return $_GET['page'];
			}
		}

		return false;
	}

	/**
	 * Privacy Policy
	 *
	 * @return void Nothing.
	 */
	public static function privacy_policy() {
		if (\function_exists('wp_add_privacy_policy_content')) {
			// The default.
			// phpcs:disable
			$privacy = __("This site retains security logs of every log-in attempt made to the CMS backend. This information — including the end user's public IP address, username, and the status of his or her attempt — is used to help prevent unauthorized system access and maintain Quality of Service for all site visitors.", 'apocalypse-meow');
			// phpcs:enable

			// Community pool additionally shares this information with
			// Blobfolio.
			if (options::get('login-community')) {
				$privacy .= "\n\n" . \sprintf(
					// phpcs:disable
					__('For additional security, this web site participates in a community-sourced attack traffic monitoring and mitigation program. As a contributing member, IP addresses associated with attacks against this web site are periodically shared with Blobfolio, LLC (%s), the maintainer of the centralized database. Any IP addresses identified by multiple, independent sources are published to a publicly available blocklist.', 'apocalypse-meow'),
					// phpcs:enable
					'<a href="https://blobfolio.com/privacy-policy/" target="_blank" rel="noopener">' . \__('Privacy Policy', 'apocalypse-meow') . '</a>'
				);
			}

			// Mention pruning.
			if (options::get('prune-active')) {
				$privacy .= "\n\n" . \sprintf(
					\__('This data is only retained while relevant for security purposes and is automatically removed after %d days.', 'apocalypse-meow'),
					options::get('prune-limit')
				);
			}
			else {
				$privacy .= "\n\n" . \__('This information is exclusively used to help restrict unauthorized system access and maintain quality of service. Log-in data is not shared with any third-party.', 'apocalypse-meow');
			}

			// Add the notice!
			\wp_add_privacy_policy_content(
				'Apocalypse Meow',
				\wp_kses_post(\wpautop($privacy))
			);
		}
	}

	// --------------------------------------------------------------------- end general



	// ---------------------------------------------------------------------
	// Menus & Pages
	// ---------------------------------------------------------------------

	/**
	 * Export JSON
	 *
	 * This sanitizes and exports JSON data to a container on the page that
	 * Vue scripts can read from.
	 *
	 * @param array $data Data.
	 * @return void Nothing.
	 */
	public static function json_meowdata($data) {
		common\ref\format::json_encode($data, \JSON_HEX_AMP | \JSON_HEX_TAG);
		?><script type="application/json" id="meow-data"><?php
			echo $data;
		?></script><?php
	}

	/**
	 * Register Scripts & Styles
	 *
	 * Register our assets and enqueue some of them maybe.
	 *
	 * @return bool True/false.
	 */
	public static function enqueue_scripts() {
		// Find our CSS and JS roots. Easy if this
		// is a regular plugin.
		$js = \MEOW_PLUGIN_URL . 'js/';
		$css = \MEOW_PLUGIN_URL . 'css/';

		// Dashboard CSS.
		\wp_register_style(
			'meow_css_dashboard',
			"{$css}dashboard.css",
			array(),
			\MEOW_VERSION
		);
		\wp_enqueue_style('meow_css_dashboard');

		// The rest is for our pages.
		if (false === ($screen = static::current_screen())) {
			return true;
		}

		// Chartist CSS.
		\wp_register_style(
			'meow_css_chartist',
			"{$css}chartist.css",
			array(),
			\MEOW_VERSION
		);
		if ('meow-stats' === $screen) {
			\wp_enqueue_style('meow_css_chartist');
		}

		// Prism CSS.
		\wp_register_style(
			'meow_css_prism',
			"{$css}prism.css",
			array(),
			\MEOW_VERSION
		);
		if (\in_array($screen, array('meow-help', 'meow-settings', 'meow-tools'), true)) {
			\wp_enqueue_style('meow_css_prism');
		}

		// Main CSS.
		\wp_register_style(
			'meow_css',
			"{$css}core.css",
			array(),
			\MEOW_VERSION
		);
		\wp_enqueue_style('meow_css');

		// Chartist JS.
		\wp_register_script(
			'meow_js_chartist',
			"{$js}chartist.min.js",
			array('meow_js_vue'),
			\MEOW_VERSION,
			true
		);

		// Clipboard JS.
		\wp_register_script(
			'meow_js_clipboard',
			"{$js}clipboard.min.js",
			array(),
			\MEOW_VERSION,
			true
		);

		// Prism JS.
		\wp_register_script(
			'meow_js_prism',
			"{$js}prism.min.js",
			array('meow_js_clipboard'),
			\MEOW_VERSION,
			true
		);

		// Vue JS.
		\wp_register_script(
			'meow_js_vue',
			(\defined('WP_DEBUG') && \WP_DEBUG ? "{$js}vue-debug.min.js" : "{$js}vue.min.js"),
			array('jquery'),
			\MEOW_VERSION,
			true
		);

		// Activity JS.
		\wp_register_script(
			'meow_js_activity',
			"{$js}core-activity.min.js",
			array(
				'meow_js_vue',
			),
			\MEOW_VERSION,
			true
		);
		if ('meow-activity' === $screen) {
			\wp_enqueue_script('meow_js_activity');
		}

		// Help JS.
		\wp_register_script(
			'meow_js_help',
			"{$js}core-help.min.js",
			array(
				'meow_js_vue',
				'meow_js_prism',
			),
			\MEOW_VERSION,
			true
		);
		if ('meow-help' === $screen) {
			\wp_enqueue_script('meow_js_help');
		}

		// Retroactive Reset JS.
		\wp_register_script(
			'meow_js_retroactive_reset',
			"{$js}core-retroactive-reset.min.js",
			array(
				'meow_js_vue',
			),
			\MEOW_VERSION,
			true
		);
		if ('meow-retroactive-reset' === $screen) {
			\wp_enqueue_script('meow_js_retroactive_reset');
		}

		// Settings JS.
		\wp_register_script(
			'meow_js_settings',
			"{$js}core-settings.min.js",
			array(
				'meow_js_vue',
				'meow_js_prism',
			),
			\MEOW_VERSION,
			true
		);
		if ('meow-settings' === $screen) {
			\wp_enqueue_script('meow_js_settings');
		}

		// Stats JS.
		\wp_register_script(
			'meow_js_stats',
			"{$js}core-stats.min.js",
			array(
				'meow_js_vue',
				'meow_js_chartist',
			),
			\MEOW_VERSION,
			true
		);
		if ('meow-stats' === $screen) {
			\wp_enqueue_script('meow_js_stats');
		}

		// Tools JS.
		\wp_register_script(
			'meow_js_tools',
			"{$js}core-tools.min.js",
			array(
				'meow_js_vue',
				'meow_js_prism',
			),
			\MEOW_VERSION,
			true
		);
		if ('meow-tools' === $screen) {
			\wp_enqueue_script('meow_js_tools');
		}

		return true;
	}

	/**
	 * Register Menus
	 *
	 * @return void Nothing.
	 */
	public static function register_menus() {
		$pages = array(
			'retroactive_reset',
			'settings',
			'activity',
			'stats',
			'tools',
			'help',
			'rename',
		);

		foreach ($pages as $page) {
			\add_action('admin_menu', array(static::class, "{$page}_menu"));
		}

		// Register plugins page quick links if we aren't running in
		// Must-Use mode.
		if (! \MEOW_MUST_USE) {
			\add_filter(
				'plugin_action_links_' . \plugin_basename(\MEOW_INDEX),
				array(static::class, 'plugin_action_links')
			);
		}
	}

	/**
	 * Password Reset Menu
	 *
	 * Rather than sending users to the (huge) profile page to reset
	 * passwords, let's give them something more straight-forward.
	 *
	 * @return void Nothing.
	 */
	public static function retroactive_reset_menu() {
		if (login::password_require_reset_needed()) {
			\add_submenu_page(
				'index.php',
				\__('Reset Password', 'apocalypse-meow'),
				\__('Reset Password', 'apocalypse-meow'),
				'read',
				'meow-retroactive-reset',
				array(static::class, 'retroactive_reset_page')
			);
		}
	}

	/**
	 * Password Reset Page
	 *
	 * @return void Nothing.
	 */
	public static function retroactive_reset_page() {
		require \MEOW_PLUGIN_DIR . 'admin/retroactive-reset.php';
	}

	/**
	 * Settings Menu
	 *
	 * @return void Nothing.
	 */
	public static function settings_menu() {
		// Send settings.
		\add_menu_page(
			\__('Settings', 'apocalypse-meow'),
			\__('Settings', 'apocalypse-meow'),
			'manage_options',
			'meow-settings',
			array(static::class, 'settings_page'),
			'dashicons-meow'
		);
	}

	/**
	 * Settings Pages
	 *
	 * @return void Nothing.
	 */
	public static function settings_page() {
		require \MEOW_PLUGIN_DIR . 'admin/settings.php';
	}

	/**
	 * Activity Menu
	 *
	 * @return void Nothing.
	 */
	public static function activity_menu() {
		\add_submenu_page(
			'meow-settings',
			\__('Login Activity', 'apocalypse-meow'),
			\__('Login Activity', 'apocalypse-meow'),
			'manage_options',
			'meow-activity',
			array(static::class, 'activity_page')
		);
	}

	/**
	 * Activity Page
	 *
	 * @return void Nothing.
	 */
	public static function activity_page() {
		require \MEOW_PLUGIN_DIR . 'admin/activity.php';
	}

	/**
	 * Reference Menu
	 *
	 * @return void Nothing.
	 */
	public static function help_menu() {
		\add_submenu_page(
			'meow-settings',
			\__('Reference', 'apocalypse-meow'),
			\__('Reference', 'apocalypse-meow'),
			'manage_options',
			'meow-help',
			array(static::class, 'help_page')
		);
	}

	/**
	 * Reference Page
	 *
	 * @return void Nothing.
	 */
	public static function help_page() {
		require \MEOW_PLUGIN_DIR . 'admin/help.php';
	}

	/**
	 * Stats Menu
	 *
	 * @return void Nothing.
	 */
	public static function stats_menu() {
		\add_submenu_page(
			'meow-settings',
			\__('Login Stats', 'apocalypse-meow'),
			\__('Login Stats', 'apocalypse-meow'),
			'manage_options',
			'meow-stats',
			array(static::class, 'stats_page')
		);
	}

	/**
	 * Stats Page
	 *
	 * @return void Nothing.
	 */
	public static function stats_page() {
		require \MEOW_PLUGIN_DIR . 'admin/stats.php';
	}

	/**
	 * Tools
	 *
	 * @return void Nothing.
	 */
	public static function tools_menu() {
		\add_submenu_page(
			'meow-settings',
			\__('Tools', 'apocalypse-meow'),
			\__('Tools', 'apocalypse-meow'),
			'manage_options',
			'meow-tools',
			array(static::class, 'tools_page')
		);
	}

	/**
	 * Tools Page
	 *
	 * @return void Nothing.
	 */
	public static function tools_page() {
		require \MEOW_PLUGIN_DIR . 'admin/tools.php';
	}

	/**
	 * Rename Menu
	 *
	 * We want to change the main menu name but leave the main submenu
	 * link as is. This requires a bit of a hack after the menu has
	 * been populated.
	 *
	 * @return void Nothing.
	 */
	public static function rename_menu() {
		global $menu;
		$tmp = \array_reverse($menu, true);
		foreach ($tmp as $k=>$v) {
			if (! \is_array($v) || \count($v) < 3) {
				continue;
			}
			if (isset($v[2]) && ('meow-settings' === $v[2])) {
				$menu[$k][0] = 'Apocalypse Meow';
				break;
			}
		}
		unset($tmp);
	}

	/**
	 * Plugin Links
	 *
	 * Add some quick links to the entry on the plugins page.
	 *
	 * @param array $links Links.
	 * @return array Links.
	 */
	public static function plugin_action_links($links) {
		// Settings.
		$links[] = '<a href="' . \esc_url(\admin_url('admin.php?page=meow-settings')) . '">' . \__('Settings', 'apocalypse-meow') . '</a>';

		// Activity.
		$links[] = '<a href="' . \esc_url(\admin_url('admin.php?page=meow-activity')) . '">' . \__('Activity', 'apocalypse-meow') . '</a>';

		// Tools.
		$links[] = '<a href="' . \esc_url(\admin_url('admin.php?page=meow-tools')) . '">' . \__('Tools', 'apocalypse-meow') . '</a>';

		return $links;
	}

	// --------------------------------------------------------------------- end menus



	// ---------------------------------------------------------------------
	// User Columns
	// ---------------------------------------------------------------------

	/**
	 * Add User Columns
	 *
	 * @param array $columns Columns.
	 * @return array Columns.
	 */
	public static function users_columns($columns) {
		if (\current_user_can('manage_options')) {
			$columns['meow_last_login'] = \__('Last Login', 'apocalypse-meow');
			$columns['meow_failed_logins'] = \__('Failed Logins', 'apocalypse-meow');
			$columns['meow_registered'] = \__('Registered', 'apocalypse-meow');
		}

		return $columns;
	}

	/**
	 * Add Sortable User Columns
	 *
	 * @param array $columns Columns.
	 * @return array Columns.
	 */
	public static function users_sortable_columns($columns) {
		if (\current_user_can('manage_options')) {
			$columns['meow_registered'] = 'user_registered';
		}

		return $columns;
	}

	/**
	 * User Column Values
	 *
	 * @param string $value Column value.
	 * @param string $column Column.
	 * @param int $user_id User ID.
	 * @return string Value.
	 */
	public static function users_custom_column($value, $column, $user_id) {
		$user_id = (int) $user_id;
		if (\current_user_can('manage_options') && $user_id > 0) {
			global $wpdb;

			switch ($column) {
				case 'meow_last_login':
					$dbResult = $wpdb->get_results("
						SELECT
							l.date_created,
							l.ip
						FROM
							`{$wpdb->users}` AS u,
							`{$wpdb->prefix}meow2_log` AS l
						WHERE
							u.ID=$user_id AND
							u.user_login=l.username AND
							l.type='success'
						ORDER BY l.date_created DESC
						LIMIT 1
					", \ARRAY_A);
					if (\is_array($dbResult) && \count($dbResult)) {
						$Row = common\data::array_pop_top($dbResult);
						common\ref\sanitize::ip($Row['ip']);

						$value = static::get_column_date($Row['date_created']);
						if ($Row['ip']) {
							$value .= "<br>{$Row['ip']}";
						}
					}

					break;
				case 'meow_failed_logins':
					$dbResult = $wpdb->get_results("
						SELECT
							MIN(l.date_created) AS `date_min`,
							MAX(l.date_created) AS `date_max`,
							COUNT(*) AS `fails`
						FROM
							`{$wpdb->users}` AS u,
							`{$wpdb->prefix}meow2_log` AS l
						WHERE
							u.ID=$user_id AND
							u.user_login=l.username AND
							l.type='fail'
						GROUP BY u.ID
					", \ARRAY_A);
					if (\is_array($dbResult) && \count($dbResult)) {
						$Row = common\data::array_pop_top($dbResult);

						$value = (int) $Row['fails'];

						$first = static::get_column_date($Row['date_min']);
						$last = static::get_column_date($Row['date_max']);

						// Multiple times.
						if (\strip_tags($first) !== \strip_tags($last)) {
							$value .= "<br>$first - $last";
						}
						// One time.
						else {
							$value .= "<br>$first";
						}
					}
					else {
						$value = 0;
					}

					break;
				case 'meow_registered':
					if (false !== ($user = \get_userdata($user_id))) {
						$value = static::get_column_date($user->user_registered);
					}

					break;
			}
		}

		return $value;
	}

	/**
	 * Column Time Formatter
	 *
	 * This formats dates the same way other admin columns do, using an
	 * <abbr> tag to cut down on the display real estate.
	 *
	 * @param string $date Datetime.
	 * @param bool $utc UTC.
	 * @return string HTML.
	 */
	protected static function get_column_date($date, $utc=false) {
		$time = \strtotime($date);
		$t_time = \date(\__('Y/m/d g:i:s a'), $time);
		$now = $utc ? \time() : \current_time('timestamp');
		$time_diff = $now - $time;

		// Relative time.
		if ($time_diff > 0 && $time_diff < \DAY_IN_SECONDS) {
			$h_time = \sprintf(
				\__('%s ago'),
				\human_time_diff($now, $time)
			);
		}
		// A date.
		else {
			$h_time = \mysql2date(\__('Y/m/d'), $date);
		}

		return '<abbr title="' . \esc_attr($t_time) . '">' . $h_time . '</abbr>';
	}

	// --------------------------------------------------------------------- end user cols

}
