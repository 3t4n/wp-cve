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
 * VikAppointments all orders (profile) view.
 *
 * @since 1.4
 */
class VikAppointmentsViewallorders extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$this->user = JFactory::getUser();
		
		if (!$this->user->guest)
		{
			$options = array();
			$options['start'] = $app->getUserStateFromRequest('allorders.limitstart', 'limitstart', 0, 'uint');
			$options['limit'] = 5;
			
			/**
			 * Load orders through the view model.
			 *
			 * @since 1.7
			 */
			$model = JModelVAP::getInstance('allorders');

			// load latest orders
			$this->orders = $model->getItems($options);

			if ($this->orders)
			{
				// get pagination HTML
				$this->navbut = $model->getPagination()->getPagesLinks();
			}
			else
			{
				$this->navbut = '';
			}

			// get customer details
			$this->customer = VikAppointments::getCustomer();

			if ($this->customer)
			{
				// check if the customer owns at least a package order
				$this->hasPackages = $model->hasPackages($this->customer->id);

				// check if the customer owns at least a subscription order
				$this->hasSubscriptions = $model->hasSubscriptions($this->customer->id);
			}
			else
			{
				$this->hasPackages = $this->hasSubscriptions = false;
			}
		}
		else
		{
			// user not logged in, use the login/registration layout
			$tpl = 'login';
		}
		
		$this->itemid = $input->getUint('Itemid', 0);
		
		// prepare page content
		VikAppointments::prepareContent($this);
		
		// display the template
		parent::display($tpl);
	}
}
