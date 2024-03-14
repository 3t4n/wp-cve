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
 * VikAppointments analytics management view.
 *
 * @since 1.7
 */
class VikAppointmentsViewmanageanalytics extends JViewVAP
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

		// recover user state
		$data = $app->getUserState('vap.statistics.data', array());

		if (!empty($data['location']))
		{
			// get location from user state
			$this->location = $data['location'];
		}
		else
		{
			// get location from request
			$this->location = $input->get('location', 'dashboard', 'string');
		}

		switch ($this->location)
		{
			case 'dashboard':
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

		// get supported widgets
		$this->supported = VAPStatisticsFactory::getSupportedWidgets($this->location);

		// get supported positions
		$this->positions = VAPStatisticsFactory::getSupportedPositions($this->location);
		
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
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWDASHBOARD'), 'vikappointments');

		JToolbarHelper::apply('analytics.save', JText::translate('VAPSAVE'));
		JToolbarHelper::save('analytics.saveclose', JText::translate('VAPSAVEANDCLOSE'));

		JToolbarHelper::cancel('analytics.cancel');
	}
}
