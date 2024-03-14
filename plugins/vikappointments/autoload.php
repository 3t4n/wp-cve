<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

// include defines
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'defines.php';

// it is possible to inject debug=on in query string to force the error reporting to MAXIMUM
if (VIKAPPOINTMENTS_DEBUG || (isset($_GET['debug']) && $_GET['debug'] == 'on'))
{
	error_reporting(E_ALL);
	ini_set('display_errors', true);
}

// include internal loader if not exists
if (!class_exists('JLoader'))
{
	require_once implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'libraries', 'adapter', 'loader', 'loader.php']);

    // setup auto-loader
    JLoader::setup();

	// setup base path
	JLoader::$base = VIKAPPOINTMENTS_LIBRARIES;
}

// load framework dependencies
JLoader::import('adapter.acl.access');
JLoader::import('adapter.loader.utils');
JLoader::import('adapter.mvc.view');
JLoader::import('adapter.mvc.controller');
JLoader::import('adapter.factory.factory');
JLoader::import('adapter.html.html');
JLoader::import('adapter.input.input');
JLoader::import('adapter.output.filter');
JLoader::import('adapter.language.text');
JLoader::import('adapter.layout.helper');
JLoader::import('adapter.session.handler');
JLoader::import('adapter.application.route');
JLoader::import('adapter.application.version');
JLoader::import('adapter.uri.uri');
JLoader::import('adapter.toolbar.helper');
JLoader::import('adapter.editor.editor');
JLoader::import('adapter.date.date');
JLoader::import('adapter.component.helper');
JLoader::import('adapter.event.pluginhelper');
JLoader::import('adapter.event.dispatcher');
JLoader::import('adapter.database.table');
JLoader::import('adapter.session.session');
JLoader::import('adapter.http.http');
JLoader::import('adapter.cipher.crypto');
JLoader::import('adapter.usergroups.helper');

// import internal loader
JLoader::import('loader.loader', VIKAPPOINTMENTS_LIBRARIES);

// load plugin dependencies
VikAppointmentsLoader::import('layout.helper');
VikAppointmentsLoader::import('lite.manager');
VikAppointmentsLoader::import('system.body');
VikAppointmentsLoader::import('system.builder');
VikAppointmentsLoader::import('system.cron');
VikAppointmentsLoader::import('system.install');
VikAppointmentsLoader::import('system.screen');
VikAppointmentsLoader::import('system.feedback');
VikAppointmentsLoader::import('system.rssfeeds');
VikAppointmentsLoader::import('system.assets');

// invoke component auto-loader
VikAppointmentsLoader::import('site.helpers.libraries.autoload', VIKAPPOINTMENTS_BASE);
