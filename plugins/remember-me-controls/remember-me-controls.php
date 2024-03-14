<?php
/**
 * Plugin Name: Remember Me Controls
 * Version:     2.0.1
 * Plugin URI:  https://coffee2code.com/wp-plugins/remember-me-controls/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: remember-me-controls
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Have "Remember Me" checked by default on the login page and configure how long a login is remembered. Or disable the feature altogether.
 *
 * Compatible with WordPress 4.9+ through 6.2+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/remember-me-controls/
 *
 * @package Remember_Me_Controls
 * @author  Scott Reilly
 * @version 2.0.1
 */

/*
	Copyright (c) 2009-2023 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_RememberMeControls' ) ) :

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'c2c-plugin.php' );

final class c2c_RememberMeControls extends c2c_Plugin_065 {

	/**
	 * Name of plugin's setting.
	 *
	 * @var string
	 */
	const SETTING_NAME = 'c2c_remember_me_controls';

	/**
	 *  The one true instance.
	 *
	 * @var c2c_RememberMeControls
	 * @access private
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @since 1.4
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct( '2.0.1', 'remember-me-controls', 'c2c', __FILE__, array() );
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );

		return self::$instance = $this;
	}

	/**
	 * Handles activation tasks, such as registering the uninstall hook.
	 *
	 * @since 1.1
	 */
	public static function activation() {
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}

	/**
	 * Handles uninstallation tasks, such as deleting plugin options.
	 *
	 * @since 1.1
	 */
	public static function uninstall() {
		delete_option( self::SETTING_NAME );
	}

	/**
	 * Initializes the plugin's configuration and localizable text variables.
	 */
	public function load_config() {
		$this->name      = __( 'Remember Me Controls', 'remember-me-controls' );
		$this->menu_name = __( 'Remember Me', 'remember-me-controls' );

		$this->config = array(
			'auto_remember_me' => array(
				'input'    => 'checkbox',
				'default'  => false,
				'label'    => __( 'Automatically remember?', 'remember-me-controls' ),
				'help'     => __( 'Enable "Remember Me" on the login form by default.', 'remember-me-controls' ),
				'raw_help' => '<ul class="description"><li>'
					. __( 'Ignored if "Never remember?" is checked.', 'remember-me-controls' )
					. '</li></ul>',
			),
			'remember_me_forever' => array(
				'input'    => 'checkbox',
				'default'  => false,
				'label'    => sprintf(
					/* translators: %s: Markup for a character indicating a footnote. */
					__( 'Remember forever%s?', 'remember-me-controls' ),
					'<sup style="color:red;font-weight:bold;">*</sup>'
				),
				'help'     => __( 'Remember logins for as long as possible.', 'remember-me-controls' ),
				'raw_help' => '<ul class="description"><li>'
					. __( 'If checked, then the "Remember Me duration" value below is disabled and ignored.', 'remember-me-controls')
					. '</li><li>'
					. __( 'Ignored if "Never remember?" is checked.', 'remember-me-controls' )
					. '</li><li>'
					. '<sup style="color:red;font-weight:bold;margin-right:2px;">*</sup>'
					. __( "A login is not quite remembered forever; technically it's 100 years.", 'remember-me-controls' )
					. '</ul>'
					. sprintf(
						'<p class="c2c-notice-inline notice notice-info">%s</p>' . "\n",
						__( 'NOTE: This change will not immediately affect existing login sessions. It will only take effect the next time they log in.', 'remember-me-controls' )
					),
			),
			'remember_me_duration' => array(
				'input'    => 'number',
				'default'  => '',
				'datatype' => 'int',
				'label'    => __( 'Remember Me duration', 'remember-me-controls' ),
				'inline_help' => _x( 'hours', 'The unit of time for the duration input', 'remember-me-controls' ),
				'help'     => __( 'The length of time a login with "Remember Me" checked will last.', 'remember-me-controls' ),
				'raw_help' => '<ul class="description"><li>'
					. __( 'If not provided or 0, then the WordPress default of 336 (i.e. two weeks) will be used.', 'remember-me-controls' )
					. '</li><li>'
					. __( 'Ignored if "Remember forever?" or "Never remember?" is checked.', 'remember-me-controls' )
					. '</li></ul>'
					. sprintf(
						'<p class="c2c-notice-inline notice notice-info">%s</p>' . "\n",
						__( 'NOTE: This change will not immediately affect existing login sessions. It will only take effect the next time they log in.', 'remember-me-controls' )
					),
			),
			'disable_remember_me' => array(
				'input'    => 'checkbox',
				'default'  => false,
				'label'    => __( 'Never remember?', 'remember-me-controls' ),
				'help'     => __( 'Remove the "Remember Me" checkbox from the login form and limit login sessions to no longer than 2 days (48 hours).', 'remember-me-controls' ),
				'raw_help' => '<ul class="description"><li>'
					. __( 'If checked, then all other settings are disabled and ignored.', 'remember-me-controls' )
					. '</li></ul>'
					. sprintf(
						'<p class="c2c-notice-inline notice notice-info">%s</p> ' . "\n",
						__( 'NOTE: This change will not immediately affect existing login sessions. It will only take effect the next time they log in.', 'remember-me-controls' )
					),
			),
		);
	}

	/**
	 * Override the plugin framework's register_filters() to register actions
	 * and filters.
	 */
	public function register_filters() {
		add_action( 'auth_cookie_expiration',                 array( $this, 'auth_cookie_expiration' ), 10, 3 );
		add_action( 'admin_head',                             array( $this, 'add_admin_js' ) );
		add_action( 'login_head',                             array( $this, 'add_css' ) );
		add_filter( 'login_footer',                           array( $this, 'add_js' ) );
		add_action( $this->get_hook( 'post_display_option' ), array( $this, 'maybe_add_hr' ) );
		add_filter( 'login_form_defaults',                    array( $this, 'login_form_defaults' ) );

		// Compat for BuddyPress Login Widget.
		add_action( 'bp_before_login_widget_loggedout',       array( $this, 'add_css' ) );
		add_action( 'bp_after_login_widget_loggedout',        array( $this, 'add_js' ) );

		// Compat for Login Widget With Shortcode plugin.
		add_filter( 'pre_option_login_afo_rem',               '__return_empty_string' );

		// Compat for Sidebar Login plugin.
		add_filter( 'sidebar_login_widget_form_args',         array( $this, 'compat_for_sidebar_login' ) );
		add_action( 'wp_ajax_sidebar_login_process',          array( $this, 'compat_for_sidebar_login_ajax_handler' ), 1 );
		add_action( 'wp_ajax_nopriv_sidebar_login_process',   array( $this, 'compat_for_sidebar_login_ajax_handler' ), 1 );
	}

	/**
	 * Returns translated strings used by c2c_Plugin parent class.
	 *
	 * @since 2.0
	 *
	 * @param string $string Optional. The string whose translation should be
	 *                       returned, or an empty string to return all strings.
	 *                       Default ''.
	 * @return string|string[] The translated string, or if a string was provided
	 *                         but a translation was not found then the original
	 *                         string, or an array of all strings if $string is ''.
	 */
	public function get_c2c_string( $string = '' ) {
		$strings = array(
			'%s cannot be cloned.'
				/* translators: %s: Name of plugin class. */
				=> __( '%s cannot be cloned.', 'remember-me-controls' ),
			'%s cannot be unserialized.'
				/* translators: %s: Name of plugin class. */
				=> __( '%s cannot be unserialized.', 'remember-me-controls' ),
			'A value is required for: "%s"'
				/* translators: %s: Label for setting. */
				=> __( 'A value is required for: "%s"', 'remember-me-controls' ),
			'Click for more help on this plugin'
				=> __( 'Click for more help on this plugin', 'remember-me-controls' ),
			' (especially check out the "Other Notes" tab, if present)'
				=> __( ' (especially check out the "Other Notes" tab, if present)', 'remember-me-controls' ),
			'Coffee fuels my coding.'
				=> __( 'Coffee fuels my coding.', 'remember-me-controls' ),
			'Donate'
				=> __( 'Donate', 'remember-me-controls' ),
			'Expected integer value for: %s'
				=> __( 'Expected integer value for: %s', 'remember-me-controls' ),
			'If this plugin has been useful to you, please consider a donation'
				=> __( 'If this plugin has been useful to you, please consider a donation', 'remember-me-controls' ),
			'Invalid file specified for C2C_Plugin: %s'
				/* translators: %s: Path to the plugin file. */
				=> __( 'Invalid file specified for C2C_Plugin: %s', 'remember-me-controls' ),
			'More information about %1$s %2$s'
				/* translators: 1: plugin name 2: plugin version */
				=> __( 'More information about %1$s %2$s', 'remember-me-controls' ),
			'More Help'
				=> __( 'More Help', 'remember-me-controls' ),
			'More Plugin Help'
				=> __( 'More Plugin Help', 'remember-me-controls' ),
			'Reset Settings'
				=> __( 'Reset Settings', 'remember-me-controls' ),
			'Save Changes'
				=> __( 'Save Changes', 'remember-me-controls' ),
			'See the "Help" link to the top-right of the page for more help.'
				=> __( 'See the "Help" link to the top-right of the page for more help.', 'remember-me-controls' ),
			'Settings'
				=> __( 'Settings', 'remember-me-controls' ),
			'Settings reset.'
				=> __( 'Settings reset.', 'remember-me-controls' ),
			'Something went wrong.'
				=> __( 'Something went wrong.', 'remember-me-controls' ),
			"Thanks for the consideration; it's much appreciated."
				=> __( "Thanks for the consideration; it's much appreciated.", 'remember-me-controls' ),
			'The method %1$s should not be called until after the %2$s action.'
				/* translators: 1: The name of a code function, 2: The name of a WordPress action. */
				=> __( 'The method %1$s should not be called until after the %2$s action.', 'remember-me-controls' ),
			'The plugin author homepage.'
				=> __( 'The plugin author homepage.', 'remember-me-controls' ),
			"The plugin configuration option '%s' must be supplied."
				/* translators: %s: The setting configuration key name. */
				=>__( "The plugin configuration option '%s' must be supplied.", 'remember-me-controls' ),
			'This plugin brought to you by %s.'
				/* translators: %s: Link to plugin author's homepage. */
				=> __( 'This plugin brought to you by %s.', 'remember-me-controls' ),
		);

		if ( ! $string ) {
			return array_values( $strings );
		}

		return ! empty( $strings[ $string ] ) ? $strings[ $string ] : $string;
	}

	/**
	 * Outputs the text above the setting form.
	 *
	 * @param string $localized_heading_text Optional. Localized page heading text.
	 */
	public function options_page_description( $localized_heading_text = '' ) {
		parent::options_page_description( __( 'Remember Me Controls Settings', 'remember-me-controls' ) );

		echo '<p>' . __( 'Take control of the "Remember Me" login feature for WordPress by customizing its behavior or disabling it altogether.', 'remember-me-controls' ) . "</p>\n";
		echo '<p>' . __( 'For those unfamiliar, "Remember Me" is a checkbox present when logging into WordPress. If checked, by default WordPress will remember the login session for 14 days. If unchecked, the login session will be remembered for only 2 days. Once a login session expires, WordPress will require you to log in again if you wish to continue using the admin section of the site.', 'remember-me-controls' ) . "</p>\n";
		echo '<p>' . __( 'NOTE: WordPress remembers who you are based on cookies stored in your web browser. If you use a different web browser, clear your cookies, use a browser on a different machine, the site owner invalidates all existing login sessions, or you uninstall/reinstall (and possibly even just restart) your browser then you will have to log in again since WordPress will not be able to locate the cookies needed to identify you.', 'remember-me-controls' ) . "</p>\n";
		echo '<p>' . __( 'NOTE: Any changes to the duration of a login session only take effect on subsequent logins and will not affect currently active sessions.', 'remember-me-controls' ) . "</p>\n";

		$this->display_current_login_duration();
	}

	/**
	 * Converts seconds to a human-friendly expression of the length of time,
	 * in years, months, days, and/or hours.
	 *
	 * @param int $seconds The number of seconds.
	 * @return string
	 */
	public function humanize_seconds( $seconds ) {
		$year_string = $month_string = $day_string = $hour_string = '';

		if ( ! is_int( $seconds ) ) {
			return '';
		}

		// Determine years.
		$years = floor( $seconds / YEAR_IN_SECONDS);
		if ( $years ) {
			$year_string = sprintf(
				_n( '%d year', '%d years', $years, 'remember-me-controls' ),
				$years
			);
		}
		$seconds -= $years * YEAR_IN_SECONDS;

		// Determine months.
		$monthSeconds = $seconds % YEAR_IN_SECONDS;
		$months = floor( $monthSeconds / MONTH_IN_SECONDS );
		if ( $months ) {
			$month_string = sprintf(
				_n( '%d month', '%d months', $months, 'remember-me-controls' ),
				$months
			);
		}
		$seconds -= $months * MONTH_IN_SECONDS;

		// Determine days.
		$daySeconds = $seconds % MONTH_IN_SECONDS;
		$days = floor( $daySeconds / DAY_IN_SECONDS );
		if ( $days ) {
			$day_string = sprintf(
				_n( '%d day', '%d days', $days, 'remember-me-controls' ),
				$days
			);
		}
		$seconds -= $days * DAY_IN_SECONDS;

		// Determine hours.
		$hourSeconds = $seconds % DAY_IN_SECONDS;
		$hours = floor( $hourSeconds / HOUR_IN_SECONDS );
		if ( $hours ) {
			$hour_string = sprintf(
				_n( '%d hour', '%d hours', $hours, 'remember-me-controls' ),
				$hours
			);
		}

		// Merge the time segments that have values into a single string.
		$time_string = sprintf(
			/* translators: 1: Written out number of years, 2: Written out number of months, 3: Written out number of days, 4: Written out number of hours. */
			__( '%1$s, %2$s, %3$s, %4$s', 'remember-me-controls' ),
			$year_string,
			$month_string,
			$day_string,
			$hour_string
		);

		// Remove blank entries.
		return trim( str_replace( ', , ', ', ', $time_string ), ', ' );
	}

	/**
	 * Returns a potentially formatted representation of the login session duraction.
	 *
	 * @return string
	 */
	public function get_login_session_duration( $remembered = false ) {
		$default_duration = $remembered
			? self::get_default_remembered_login_duration()
			: self::get_default_login_duration();

		$duration_in_sec = $this->auth_cookie_expiration( $default_duration, 0, true );
		$duration = $duration_in_sec / HOUR_IN_SECONDS;

		if ( $this->get_max_login_duration() === $duration_in_sec ) {
			$max = floor( $duration_in_sec / YEAR_IN_SECONDS );
			$human_duration = sprintf(
				_n( '%d year', '%d years', $max, 'remember-me-controls' ),
				$max
			);
		} else {
			$human_duration = $this->humanize_seconds( $duration_in_sec );
		}

		$seconds = $duration;

		return $human_duration;
	}

	/**
	 * Display the current login session duration.
	 */
	public function display_current_login_duration() {
		$hours = $this->get_login_session_duration( true );

		if ( ! $hours ) {
			return;
		}

		echo <<<HTML
		<style>
		.c2c-remember-me-duration-banner {
			background-color: beige;
			margin:1rem 0;
			padding:1rem;
		}
		</style>
HTML;

		echo '<div class="c2c-remember-me-duration-banner">';
		printf(
			__( 'Currently, a remembered user login session will last up to <strong>%s</strong>.', 'remember-me-controls' ),
			$hours
		);
		echo '</div>' . "\n";
	}

	/**
	 * Configures help tabs content.
	 *
	 * @since 1.4
	 */
	public function help_tabs_content( $screen ) {
		$screen->add_help_tab( array(
			'id'      => $this->id_base . '-' . 'about',
			'title'   => __( 'About', 'remember-me-controls' ),
			'content' =>
				'<p>' . __( 'Take control of the "Remember Me" login feature for WordPress by customizing its behavior or disabling it altogether.', 'remember-me-controls' ) . "</p>\n" .
				'<p>' . __( 'For those unfamiliar, "Remember Me" is a checkbox present when logging into WordPress. If checked, by default WordPress will remember the login session for 14 days. If unchecked, the login session will be remembered for only 2 days. Once a login session expires, WordPress will require you to log in again if you wish to continue using the admin section of the site.', 'remember-me-controls' ) . "</p>\n" .
				'<p>' . __( 'This plugin provides three primary controls over the behavior of the "Remember Me" feature:', 'remember-me-controls' ) . "</p>\n" .
				'<ul class="c2c-plugin-list">' . "\n" .
				'<li><strong>' . __( 'Automatically check "Remember Me"', 'remember-me-controls' ) . '</strong><br>' . __( 'Have the "Remember Me" checkbox automatically checked when the login form is loaded (it isn\'t checked by default).', 'remember-me-controls' ) . "</li>\n" .
				'<li><strong>' . __( 'Customize the duration of the "Remember Me"', 'remember-me-controls' ) . '</strong><br>' . __( 'Customize how long WordPress will remember a login session when "Remember Me" is checked, either forever or a customizable number of hours.', 'remember-me-controls' ) . "</li>\n" .
				'<li><strong>' . __( 'Disable "Remember Me"', 'remember-me-controls' ) . '</strong><br>' . __( 'Completely disable the feature, preventing the checkbox from appearing and restricting all login sessions to two days.', 'remember-me-controls' ) . "</li>\n" .
				"</ul>\n",
		) );

		parent::help_tabs_content( $screen );
	}

	/**
	 * Outputs CSS within style tags.
	 */
	public function add_css() {
		$options = $this->get_options();

		if ( $options['disable_remember_me'] ) {
			echo '<style>.forgetmenot { display:none; }</style>' . "\n";
		}
	}

	/**
	 * Outputs JavaScript within script tags.
	 */
	public function add_js() {
		$options = $this->get_options();

		if ( $options['auto_remember_me'] && ! $options['disable_remember_me'] ) {
			echo <<<HTML
		<script>
			const rememberme_checkbox = document.getElementById('rememberme');
			if ( null !== rememberme_checkbox ) {
				rememberme_checkbox.checked = true;
			}
		</script>

HTML;
		}
	}

	/**
	 * Outputs admin JavaScript within script tags.
	 */
	public function add_admin_js() {
		// Bail if not on plugin settings page.
		if ( ! $this->is_plugin_admin_page() ) {
			return;
		}

		echo <<<HTML
		<script>
			document.addEventListener("DOMContentLoaded", function(){
				const remember_forever_checkbox = document.getElementById('remember_me_forever');
				const remember_me_duration      = document.getElementById('remember_me_duration');
				const never_remember_checkbox   = document.getElementById('disable_remember_me');
				const auto_remember_me_checkbox = document.getElementById('auto_remember_me');

				if ( null === remember_forever_checkbox ) {
					return;
				}

				function disableBasedOnRememberForever() {
					// Disable duration field if remember forever is checked.
					remember_me_duration.disabled = remember_forever_checkbox.checked;
				}

				disableBasedOnRememberForever();

				// Update disabling of fields based on remember forever checkbox.
				remember_forever_checkbox.addEventListener('click', function(){
					disableBasedOnRememberForever();
				});

				if ( null === never_remember_checkbox ) {
					return;
				}

				function disableBasedOnNeverRemember() {
					// Disable all other fields if never remember is checked.
					auto_remember_me_checkbox.disabled = never_remember_checkbox.checked;
					remember_forever_checkbox.disabled = never_remember_checkbox.checked;
					remember_me_duration.disabled = never_remember_checkbox.checked || remember_forever_checkbox.checked;
				}

				disableBasedOnNeverRemember();

				// Update disabling of fields based on never remember checkbox.
				never_remember_checkbox.addEventListener('click', function(){
					disableBasedOnNeverRemember();
				});
			});
		</script>

HTML;
	}

	/**
	 * Returns the maximum login duration.
	 *
	 * @return int Duration in seconds.
	 */
	public function get_max_login_duration() {
		return 100 * YEAR_IN_SECONDS; // 100 years
	}

	/**
	 * Returns the minimum login duration.
	 *
	 * @return int Duration in seconds.
	 */
	public function get_min_login_duration() {
		return HOUR_IN_SECONDS;
	}

	/**
	 * Returns the default login duration when not being remembered.
	 *
	 * @return int Duration in seconds.
	 */
	public function get_default_login_duration() {
		return 2 * DAY_IN_SECONDS;
	}

	/**
	 * Returns the default login duration when not being remembered.
	 *
	 * @return int Duration in seconds.
	 */
	public function get_default_remembered_login_duration() {
		return 14 * DAY_IN_SECONDS;
	}

	/**
	 * Possibly modifies the authorization cookie expiration duration based on
	 * plugin configuration.
	 *
	 * Minimum number of hours for the remember_me_duration is 2.
	 *
	 * @param int  $expiration The time interval, in seconds, before auth_cookie expiration.
	 * @param int  $user_id    User ID.
	 * @param bool $remember   If the remember_me_duration should be used instead of the default.
	 * @return int
	 */
	public function auth_cookie_expiration( $expiration, $user_id, $remember ) {
		$options = $this->get_options();
		$max_expiration = $this->get_max_login_duration();
		$min_expiration = $this->get_min_login_duration();
		$default_expiration = $this->get_default_login_duration();

		if ( $options['disable_remember_me'] ) { // Regardless of checkbutton state, if 'remember me' is disabled, use the non-remember-me duration
			$expiration = $default_expiration;
		} elseif ( $remember && $options['remember_me_forever'] ) {
			$expiration = $max_expiration;
		} elseif ( $remember && ( (int) $options['remember_me_duration'] >= 1 ) ) {
			$expiration = (int) $options['remember_me_duration'] * HOUR_IN_SECONDS;
		} elseif ( ! $expiration ) {
			$expiration = $default_expiration;
		}

		// In reality, we just need to prevent the user from specifying an expiration that would
		// exceed the year 9999. But a fixed max expiration is simpler and quite reasonable.
		$expiration = min( $expiration, $max_expiration );

		// Ensure an expiration of less than an hour is not used.
		$expiration = max( $expiration, $min_expiration );

		return $expiration;
	}

	/**
	 * Outputs a horizontal rule (or rather, the equivalent of such) after a particular option.
	 *
	 * @param string $opt The option name.
	 */
	public function maybe_add_hr( $opt ) {
		if ( 'remember_me_duration' === $opt ) {
			echo "</tr><tr><td colspan='2'><div class='hr'>&nbsp;</div></td>\n";
		}
	}

	/**
	 * Changes default login form default configuration.
	 *
	 * WordPress doesn't currently allow for the final config options to be
	 * overridden, so this may not have much practical applicability for
	 * guaranteeing conformance to plugin's settings by third-party login forms.
	 *
	 * @since 1.7
	 *
	 * @param array $defaults Default configuration options.
	 * @return array
	 */
	public function login_form_defaults( $defaults ) {
		$options = $this->get_options();

		if ( $options['auto_remember_me'] ) {
			$defaults['value_remember'] = true;
		}

		if ( $options['disable_remember_me'] ) {
			$defaults['remember']       = false;
			$defaults['value_remember'] = false;
		}

		return $defaults;
	}

	/**
	 * Modifies setting for widget provided by Sidebar Login plugin.
	 *
	 * @since 1.7
	 *
	 * @param array $args Form arguments for Sidebar Login widget.
	 * @return array
	 */
	public function compat_for_sidebar_login( $args ) {
		return $this->login_form_defaults( $args );
	}

	/**
	 * Overrides AJAX handling for Sidebar Login plugin to prevent the remember me
	 * value from being saved if the feature is disabled by this plugin.
	 *
	 * @since 1.7
	 */
	public function compat_for_sidebar_login_ajax_handler() {
		$options = $this->get_options();

		if ( $options['disable_remember_me'] ) {
			unset( $_POST['remember'] );
		}
	}

} // end class

add_action( 'plugins_loaded', array( 'c2c_RememberMeControls', 'get_instance' ) );

endif; // end if !class_exists()
