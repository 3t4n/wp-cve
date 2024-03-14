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
 * VikAppointments services working days view.
 *
 * @since 1.0
 */
class VikAppointmentsViewserworkdays extends JViewVAP
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

		$filters = array();
		$filters['id_service']  = $input->getUint('id_service', 0);
		$filters['id_employee'] = $input->getUint('id_employee', 0);
		$filters['status'] 	    = $app->getUserStateFromRequest('vapserwd.status', 'status', -1, 'int');
		$filters['type']	    = $app->getUserStateFromRequest('vapserwd.type', 'type', -1, 'int');
		$filters['date']	    = $app->getUserStateFromRequest('vapserwd.date', 'date', '', 'string');
		
		// get service name
		$q = $dbo->getQuery(true)
			->select($dbo->qn('name'))
			->from($dbo->qn('#__vikappointments_service'))
			->where($dbo->qn('id') . ' = ' . $filters['id_service']);

		$dbo->setQuery($q, 0, 1);
		$serviceName = $dbo->loadResult();

		if (!$serviceName)
		{
			// service not found
			$app->redirect('index.php?option=com_vikappointments&view=services');
		}

		// Set the toolbar
		$this->addToolBar($serviceName);

		// get employees list
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('e.id', 'e.nickname')))
			->from($dbo->qn('#__vikappointments_employee', 'e'))
			->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('a.id_employee'))
			->where($dbo->qn('a.id_service') . ' = ' . $filters['id_service'])
			->order($dbo->qn('a.ordering') . ' ASC');
		
		$dbo->setQuery($q);
		$employees = $dbo->loadObjectList();

		if (!$employees)
		{
			// no employees for this service
			$app->enqueueMessage(JText::translate('VAPNOSERWORKDAYSERR'), 'error');
			$app->redirect('index.php?option=com_vikappointments&task=service.edit&cid[]=' . $filters['id_service'] . '#service_assoc');
		}

		if (empty($filters['id_employee']))
		{
			$filters['id_employee'] = $employees[0]->id;
		}

		// get working days

		$rows = array();

		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$today = strtotime('00:00:00', VikAppointments::now());

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS *')
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where(array(
				$dbo->qn('id_employee') . ' = ' . $filters['id_employee'],
				$dbo->qn('id_service') . ' = ' . $filters['id_service'],
			))
			->order(array(
				$dbo->qn('ts') . ' ASC',
				$dbo->qn('day') . ' ASC',
				$dbo->qn('fromts') . ' ASC',
				$dbo->qn('closed') . ' ASC',
			));

		if ($filters['status'] != -1)
		{
			$q->where($dbo->qn('closed') . ' <> ' . $filters['status']);
		}

		if ($filters['type'] != -1)
		{
			$cmp = ($filters['type'] != 1 ? '<>' : '=');

			$q->where($dbo->qn('ts') . " $cmp -1");
		}

		if ($filters['date'])
		{
			// get date filter
			$date = VAPDateHelper::getDate($filters['date']);

			$q->andWhere(array(
				$dbo->qn('day') . ' = ' . (int) $date->format('w') . ' AND ' . $dbo->qn('ts') . ' = -1',
				$dbo->qn('tsdate') . ' = ' . $dbo->q($date->toSql()),
			), 'OR');

			// unset limits
			$lim0 = 0;
			$lim  = null;
		}
		else
		{
			// do not show dates in the past
			$q->andWhere(array(
				$dbo->qn('ts') . ' = -1',
				$dbo->qn('ts') . ' >= ' . $today,
			), 'OR');
		}

		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();

		// assert limit used for list query
		$this->assertListQuery($lim0, $lim);

		if ($dbo->getNumRows())
		{
			$rows = $dbo->loadAssocList();

			if ($lim)
			{
				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$pageNav = new JPagination($dbo->loadResult(), $lim0, $lim);
				$navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);
			}
		}

		if ($filters['date'])
		{
			$flag = false;

			foreach ($rows as $r)
			{
				// look for a special working day
				$flag = $flag || $r['ts'] != -1;
			}

			if ($flag)
			{
				// exclude recurring working days
				$rows = array_values(array_filter($rows, function($r)
				{
					return $r['ts'] != -1;
				}));
			}
		}

		$this->rows      = $rows;
		$this->navbut    = $navbut;
		$this->employees = $employees;
		$this->filters   = $filters;
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($name)
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::sprintf('VAPSERWORKDAYSTITLE', $name), 'vikappointments');
	
		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=services');

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('serworkday.add', JText::translate('VAPNEW'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('serworkday.edit', JText::translate('VAPEDIT'));
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'serworkday.delete', JText::translate('VAPDELETE'));
			JToolBarHelper::custom('serworkday.restore', 'refresh', 'refresh', JText::translate('VAPRESTORE'), false);
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return $this->filters['type'] != -1
			|| $this->filters['status'] != -1
			|| $this->filters['date'];
	}
}
