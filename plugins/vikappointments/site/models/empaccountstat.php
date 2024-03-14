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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments employee area account statistics view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpaccountstat extends JModelVAP
{
	/**
	 * The customer model instance.
	 *
	 * @var JModel
	 */
	private $customerModel;

	/**
	 * Loads the account details of the employee.
	 *
	 * @return 	array
	 */
	public function getAccountData()
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$data = array(
			'active' => array(),
		);

		if ($auth->active_to == -1)
		{
			// lifetime license
			$data['active']['value'] = JText::translate('VAPACCOUNTVALIDTHRU1');
			$data['active']['class'] = 'active';    
		}
		else if ($auth->active_to == 0)
		{
			// pending license
			$data['active']['value'] = JText::translate('VAPACCOUNTVALIDTHRU2');
			$data['active']['class'] = 'pending'; 
		}
		else
		{
			// format expiration date
			$data['active']['value'] = JHtml::fetch('date', $auth->active_to_date, JText::translate('DATE_FORMAT_LC1'), $auth->timezone);
			
			$now = JFactory::getDate();

			// check whether the employee is still active
			if ($now->toSql() > $auth->active_to_date)
			{
				// expired subscription
				$data['active']['class'] = 'expired';
			}
			else
			{
				// calculate remaining days to expiration date
				$days = VAPDateHelper::diff($auth->active_to_date, $now, 'days');

				if ($days <= 7)
				{
					// close to expiration
					$data['active']['class'] = 'pending';
				}
				else
				{
					// subscription active
					$data['active']['class'] = 'active';
				}
			}
		}

		// register first activation date
		if (!VAPDateHelper::isNull($auth->active_since))
		{
			$data['active']['since'] = JHtml::fetch('date', $auth->active_since, JText::translate('DATE_FORMAT_LC1'), $auth->timezone);
		}
		else
		{
			$data['active']['since'] = null;
		}

		// fetch appointments data
		$data['appointments'] = $this->getAppointmentsData($auth);

		return $data;
	}

	/**
	 * Fetches the overall appointments details.
	 *
	 * @param 	VAPEmployeeAuth  $auth
	 *
	 * @return 	void
	 */
	protected function getAppointmentsData(VAPEmployeeAuth $auth)
	{
		$dbo = JFactory::getDbo();

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1)); 

		$inner = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_reservation', 'i'))
			->where($dbo->qn('i.id_employee') . ' = ' . $auth->id);

		$q = $dbo->getQuery(true)
			->select('COUNT(1) AS ' . $dbo->qn('confirmed'))
			->select('SUM(' . $dbo->qn('r.total_cost') . ') AS ' . $dbo->qn('total'))
			->select('SUM(' . $dbo->qn('r.tot_paid') . ') AS ' . $dbo->qn('totalpaid'))
			->select('(' . $inner . ') AS ' . $dbo->qn('count'))
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->where($dbo->qn('r.id_employee') . ' = ' . $auth->id)
			->where($dbo->qn('r.id_parent') . ' > 0');

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		$dbo->setQuery($q);

		if ($data = $dbo->loadAssoc())
		{
			return $data;
		}

		return array(
			'confirmed' => 0,
			'total'     => 0,
			'totalpaid' => 0,
			'count'     => 0,
		);
	}

	/**
	 * Loads a list of customers to be displayed within the
	 * employees area view.
	 *
	 * @param 	array  &$filters  An array of filters.
	 * @param 	array  &$options  An array of options, such as the ordering mode.
	 *
	 * @return 	array  A list of customers.
	 */
	public function getCustomers(array &$filters = array(), array &$options = array())
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// search customers through model
		$this->customerModel = JModelVAP::getInstance('customer');

		// take only the customers assigned to this employee
		$options['id_employee'] = $auth->id;

		// load customers
		return array_values($this->customerModel->search($filters['search'], $options));
	}

	/**
	 * Returns the list pagination.
	 *
	 * @param 	array  $filters  An array of filters.
	 * @param 	array  $options  An array of options.
	 *
	 * @return  JPagination
	 */
	public function getPagination(array $filters = array(), array $options = array())
	{
		if (!$this->customerModel)
		{
			throw new RuntimeException('Model not set', 500);
		}

		return $this->customerModel->getPagination($filters, $options);
	}

	/**
	 * Returns the total number of employees matching the search query.
	 *
	 * @return 	integer
	 */
	public function getTotal()
	{
		if (!$this->customerModel)
		{
			throw new RuntimeException('Model not set', 500);
		}

		return $this->customerModel->getTotal();
	}
}
