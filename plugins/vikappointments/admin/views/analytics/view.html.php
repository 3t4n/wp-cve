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
 * VikAppointments analytics view.
 *
 * @since 1.7
 */
class VikAppointmentsViewanalytics extends JViewVAP
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

		// get location
		$this->location = $input->get('location', null, 'string');

		switch ($this->location)
		{
			case 'finance':
			case 'appointments':
			case 'services':
			case 'employees':
			case 'customers':
			case 'packages':
			case 'subscriptions':
				// supported locations
				break;

			default:
				throw new DomainException(sprintf('Analytic location [%s] is not supported', $this->location), 404);
		}
		
		// set the toolbar
		$this->addToolBar();

		VAPLoader::import('libraries.statistics.factory');

		// load active widgets
		$this->dashboard = VAPStatisticsFactory::getDashboard($this->location);
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	private function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWANALYTICS' . strtoupper($this->location)), 'vikappointments');

		// add button to manage the widgets
		JToolbarHelper::addNew('analytics.add', JText::translate('VAP_TOOLBAR_NEW_WIDGET'));
	}
}
