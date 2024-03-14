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
 * VikAppointments methods of payment view.
 *
 * @since 1.0
 */
class VikAppointmentsViewpayments extends JViewVAP
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

		$filters = array();
		$filters['keys']   = $app->getUserStateFromRequest($this->getPoolName() . '.keys', 'keys', '', 'string');
		$filters['status'] = $app->getUserStateFromRequest($this->getPoolName() . '.status', 'status', '', 'string');
		$filters['type']   = $app->getUserStateFromRequest($this->getPoolName() . '.type', 'type', 0, 'uint');
		$filters['file']   = $app->getUserStateFromRequest($this->getPoolName() . '.file', 'file', '', 'string');

		$filters['id_employee'] = $input->get('id_employee', 0, 'uint');

		if ($filters['id_employee'])
		{
			$q = $dbo->getQuery(true)
				->select($dbo->qn('nickname'))
				->from($dbo->qn('#__vikappointments_employee'))
				->where($dbo->qn('id') . ' = ' . (int) $filters['id_employee']);

			$dbo->setQuery($q, 0, 1);
			$employee = $dbo->loadResult();

			if (!$employee)
			{
				throw new Exception(sprintf('Employee [%d] not found', $filters['id_employee']), 404);
			}
		}
		else
		{
			$employee = '';
		}

		// set the toolbar
		$this->addToolBar($employee);

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'p.ordering', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS p.*')
			->from($dbo->qn('#__vikappointments_gpayments', 'p'))
			->where($dbo->qn('p.id_employee') . ' = ' . $filters['id_employee'])
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('p.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('p.file') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('p.published') . ' = ' . (int) $filters['status']);
		}

		if ($filters['type'] == 1)
		{
			$q->where($dbo->qn('p.appointments') . ' = 1');
		}
		else if ($filters['type'] == 2)
		{
			$q->where($dbo->qn('p.subscr') . ' = 1');
		}

		if ($filters['file'])
		{
			$q->where($dbo->qn('p.file') . ' = ' . $dbo->q($filters['file']));
		}

		/**
		 * Add support for manipulating query through the plugins.
		 *
		 * @see 	/site/helpers/libraries/mvc/view.php @ JViewVAP::onBeforeListQuery()
		 *
		 * @since 	1.7
		 */
		$this->onBeforeListQuery($q);

		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();

		// assert limit used for list query
		$this->assertListQuery($lim0, $lim);

		if ($dbo->getNumRows())
		{
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination($dbo->loadResult(), $lim0, $lim);
			$navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);
		}

		if (VikAppointments::isMultilanguage())
		{
			$translator = VAPFactory::getTranslator();

			// find available translations
			$lang = $translator->getAvailableLang(
				'payment',
				array_map(function($row) {
					return $row['id'];
				}, $rows)
			);

			// assign languages found to the related elements
			foreach ($rows as $k => $row)
			{
				$rows[$k]['languages'] = isset($lang[$row['id']]) ? $lang[$row['id']] : array();
			}
		}

		$this->rows   = $rows;
		$this->navbut = $navbut;
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string 	$employee  The employee name, if not looking for global payments.
	 *
	 * @return 	void
	 */
	protected function addToolBar($employee = '')
	{
		// add menu title and some buttons to the page
		if ($employee)
		{
			// employee payments
			JToolBarHelper::title(JText::sprintf('VAPMAINTITLEVIEWEMPPAYMENTS', $employee), 'vikappointments');
		}
		else
		{
			// global payments
			JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWPAYMENTS'), 'vikappointments');
		}
		
		$user = JFactory::getUser();

		if ($employee)
		{
			JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=employees');
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('payment.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('payment.edit');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'payment.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return (strlen($this->filters['status'])
			|| $this->filters['type']
			|| $this->filters['file']);
	}
}
