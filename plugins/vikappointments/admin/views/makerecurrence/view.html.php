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
 * VikAppointments make recurrence view.
 *
 * @since 1.5
 */
class VikAppointmentsViewmakerecurrence extends JViewVAP
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

		$this->addToolBar();
		
		$ids = $input->getUint('cid', array(0));

		VAPLoader::import('libraries.order.factory');
		$order = VAPOrderFactory::getAppointments($ids[0], JFactory::getLanguage()->getTag());

		// get details of first appointment
		$this->order = $order->appointments[0];

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLEMAKERECURRENCE'), 'vikappointments');
		
		JToolBarHelper::cancel('reservation.cancel');
	}
}
