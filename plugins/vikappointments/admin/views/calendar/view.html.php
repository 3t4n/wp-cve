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
 * VikAppointments monthly calendar view.
 *
 * @since 1.0
 */
class VikAppointmentsViewcalendar extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$dbo = JFactory::getDbo();

		// get view model
		$model = JModelVAP::getInstance('calendar');

		// set the toolbar
		$this->addToolBar();

		$filters = array();

		$filters['id_emp'] = $app->getUserStateFromRequest('calendar.id_emp', 'id_emp', null, 'uint');
		$filters['id_ser'] = $app->getUserStateFromRequest('calendar.id_ser', 'id_ser', null, 'uint');
		$filters['year']   = $app->getUserStateFromRequest('calendar.year', 'year', null, 'uint');
		
		if (empty($filters['year']))
		{
			// use current year
			$filters['year'] = (int) JHtml::fetch('date', 'now', 'Y');
		}

		$employees = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('e.id', 'e.nickname')))
			->from($dbo->qn('#__vikappointments_employee', 'e'))
			->leftjoin($dbo->qn('#__vikappointments_reservation', 'r') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'))
			->group($dbo->qn('e.id'))
			->order('COUNT(' . $dbo->qn('r.id') . ') DESC');

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			$employees = $dbo->loadObjectList();

			if (!$filters['id_emp'])
			{
				// use first available employee
				$filters['id_emp'] = $employees[0]->id;
			}
		}
		
		$services = array();

		// get employee model
		$empModel = JModelVAP::getInstance('employee');
			
		// get assigned services
		$services = $empModel->getServices($filters['id_emp']);

		if ($services)
		{
			/**
			 * After switching search mode, we can face this scenario:
			 * - id_service = X
			 * - id_employee = ALL EMPLOYEES
			 *
			 * is switched to:
			 * - id_employee = Y (the first employee available)
			 * - id_service = X
			 *
			 * Since the service ID is not changed, we need to make
			 * sure that it is supported by the selected employee.
			 * If it isn't, we need to use the first available service.
			 *
			 * @since 1.6 
			 */
			if (!$filters['id_ser'] || !$this->isSupported($filters['id_ser'], $services))
			{
				// use first available service
				$filters['id_ser'] = $services[0]->id;
			}
		}
		
		$this->services  = $services;
		$this->employees = $employees;

		// fetch calendar by using the view model
		$this->calendar = $model->getCalendar($filters);

		$this->filters = $filters;

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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWCALENDAR'), 'vikappointments');
	}

	/**
	 * Checks if the specified service/employee is contained within the list.
	 *
	 * @param 	integer  $needle 	The service/employee to search.
	 * @param 	array 	 $haystack 	The haystack.
	 *
	 * @return 	boolean  True if supported, otherwise false.
	 *
	 * @since 	1.6
	 */
	protected function isSupported($needle, array $haystack)
	{
		foreach ($haystack as $tmp)
		{
			if ($tmp->id == $needle)
			{
				return true;
			}
		}

		return false;
	}
}
