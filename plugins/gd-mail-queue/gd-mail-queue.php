<?php

/*
Plugin Name:       GD Mail Queue
Plugin URI:        https://plugins.dev4press.com/gd-mail-queue/
Description:       Intercept emails sent with wp_mail and implements flexible mail queue system for sending emails, converting plain text emails to HTML with templates customization, email log and more.
Author:            Milan Petrovic
Author URI:        https://www.dev4press.com/
Text Domain:       gd-mail-queue
Version:           4.2.1
Requires at least: 5.5
Tested up to:      6.3
Requires PHP:      7.3
License:           GPLv3 or later
License URI:       https://www.gnu.org/licenses/gpl-3.0.html

== Copyright ==
Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

$gdmaq_dirname_basic = dirname(__FILE__).'/';
$gdmaq_urlname_basic = plugins_url('/gd-mail-queue/');

define('GDMAQ_PATH', $gdmaq_dirname_basic);
define('GDMAQ_URL', $gdmaq_urlname_basic);
define('GDMAQ_D4PLIB', $gdmaq_dirname_basic.'d4plib/');

/* D4PLIB */
if (!defined('D4PLIB_PATH')) {
    define('D4PLIB_PATH', GDMAQ_PATH.'d4plib/');
}

if (!defined('D4PLIB_URL')) {
    define('D4PLIB_URL', GDMAQ_URL.'d4plib/');
}

require_once(GDMAQ_D4PLIB.'d4p.core.php');
/* D4PLIB */

d4p_includes(array(
    array('name' => 'datetime', 'directory' => 'core'),
    array('name' => 'scope', 'directory' => 'core'),
    array('name' => 'wpdb', 'directory' => 'core'),
    array('name' => 'plugin', 'directory' => 'plugin'),
    array('name' => 'errors', 'directory' => 'plugin'), 
    array('name' => 'settings', 'directory' => 'plugin'),
    array('name' => 'ip', 'directory' => 'classes'),
    'functions',
    'sanitize', 
    'access', 
    'wp'
), GDMAQ_D4PLIB);

require_once(GDMAQ_PATH.'core/version.php');
require_once(GDMAQ_PATH.'core/settings.php');
require_once(GDMAQ_PATH.'core/functions.php');
require_once(GDMAQ_PATH.'core/plugin.php');

require_once(GDMAQ_PATH.'core/objects/core.engine.php');
require_once(GDMAQ_PATH.'core/objects/core.service.php');

require_once(GDMAQ_PATH.'core/objects/core.db.php');
require_once(GDMAQ_PATH.'core/objects/core.detect.php');
require_once(GDMAQ_PATH.'core/objects/core.mailer.php');
require_once(GDMAQ_PATH.'core/objects/core.htmlfy.php');
require_once(GDMAQ_PATH.'core/objects/core.queue.php');
require_once(GDMAQ_PATH.'core/objects/core.log.php');
require_once(GDMAQ_PATH.'core/objects/core.external.php');

require_once(GDMAQ_PATH.'core/objects/core.fake.php');
require_once(GDMAQ_PATH.'core/objects/core.email.php');
require_once(GDMAQ_PATH.'core/objects/core.mirror.php');

global $_gdmaq_core, $_gdmaq_settings;

$_gdmaq_settings = new gdmaq_core_settings();
$_gdmaq_core = new gdmaq_core_plugin();

/** @return gdmaq_core_plugin */
function gdmaq() {
    global $_gdmaq_core;
    return $_gdmaq_core;
}

/** @return gdmaq_core_settings */
function gdmaq_settings() {
    global $_gdmaq_settings;
    return $_gdmaq_settings;
}

if (D4P_ADMIN) {
    d4p_includes(array(
        array('name' => 'admin', 'directory' => 'plugin'),
        array('name' => 'functions', 'directory' => 'admin')
    ), GDMAQ_D4PLIB);

    require_once(GDMAQ_PATH.'core/admin/plugin.php');
}

if (D4P_AJAX) {
    require_once(GDMAQ_PATH.'core/admin/ajax.php');
}
