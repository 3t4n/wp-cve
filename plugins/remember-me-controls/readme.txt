=== Remember Me Controls ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: login, remember, remember me, cookie, session, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.9
Tested up to: 6.2
Stable tag: 2.0.1

Have "Remember Me" checked by default on the login page and configure how long a login is remembered. Or disable the feature altogether.


== Description ==

Take control of the "Remember Me" login feature for WordPress by having it enabled by default, customize how long users are remembered, or disable this built-in feature by default.

For those unfamiliar, "Remember Me" is a checkbox present when logging into WordPress. If checked, WordPress will remember the login session for 14 days. If unchecked, the login session will be remembered for only 2 days. Once a login session expires, WordPress will require you to log in again if you wish to continue using the admin section of the site.

This plugin provides three primary controls over the behavior of the "Remember Me" feature:

* Automatically check "Remember Me" : The ability to have the "Remember Me" checkbox automatically checked when the login form is loaded (it isn't checked by default).
* Customize the duration of the "Remember Me" : The ability to customize how long WordPress will remember a login session when "Remember Me" is checked, either forever or a customizable number of hours.
* Disable "Remember Me" : The ability to completely disable the feature, preventing the checkbox from appearing and restricting all login sessions to 2 days.

NOTE: WordPress remembers who you are based on cookies stored in your web browser. If you use a different web browser, clear your cookies, use a browser on a different machine, or uninstall/reinstall (and possibly even just restarting) your browser then you will have to log in again since WordPress will not be able to locate the cookies needed to identify you.

= Compatibility =

Other than the plugins listed below, compatibility has not been tested or attempted for any other third-party plugins that provide their own login widgets or login handling.

Special handling has been added to provide compatibility with the following plugins:

* [BuddyPress](https://wordpress.org/plugins/buddypress/) (in particular, its "Log in" widget)
* [Sidebar Login](https://wordpress.org/plugins/sidebar-login/)
* [Login Widget With Shortcode](https://wordpress.org/plugins/login-sidebar-widget/)

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/remember-me-controls/) | [Plugin Directory Page](https://wordpress.org/plugins/remember-me-controls/) | [GitHub](https://github.com/coffee2code/remember-me-controls/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Whether installing or updating, whether this plugin or any other, it is always advisable to back-up your data before starting
1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to "Settings" -> "Remember Me" and configure the settings


== Frequently Asked Questions ==

= How long does WordPress usually keep me logged in? =

By default, if you log in without "Remember Me" checked, WordPress keeps you logged in for up to 2 days. If you check "Remember Me" (without this plugin active), WordPress keeps you logged in for up to 14 days.

= Why am I being asked to log in again even though I've configured the plugin to remember me forever (or an otherwise long enough duration that hasn't been met yet)? =

WordPress remembers who you are based on cookies stored in your web browser. If you use a different web browser, clear your cookies, use a browser on a different machine, the site owner invalidates all existing login sessions, or you uninstall/reinstall (and possibly even just restart) your browser then you will have to log in again since WordPress will not be able to locate the cookies needed to identify you.

Also, if you changed the remember me duration but hadn't logged out after having done so, that particular login session would still be affected by the default (or previously configured) duration.

= How can I set the session duration to less than an hour? =

You can't (and probably shouldn't). With a session length of less than an hour you risk timing out users too quickly.

= Do changes to the remember me duration take effect for all current login sessions? =

No. The duration for which a login cookie is valid is defined within the cookie when it gets created (which is when you log in). Changing the setting for the remember me duration will only affect cookies created thereafter. You can log out and then log back in if you want the newly configured remember me duration to apply to your session. More precisely, the changes take effect for all *new* logins, which can happen after a preexisting login session expires, the user logs out, or the user's cookies are cleared in their browser (manually or automatically).

= Why are some of the plugin settings disabled? =

Certain settings being enabled may disable other settings that get superceded by the enabled setting. For instance, if the "Never remember?" setting is enabled, then all other settings are disabled since they wouldn't apply. The onscreen help text for each setting indicates what other settings are relatedly affected.

= How can I make the plugin configuration changes I've made take effect immediately? =

As explained in the previous FAQ entry, changes to the plugin's settings only take effect the next time a user logs in. Existing login sessions will abide by the remember me duration configured at the time they logged into their current session.

The login cookies for a user session can become invalidated by the visitor by logging out or clearing their cookies.

Here are some options to force all active login sessions to abide by the current login session duration:
* [Manually refresh](https://developer.wordpress.org/reference/functions/wp_salt/) your site's authentication keys and salts.
* Use WP-CLI to [regenerate salts](https://developer.wordpress.org/cli/commands/config/shuffle-salts/).
* Use the plugin [WPForce Logout](https://wordpress.org/plugins/wp-force-logout/) (to force session logouts) or [Salt Shaker](https://wordpress.org/plugins/salt-shaker/) (to regenerate salts). _Note: Plugins are merely suggestions and not necessarily recommendations._

= What plugins are this plugin compatible with? =

Special handling has been added to provide compatibility with the following plugins:

* [BuddyPress](https://wordpress.org/plugins/buddypress/) (in particular, its "Log in" widget)
* [Sidebar Login](https://wordpress.org/plugins/sidebar-login/)
* [Login Widget With Shortcode](https://wordpress.org/plugins/login-sidebar-widget/)

= Is this plugin GDPR-compliant? =

Yes. This plugin does not collect, store, or disseminate any information from any users or site visitors.

= Does this plugin include unit tests? =

Yes.


== Screenshots ==

1. A screenshot of the plugin's admin settings page.
2. A screenshot of the login form with "Remember Me" checked by default
3. A screenshot of the login form with "Remember Me" removed


== Changelog ==

= 2.0.1 (2023-06-19) =

Highlights:

This is minor bugfix release fixes the plugin settings page's info banner that reports the current remembered session duration. When the WordPress default remembered session duration (of "14 days") is applicable, that value is now shown instead of stating an incorrect value ("2 days"). Actual session durations and plugin functionality were not affected.

Details:

- Fix: Fix info banner reporting the wrong remembered duration (of "2 days") when the default WordPress remembered duration applies (which is "14 days")
- New: Add `get_default_remembered_login_duration()`
- Change: Add optional argument to `get_login_session_duration()` to indicate if the default duration should be the default remembered duration or not
- Change: Updated screenshot

= 2.0 (2023-06-14) =

Highlights:

This is a recommended and notable release that improves the labeling, help text, data display, and functionality of the plugin's settings page; restructures the unit tests; verifies compatibility through WordPress 6.2+; and other minor behind-the-scenes tweaks.

Details:

* New: Add a notice banner to settings page to provide human-friendly summary of current login session duration
* New: Add getters for the acceptable maximum, minimum, and default non-remembered login duration values
* Change: Enforce a minimum of one hour for login session duration
* Change: Return default login session duration (2 days) if for some reason a 0 duration is encountered
* Change: Improve plugin's settings page
    * Change: Dynamically disable settings input fields if their functionality is disabled by another setting's value
    * Change: Display notable helptext for settings as inline notices
    * Change: Clarify that disabling the "Remember Me" feature will causes sessions to last 2 days, not 1
    * Change: Use a number field as the duration input field
    * Change: Reword labels and help text for clarity and brevity
    * Change: Add additional help text to clarify how settings are related
    * Change: Improve style and layout of help text
    * Change: Output newlines after block-level tags in settings page
* Change: Omit `type` attribute to `script` and `style` tags
* Change: Improve formatting of text in Help panel
* Change: Add FAQ item to address how to make login session duration changes take effect immediately
* Change: Update plugin framework to 065
    * 065:
    * New: Add support for 'inline_help' setting configuration option
    * New: Add support for 'raw_help' setting configuration option
    * New: Add support for use of lists within settings descriptions
    * Change: Add an 'id' attribute to settings form
    * Change: Add styles for disabled input text fields and inline setting help notices
    * Change: Support 'number' input by assigning 'small-text' class
    * Change: Tweak styling for settings page footer
    * Change: Note compatibility through WP 6.2+
    * Change: Update copyright date (2023)
    * 064:
    * New: For checkbox settings, support a 'more_help' config option for defining help text to appear below checkbox and its label
    * Fix: Fix URL for plugin listing donate link
    * Change: Store donation URL as object variable
    * Change: Update strings used for settings page donation link
    * 063:
    * Fix: Simplify settings initialization to prevent conflicts with other plugins
    * Change: Remove ability to detect plugin settings page before current screen is set, as it is no longer needed
    * Change: Enqueue thickbox during `'admin_enqueue_scripts'` action instead of during `'init'`
    * Change: Use `is_plugin_admin_page()` in `help_tabs()` instead of reproducing its functionality
    * Change: Trigger a debugging warning if `is_plugin_admin_page()` is used before `'admin_init'` action is fired
    * 062:
    * Change: Update `is_plugin_admin_page()` to use `get_current_screen()` when available
    * Change: Actually prevent object cloning and unserialization by throwing an error
    * Change: Check that there is a current screen before attempting to access its property
    * Change: Remove 'type' attribute from `style` tag
    * Change: Incorporate commonly defined styling for inline_textarea
    * 061:
    * Fix bug preventing settings from getting saved
    * 060:
    * Rename class from `c2c_{PluginName}_Plugin_051` to `c2c_Plugin_060`
    * Move string translation handling into inheriting class making the plugin framework code plugin-agnostic
        * Add abstract function `get_c2c_string()` as a getter for translated strings
        * Replace all existing string usage with calls to `get_c2c_string()`
    * Handle WordPress's deprecation of the use of the term "whitelist"
        * Change: Rename `whitelist_options()` to `allowed_options()`
        * Change: Use `add_allowed_options()` instead of deprecated `add_option_whitelist()` for WP 5.5+
        * Change: Hook `allowed_options` filter instead of deprecated `whitelist_options` for WP 5.5+
    * New: Add initial unit tests (currently just covering `is_wp_version_cmp()` and `get_c2c_string()`)
    * Add `is_wp_version_cmp()` as a utility to compare current WP version against a given WP version
    * Refactor `contextual_help()` to be easier to read, and correct function docblocks
    * Don't translate urlencoded donation email body text
    * Add inline comments for translators to clarify purpose of placeholders
    * Change PHP package name (make it singular)
    * Tweak inline function description
    * Note compatibility through WP 5.7+
    * Update copyright date (2021)
* Change: Move translation of all parent class strings into main plugin file
* Change: Note compatibility through WP 6.2+
* Change: Update copyright date (2023)
* Change: Tweak installation instruction
* Unit tests:
    * New: Add unit tests specific to plugin framework
    * Change: Restructure unit test directories
        * Change: Move `bin/` into `tests/`
        * Change: Move `tests/bootstrap.php` into `tests/phpunit/`
        * Change: Move `tests/test-*.php` into `tests/phpunit/tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

= 1.9.1 (2021-02-13) =
* Fix: Add missing textdomain. Props @kittmedia.
* Change: Enhance a FAQ answer to make clear that an existing login session will not be affected by an update to the remember me duration (must log in again)
* Change: Note compatibility through WP 5.6+
* Change: Update copyright date (2021)

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/remember-me-controls/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.0.1 =
Minor bugfix: fixed the plugin setting's page info banner from reporting the wrong remembered session duration (of "2 days") when the default WordPress remembered session duration applied (of "14 days").

= 2.0 =
Recommended update: improved labeling, help text, and functionality of settings page; updated plugin framework to version 065; noted compatibility through WP 6.2+; updated copyright date (2023).

= 1.9.1 =
Trivial update: added missing translation textdomain, noted compatibility through WP 5.6+, and updated copyright date (2021)

= 1.9 =
Minor update: allowed commas in numerical input, improved documentation, added HTML5 compliance when supported by the theme, updated plugin framework, added TODO.md file, updated a few URLs to be HTTPS, expanded unit testing, updated compatibility to be WP 4.9 through 5.4+, and minor behind-the-scenes tweaks.

= 1.8.1 =
Trivial update: noted compatibility through WP 5.3+ and updated copyright date (2020)

= 1.8 =
Minor update: tweaked plugin initialization, updated plugin framework to version 049, noted compatibility through WP 5.2+, created CHANGELOG.md to store historical changelog outside of readme.txt, and updated copyright date (2019)

= 1.7 =
Recommended update: added support for BuddyPress Login widget, Sidebar Login plugin, and Login Widget With Shortcode plugin; updated plugin framework to version 047; compatibility is now with WP 4.7-4.9+; updated copyright date (2018).

= 1.6 =
Minor update: improved support for localization; verified compatibility through WP 4.4; removed compatibility with WP earlier than 4.1; updated copyright date (2016)

= 1.5 =
Minor update: add unit tests; updated plugin framework to 039; noted compatibility through WP 4.1+; updated copyright date (2015); added plugin icon

= 1.4 =
Recommended update: updated plugin framework; compatibility now WP 3.6-3.8+

= 1.3 =
Minor update. Highlights: updated plugin framework; noted compatibility through WP 3.5+; and more.

= 1.2 =
Recommended update. Highlights: added new setting to remember logins forever; misc improvements and minor bug fixes; updated plugin framework; compatibility is now for WP 3.1 - 3.3+.

= 1.1 =
Recommended upgrade! Fixed bug relating to value conversion from hours to seconds; fix for proper activation; noted compatibility through WP 3.2; dropped compatibility with versions of WP 3.0; deprecated use of global updated plugin framework; and more.

= 1.0.1 =
Recommended bugfix release.

= 1.0 =
Initial public release!
