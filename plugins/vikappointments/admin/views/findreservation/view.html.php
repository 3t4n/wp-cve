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
 * VikAppointments reservation availability view.
 *
 * @since 1.0
 */
class VikAppointmentsViewfindreservation extends JViewVAP
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
		$config = VAPFactory::getConfig();

		// get view model
		$model = JModelVAP::getInstance('findreservation');

		// set the toolbar
		$this->addToolBar();

		$filters = array();
		
		$filters['id_emp'] = $app->getUserStateFromRequest($this->getPoolName() . '.id_emp', 'id_emp', 0, 'uint');
		$filters['id_ser'] = $app->getUserStateFromRequest($this->getPoolName() . '.id_ser', 'id_ser', 0, 'uint');

		$filters['search_mode'] = $app->getUserStateFromRequest($this->getPoolName() . '.searchmode', 'searchmode', null, 'uint');

		$filters['id_res'] = $input->getUint('id_res', 0);
		$filters['day']    = $input->getString('day', '');
		$filters['people'] = $input->getUint('people', 1);

		if ($filters['day'])
		{
			// rebuild date into military format and strip the time
			$filters['day'] = JDate::getInstance($filters['day'])->format('Y-m-d');
		}

		// get search mode stored within the configuration
		$stored_search_mode = $config->getUint('findresmode');

		if (empty($filters['search_mode']))
		{
			// use default one stored in config
			$filters['search_mode'] = $stored_search_mode;
		}
		else if ($stored_search_mode != $filters['search_mode'])
		{
			// search mode as changed, update configuration
			$config->set('findresmode', $filters['search_mode']);
		}
		
		$employees = array();
		$services  = array();
		
		// search by employee > service
		if ($filters['search_mode'] == 1)
		{
			// get employees (sorted by the total number of assigned reservations)
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('e.id', 'e.nickname')))
				->from($dbo->qn('#__vikappointments_employee', 'e'))
				->leftjoin($dbo->qn('#__vikappointments_reservation', 'r') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'))
				->group($dbo->qn('e.id'))
				->order('COUNT(' . $dbo->qn('r.id') . ') DESC')
				->order($dbo->qn('e.id') . ' ASC');
	
			$dbo->setQuery($q);
			$employees = $dbo->loadObjectList();

			if (!$filters['id_emp'])
			{
				// use first available employee
				$filters['id_emp'] = $employees[0]->id;
			}

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
		}
		// search by service > employee
		else
		{
			// get services
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('s.id', 's.name')))
				->from($dbo->qn('#__vikappointments_service', 's'))
				->order(array(
					$dbo->qn('s.published') . ' DESC',
					$dbo->qn('s.ordering') . ' ASC',
				));

			$dbo->setQuery($q);
			$services = $dbo->loadObjectList();

			if (!$filters['id_ser'])
			{
				// use first available service
				$filters['id_ser'] = $services[0]->id;
			}

			// get service model
			$serModel = JModelVAP::getInstance('service');
			
			// get assigned employees
			$employees = $serModel->getEmployees($filters['id_ser']);

			if ($employees)
			{
				/**
				 * After switching service, we can face this scenario
				 * - id_service = X
				 * - id_employee = Y
				 *
				 * then:
				 * - id_employee = Y
				 * - id_service = Z
				 *
				 * Since the employee ID is not changed, we need to make
				 * sure that it is supported by the selected service.
				 * If it isn't, we need to unset the selected employee.
				 *
				 * @since 1.6 
				 */
				if ($filters['id_emp'] && !$this->isSupported($filters['id_emp'], $employees))
				{
					$filters['id_emp'] = 0;
				}

				if (!$filters['id_emp'] && count($employees) == 1)
				{
					// if the list contains only one employee, use it in
					// place of the empty option to ignore the employee
					$filters['id_emp'] = $employees[0]->id;
				}
			}
		}

		/**
		 * In case both the employee and the service have
		 * been specified, look for the correct overrides.
		 *
		 * @since 1.7
		 */
		if ($filters['id_emp'] && $filters['id_ser'])
		{
			// get service-employee association model
			$assocModel = JModelVAP::getInstance('serempassoc');
			// get service-employee overrides
			$this->override = $assocModel->getOverrides($filters['id_ser'], $filters['id_emp']);
		}
		else if ($filters['id_ser'])
		{
			// get service model
			$serModel = JModelVAP::getInstance('service');
			// get service details
			$this->override = $serModel->getItem($filters['id_ser']);
		}
		
		$this->services  = $services;
		$this->employees = $employees;

		// fetch calendar by using the view model
		$this->calendar = $model->getCalendar($filters);

		$this->filters = $filters;

		if ($filters['id_res'] > 0)
		{
			VAPLoader::import('libraries.order.factory');

			try
			{
				// load details of the appointment that we are editing
				$this->appointment = VAPOrderFactory::getAppointments($filters['id_res'], JFactory::getLanguage()->getTag());
				// take only the details of the first appointment
				$this->appointment = array_shift($this->appointment->appointments);
			}
			catch (Exception $e)
			{
				// appointment not found, suppress error
			}
		}

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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEFINDRESERVATION'), 'vikappointments');
		
		JToolBarHelper::cancel('reservation.cancel');
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
