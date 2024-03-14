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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments modules helper controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerModules extends VAPControllerAdmin
{
	/**
	 * AJAX task used to validated the specified zip code
	 * for the given employee and service.
	 *
	 * @return 	void
	 */
	function validatezip()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
		
		$id_ser   = $input->getUint('id_ser', 0);
		$id_emp   = $input->getUint('id_emp', 0);
		$zip_code = $input->getString('zip', '');
		
		// validate ZIP code
		$valid = VikAppointments::validateZipCode($zip_code, $id_emp, $id_ser);
		
		// send result to caller
		$this->sendJSON($valid);
	}
	
	/**
	 * AJAX task used to return the list of employees
	 * assigned to the specified service.
	 *
	 * This task is used by the SEARCH module to obtain
	 * the employees after switching value from the services
	 * dropdown.
	 *
	 * @return 	void
	 */
	function serviceemployees()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
		
		$id_ser = $input->getUint('id_ser', 0);

		// get service model
		$model = $this->getModel('service');

		// load service details
		$service = $model->getItem($id_ser);

		if (!$service || !$service->choose_emp)
		{
			// do not load the employees
			$this->sendJSON([]);
		}

		// load all the available employees
		$employees = $model->getEmployees($id_ser, $strict = true);

		if ($employees)
		{
			// translate the employees
			VikAppointments::translateEmployees($employees);
		}
		
		// send employees to caller
		$this->sendJSON($employees);
	}

	/**
	 * AJAX task used to return the list of options assigned to the
	 * specified service.
	 *
	 * This task is used by the ONE PAGE BOOKING module to obtain
	 * the options after switching value from the services dropdown.
	 *
	 * @return 	void
	 * 
	 * @since 	1.7.3
	 */
	function serviceoptions()
	{
		$input = JFactory::getApplication()->input;

		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
		
		$id_ser = $input->getUint('id_ser', 0);
		$mode   = $input->getString('mode', 'array');

		// get service search view model
		$model = $this->getModel('servicesearch');

		// load options
		$options = $model->getOptions($id_ser);

		if ($mode === 'html')
		{
			if ($options)
			{
				// render options form
				$options = json_encode(JLayoutHelper::render('blocks.options', [
					'options' => $options,
				]));
			}
			else
			{
				// no available options
				$options = null;
			}
		}

		// send response to caller
		$this->sendJSON($options);
	}

	/**
	 * AJAX task used to return the list of services that
	 * belong to the specified group.
	 *
	 * This task is used by the EMPLOYEES FILTER module to 
	 * obtain the list of services after switching group.
	 *
	 * @return 	void
	 */
	function groupservices()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
		
		$id_group = $input->getUint('id_group', 0);
		
		$services = array();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name')))
			->from($dbo->qn('#__vikappointments_service'))
			->order($dbo->qn('ordering') . ' ASC');

		if ($id_group > 0)
		{
			$q->where($dbo->qn('id_group') . ' = ' . $id_group);
		}
		
		$dbo->setQuery($q);
		$services = $dbo->loadAssocList();
		VikAppointments::translateServices($services);
		
		// send response to caller
		$this->sendJSON($services);
	}

	/**
	 * AJAX task used to return the list of states that
	 * belong to the specified country.
	 *
	 * @return 	void.
	 */
	function countrystates()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$id_country = $input->getUint('id_country', 0);
		$states 	= VAPLocations::getStates($id_country, 'state_name');

		// send states to caller
		$this->sendJSON($states);
	}
	
	/**
	 * AJAX task used to return the list of cities that
	 * belong to the specified state.
	 *
	 * @return 	void.
	 */
	function statecities()
	{	
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$id_state = $input->getUint('id_state', 0);
		$cities   = VAPLocations::getCities($id_state, 'city_name');

		// send cities to caller
		$this->sendJSON($cities);
	}

	/**
	 * AJAX end-point used to fetch the availability timeline.
	 * This task expects the following arguments set in request.
	 *
	 * @param 	integer  $id_ser     The service ID.
	 * @param 	integer  $id_emp     The employee ID.
	 * @param 	string   $day        The check-in date.
	 * @param 	integer  $people     The number of participants.
	 * @param 	array    $locations  A list of selected locations.
	 *
	 * @return 	void
	 */
	public function timelineajax()
	{
		$input = JFactory::getApplication()->input;

		$args = array();
		$args['id_emp']    = $input->getUint('id_emp', 0);
		$args['id_ser']    = $input->getUint('id_ser', 0);
		$args['date'] 	   = $input->getString('day', '');
		$args['people']    = $input->getUint('people', 1);
		$args['locations'] = $input->getUint('locations', null);

		// get model
		$model = $this->getModel('employeesearch');
		// use model to create the timeline
		$timeline = $model->getTimeline($args);

		$result = new stdClass;

		if ($timeline)
		{
			// create timeline response
			$result->timeline = $timeline->getTimeline();

			// recalculate rate by specifing the selected arguments
			$result->rate = VAPSpecialRates::getRate($args['id_ser'], $args['id_emp'], $args['date'], $args['people']);
			// multiply by the number of selected participants
			$result->rate *= $args['people'];
		}
		else
		{
			// raise error message
			$result->error    = $model->getError($index = null, $string = true);
			$result->timeline = array();
		}

		// send timeline to caller
		$this->sendJSON($result);
	}
}
