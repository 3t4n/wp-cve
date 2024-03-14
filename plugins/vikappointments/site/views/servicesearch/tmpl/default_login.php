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

/**
 * Template file used to display a login/registration form
 * to allow the users to access their accounts.
 *
 * @since 1.6
 */

// build query string
$query = array(
	'Itemid'      => $this->itemid,
	'id_service'  => $this->idService,
	'id_employee' => $this->idEmployee,
	'date'        => $this->date,
	'month'       => $this->month,
);

// remove empty tags
$query = array_filter($query, function($elem)
{
	return !empty($elem) && (!is_numeric($elem) || $elem > 0);
});

$data = array(
	/**
	 * @param 	boolean  register 	True to enable the registration form, otherwise false.
	 * 								If not provided, the registration is disabled by default.
	 */
	'register' => JComponentHelper::getParams('com_users')->get('allowUserRegistration'),

	/**
	 * @param 	string  return 		The return URL used after the login.
	 *								The URL must be plain (non-routed).
	 */
	'return' => 'index.php?option=com_vikappointments&view=servicesearch' . ($query ? '&' . http_build_query($query) : ''),

	/**
	 * @param 	boolean  remember 	True to remember the user after the login (an authentication
	 * 								cookie will be created to avoid re-logging in from the browser used).
	 * 								False to allow the customers to choose to remember the login or not.
	 * 								If not provided, the remember option is disabled by default.
	 */
	'remember' => false,

	/**
	 * @param 	boolean  captcha 	True to use the reCAPTCHA within the registration form 
	 * 								to prevent bots to create mass accounts. False to disable captcha.
	 * 								If not provided, it will be used the value specified 
	 * 								in the configuration of com_users.
	 */
	// 'captcha' => true,

	/**
	 * @param 	boolean  gdpr 	  	True to place a disclaimer for GDPR European law, otherwise false.
	 * 								If not provided, the value will be retrived from the global configuration.
	 */
	// 'gdpr' => false,

	/**
	 * @param 	boolean  footer 	True to display the footer links to allow the users
	 * 								to recover the password and the name of the account.
	 * 								If not provided, the links are not displayed.
	 */
	'footer' => true,

	/**
	 * @param 	string  active 		The name of the active tab.
	 *								The accepted values are: "login" and "registration".
	 * 								If not provided, the login form will be active by default.
	 *								In case this value is set to "registration" and the "register"
	 * 								field is disabled, the active value will be reset to "login".
	 */
	// 'active' => 'login',
);

/**
 * The login form is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/login.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the login.php file on your template to create your own layout
 *
 * @since 1.6
 */
echo JLayoutHelper::render('blocks.login', $data);
