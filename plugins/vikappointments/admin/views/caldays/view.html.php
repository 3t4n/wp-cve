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
 * VikAppointments weekly calendar view.
 *
 * @since 1.6
 */
class VikAppointmentsViewcaldays extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		$dbo 	= JFactory::getDbo();

		// get view model
		$model = JModelVAP::getInstance('caldays');

		$filters = array();

		$filters['layout'] = $input->getString('mode');

		if ($filters['layout'] == 'day')
		{
			// do not cache date in the user state and avoid employee filter
			$filters['date'] 	 = $input->getString('date', '');
			$filters['employee'] = 0;
		}
		else
		{
			$filters['date'] 	 = $app->getUserStateFromRequest('caldays.date', 'date', '', 'string');
			$filters['employee'] = $app->getUserStateFromRequest('caldays.employee', 'employee', 0, 'uint');
		}

		$filters['services'] = $input->get('services', array(), 'uint');

		if ($input->getBool('employee_changed'))
		{
			// unset selected services every time the employee changes
			$filters['services'] = array();
		}

		// register filters
		$this->filters = $filters;

		// let the model prepares the calendar structure
		$this->calendar = $model->getCalendar($filters);

		// register services
		$this->services = $model->getServices($filters);

		// setup toolbar
		$this->addToolBar();

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 */
	protected function addToolBar()
	{
		// Add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWCALENDAR'), 'vikappointments');

		if ($this->filters['layout'] == 'day')
		{
			JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=caldays');
		}

		if (JFactory::getUser()->authorise('core.edit', 'com_vikappointments'))
		{
			if ($this->filters['employee'])
			{
				JToolBarHelper::custom('reportsemp', 'bars', 'bars', JText::translate('VAPREPORTS'), false);
			}

			JToolBarHelper::divider();
		}
	}
}
