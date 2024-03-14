<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_services
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
	'version' => '1.4.2',
);

$vik = VAPApplication::getInstance();

/**
 * Load CSS environment.
 * 
 * @since 1.4.1
 */
JHtml::fetch('vaphtml.assets.environment');

$vik->addStyleSheet(VAPMODULES_URI . 'mod_vikappointments_services/mod_vikappointments_services.css', $options);
$vik->addStyleSheet(VAPMODULES_URI . 'mod_vikappointments_services/src/owl.carousel.min.css', $options);
$vik->addStyleSheet(VAPMODULES_URI . 'mod_vikappointments_services/src/owl.theme.min.css', $options);
$vik->addStyleSheet(VAPASSETS_URI . 'css/input-select.css');

/**
 * Load custom CSS file.
 * 
 * @since 1.4.1
 */
JHtml::fetch('vaphtml.assets.customcss');

// since jQuery is a required dependency, the framework should be 
// invoked even if jQuery is disabled
$vik->loadFramework('jquery.framework');

$vik->addScript(VAPASSETS_URI . 'js/jquery-ui.min.js');
$vik->addScript(VAPMODULES_URI . 'mod_vikappointments_services/src/owl.carousel.min.js', $options);

// get services to display

$services = VikAppointmentsServicesModuleHelper::getServices($params);

$module_id = VikAppointmentsServicesModuleHelper::getID($module);

// load specified layout

require JModuleHelper::getLayoutPath('mod_vikappointments_services', $params->get('layout'));
