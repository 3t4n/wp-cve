<?php
/*
	Plugin Name: Security Antivirus Firewall (wpTools S.A.F.)
	Plugin URI: http://wptools.co/wordpress-security-antivirus-firewall
	Description: Security plugin to protect website with firewall and antivirus scanner, brute force security monitor, life system security monitor
	Version: 2.3.5
	Network: True
	Author: wpTools
	Author URI: http://wptools.co/
	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	Text Domain:  security-antivirus-firewall
 */


if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

define('WPTSAF_VERSION', 			'2.3.5');
define('WPTSAF_PLUGINNAME', 		basename(dirname(__FILE__)) . '/' . basename(__FILE__));

define('WPTSAF_DIR', 				dirname(__FILE__) . '/');
define('WPTSAF_URL', 				plugin_dir_url(__FILE__));

require_once WPTSAF_DIR . 'includes/wptsafHellper.php';

$premiumPath = wpToolsSAFHelperClass::getKeyFile();
if($premiumPath){
	define("WPTSAF_PRO", 1);
	define("WPTSAF_KEY_PATH", $premiumPath );
	require_once WPTSAF_KEY_PATH;
} else {
	define("WPTSAF_PRO", 0);
}

define('WPTSAF_ACCESS_LEVEL', 		'manage_options');

define('WPTSAF_ACCESS_OFFER', 		'0');

global $wpdb;
define('WPTSAF_DB_PREFIX', 			(is_multisite() ? $wpdb->base_prefix : $wpdb->prefix) . 'wptsaf_security_');
define('WPTSAF_OPTION_KEY_PREFIX', 	'wptsaf_security_');

define('WPTSAF_BODY_CLASS', 		'wptsaf');
define('WPTSAF_NONCE', 				'wptsaf-security-nonce');

define('WPTSAF_LOG_LIMIT', 			10000);

define('WPTSAF_DATE_FORMAT', 			'd.m.y H-i');
define('WPTSAF_DATE_FORMAT_REPORT', 	'd.m.y');
define('WPTSAF_DATE_FORMAT_DATEPICKER', 'DD.MM.YY HH-mm');

define('WPTSAF_DATE_GMT_OFFSET', 	get_option('gmt_offset') * HOUR_IN_SECONDS);

require_once WPTSAF_DIR . 'includes/wptsafAbstractExtension.php';
require_once WPTSAF_DIR . 'includes/wptsafAbstractExtensionAjaxHandle.php';
require_once WPTSAF_DIR . 'includes/wptsafAbstractExtensionWidget.php';
require_once WPTSAF_DIR . 'includes/wptsafAbstractExtensionReportBuilder.php';
require_once WPTSAF_DIR . 'includes/wptsafAbstractLog.php';
require_once WPTSAF_DIR . 'includes/wptsafEnv.php';
require_once WPTSAF_DIR . 'includes/wptsafExtensionCron.php';
require_once WPTSAF_DIR . 'includes/wptsafSettings.php';
require_once WPTSAF_DIR . 'includes/wptsafValidator.php';
require_once WPTSAF_DIR . 'includes/wptsafView.php';

require_once WPTSAF_DIR . 'includes/admin/page/wptsafAbstractAdminPage.php';

require_once WPTSAF_DIR . 'includes/ajax/wptsafAjaxHandle.php';
require_once WPTSAF_DIR . 'includes/ajax/wptsafAjaxResponse.php';
new wptsafAjaxHandle();

require_once WPTSAF_DIR . 'core/init.php';

if(!WPTSAF_PRO) require_once WPTSAF_DIR . 'extensions/info/init.php';
require_once WPTSAF_DIR . 'extensions/extension-error-monitor/init.php';
require_once WPTSAF_DIR . 'extensions/system-log/init.php';

/*require_once WPTSAF_DIR . 'extensions/file-change/init.php';
require_once WPTSAF_DIR . 'extensions/malware-scanner/init.php';*/

require_once WPTSAF_DIR . 'extensions/404-detection/init.php';
require_once WPTSAF_DIR . 'extensions/network-monitor/init.php';
require_once WPTSAF_DIR . 'extensions/login-brute-force/init.php';

//require_once WPTSAF_DIR . 'extensions/filesystem-state/init.php';

require_once WPTSAF_DIR . 'extensions/easy-password/init.php';
require_once WPTSAF_DIR . 'extensions/google-captcha/init.php';
require_once WPTSAF_DIR . 'extensions/auto-update/init.php';


//24.08.2017
require_once WPTSAF_DIR . 'extensions/remove-info/init.php';

require_once WPTSAF_DIR . 'extensions/report/init.php';
//require_once WPTSAF_DIR . 'extensions/cron-schedule/init.php';
