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
 * VikAppointments reservation management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagereservation extends JViewVAP
{
	/**
	 * Flag for multi-order layout.
	 *
	 * @var boolean
	 */
	public $multiOrder = false;

	/**
	 * VikAppointments view display method.
	 *
	 * @return void
	 */
	function display($tpl = null)
	{	
		$app   = JFactory::getApplication();
		$input = $app->input;
		$dbo   = JFactory::getDbo();
		
		$ids  = $input->getUint('cid', array());
		$type = $ids ? 'edit' : 'new';
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true);

			// select reservation columns
			$q->select('r.*');
			$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

			// select employee columns
			$q->select(array(
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('e.timezone'),
				$dbo->qn('e.notify'),
			));
			$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'));

			// select service columns
			$q->select(array(
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('s.duration', 'service_duration'),
			));
			$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));

			// select customer columns
			$q->select($dbo->qn('u.jid'));
			$q->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('r.id_user') . ' = ' . $dbo->qn('u.id'));

			// select author columns (not needed...)
			// $q->select(array(
			// 	$dbo->qn('j.name', 'author_name'),
			// 	$dbo->qn('j.username', 'author_username'),
			// ));
			// $q->leftjoin($dbo->qn('#__users', 'j') . ' ON ' . $dbo->qn('r.createdby') . ' = ' . $dbo->qn('j.id'));
			
			$q->where($dbo->qn('r.id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$reservation = $dbo->loadObject();
			
			if ($reservation)
			{
				if ($reservation->closure == 1)
				{
					// edit closure from the related page
					$app->redirect('index.php?option=com_vikappointments&task=closure.edit&cid[]=' . $reservation->id);
					exit;
				}

				// decode custom fields and uploads
				$reservation->custom_f  = (array) ($reservation->custom_f ? json_decode($reservation->custom_f, true) : []);
				$reservation->attendees = (array) ($reservation->attendees ? json_decode($reservation->attendees, true) : []);
				$reservation->uploads   = (array) ($reservation->uploads ? json_decode($reservation->uploads, true) : []);
				// merge them together
				$reservation->custom_f = array_merge($reservation->custom_f, $reservation->uploads);

				$reservation->options = [];
				$reservation->items   = [];
				
				if ($reservation->id_parent == -1)
				{
					$this->multiOrder = true;

					// get all appointments assigned to this parent order
					$q->clear('where');
					$q->clear('limit');
					$q->where($dbo->qn('r.id_parent') . ' = ' . $reservation->id);

					$dbo->setQuery($q);
					$reservation->items = $dbo->loadObjectList();
				}
				else
				{
					// load assigned options
					$q = $dbo->getQuery(true)
						->select('a.*')
						->select($dbo->qn('o.name'))
						->select($dbo->qn('v.name', 'var_name'))
						->from($dbo->qn('#__vikappointments_option', 'o'))
						->leftjoin($dbo->qn('#__vikappointments_res_opt_assoc', 'a') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('a.id_option'))
						->leftjoin($dbo->qn('#__vikappointments_option_value', 'v') . ' ON ' . $dbo->qn('v.id') . ' = ' . $dbo->qn('a.id_variation'))
						->where($dbo->qn('a.id_reservation') . ' = ' . $reservation->id)
						->order($dbo->qn('a.id') . ' ASC');

					$dbo->setQuery($q);
					$reservation->options = $dbo->loadObjectList();
				}
			}
		}

		$this->recalculate = false;

		if (empty($reservation))
		{
			$reservation = (object) $this->getBlankItem();

			// always calculate booking data while creating new reservations
			$this->recalculate = true;
		}
		else
		{
			// get reservation data stored within the user state
			$tmp = $app->getUserState('vap.reservation.data', array());

			// look for "day" attribute
			if (isset($tmp['day']))
			{
				// the user state contains "day" attribute, meaning
				// that we are editing the details of the appointment
				$this->recalculate = true;
			}
		}

		// use reservation data stored in user state
		$this->injectUserStateData($reservation, 'vap.reservation.data');

		if (!$this->multiOrder)
		{
			// get service details
			$service = JModelVAP::getInstance('serempassoc')->getOverrides($reservation->id_service, $reservation->id_employee);

			if (!$service)
			{
				throw new Exception('Employee/service relation not found.', 404);
			}

			$this->service = $service;

			if ($this->recalculate)
			{
				// update service name, duration and sleep time
				$reservation->service_name = $service->name;
				$reservation->duration     = $service->duration;
				$reservation->sleep        = $service->sleep;

				// get employee details
				$employee = JModelVAP::getInstance('employee')->getItem($reservation->id_employee);

				if (!$employee)
				{
					// employee not found
					throw new Exception(sprintf('Employee [%d] not found.', $reservation->id_employee), 404);
				}

				// update employee name, timezone and notifications flag
				$reservation->employee_name = $employee->nickname;
				$reservation->timezone      = $employee->timezone;
				$reservation->notify        = $employee->notify;

				// recalculate reservation totals
				$model = JModelVAP::getInstance('reservation');
				$model->recalculateTotals($reservation, $service);
			}

			// replicate reservation details within the items array
			$reservation->items = array($reservation);
		}
		else
		{
			$this->allServices = array();

			$map = array();

			// If multi order, we should get all the children orders
			// to check if there is only one employee.
			foreach ($reservation->items as $appointment)
			{
				// in case the employee has been already set, the last 'view_emp' will be taken
				$map[$appointment->id_employee] = $appointment->view_emp;

				if (!in_array($appointment->id_service, $this->allServices))
				{
					$this->allServices[] = $appointment->id_service;
				}
			}

			$employees = array_keys($map);

			// make sure there is only one employee
			if (count($employees) == 1)
			{
				// inject employee ID
				$reservation->id_employee = $employees[0];
				// inject 'view_emp'
				$reservation->view_emp = reset($map);
			}
		}

		// import custom fields renderer and loader (as dependency)
		VAPLoader::import('libraries.customfields.renderer');

		// get relevant custom fields only
		$fieldsLoader = VAPCustomFieldsLoader::getInstance()
			->translate()
			->noRequiredCheckbox();

		// check whether we should include the custom fields of the employee
		if ($reservation->view_emp && $reservation->id_employee > 0)
		{
			// include employee custom fields
			$fieldsLoader->ofEmployee($reservation->id_employee);
		}

		// check whether we should include the custom fields of the service(s)
		if (!empty($this->allServices))
		{
			// include all services assigned to the multi-order
			$fieldsLoader->forService($this->allServices);
		}
		else
		{
			// include the custom fields of the service
			$fieldsLoader->forService($reservation->id_service);
		}

		// load custom fields
		$this->customFields = $fieldsLoader->fetch();

		// get payments
		$payments_groups = 0;

		if (!empty($reservation->view_emp))
		{
			$payments_groups = $reservation->id_employee;
		}

		// load all the payments assigned to the selected employee
		$this->payments = VikAppointments::getAllEmployeePayments($payments_groups);

		/**
		 * Get all unpublished and global e-mail custom texts.
		 *
		 * @since 1.6.5
		 */
		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_cust_mail'))
			->where($dbo->qn('published') . ' = 0')
			->where($dbo->qn('id_employee') . ' IN (0, ' . (int) $reservation->id_employee . ')')
			->where($dbo->qn('id_service') . ' IN (0, ' . (int) $reservation->id_service . ')')
			->order($dbo->qn('id') . ' DESC');

		$dbo->setQuery($q);
		$templates = $dbo->loadObjectList();

		/**
		 * Load all notes assigned to this reservation.
		 *
		 * @since 1.7
		 */
		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_user_notes'))
			->where($dbo->qn('id_parent') . ' = ' . (int) $reservation->id)
			->where($dbo->qn('group') . ' = ' . $dbo->q('appointments'))
			->order(sprintf(
				'IFNULL(%s, %s) %s',
				$dbo->qn('modifiedon'),
				$dbo->qn('createdon'),
				'DESC'
			));

		$dbo->setQuery($q);
		$this->usernotes = $dbo->loadObjectList();
		
		$this->reservation   = $reservation;
		$this->mailTemplates = $templates;
		$this->from          = $input->get('from');

		// set the toolbar
		$this->addToolBar($type);

		// display the template
		parent::display($tpl);
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		// get blank item
		$item = JModelVAP::getInstance('reservation')->getItem(0, $blank = true);

		$item->custom_f  = array();
		$item->attendees = array();
		$item->options   = array();
		$item->jid       = 0;
		
		return $item;
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITRESERVATION'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWRESERVATION'), 'vikappointments');
		}

		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			if (!$this->multiOrder)
			{
				JToolbarHelper::apply('reservation.save', JText::translate('VAPSAVE'));
				JToolbarHelper::save('reservation.saveclose', JText::translate('VAPSAVEANDCLOSE'));
			}
			else
			{
				JToolbarHelper::apply('multiorder.save', JText::translate('VAPSAVE'));
				JToolbarHelper::save('multiorder.saveclose', JText::translate('VAPSAVEANDCLOSE'));
			}
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			if (!$this->multiOrder)
			{
				JToolbarHelper::save2new('reservation.savenew', JText::translate('VAPSAVEANDNEW'));
			}
		}
		
		JToolBarHelper::cancel('reservation.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
