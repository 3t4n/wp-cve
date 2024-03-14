<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_cart
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Cart widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_CartLanguageHandler extends VikAppointmentsLanguageWidget
{
	/**
	 * Checks if exists a translation for the given string.
	 *
	 * @param 	string 	$string  The string to translate.
	 *
	 * @return 	string 	The translated string, otherwise null.
	 */
	public function translate($string)
	{
		$result = null;

		/**
		 * Translations go here.
		 * @tip Use 'TRANSLATORS:' comment to attach a description of the language.
		 */

		switch ($string)
		{
			/**
			 * Cart module.
			 */

			case 'VIKAPPOINTMENTS_CART_MODULE_TITLE':
				$result = __('VikAppointments Cart', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_CART_MODULE_DESCRIPTION':
				$result = __('Displays a widget that contains the appointments within the cart.', 'vikappointments');
				break;

			case 'VAP_CART_EXPAND':
				$result = __('Expand Items', 'vikappointments');
				break;

			case 'VAP_CART_EXPAND_DESC':
				$result = __('Choose whether the rows of the widget should be automatically expanded.<br ><b>No</b>, all the rows will be collapsed.<br /><b>Yes</b>, all the rows will be expanded (services and details).<br /><b>Only Services</b>, the services will be expanded and the inner details will be collapsed.', 'vikappointments');
				break;

			case 'VAP_CART_EXPAND_ONLY_SERVICES':
				$result = __('Only Services', 'vikappointments');
				break;

			case 'VAPMODCARTEMPTYERR':
				$result = __('Your cart is empty!', 'vikappointments');
				break;

			case 'VAPMODCARTEMPTY':
				$result = __('Empty', 'vikappointments');
				break;

			case 'VAPMODCARTCHECKOUT':
				$result = __('Checkout', 'vikappointments');
				break;

			case 'VAPMODCARTTOTALCOST':
				$result = __('Total Cost', 'vikappointments');
				break;

			case 'VAPMODCARTQUANTITYSUFFIX':
				$result = __('x', 'vikappointments');
				break;

			case 'VAPMODCARTSHORTMIN':
				$result = __('min.', 'vikappointments');
				break;
				
			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}
