<?php
/*
* Security Ninja
* Test functions
*/

namespace WPSecurityNinja\Plugin;

// this is an include only WP file
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Wf_Sn_Tests extends WF_SN {



	public static $security_tests;

	public static function return_security_tests() {
		return array(
			'ver_check'                 => array(
				'title'   => __( 'Check if WordPress core is up to date.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'You are using the latest version of WordPress.', 'security-ninja' ),
				'msg_bad' => __( 'You are not using the latest version of WordPress.', 'security-ninja' ),
			),

			'core_updates_check'        => array(
				'title'   => __( 'Check if automatic WordPress core updates are enabled.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'Core updates are configured optimally.', 'security-ninja' ),
				'msg_bad' => __( 'Automatic core updates are not configured optimally.', 'security-ninja' ),
			),

			// Plugins

			'plugins_ver_check'         => array(
				'title'   => __( 'Check if plugins are up to date.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'All plugins are up to date.', 'security-ninja' ),
				// translators: Number of plugins with new versions available
				'msg_bad' => __( 'At least %s plugins have new versions available and have to be updated.', 'security-ninja' ),
			),

			'deactivated_plugins'       => array(
				'title'   => __( 'Check if there are deactivated plugins.', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'There are no deactivated plugins.', 'security-ninja' ),
				// translators: Number of deactivated plugins
				'msg_bad' => __( 'There are %s deactivated plugins.', 'security-ninja' ),
			),

			'old_plugins'               => array(
				'title'       => __( 'Check if active plugins have been updated in the last 12 months.', 'security-ninja' ),
				'score'       => 3,
				'msg_ok'      => __( 'All active plugins have been updated in the last 12 months.', 'security-ninja' ),
				'msg_warning' => __( 'We were not able to verify the last update date for any of your active plugins.', 'security-ninja' ),
				// translators: Number of plugins not updated past 12 months
				'msg_bad'     => __( 'The following plugin(s) have not been updated in the last 12 months: %s.', 'security-ninja' ),
			),

			'incompatible_plugins'      => array(
				'title'       => __( 'Check if active plugins are compatible with your version of WP.', 'security-ninja' ),
				'score'       => 3,
				'msg_ok'      => __( 'All active plugins are compatible with your version of WordPress.', 'security-ninja' ),
				'msg_warning' => __( 'We were not able to verify the compatibility for any of your active plugins.', 'security-ninja' ),
				// translators: Number of plugins untested with current version of WordPress
				'msg_bad'     => __( 'The following plugin(s) have not been tested with your version of WP and could be incompatible: %s.', 'security-ninja' ),
			),

			// themes

			'themes_ver_check'          => array(
				'title'   => __( 'Check if themes are up to date.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'All themes are up to date.', 'security-ninja' ),
				// translators: How many installed themes are outdated
				'msg_bad' => __( '%s theme(s) are outdated.', 'security-ninja' ),
			),

			'deactivated_themes'        => array(
				'title'   => __( 'Check if there are unnecessary themes.', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'There are no unused themes.', 'security-ninja' ),
				// translators: How many deactivated themes
				'msg_bad' => __( 'There are %s unnecessary themes.', 'security-ninja' ),
			),

			// WP header
			'wp_header_meta'            => array(
				'title'       => __( 'Check if full WordPress version info is revealed in page\'s meta data.', 'security-ninja' ),
				'score'       => 3,
				'msg_ok'      => __( 'Your site does not reveal full WordPress version info.', 'security-ninja' ),
				'msg_warning' => __( 'Site homepage could not be fetched.', 'security-ninja' ),
				'msg_bad'     => __( 'Your site reveals full WordPress version info in meta tags.', 'security-ninja' ),
			),

			'wlw_meta'                  => array(
				'title'       => __( 'Check if Windows Live Writer link is present in the header data.', 'security-ninja' ),
				'score'       => 1,
				'msg_ok'      => __( 'WLW link is not present in the header.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to perform test.', 'security-ninja' ),
				'msg_bad'     => __( 'WLW link is present in the header.', 'security-ninja' ),
			),

			// files check
			'readme_check'              => array(
				'title'       => __( 'Check if readme.html file is accessible via HTTP on the default location.', 'security-ninja' ),
				'score'       => 3,
				'msg_ok'      => __( 'readme.html is not accessible at the default location.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to determine status of readme.html.', 'security-ninja' ),
				'msg_bad'     => __( 'readme.html is accessible via HTTP on the default location.', 'security-ninja' ),
			),

			'license_check'             => array(
				'title'       => __( 'Check if license.txt file is accessible via HTTP on the default location.', 'security-ninja' ),
				'score'       => 3,
				'msg_ok'      => __( 'license.txt is not accessible at the default location.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to determine status of license.txt.', 'security-ninja' ),
				'msg_bad'     => __( 'license.txt is accessible via HTTP on the default location.', 'security-ninja' ),
			),

			'install_file_check'        => array(
				'title'       => __( 'Check if install.php file is accessible via HTTP on the default location.', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'install.php is not accessible on the default location.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to determine status of install.php file.', 'security-ninja' ),
				'msg_bad'     => __( 'install.php is accessible via HTTP on the default location.', 'security-ninja' ),
			),

			'upgrade_file_check'        => array(
				'title'       => __( 'Check if upgrade.php file is accessible via HTTP on the default location.', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'upgrade.php is not accessible on the default location.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to determine status of upgrade.php file.', 'security-ninja' ),
				'msg_bad'     => __( 'upgrade.php is accessible via HTTP on the default location.', 'security-ninja' ),
			),

			// server related
			'php_ver'                   => array(
				'title'       => __( 'Check the PHP version.', 'security-ninja' ),
				'score'       => 4,
				// translators: Which PHP version is in use - All is OK
				'msg_ok'      => __( 'You are using PHP version %s.', 'security-ninja' ),
				// translators: Which PHP version is in use - Warning
				'msg_warning' => __( 'You are using PHP version %s which meets the minimum requirements set by WP, but it is recommended upgrading to PHP 7.', 'security-ninja' ),
				// translators: Which PHP version is in use - Fail message
				'msg_bad'     => __( 'You are using PHP version %s which is obsolete. Please upgrade to PHP 7.', 'security-ninja' ),
			),

			// database
			'mysql_ver'                 => array(
				'title'       => __( 'Check the MySQL version.', 'security-ninja' ),
				'score'       => 4,
				// translators:
				'msg_ok'      => __( 'You are using MySQL version %s.', 'security-ninja' ),
				// translators:
				'msg_warning' => __( 'You are using MySQL version %s which meets the minimum requirements set by WP, but it is recommended upgrading to at least v5.6.', 'security-ninja' ),
				// translators:
				'msg_bad'     => __( 'You are using MySQL version %s which is obsolete. Please upgrade to at least v5.6.', 'security-ninja' ),
			),

			'db_table_prefix_check'     => array(
				'title'   => __( 'Check if database table prefix is the default one (wp_).', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'Database table prefix is not default.', 'security-ninja' ),
				'msg_bad' => __( 'Database table prefix is default.', 'security-ninja' ),
			),

			// server headers
			'php_headers'               => array(
				'title'   => __( 'Check if server response headers contain detailed PHP version info.', 'security-ninja' ),
				'score'   => 2,
				'msg_ok'  => __( 'Headers does not contain detailed PHP version info.', 'security-ninja' ),
				'msg_bad' => __( 'Server response headers contain detailed PHP version info.', 'security-ninja' ),
			),

			'expose_php_check'          => array(
				'title'   => __( 'Check if expose_php PHP directive is turned off.', 'security-ninja' ),
				'score'   => 1,
				'msg_ok'  => __( 'expose_php PHP directive is turned off.', 'security-ninja' ),
				'msg_bad' => __( 'expose_php PHP directive is turned on.', 'security-ninja' ),
			),

			// users
			'user_exists'               => array(
				'title'   => __( 'Check if user with username "admin" exists.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'User "admin" does not exist.', 'security-ninja' ),
				'msg_bad' => __( 'User "admin" exists.', 'security-ninja' ),
			),

			'anyone_can_register'       => array(
				'title'   => __( 'Check if "anyone can register" option is enabled.', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( '"Anyone can register" option is disabled.', 'security-ninja' ),
				'msg_bad' => __( '"Anyone can register" option is enabled.', 'security-ninja' ),
			),

			'id1_user_check'            => array(
				'title'   => __( 'Check if user with ID "1" exists.', 'security-ninja' ),
				'score'   => 1,
				'msg_ok'  => __( 'Such user does not exist.', 'security-ninja' ),
				// translators:
				'msg_bad' => __( 'User with ID "1" exists; username: %s.', 'security-ninja' ),
			),

			// login
			'check_failed_login_info'   => array(
				'title'   => __( 'Check for display of unnecessary information on failed login attempts.', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'No unnecessary info is shown on failed login attempts.', 'security-ninja' ),
				'msg_bad' => __( 'Unnecessary information is displayed on failed login attempts.', 'security-ninja' ),
			),

			// wp-config file checks
			'config_chmod'              => array(
				'title'       => __( 'Check if wp-config.php file has the right permissions (chmod) set.', 'security-ninja' ),
				'score'       => 5,
				// translators:
				'msg_ok'      => __( 'WordPress config file has the right chmod set. (%s)', 'security-ninja' ),
				'msg_warning' => __( 'Unable to read chmod of wp-config.php.', 'security-ninja' ),
				// translators:
				'msg_bad'     => __( 'Current wp-config.php chmod (%s) is not ideal and other users on the server can access the file.', 'security-ninja' ),
			),

			'config_location'           => array(
				'title'   => __( 'Check if wp-config.php is present on the default location.', 'security-ninja' ),
				'score'   => 2,
				'msg_ok'  => __( 'wp-config.php is not present on the default location.', 'security-ninja' ),
				'msg_bad' => __( 'wp-config.php is present on the default location.', 'security-ninja' ),
			),

			'db_password_check'         => array(
				'title'   => __( 'Check the strength of WordPress database password.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'Database password is strong enough.', 'security-ninja' ),
				// translators:
				'msg_bad' => __( 'Database password is weak (%s).', 'security-ninja' ),
			),

			'salt_keys_check'           => array(
				'title'   => __( 'Check if security keys and salts have proper values.', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'All keys have proper values set.', 'security-ninja' ),
				// translators:
				'msg_bad' => __( 'Following keys do not have proper values set: %s.', 'security-ninja' ),
			),

			'salt_keys_age_check'       => array(
				'title'       => __( 'Check the age of security keys and salts.', 'security-ninja' ),
				'score'       => 1,
				'msg_ok'      => __( 'Keys and salts have been changed in the last 3 months.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to read wp-config.php.', 'security-ninja' ),
				'msg_bad'     => __( 'Keys and salts have not been changed for more than 3 months.', 'security-ninja' ),
			),

			'debug_check'               => array(
				'title'   => __( 'Check if general debug mode is enabled.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'General debug mode is disabled.', 'security-ninja' ),
				'msg_bad' => __( 'General debug mode is enabled.', 'security-ninja' ),
			),

			'debug_log_file_check'      => array(
				'title'       => __( 'Check if WordPress debug.log file exists.', 'security-ninja' ),
				'score'       => 4,
				'msg_ok'      => __( 'Great, the debug.log file does not exist or you are blocking access to it.', 'security-ninja' ),
				'msg_warning' => __( 'We were not able to check for the debug.log file.', 'security-ninja' ),
				'msg_bad'     => __( 'The debug.log file exists - please delete or block access.', 'security-ninja' ),
			),

			'db_debug_check'            => array(
				'title'   => __( 'Check if database debug mode is enabled.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Database debug mode is disabled.', 'security-ninja' ),
				'msg_bad' => __( 'Database debug mode is enabled.', 'security-ninja' ),
			),

			'script_debug_check'        => array(
				'title'   => __( 'Check if JavaScript debug mode is enabled.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'JavaScript debug mode is disabled.', 'security-ninja' ),
				'msg_bad' => __( 'JavaScript debug mode is enabled.', 'security-ninja' ),
			),

			'display_errors_check'      => array(
				'title'   => __( 'Check if display_errors PHP directive is turned off.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'display_errors PHP directive is turned off.', 'security-ninja' ),
				'msg_bad' => __( 'display_errors PHP directive is turned on.', 'security-ninja' ),
			),

			'blog_site_url_check'       => array(
				'title'   => __( 'Check if WordPress installation address is the same as the site address.', 'security-ninja' ),
				'score'   => 2,
				'msg_ok'  => __( 'WordPress installation address is different from the site address.', 'security-ninja' ),
				'msg_bad' => __( 'WordPress installation address is the same as the site address.', 'security-ninja' ),
			),

			// server settings and PHP checks
			'register_globals_check'    => array(
				'title'   => __( 'Check if register_globals PHP directive is turned off.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'register_globals PHP directive is turned off.', 'security-ninja' ),
				'msg_bad' => __( 'register_globals PHP directive is turned on.', 'security-ninja' ),
			),

			'safe_mode_check'           => array(
				'title'   => __( 'Check if PHP safe mode is disabled.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'Safe mode is disabled.', 'security-ninja' ),
				'msg_bad' => __( 'Safe mode is enabled.', 'security-ninja' ),
			),

			'allow_url_include_check'   => array(
				'title'   => __( 'Check if allow_url_include PHP directive is turned off.', 'security-ninja' ),
				'score'   => 5,
				'msg_ok'  => __( 'allow_url_include PHP directive is turned off.', 'security-ninja' ),
				'msg_bad' => __( 'allow_url_include PHP directive is turned on.', 'security-ninja' ),
			),

			// WordPress features
			'file_editor'               => array(
				'title'   => __( 'Check if plugins/themes file editor is enabled.', 'security-ninja' ),
				'score'   => 2,
				'msg_ok'  => __( 'File editor is disabled.', 'security-ninja' ),
				'msg_bad' => __( 'File editor is enabled.', 'security-ninja' ),
			),

			'uploads_browsable'         => array(
				'title'       => __( 'Check if uploads folder is browsable by browsers.', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'Uploads folder is not browsable.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to determine status of uploads folder.', 'security-ninja' ),
				// translators:
				'msg_bad'     => __( '<a href="%s" target="_blank">Uploads folder</a> is browsable.', 'security-ninja' ),
			),

			'application_passwords'     => array(
				'title'       => __( 'Check if Application Passwords are enabled.', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'The Application Passwords feature is disabled.', 'security-ninja' ),
				'msg_warning' => __( 'The Application Passwords feature is enabled.', 'security-ninja' ),
				'msg_bad'     => '',
			),

			'mysql_external'            => array(
				'title'       => __( 'Check if MySQL server is connectable from outside with the WP user.', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'No, you can only connect to the MySQL from localhost.', 'security-ninja' ),
				// translators: Not conclusive for database user
				'msg_warning' => __( 'Test results are not conclusive for MySQL user %s.', 'security-ninja' ),
				'msg_bad'     => __( 'You can connect to the MySQL server from any host.', 'security-ninja' ),
			),

			'rpc_meta'                  => array(
				'title'       => __( 'Check if EditURI (XML-RPC) link is present in the header data.', 'security-ninja' ),
				'score'       => 1,
				'msg_ok'      => __( 'The EditURI (XML-RPC) link is not present in the header.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to perform test.', 'security-ninja' ),
				'msg_bad'     => __( 'EditURI link is present in the header.', 'security-ninja' ),
			),

			'tim_thumb'                 => array(
				'title'       => __( 'Check if Timthumb script is used in the active theme.', 'security-ninja' ),
				'score'       => 5,
				// translators: The Timthumb script was not found in the theme
				'msg_ok'      => __( 'Timthumb was not found in %s.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to perform test. Cannot read the PHP files in the theme', 'security-ninja' ),
				// translators: The Timthumb script WAS found in the theme
				'msg_bad'     => __( 'Timthumb was found in %s.', 'security-ninja' ),
			),

			'shellshock_6271'           => array(
				'title'       => __( 'Check if the server is vulnerable to the Shellshock bug #6271.', 'security-ninja' ),
				'score'       => 4,
				'msg_ok'      => __( 'Server is not vulnerable.', 'security-ninja' ),
				'msg_warning' => __( 'You are running WordPress on a server which is not affected by the Shellshock bug or PHP proc_open() is disabled on the server.', 'security-ninja' ),
				'msg_bad'     => __( 'Server is vulnerable to Shellshock!', 'security-ninja' ),
			),

			'shellshock_7169'           => array(
				'title'       => __( 'Check if the server is vulnerable to the Shellshock bug #7169.', 'security-ninja' ),
				'score'       => 4,
				'msg_ok'      => __( 'Server is not vulnerable.', 'security-ninja' ),
				'msg_warning' => __( 'You are running WordPress on a server which is not affected by the Shellshock bug or PHP proc_open() is disabled on the server.', 'security-ninja' ),
				'msg_bad'     => __( 'Server is vulnerable to Shellshock!', 'security-ninja' ),
			),

			'admin_ssl'                 => array(
				'title'       => __( 'Check if admin interface is delivered via SSL', 'security-ninja' ),
				'score'       => 3,
				'msg_ok'      => __( 'Connection is secured via SSL.', 'security-ninja' ),
				'msg_warning' => __( 'Unable to determine if the admin interface is secured via SSL.', 'security-ninja' ),
				'msg_bad'     => __( 'Connection is not secured via SSL.', 'security-ninja' ),
			),

			'mysql_permissions'         => array(
				'title'       => __( 'Check if MySQL account used by WordPress has too many permissions', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'Only those permissions that are needed are granted.', 'security-ninja' ),
				'msg_warning' => __( 'Things are most probably fine but we would still advise you to manually check priviledges.', 'security-ninja' ),
				'msg_bad'     => __( 'Account has far too many unnecessary permissions granted.', 'security-ninja' ),
			),

			'usernames_enumeration'     => array(
				'title'   => __( 'Check if the list of usernames can be fetched by looping through user IDs', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'Usernames cannot be fetched via user IDs', 'security-ninja' ),
				'msg_bad' => __( 'List of all usernames can be fetched by looping through user IDs via siteurl.com/?author={id}', 'security-ninja' ),
			),
			'rest_api_links'            => array(
				'title'       => __( 'Check if the REST API links are shown in code', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'Great, REST API links are not shown in the HTML code.', 'security-ninja' ),
				'msg_warning' => __( 'The REST API links are visible in the source code.', 'security-ninja' ),
				'msg_bad'     => __( 'The REST API links are visible in the source code.', 'security-ninja' ),
			),

			'x_content_type_options'    => array(
				'title'   => __( 'Check if server response headers contain X-Content-Type-Options.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Great, the header contains X-Content-Type-Options.', 'security-ninja' ),
				'msg_bad' => __( 'The server does not have the "X-Content-Type-Options" feature enabled.', 'security-ninja' ),
			),

			'x_frame_options'           => array(
				'title'   => __( 'Check if server response headers contain X-Frame-Options.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Great, the header contains X-Frame-Options.', 'security-ninja' ),
				'msg_bad' => __( 'The server does not have the "X-Frame-Options" feature set.', 'security-ninja' ),
			),

			'xxss_protection'           => array(
				'title'   => __( 'Check if server response headers contain X-XSS-Protection.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Great, the header contains X-XSS-Protection.', 'security-ninja' ),
				'msg_bad' => __( 'The server does not have the "X-XSS-Protection" feature enabled.', 'security-ninja' ),
			),

			'strict_transport_security' => array(
				'title'   => __( 'Check if server response headers contain Strict-Transport-Security.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Great, the header contains Strict-Transport-Security.', 'security-ninja' ),
				'msg_bad' => __( 'The server does not have the "Strict-Transport-Security" feature enabled.', 'security-ninja' ),
			),

			'referrer_policy'           => array(
				'title'   => __( 'Check if server response headers contain Referrer-Policy.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Great, the header contains Referrer-Policy.', 'security-ninja' ),
				'msg_bad' => __( 'Your website does not have the "Referrer-Policy" header enabled.', 'security-ninja' ),
			),

			'feature_policy'            => array(
				'title'   => __( 'Check if server response headers contain Permissions-Policy.', 'security-ninja' ),
				'score'   => 4,
				'msg_ok'  => __( 'Great, the header contains Permissions-Policy.', 'security-ninja' ),
				'msg_bad' => __( 'Your website does not have the "Permissions-Policy" header enabled.', 'security-ninja' ),
			),

			'content_security_policy'   => array(
				'title'       => __( 'Check if server response headers contain Content-Security-Policy.', 'security-ninja' ),
				'score'       => 4,
				'msg_ok'      => __( 'Great, the header contains Content-Security-Policy.', 'security-ninja' ),
				'msg_bad'     => __( 'The server does not have the "Content-Security-Policy" feature enabled.', 'security-ninja' ),
				'msg_warning' => __( 'The report only mode has been set. Remember to turn off "Report Only" when you are finished.', 'security-ninja' ),
			),

			'rest_api_enabled'          => array(
				'title'       => __( 'Check if the REST API is enabled.', 'security-ninja' ),
				'score'       => 2,
				'msg_ok'      => __( 'The REST API is blocked.', 'security-ninja' ),
				'msg_bad'     => __( 'The REST API is enabled publicly.', 'security-ninja' ),
				'msg_warning' => __( 'We could not test if the REST API is enabled.', 'security-ninja' ),
			),

			'dangerous_files'           => array(
				'title'   => __( 'Check for unwanted files in your root folder you should remove.', 'security-ninja' ),
				'score'   => 3,
				'msg_ok'  => __( 'Great! No unwanted files were found!', 'security-ninja' ),
				'msg_bad' => __( 'Unwanted files found in your website root folder, you should delete these files.', 'security-ninja' ),
			),
		);
	}

	/**
	 * rest_api_enabled.
	 *
	 * @author  Lars Koudal
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0    Monday, January 25th, 2021.
	 * @version v1.0.1    Wednesday, December 20th, 2023.
	 * @access  public static
	 * @return  void
	 */
	public static function rest_api_enabled() {
		$return = array();

		$url = get_rest_url();

		// Check if the REST API URL was retrieved successfully
		if ( ! $url ) {
			return array(
				'status'  => 10,
				'details' => 'REST API URL not found.',
			);
		}

		$response = wp_remote_get( $url, array( 'redirection' => 0 ) );

		// Check if the request was successful
		if ( is_wp_error( $response ) ) {
			// If there's an error, set status to 10 with error details
			return array(
				'status'  => 10,
				'details' => $response->get_error_message(),
			);
		}

		// Check the response code
		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $response_code ) {
			// Status code 200 means the API is accessible
			return array(
				'status'  => 5,
				'details' => 'REST API is accessible.',
			);
		} else {
			// Any other status code means the API is not accessible
			return array(
				'status'  => 10,
				'details' => 'REST API is not accessible. Response Code: ' . $response_code,
			);
		}
	}


	/**
	 * Scan for dangerous files in root
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function dangerous_files() {
		// @todo - fix case Insensitive files - glob no fun
		// @todo - maybe run in subfolders also? worth it?

		$return = array();

		$dangerous_files = array(
			'wp-config.php.old'      => 'Common name for config file backup - can contain critical information',
			'wp-config.php_bak'      => 'Common name for config file backup - can contain critical information',
			'wp-config.php~'         => 'Common name for config file backup - can contain critical information',
			'wp-config.php-'         => 'Common name for config file backup - can contain critical information',
			'wp-config.php--'        => 'Common name for config file backup - can contain critical information',
			'wp-config.php---'       => 'Common name for config file backup - can contain critical information',
			'wp-config.php.bkp'      => 'Common name for config file backup - can contain critical information',
			'wp-config.php_revision' => 'Common name for config file backup - can contain critical information',
			'php_errorlog'           => 'Can contain server details or errors that can be exploited.',
			'php_mail.log'           => 'Can contain user details or errors that can be exploited.',
			'.htaccess.sg'           => '.htaccess backup files on SiteGround - Can show server details or configurations that should not be public.',
			'.htaccess_swift_backup' => '.htaccess backup file by Swift Performance - Can show server details or configurations that should not be public.',
			'*.sql'                  => '.sql files should not be kept on your server - they may contain sensitive data.',
			'phpinfo.php'            => 'Displays all details about PHP on your website, should only exist briefly during development.',
			'info.php'               => 'Should only exist briefly during development and not on a live site.',
			'test.php'               => 'Should only exist briefly during development and not on a live site.',
			'*.bak'                  => 'Copies of old files could contain important info about your server.',
		);

		$return['status']  = 10;
		$return['details'] = '<dl>';

		foreach ( $dangerous_files as $key => $explanation ) {
			// If its a wildcard search
			if ( false !== strpos( $key, '*.' ) ) {
				$files = glob( ABSPATH . $key );
				if ( ( is_array( $files ) ) && ( count( $files ) > 0 ) ) {
					foreach ( $files as $f ) {
						$display_name       = str_replace( ABSPATH, '', $f );
						$return['details'] .= '<dt><strong>' . $display_name . '</strong></dt><dd>' . $explanation . '</dd>';
					}
					$return['status'] = 0;
				}
			} else {
				$check = file_exists( ABSPATH . $key );
				if ( $check ) {
					$return['details'] .= '<dt><strong>' . $key . '</strong></dt><dd>' . $explanation . '</dd>';
					$return['status']   = 0;
				}
			}
		}

		$return['details'] .= '</dl>';

		return $return;
	}

	/**
	 * Checks if the website is SSL or not
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Thursday, April 8th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function check_server_ssl() {
		$is_https =
		$_SERVER['HTTPS']
		?? $_SERVER['REQUEST_SCHEME']
		?? $_SERVER['HTTP_X_FORWARDED_PROTO']
		?? null;

		$is_https =
		$is_https && (
		strcasecmp( 'on', $is_https ) === 0
		|| strcasecmp( 'https', $is_https ) === 0
		);
		return $is_https;
	}


	/**
	 * Checks if admin is using SSL
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function admin_ssl() {
		$return = array();

		if ( false === stripos( get_admin_url(), 'https' ) ) {
			$return['status']  = 0;
			$return['details'] = 'You should set your Settings -> General URLs to start with https://';
		} else {
			$return['status']  = 10;
			$return['details'] = 'Admin URLS set to start with https';
		}

		$force_ssl_admin = force_ssl_admin();
		if ( $force_ssl_admin ) {
			$return['status']  = 10;
			$return['details'] = 'Great, admin pages are secured by SSL.';
		}

		return $return;
	}


	/**
	 * check if Timthumb is used
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function tim_thumb() {
		$return = array();
		$theme  = wp_get_theme();
		$theme  = $theme->Name . ' v' . $theme->Version;
		$tmp    = self::tim_thumb_scan( get_theme_root() );

		$return['status'] = $tmp;
		$return['msg']    = $theme;

		return $return;
	}




	/**
	 * scan all PHP files and look for timtumb script
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @version v1.0.1  Saturday, November 18th, 2023.
	 * @access  public static
	 * @param   mixed $path
	 * @return  integer
	 */
	public static function tim_thumb_scan( $path ) {
		global $wp_filesystem;

		// Setup the WordPress filesystem, if not already set up
		if ( empty( $wp_filesystem ) ) {
			include_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		// Array to hold all the PHP files we want to scan
		$php_files = array();

		// Scan the directory for PHP files
		$files = $wp_filesystem->dirlist( $path );
		foreach ( $files as $file => $fileinfo ) {
			if ( 'php' === pathinfo( $file, PATHINFO_EXTENSION ) ) {
				$php_files[] = trailingslashit( $path ) . $file;
			}

			if ( 'd' === $fileinfo['type'] ) {
				$sub_files = $wp_filesystem->dirlist( trailingslashit( $path ) . $file );
				foreach ( $sub_files as $sub_file => $sub_fileinfo ) {
					if ( 'php' === pathinfo( $sub_file, PATHINFO_EXTENSION ) ) {
						$php_files[] = trailingslashit( $path ) . trailingslashit( $file ) . $sub_file;
					}
				}
			}
		}

		foreach ( $php_files as $php_file ) {
			$content = $wp_filesystem->get_contents( $php_file );
			if ( false !== $content ) {
				if ( false !== stripos( $content, 'TimThumb script created by Tim McDaniels' ) ) {
					return 0;
				}
			} else {
				return 5;
			}
		}

		return 10;
	}







	/**
	 * check if user with DB ID 1 exists
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function id1_user_check() {
		$return = array();

		$check = get_userdata( 1 );
		if ( $check ) {
			$return['status'] = 0;
			$return['msg']    = $check->user_login;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}






	/**
	 * check if wp-config is present on the default location
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function config_location() {
		$return     = array();
		$testedfile = ABSPATH . 'wp-config.php';
		$check      = file_exists( ABSPATH . 'wp-config.php' );

		$return['details'] = 'Looked for file here: ' . ABSPATH;
		if ( $check ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}





	/**
	 * check if the WP MySQL user can connect from an external host
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function mysql_external() {
		$return = array();
		global $wpdb;

		$check = $wpdb->get_var( 'SELECT CURRENT_USER()' );
		if ( strpos( $check, '@%' ) !== false ) {
			$return['status'] = 0;
		} elseif ( strpos( $check, '@127.0.0.1' ) !== false || stripos( $check, '@localhost' ) !== false ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 5;
			$return['msg']    = $check;
		}

		return $return;
	}





	/**
	 * check if the WP MySQL user has too many permissions granted
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function mysql_permissions() {
		$return = array( 'status' => 10 );
		global $wpdb;

		$grants = $wpdb->get_results( 'SHOW GRANTS', ARRAY_N );
		foreach ( $grants as $grant ) {
			if ( false !== stripos( $grant[0], 'GRANT ALL PRIVILEGES' ) ) {
				$return['status'] = 0;
				break;
			}
		} // foreach

		return $return;
	}





	/**
	 * check if WLW link ispresent in header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function wlw_meta() {
		$return = array();

		$request = wp_remote_get(
			get_home_url(),
			array(
				'sslverify'   => false,
				'timeout'     => 25,
				'redirection' => 2,
			)
		);
		$html    = wp_remote_retrieve_body( $request );

		if ( $html ) {
			$return['status'] = 10;
			// extract content in <head> tags
			$start = strpos( $html, '<head' );
			$len   = strpos( $html, 'head>', $start + strlen( '<head' ) );
			$html  = substr( $html, $start, $len - $start + strlen( 'head>' ) );
			// find all link tags
			preg_match_all( '#<link([^>]*)>#si', $html, $matches );
			$meta_tags = $matches[0];

			foreach ( $meta_tags as $meta_tag ) {
				if ( false !== stripos( $meta_tag, 'wlwmanifest' ) ) {
					$return['status'] = 0;
					break;
				}
			}
		} else {
			// error
			$return['status'] = 5;
		}

		return $return;
	}







	/**
	 * check if RPC link ispresent in header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function rpc_meta() {
		$return = array();

		$request = wp_remote_get(
			get_home_url(),
			array(
				'timeout'     => 25,
				'redirection' => 2,
			)
		);
		$html    = wp_remote_retrieve_body( $request );

		if ( $html ) {
			$return['status'] = 10;
			// extract content in <head> tags
			$start = strpos( $html, '<head' );
			$len   = strpos( $html, 'head>', $start + strlen( '<head' ) );
			$html  = substr( $html, $start, $len - $start + strlen( 'head>' ) );
			// find all link tags
			preg_match_all( '#<link([^>]*)>#si', $html, $matches );
			$meta_tags = $matches[0];

			foreach ( $meta_tags as $meta_tag ) {
				if ( false !== stripos( $meta_tag, 'EditURI' ) ) {
					$return['status'] = 0;
					break;
				}
			}
		} else {
			// error
			$return['status'] = 5;
		}

		return $return;
	}






	/**
	 * check if register_globals is off
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function register_globals_check() {
		$return  = array();
		$getval  = 'register';
		$getval .= '_globals';
		$check   = (bool) ini_get( $getval );
		if ( $check ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}






	/**
	 * check if display_errors is off
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function display_errors_check() {
		$return = array();

		$check = (bool) ini_get( 'display_errors' );
		if ( $check ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}




	/**
	 * Tests for Application Passwords feature is enabled.
	 *
	 * @author Lars Koudal
	 * @since  v0.0.1
	 * @access public static
	 * @global
	 * @return mixed
	 */
	public static function application_passwords() {
		$return = array();
		if ( ! function_exists( 'wp_is_application_passwords_available' ) ) {
			$return['status']  = 0;
			$return['details'] = 'The feature is not available';
			return $return;
		}
		if ( ! wp_is_application_passwords_available() ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 0;
		}
		return $return;
	}



	/**
	 * is theme/plugin editor disabled?
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function file_editor() {
		$return = array();

		if ( defined( 'DISALLOW_FILE_EDIT' ) && constant( 'DISALLOW_FILE_EDIT' ) ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 0;
		}

		return $return;
	}


	/**
	 * check if expose_php is off
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function expose_php_check() {
		$return = array();

		$check = (bool) ini_get( 'expose_php' );
		if ( $check ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check if allow_url_include is off
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function allow_url_include_check() {
		$return = array();
		if ( strnatcmp( phpversion(), '7.4' ) >= 0 ) {
			$return['status']  = 10;
			$return['details'] = 'Running PHP 7.4 - All good.';
		}
		//  We have a test for HP 7.4 just before, so we can include deprecated code here.
		$check = (bool) ini_get( 'allow_url_include' ); // phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives
		if ( $check ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check if safe mode is off
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function safe_mode_check() {
		$return    = array();
		$checkval  = 'safe';
		$checkval .= '_mode';
		$check     = (bool) ini_get( $checkval );
		if ( $check ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check if anyone can register on the site
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function anyone_can_register() {
		$return = array();
		$test   = get_option( 'users_can_register' );

		if ( $test ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check REST api is enabled
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function rest_api_links() {
		$return = array();

		$collected_prios = intval( has_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' ) ) + intval( has_action( 'wp_head', 'rest_output_link_wp_head' ) ) + intval(
			has_action( 'template_redirect', 'rest_output_link_header' )
		);

		if ( $collected_prios > 0 ) {
			$return['status'] = 5;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}



	/**
	 * check WP version
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function ver_check() {
		$return = array();

		if ( ! function_exists( 'get_preferred_from_update_core' ) ) {
			include_once ABSPATH . 'wp-admin/includes/update.php';
		}

		// get version
		wp_version_check();
		$latest_core_update = get_preferred_from_update_core();

		if ( isset( $latest_core_update->response ) && ( 'upgrade' === $latest_core_update->response ) ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	} // ver_check



	/**
	 * check if debug.log file is accessible.
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function debug_log_file_check() {
		$return = array();

		// @todo - check if wp_Debug set and enabled
		// @todo - check if you can access debug.log file
		// @todo - offer way to block access to the debug.log file

		$url      = trailingslashit( content_url() ) . 'debug.log';
		$response = wp_remote_get( $url, array( 'redirection' => 0 ) );

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
		}

		if ( is_wp_error( $response ) ) {
			$return['status'] = 5;
		} elseif ( 200 === $response['response']['code'] ) {
			$return['status'] = 0;
		} elseif ( 404 === $response['response']['code'] ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 10;
		}

		return $return;
	} // ver_check





	/**
	 * core updates should be enabled onlz for minor updates
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function core_updates_check() {
		$return = array();

		// the define has been set.
		if ( ( defined( 'WP_AUTO_UPDATE_CORE' ) && WP_AUTO_UPDATE_CORE ) ) {
			$return['status'] = 10;
			return $return;
		}

		if ( ( defined( 'WP_AUTO_UPDATE_CORE' ) && ! WP_AUTO_UPDATE_CORE ) ) {
			$return['status']  = 0;
			$return['details'] = 'WP_AUTO_UPDATE_CORE has been set to false.';
			return $return;
		}

		if ( ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED ) || ( defined( 'WP_AUTO_UPDATE_CORE' ) && 'minor' !== WP_AUTO_UPDATE_CORE ) ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}




	/**
	 * check if certain username exists
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @param   string $username Default: 'admin'
	 * @return  mixed
	 */
	public static function user_exists( $username = 'admin' ) {
		$return = array();

		if ( username_exists( $username ) ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}





	/**
	 * check if plugins are up to date
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function plugins_ver_check() {
		$return = array();

		//Get the current update info
		$current = get_site_transient( 'update_plugins' );

		if ( ! is_object( $current ) ) {
			$current = new stdClass();
		}

		set_site_transient( 'update_plugins', $current );

		// run the internal plugin update check
		wp_update_plugins();

		$current = get_site_transient( 'update_plugins' );

		if ( isset( $current->response ) && is_array( $current->response ) ) {
			$plugin_update_cnt = count( $current->response );
		} else {
			$plugin_update_cnt = 0;
		}

		if ( $plugin_update_cnt > 0 ) {
			$return['status'] = 0;
			$return['msg']    = count( $current->response );
		} else {
			$return['status'] = 10;
		}

		return $return;
	}




	/**
	 * check if there are deactivated plugins
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function deactivated_plugins() {
		$return = array();

		$all_plugins    = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );

		if ( count( $all_plugins ) > count( $active_plugins ) ) {
			$return['status'] = 0;
			$return['msg']    = count( $all_plugins ) - count( $active_plugins );
		} else {
			$return['status'] = 10;
		}

		return $return;
	} // deactivated_plugins




	/**
	 * check if there are deactivated themes
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @version v1.0.1  Monday, February 28th, 2022.
	 * @access  public static
	 * @return  mixed
	 */
	public static function deactivated_themes() {
		$return     = array();
		$all_themes = wp_get_themes();
		// Note - keep in reverse order, latest first - this way the rest will be.
		$wp_themes_to_keep = array(
			'twentytwentyfour',
			'twentytwentythree',
			'twentytwentytwo',
			'twentytwentyone',
			'twentytwenty',
			'twentynineteen',
			'twentyseventeen',
			'twentysixteen',
			'twentyfifteen',
			'twentyfourteen',
			'twentythirteen',
			'twentytwelve',
			'twentyeleven',
			'twentyten',
		);

		// Parent
		$get_template = get_template();
		// Potentially a child sheet
		$get_stylesheet = get_stylesheet();

		// Unset active theme
		if ( isset( $all_themes[ $get_template ] ) ) {
			unset( $all_themes[ $get_template ] );
		}
		// Unset child theme
		if ( isset( $all_themes[ $get_stylesheet ] ) ) {
			unset( $all_themes[ $get_stylesheet ] );
		}

		$newest_wp_found = false;
		foreach ( $wp_themes_to_keep as $wttk ) {
			if ( ! $newest_wp_found && isset( $all_themes[ $wttk ] ) ) {
				unset( $all_themes[ $wttk ] );
				$newest_wp_found = true;
			}
		}

		$return['details'] = 'Safe to remove: ' . implode( ', ', $all_themes );
		if ( count( $all_themes ) > 0 ) {
			$return['status'] = 0;
			$return['msg']    = count( $all_themes );
		} else {
			$return['status']  = 10;
			$return['details'] = '';
		}

		return $return;
	}


	/**
	 * check themes versions
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function themes_ver_check() {
		$return = array();

		$current = get_site_transient( 'update_themes' );

		if ( ! is_object( $current ) ) {
			$current = new stdClass();
		}

		set_site_transient( 'update_themes', $current );
		wp_update_themes();

		$current = get_site_transient( 'update_themes' );

		if ( isset( $current->response ) && is_array( $current->response ) ) {
			$theme_update_cnt = count( $current->response );
		} else {
			$theme_update_cnt = 0;
		}

		if ( $theme_update_cnt > 0 ) {
			$return['status'] = 0;
			$return['msg']    = count( $current->response );
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check DB table prefix
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function db_table_prefix_check() {
		global $wpdb;
		$return = array();

		if ( 'wp_' === $wpdb->prefix || 'wordpress_' === $wpdb->prefix || 'wp3_' === $wpdb->prefix ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}



	/**
	 * check if global WP debugging is enabled
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function debug_check() {
		$return = array();

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check if global WP JS debugging is enabled
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function script_debug_check() {
		$return = array();

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * check if DB debugging is enabled
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function db_debug_check() {
		global $wpdb;
		$return = array();

		if ( true === $wpdb->show_errors ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}


	/**
	 * does readme.html exist?
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function readme_check() {
		$return   = array();
		$url      = get_bloginfo( 'wpurl' ) . '/readme.html?rnd=' . wp_rand();
		$response = wp_remote_get( $url, array( 'redirection' => 0 ) );

		if ( is_wp_error( $response ) ) {
			$return['status'] = 5;
		} elseif ( 200 === $response['response']['code'] ) {
			$return['status'] = 0;
		} elseif ( 404 === $response['response']['code'] ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}



	/**
	 * does readme.html exist?
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function license_check() {
		$return   = array();
		$url      = get_bloginfo( 'wpurl' ) . '/license.txt?rnd=' . wp_rand();
		$response = wp_remote_get( $url, array( 'redirection' => 0 ) );

		if ( is_wp_error( $response ) ) {
			$return['status'] = 5;
		} elseif ( 200 === $response['response']['code'] ) {
			$return['status'] = 0;
		} elseif ( 404 === $response['response']['code'] ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 10;
		}

		return $return;
	} // license_check




	/**
	 * does WP install.php file exist?
	 *
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Wednesday, December 16th, 2020.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function install_file_check() {
		$return   = array();
		$url      = get_bloginfo( 'wpurl' ) . '/wp-admin/install.php?rnd=' . wp_rand();
		$response = wp_remote_get( $url, array( 'redirection' => 0 ) );

		if ( is_wp_error( $response ) ) {
			$return['status'] = 5;
		} elseif ( 200 === $response['response']['code'] ) {
			$return['status'] = 0;
		} elseif ( 404 === $response['response']['code'] ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}





	/**
	 * does WP upgrade.php file exist?
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function upgrade_file_check() {
		$return   = array();
		$url      = get_bloginfo( 'wpurl' ) . '/wp-admin/upgrade.php?rnd=' . wp_rand();
		$response = wp_remote_get( $url, array( 'redirection' => 0 ) );

		if ( is_wp_error( $response ) ) {
			$return['status'] = 5;
		} elseif ( 200 === $response['response']['code'] ) {
			$return['status'] = 0;
		} elseif ( 404 === $response['response']['code'] ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}








	/**
	 * Check if wp-config.php has the right chmod
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function config_chmod() {
		$return = array();
		include_once ABSPATH . 'wp-admin/includes/file.php';

		WP_Filesystem();

		global $wp_filesystem;

		$testedfile = ABSPATH . 'wp-config.php';
		if ( file_exists( $testedfile ) ) {
			$mode = $wp_filesystem->getchmod( ABSPATH . 'wp-config.php' );
		} else {
			// Move up one folder
			$testedfile = trailingslashit( dirname( ABSPATH, 1 ) ) . 'wp-config.php';
			$mode       = $wp_filesystem->getchmod( $testedfile );
		}

		$return['details'] = 'Tested file: ' . $testedfile;

		$good_modes = array( '400', '440', '0400', '0440', '660', '0660', '664', '0664' );

		if ( ! $mode ) {
			$return['status'] = 5;
		} elseif ( ! in_array( $mode, $good_modes, true ) ) {
			$return['status'] = 0;
			$return['msg']    = $mode;
		} else {
			$return['status'] = 10;
			$return['msg']    = $mode;
		}

		return $return;
	}






	/**
	 * check for unnecessary information on failed login
	 *
	 * @author  Lars Koudal
	 * @author  Unknown
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @version v1.0.1  Saturday, February 4th, 2023.
	 * @access  public static
	 * @return  mixed
	 */
	public static function check_failed_login_info() {
		$return = array();

		$params = array(
			'log' => 'sn-test_3453344355',
			'pwd' => 'sn-test_2344323335',
		);

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request(
			get_bloginfo( 'wpurl' ) . '/wp-login.php',
			array(
				'method' => 'POST',
				'body'   => $params,
			)
		);

		if ( ( isset( $response['body'] ) ) && ( stripos( $response['body'], 'invalid username' ) !== false ) ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}






	/**
	 * helper function
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @param   mixed $username
	 * @param   mixed $password
	 * @return  void
	 */
	public static function try_login( $username, $password ) {
		$user = apply_filters( 'authenticate', null, $username, $password );

		if ( isset( $user->ID ) && ! empty( $user->ID ) ) {
			return true;
		} else {
			return false;
		}
	}





	/**
	 * bruteforce user login
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function bruteforce_login() {
		$return           = array();
		$max_users_attack = 5;
		$passwords        = file( WF_SN_PLUGIN_DIR . 'misc/brute-force-dictionary.txt', FILE_IGNORE_NEW_LINES );

		$bad_usernames = array();

		if ( ! $max_users_attack ) {
			$return['status'] = 5;
			return $return;
		}

		$users = get_users( array( 'role' => 'administrator' ) );
		if ( count( $users ) < $max_users_attack ) {
			$users = array_merge( $users, get_users( array( 'role' => 'editor' ) ) );
		}
		if ( count( $users ) < $max_users_attack ) {
			$users = array_merge( $users, get_users( array( 'role' => 'author' ) ) );
		}
		if ( count( $users ) < $max_users_attack ) {
			$users = array_merge( $users, get_users( array( 'role' => 'contributor' ) ) );
		}
		if ( count( $users ) < $max_users_attack ) {
			$users = array_merge( $users, get_users( array( 'role' => 'subscriber' ) ) );
		}

		$i = 0;
		foreach ( $users as $user ) {
			++$i;
			$passwords[] = $user->user_login;
			foreach ( $passwords as $password ) {

				if ( self::try_login( $user->user_login, $password ) ) {
					$bad_usernames[] = $user->user_login;
					break;
				}
			} // foreach $passwords

			if ( $i > $max_users_attack ) {
				break;
			}
		} // foreach $users

		if ( empty( $bad_usernames ) ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 0;
			$return['msg']    = implode( ', ', $bad_usernames );
		}

		return $return;
	}





	/**
	 * Test for X-XSS-Protection in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function xxss_protection() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['x-xss-protection'] ) ) {
			$return['status']  = 10;
			$return['msg']     = 'Great, X-XSS-Protection has been set to ' . $response['headers']['x-xss-protection'];
			$return['details'] = '"' . $response['headers']['x-xss-protection'] . '"';
		} else {
			// x-xss-protection has not been set
			$return['status'] = 0;
		}
		return $return;
	}




	/**
	 * Test for Strict-Transport-Security in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function strict_transport_security() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['strict-transport-security'] ) ) {
			// Multiple
			if ( is_array( $response['headers']['strict-transport-security'] ) ) {
				$return['status'] = 0;
				$return['msg']    = 'Error, multiple Strict-Transport-Security headers found. You should only have one';
			} else {
				$return['status']  = 10;
				$return['msg']     = 'Great, Strict-Transport-Security has been set.';
				$return['details'] = '"' . $response['headers']['strict-transport-security'] . '"';
			}
		} else {
			// x-xss-protection has not been set
			$return['status'] = 0;
		}
		return $return;
	}




	/**
	 * Test for Content Security Policy in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function referrer_policy() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['referrer-policy'] ) ) {
			$return['status']  = 10;
			$return['msg']     = 'Great, Referrer-Policy has been set.';
			$return['details'] = '"' . $response['headers']['referrer-policy'] . '"';
		} else {
			$return['status'] = 0;
		}

		return $return;
	}



	/**
	 * Test for Feature Policy in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function feature_policy() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['permissions-policy'] ) ) {
			$return['status']  = 10;
			$return['msg']     = 'Great, Permissions-Policy has been set.';
			$return['details'] = '"' . $response['headers']['permissions-policy'] . '"';
		} else {
			$return['status'] = 0;
		}

		return $return;
	}



	/**
	 * Test for Content Security Policy in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function content_security_policy() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['content-security-policy'] ) ) {
			$return['status']  = 10;
			$return['msg']     = 'Great, Content Security Policy has been set.';
			$return['details'] = '"' . $response['headers']['content-security-policy'] . '"';
		} else {
			$return['status'] = 0;
		}

		// Test for report-only mode has been set
		if ( 0 === $return['status'] ) {
			if ( isset( $response['headers']['content-security-policy-report-only'] ) ) {
				$return['status'] = 5;
			}
		}

		return $return;
	}




	/**
	 * Test for X-Frame-Options in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function x_frame_options() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['x-frame-options'] ) ) {
			$return['status']  = 10;
			$return['details'] = '"' . $response['headers']['x-frame-options'] . '"';
		} else {
			$return['status'] = 0;
		}
		return $return;
	}





	/**
	 * Test for X-Content-Type-Options in http header
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function x_content_type_options() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( isset( $response['headers']['x-content-type-options'] ) ) {
			$return['status']  = 10;
			$return['details'] = '"' . $response['headers']['x-content-type-options'] . '"';
		} else {
			$return['status'] = 0;
		}
		return $return;
	}


	/**
	 * check if php headers contain php version
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function php_headers() {
		$return = array();

		if ( ! class_exists( 'WP_Http' ) ) {
			include ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( home_url() );

		if ( ( isset( $response['headers']['server'] ) && stripos( $response['headers']['server'], phpversion() ) !== false ) || ( isset( $response['headers']['x-powered-by'] ) && stripos( $response['headers']['x-powered-by'], phpversion() ) !== false ) ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
			$security_tests   = self::return_security_tests();
			$return['msg']    = $security_tests['php_headers']['msg_ok'];
		}

		return $return;
	} // php_headers


	/**
	 * check for WP version in meta tags
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function wp_header_meta() {
		$return = array();

		$request = wp_remote_get(
			get_home_url(),
			array(
				'sslverify'   => false,
				'timeout'     => 25,
				'redirection' => 2,
			)
		);
		$html    = wp_remote_retrieve_body( $request );

		if ( $html ) {
			$return['status'] = 10;
			// extract content in <head> tags
			$start = strpos( $html, '<head' );
			$len   = strpos( $html, 'head>', $start + strlen( '<head' ) );
			$html  = substr( $html, $start, $len - $start + strlen( 'head>' ) );
			// find all Meta Tags
			preg_match_all( '#<meta([^>]*)>#si', $html, $matches );
			$meta_tags = $matches[0];

			foreach ( $meta_tags as $meta_tag ) {
				if ( stripos( $meta_tag, 'generator' ) !== false
					&& stripos( $meta_tag, get_bloginfo( 'version' ) ) !== false
				) {
					$return['status'] = 0;
					break;
				}
			}
		} else {
			// error
			$return['status'] = 5;
		}

		return $return;
	} // wp_header_meta


	/**
	 * compare WP Blog Url with WP Site Url
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function blog_site_url_check() {
		$return = array();

		$siteurl = home_url();
		$wpurl   = site_url();

		if ( $siteurl === $wpurl ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}




	/**
	 * brute force attack on password
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @param   mixed $password
	 * @return  void
	 */
	public static function dictionary_attack( $password ) {
		$dictionary = file( WF_SN_PLUGIN_DIR . 'misc/brute-force-dictionary.txt', FILE_IGNORE_NEW_LINES );

		if ( in_array( $password, $dictionary, true ) ) {
			return true;
		} else {
			return false;
		}
	}



	/**
	 * check database password
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function db_password_check() {
		$return   = array();
		$password = DB_PASSWORD;

		if ( empty( $password ) ) {
			$return['status'] = 0;
			$return['msg']    = 'password is empty';
		} elseif ( self::dictionary_attack( $password ) ) {
			$return['status'] = 0;
			$return['msg']    = 'password is a simple word from the dictionary';
		} elseif ( strlen( $password ) < 6 ) {
			$return['status'] = 0;
			$return['msg']    = 'password length is only ' . strlen( $password ) . ' chars';
		} elseif ( count( count_chars( $password, 1 ) ) < 5 ) {
			$return['status'] = 0;
			$return['msg']    = 'password is too simple';
		} else {
			$return['status'] = 10;
			$return['msg']    = 'password is ok';
		}

		return $return;
	}




	/**
	 * unique config keys check
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function salt_keys_check() {
		$return = array();
		$ok     = true;
		$keys   = array(
			'AUTH_KEY',
			'SECURE_AUTH_KEY',
			'LOGGED_IN_KEY',
			'NONCE_KEY',
			'AUTH_SALT',
			'SECURE_AUTH_SALT',
			'LOGGED_IN_SALT',
			'NONCE_SALT',
		);

		foreach ( $keys as $key ) {

			if ( defined( $key ) ) {
				$constant = constant( $key );
			}
			if ( 'put your unique phrase here' === trim( $constant ) || strlen( $constant ) < 50 ) {
				$bad_keys[] = $key;
				$ok         = false;
			}
		} // foreach

		if ( true === $ok ) {
			$return['status'] = 10;
		} else {
			$return['status'] = 0;
			$return['msg']    = implode( ', ', $bad_keys );
		}

		return $return;
	}





	/**
	 * check if wp-config.php has the right chmod
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function salt_keys_age_check() {
		$return = array();
		$age    = 0;

		if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
			$age = filemtime( ABSPATH . 'wp-config.php' );
		} else {
			$age = filemtime( ABSPATH . '../wp-config.php' );
		}

		if ( empty( $age ) ) {
			$return['status'] = 5;
		} else {
			$diff = time() - $age;
			if ( $diff > DAY_IN_SECONDS * 93 ) {
				$return['status'] = 0;
			} else {
				$return['status'] = 10;
			}
		}

		return $return;
	}




	/**
	 * uploads_browsable.
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function uploads_browsable() {
		$return     = array();
		$upload_dir = wp_upload_dir();

		$args     = array(
			'method'      => 'GET',
			'timeout'     => 5,
			'redirection' => 0,
			'sslverify'   => false,
			'httpversion' => 1.0,
			'blocking'    => true,
			'headers'     => array(),
			'body'        => null,
			'cookies'     => array(),
		);
		$response = wp_remote_get( rtrim( $upload_dir['baseurl'], '/' ) . '/?nocache=' . wp_rand(), $args );

		if ( is_wp_error( $response ) ) {
			$return['status'] = 5;
			$return['msg']    = $upload_dir['baseurl'] . '/';
		} elseif ( '200' === $response['response']['code'] && false !== stripos( $response['body'], 'index' ) ) {
			$return['status'] = 0;
			$return['msg']    = $upload_dir['baseurl'] . '/';
		} else {
			$return['status'] = 10;
		}

		return $return;
	}



	/**
	 * shellshock_6271.
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function shellshock_6271() {
		$return = array();
		$pipes  = array();

		// Check if shell_exec is allowed
		if ( ! function_exists( 'proc_open' ) ) {
			$return['status'] = 10;
			$return['msg']    = 'The PHP module proc_open is not allowed. This is a good sign.'; // @i8n
			return $return;
		}

		if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
			$return['status'] = 10;
			return $return;
		}

		$env = array( 'SHELL_SHOCK_TEST' => '() { :;}; echo VULNERABLE' );

		$desc = array(
			0 => array( 'pipe', 'r' ),
			1 => array( 'pipe', 'w' ),
			2 => array( 'pipe', 'w' ),
		);

		$p = @proc_open( 'bash -c "echo Test"', $desc, $pipes, null, $env );
		if ( ! $pipes ) {
			$return['status'] = 5;
			return $return;
		}
		$output = stream_get_contents( $pipes[1] );
		proc_close( $p );

		if ( strpos( $output, 'VULNERABLE' ) === false ) {
			$return['status'] = 10;
			return $return;
		}

		$return['status'] = 0;
		return $return;
	}




	/**
	 * shellshock_7169.
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @global
	 * @return  mixed
	 */
	public static function shellshock_7169() {
		$return = array();
		$pipes  = array();

		// Check if shell_exec is allowed
		if ( ! function_exists( 'proc_open' ) ) {
			$return['status'] = 10;
			$return['msg']    = 'The PHP module proc_open is not allowed. This is a good sign.'; // @i8n
			return $return;
		}

		if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
			$return['status'] = 10;
			return $return;
		}

		$desc = array(
			0 => array( 'pipe', 'r' ),
			1 => array( 'pipe', 'w' ),
			2 => array( 'pipe', 'w' ),
		);

		$p = @proc_open( "rm -f echo; env 'x=() { (a)=>\' bash -c \"echo date +%Y\"; cat echo", $desc, $pipes, sys_get_temp_dir() );
		if ( ! $pipes ) {
			$return['status'] = 5;
			return $return;
		}
		$output = stream_get_contents( $pipes[1] );
		proc_close( $p );

		$test = gmdate( 'Y' );

		if ( trim( $output ) === $test ) {
			$return['status'] = 0;
			return $return;
		}

		$return['status'] = 10;
		return $return;
	}






	/**
	 * check if any active plugin hasn't been updated in last 365 days
	 * Note: This function stores details about plugins and stores it in an option for later use in incompatible_plugins() - This test needs to be run before incompatible_plugins().
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function old_plugins() {

		$return               = array();
		$good                 = array();
		$bad                  = array();
		$wf_sn_active_plugins = array();
		$active_plugins       = get_option( 'active_plugins', array() );

		foreach ( $active_plugins as $plugin_path ) {
			$plugin = explode( '/', $plugin_path );

			if ( empty( $plugin ) || empty( $plugin_path ) ) {
				continue;
			}
			if ( isset( $plugin[0] ) ) {
				$plugin = $plugin[0];
			}

			$response = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.1/?action=plugin_information&request%5Bslug%5D=' . $plugin, array( 'timeout' => 5 ) );
			if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
				$details = wp_remote_retrieve_body( $response );
				$details = json_decode( $details, true );
				if ( empty( $details ) ) {
					// No details detected
					continue;
				}
				$wf_sn_active_plugins[ $plugin_path ] = $details;
				$updated                              = strtotime( $details['last_updated'] );
				if ( $updated + 365 * DAY_IN_SECONDS < time() ) {
					$bad[ $plugin_path ] = true;
				} else {
					$good[ $plugin_path ] = true;
				}
			}
		}
		update_option( 'wf_sn_active_plugins', $wf_sn_active_plugins, false );

		if ( empty( $bad ) && empty( $good ) ) {
			$return['status'] = 5;
		} elseif ( empty( $bad ) ) {
			$return['status'] = 10;
		} else {
			$plugins = get_plugins();
			foreach ( $bad as $plugin_path => $tmp ) {
				$bad[ $plugin_path ] = $plugins[ $plugin_path ]['Name'];
			}
			$return['msg']    = implode( ', ', $bad );
			$return['status'] = 0;
		}
		return $return;
	}





	/**
	 * check if any active plugins are not compatible with current ver of WP
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function incompatible_plugins() {
		global $wp_version;

		$return            = array();
		$return['details'] = '';
		$good              = array();
		$bad               = array();

		$wf_sn_active_plugins = array();

		$active_plugins = get_option( 'active_plugins', array() );

		foreach ( $active_plugins as $plugin_path ) {
			$plugin = explode( '/', $plugin_path );

			if ( empty( $plugin ) || empty( $plugin_path ) ) {
				continue;
			}
			if ( isset( $plugin[0] ) ) {
				$plugin = $plugin[0];
			}

			$response = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.1/?action=plugin_information&request%5Bslug%5D=' . $plugin, array( 'timeout' => 5 ) );
			if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
				$details = wp_remote_retrieve_body( $response );
				$details = json_decode( $details, true );
				if ( empty( $details ) ) {
					// No details detected
					continue;
				}
				$wf_sn_active_plugins[ $plugin_path ] = $details;
				$updated                              = strtotime( $details['last_updated'] );
				if ( $updated + 365 * DAY_IN_SECONDS < time() ) {
					$bad[ $plugin_path ] = true;
				} else {
					$good[ $plugin_path ] = true;
				}
			}
		} // foreach active plugin

		if ( empty( $wf_sn_active_plugins ) ) {
			// No active plugins stored from the old_plugins() test
			return array( 'status' => 0 );
		}

		$all_plugins = get_plugins();

		foreach ( $wf_sn_active_plugins as $plugin_path => $plugin ) {

			if ( version_compare( $wp_version, $plugin['tested'], '>' ) ) {
				$bad[ $plugin_path ] = $plugin;
			} else {
				$good[ $plugin_path ] = $plugin;
			}
		} // foreach active plugins we have details on

		if ( empty( $bad ) ) {
			$return['status'] = 10;
		} else {
			$plugins = get_plugins();
			foreach ( $bad as $plugin_path => $tmp ) {
				$bad[ $plugin_path ] = $plugins[ $plugin_path ]['Name'];
				if ( '' !== $return['details'] ) {
					// add comma if not empty
					$return['details'] .= ', ';
				}
				$return['details'] .= $plugins[ $plugin_path ]['Name'] . ' tested up to ' . $wf_sn_active_plugins[ $plugin_path ]['tested'];
			}
			$return['msg']    = implode( ', ', $bad );
			$return['status'] = 0;
		}
		return $return;
	}





	/**
	 * check if PHP is up-to-date
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function php_ver() {
		$return = array( 'msg' => PHP_VERSION );

		if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
			$return['status'] = 0;
		} elseif ( version_compare( PHP_VERSION, '7.0', '<' ) ) {
			$return['status'] = 5;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}



	/**
	 * check if mysql is up-to-date
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function mysql_ver() {
		global $wpdb;

		$mysql_version = $wpdb->get_var( 'SELECT VERSION()' );

		$return = array(
			'msg' => $mysql_version,
		);

		if ( version_compare( $mysql_version, '5.0', '<' ) ) {
			$return['status'] = 0;
		} elseif ( version_compare( $mysql_version, '5.6', '<' ) ) {
			$return['status'] = 5;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}





	/**
	 * Try getting usernames from user IDs
	 *
	 * @author  Lars Koudal
	 * @since   v0.0.1
	 * @version v1.0.0  Monday, January 25th, 2021.
	 * @access  public static
	 * @return  mixed
	 */
	public static function usernames_enumeration() {
		$users   = get_users( 'number=10' );
		$success = false;
		$url     = home_url() . '/?author=';

		// Check if it's a local development environment
		if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
			// Disable SSL verification
			$args = array(
				'redirection' => 0,
				'sslverify'   => false,
			);
		} else {
			$args = array(
				'redirection' => 0,
			);
		}

		foreach ( $users as $user ) {
			$response      = wp_remote_get( $url . $user->ID, $args );
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 301 === $response_code ) {
				$success = true;
				break;
			}
		} // foreach

		if ( $success ) {
			$return['status'] = 0;
		} else {
			$return['status'] = 10;
		}

		return $return;
	}
}
