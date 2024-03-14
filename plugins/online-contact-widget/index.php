<?php
/*
Plugin Name: 多合一在线客服插件
Plugin URI: http://wordpress.org/plugins/online-contact-widget/
Version: 1.0.8
Description: Online Contact Widget（多合一在线客服插件），旨在为WordPress网站提供一系列可配置在线客服支持，包括QQ、微信（微信号、公众号和小程序QR-code）、电话、Email和工单等。
Author: 闪电博
Author URI: http://www.wbolt.com/
*/


if (!defined('ABSPATH')) {
    return;
}

define('ONLINE_CONTACT_WIDGET_PATH', __DIR__);
define('ONLINE_CONTACT_WIDGET_FILE', __FILE__);
define('ONLINE_CONTACT_WIDGET_VERSION', '1.0.8');
define('ONLINE_CONTACT_WIDGET_CODE', 'ocw');
define('ONLINE_CONTACT_WIDGET_URL', plugin_dir_url(ONLINE_CONTACT_WIDGET_FILE));

require_once __DIR__ . '/classes/admin.class.php';
require_once __DIR__ . '/classes/front.class.php';
require_once __DIR__ . '/classes/contact.class.php';
require_once __DIR__ . '/classes/captcha.class.php';
require_once __DIR__ . '/classes/sms.class.php';
require_once __DIR__ . '/classes/mail.class.php';

OCW_Admin::init();
OCW_Front::init();
OCW_Contact::init();
OCW_Captcha::init();
OCW_Sms::init();
OCW_Mail::init();
