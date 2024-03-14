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
 * VikAppointments user profile view.
 *
 * @since 1.4
 */
class VikAppointmentsViewuserprofile extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->itemid = $app->input->getInt('Itemid', 0);

		if ($user->guest)
		{
			// not logged in, back to all orders view
			$app->redirect(JRoute::rewrite('index.php?option=com_vikappointments&view=allorders', false));
			exit;
		}
		
		// get customer details
		$this->customer = VikAppointments::getCustomer();

		if (!$this->customer)
		{
			// create new empty customer object
			$this->customer = new stdClass;
		}

		/**
		 * Use default country code for country dropdown.
		 *
		 * @since 1.6.3
		 */
		if (empty($this->customer->country_code))
		{
			VAPLoader::import('libraries.customfields.loader');
			// $langtag is NULL to auto-detect the current lang tag;
			// $default is FALSE to avoid obtainining a default value (US).
			$this->customer->country_code = VAPCustomFieldsLoader::getDefaultCountryCode($langtag = null, $default = false);
		}
		
		// display the template
		parent::display($tpl);
	}

	/**
	 * Checks whether the specified field should be displayed or not.
	 *
	 * @param 	string 	 $field  The field to check.
	 *
	 * @return  boolean  True whether it should be displayed, false otherwise.
	 *
	 * @since 	1.7
	 */
	function shouldDisplayField($field)
	{
		static $allowed = null;

		if (is_null($allowed))
		{
			// define the default list of allowed fields
			$allowed = array(
				'avatar'   => true,
				'country'  => true,
				'state'    => true,
				'city'     => true,
				'address'  => true,
				'address2' => true,
				'zip'      => true,
				'company'  => true,
				'vatnum'   => true,
				'ssn'      => true,
			);

			/**
			 * Trigger hook to toggle the visibility of certain fields.
			 * In example, by using the code below, the user profile
			 * won't display the SSN field anymore.
			 *
			 * $allowed['ssn'] = false;
			 *
			 * The following fields cannot be disabled: name, mail, phone.
			 * 
			 * Assigning new attributes to the array will have no effect.
			 *
			 * @param 	array   &$allowed  An array of allowed fields.
			 * @param 	object  $customer  The customer details.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			VAPFactory::getEventDispatcher()->trigger('onToggleUserProfileFields', array(&$allowed, $this->customer));
		}

		// check whether the specified field is allowed or not
		return !empty($allowed[$field]);
	}
}
