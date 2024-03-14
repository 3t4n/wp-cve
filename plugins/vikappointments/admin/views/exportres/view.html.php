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
 * VikAppointments appointments export view.
 *
 * @since 1.1
 */
class VikAppointmentsViewexportres extends JViewVAP
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
		$dbo   = JFactory::getDbo();

		// get type set in request
		$data = new stdClass;
		$data->type        = $input->get('type', 'appointment', 'string');
		$data->cid         = $input->get('cid', array(), 'uint');
		$data->fromdate    = $input->get('fromdate', '', 'string');
		$data->todate      = $input->get('todate', '', 'string');
		$data->id_employee = $input->get('id_employee', 0, 'uint');

		// retrieve data from user state
		$this->injectUserStateData($data, 'vap.exportres.data');
		
		// set the toolbar
		$this->addToolBar($data->type);

		VAPLoader::import('libraries.order.export.factory');

		// get supported drivers
		$drivers = VAPOrderExportFactory::getSupportedDrivers($data->type);
		
		$this->data    = $data;
		$this->drivers = $drivers;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string 	$type  The export type.
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		JToolBarHelper::title(JText::translate('VAPMAINTITLEEXPORTRES'), 'vikappointments');
		
		switch ($type)
		{
			case 'appointment':
				$rule = 'core.access.reservations';
				break;

			default:
				$rule = 'core.admin';
		}

		if (JFactory::getUser()->authorise($rule, 'com_vikappointments'))
		{
			JToolbarHelper::custom('exportres.save', 'download', 'download', JText::translate('VAPDOWNLOAD'), false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('exportres.cancel');
	}
}
