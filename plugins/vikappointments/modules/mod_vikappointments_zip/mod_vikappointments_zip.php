<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_zip
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

// require autoloader
if (defined('JPATH_SITE') && JPATH_SITE !== 'JPATH_SITE')
{
	require_once implode(DIRECTORY_SEPARATOR, array(JPATH_SITE, 'components', 'com_vikappointments', 'helpers', 'libraries', 'autoload.php'));
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'helper.php';

// backward compatibility

$options = array(
	'version' => '1.3.2',
);

$vik = VAPApplication::getInstance();

/**
 * Load CSS environment.
 * 
 * @since 1.3.1
 */
JHtml::fetch('vaphtml.assets.environment');

$vik->addStyleSheet(VAPMODULES_URI . 'mod_vikappointments_zip/mod_vikappointments_zip.css', $options);
$vik->addStyleSheet(VAPASSETS_URI . 'css/input-select.css');

/**
 * Load custom CSS file.
 * 
 * @since 1.3.1
 */
JHtml::fetch('vaphtml.assets.customcss');

// since jQuery is a required dependency, the framework should be 
// invoked even if jQuery is disabled
$vik->loadFramework('jquery.framework');

// load dependencies

JHtml::fetch('vaphtml.assets.select2');

/**
 * Auto set CSRF token to ajaxSetup so all jQuery ajax call will contain CSRF token.
 *
 * @since 1.3
 */
JHtml::fetch('vaphtml.sitescripts.ajaxcsrf');

// get module data

$services  = VikAppointmentsZipCheckerHelper::getServices($params);
$employees = array();

$group = reset($services);
		
if ($services && $group['list'])
{
	$service = $group['list'][0];

	if ($service['choose_emp'])
	{
		$employees = VikAppointmentsZipCheckerHelper::getEmployees($service['id']);
	}
}

$module_id = VikAppointmentsZipCheckerHelper::getID($module);

// load specified layout

require JModuleHelper::getLayoutPath('mod_vikappointments_zip', $params->get('layout'));
