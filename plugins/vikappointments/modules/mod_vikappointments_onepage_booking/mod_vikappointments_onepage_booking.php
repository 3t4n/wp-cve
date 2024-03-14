<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
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
	'version' => '1.0.7',
);

$vik = VAPApplication::getInstance();

// autoload component language
VikAppointments::loadLanguage(JFactory::getLanguage()->getTag());

// load CSS environment
JHtml::fetch('vaphtml.assets.environment');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik->addStyleSheet(VAPMODULES_URI . 'mod_vikappointments_onepage_booking/mod_vikappointments_onepage_booking.css', $options);
$vik->addStyleSheet(VAPASSETS_URI . 'css/jquery-ui.min.css');
$vik->addStyleSheet(VAPASSETS_URI . 'css/input-select.css');

// load custom CSS file
JHtml::fetch('vaphtml.assets.customcss');

// since jQuery is a required dependency, the framework should be 
// invoked even if jQuery is disabled
$vik->loadFramework('jquery.framework');

$vik->addScript(VAPASSETS_URI . 'js/jquery-ui.min.js');
$vik->addScript(VAPASSETS_URI . 'js/vikappointments.js');
$vik->addScript(VAPMODULES_URI . 'mod_vikappointments_onepage_booking/mod_vikappointments_onepage_booking.js', $options);

// load JS dependencies
JHtml::fetch('vaphtml.assets.utils');
JHtml::fetch('vaphtml.assets.currency');
JHtml::fetch('vaphtml.assets.select2');

// auto set CSRF token to ajaxSetup so all jQuery ajax call will contain CSRF token
JHtml::fetch('vaphtml.sitescripts.ajaxcsrf');

/**
 * Localize datepicker texts.
 * 
 * @since 1.0.1
 */
VikAppointments::load_datepicker_regional();

// make translations accessible via JS
JText::script('VAP_OPB_ANY_PLACEHOLDER');
JText::script('VAP_OPB_N_SEATS_REMAINING');
JText::script('VAP_OPB_N_SEATS_REMAINING_1');
JText::script('VAP_OPB_BOOK_NOW_BUTTON');
JText::script('VAP_OPB_BOOKED_BUTTON');
JText::script('VAP_OPB_CANCEL_BUTTON');
JText::script('VAP_OPB_ASK_CANCEL_CONFIRM');
JText::script('VAP_OPB_GENERIC_ERROR');
// load translations from component
JText::script('VAP_N_PEOPLE');
JText::script('VAP_N_PEOPLE_1');
JText::script('VAPCHECKOUTAT');
JText::script('VAPFINDRESNOLONGERAVAILABLE');

// get module data

$module_id = VikAppointmentsOnepageBookingHelper::getID($module);

// register maximum width CSS rule for this module only
JFactory::getDocument()->addStyleDeclaration('#vap-opb-container' . $module_id . ' { max-width: ' . $params->get('max_width', '500px') . '; }');

// get cart instance
$cart = VikAppointmentsOnepageBookingHelper::getCart();

$cartItems = $cart->getItemsList();

// get list of supported services
$groups = VikAppointmentsOnepageBookingHelper::getServices();

if ($groups)
{
	// fetch first group
	$group = reset($groups);

	// the first service is always pre-selected
	$selectedService = $group->services[0];

	// get list of supported employees
	$employees = VikAppointmentsOnepageBookingHelper::getEmployees($selectedService->id);
}
else
{
	$employees = [];

	$selectedService = null;
}

// check if all the services booked owns the same employee
$same_emp_id = VAPCartUtils::isSameEmployee($cartItems);
// obtain the list of all the services booked
$services_id = VAPCartUtils::getServices($cartItems);

if ($same_emp_id)
{
	// we can obtain the custom payments of the booked employee (just take the first one)
	$id_employee = $cartItems[0]->getEmployeeID();
}
else
{
	// the cart owns different employees (or maybe a single one hidden), we
	// need to get the global payments (use null to exclude the employee filter)
	$id_employee = null;
}

// import custom fields renderer and loader (as dependency)
VAPLoader::import('libraries.customfields.renderer');

// force the custom fields to load the layout from the default site folder
VAPCustomFieldsRenderer::setLayoutPath(VAPBASE . DIRECTORY_SEPARATOR . 'layouts');

// get all custom fields for the selected services
$cf = VAPCustomFieldsLoader::getInstance()
	->translate()
	->setLanguageFilter()
	->forService($services_id);

if ($same_emp_id)
{
	// obtain custom fields of the specified employee
	$cf->ofEmployee($id_employee);
}

// fetch custom fields
$customFields = $cf->fetch();

// get customer details of currently logged-in user
$user = VikAppointments::getCustomer();

// get configuration
$config = VAPFactory::getConfig();

// checks whether we have a ZIP Code to validate
$zipFieldID = VikAppointments::getZipCodeValidationFieldId($id_employee, $services_id);

// always refresh remaining user credit
$cart->removeDiscount('credit');

if ($user && $user->credit > 0)
{
	// register user credit as discount
	$cart->addDiscount(new VAPCartDiscount('credit', $user->credit, $percent = false));
}

$cart->store();

// load specified layout

require JModuleHelper::getLayoutPath('mod_vikappointments_onepage_booking', $params->get('layout'));
