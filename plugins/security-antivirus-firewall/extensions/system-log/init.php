<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'wptsafExtensionSystemLog.php';
require_once 'wptsafExtensionSystemLogAjaxHandle.php';
require_once 'wptsafExtensionSystemLogCron.php';
require_once 'wptsafExtensionSystemLogLog.php';
require_once 'wptsafExtensionSystemLogSettings.php';
require_once 'wptsafExtensionSystemLogWidget.php';

wptsafSecurity::getInstance()->addExtension(wptsafExtensionSystemLog::getInstance());
