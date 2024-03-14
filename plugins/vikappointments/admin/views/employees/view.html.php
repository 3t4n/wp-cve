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
 * VikAppointments employees view.
 *
 * @since 1.0
 */
class VikAppointmentsViewemployees extends JViewVAP
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

		// set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['keys']     = $app->getUserStateFromRequest('vapemployees.keys', 'keys', '', 'string');
		$filters['status']   = $app->getUserStateFromRequest('vapemployees.status', 'status', '', 'string');
		$filters['id_group'] = $app->getUserStateFromRequest('vapemployees.group', 'id_group', -1, 'int');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest('vapemployees.ordering', 'filter_order', 'e.id', 'string');
		$this->orderDir = $app->getUserStateFromRequest('vapemployees.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS `e`.*')
			->select(array($dbo->qn('g.id', 'gid'), $dbo->qn('g.name', 'gname')))
			->from($dbo->qn('#__vikappointments_employee', 'e'))
			->leftjoin($dbo->qn('#__vikappointments_employee_group', 'g') . ' ON ' . $dbo->qn('e.id_group') . ' = ' . $dbo->qn('g.id'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		/**
		 * Added status filter.
		 *
		 * @since 1.7
		 */
		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('e.listable') . ' = ' . (int) $filters['status']);
		}

		if ($filters['id_group'] != -1)
		{
			$cmp = $filters['id_group'] == 0 ? '<=' : '=';

			$q->where($dbo->qn('e.id_group') . ' ' . $cmp . ' ' . $filters['id_group']);
		}

		if (strlen($filters['keys']))
		{
			$key = $dbo->q("%{$filters['keys']}%");

			$where = array(
				$dbo->qn('e.firstname') . ' LIKE ' . $key,
				$dbo->qn('e.lastname') . ' LIKE ' . $key,
				$dbo->qn('e.email') . ' LIKE ' . $key,
			);

			$sprintf = 'CONCAT_WS(\' \', %s, %s) LIKE %s';

			/**
			 * Search also by full name (first + last and last + first).
			 *
			 * @since 1.7
			 */
			$where[] = sprintf($sprintf, $dbo->qn('e.firstname'), $dbo->qn('e.lastname'), $key);
			$where[] = sprintf($sprintf, $dbo->qn('e.lastname'), $dbo->qn('e.firstname'), $key);

			$q->andWhere($where, 'OR');
		}

		/**
		 * Add support for manipulating query through the plugins.
		 *
		 * @see 	/site/helpers/libraries/mvc/view.php @ JViewVAP::onBeforeListQuery()
		 *
		 * @since 	1.6.6
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
				'employee',
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
	 * @return 	void
	 */
	protected function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWEMPLOYEES'), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('employee.add', JText::translate('VAPNEW'));
			JToolBarHelper::custom('employee.duplicate', 'copy', 'copy', JText::translate('VAPCLONE'), true);
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('employee.edit', JText::translate('VAPEDIT'));
		}

		if ($user->authorise('core.access.analytics.employees', 'com_vikappointments'))
		{
			JToolBarHelper::custom('reportsemp', 'bars', 'bars', JText::translate('VAPREPORTS'), true);
		}

		if ($user->authorise('core.create', 'com_vikappointments')
			&& $user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			JToolBarHelper::custom('closure.add', 'unpublish', 'unpublish', JText::translate('VAPBLOCK'));
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'employee.delete', JText::translate('VAPDELETE'));
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::custom('import', 'upload', 'upload', JText::translate('VAPIMPORT'), false);
		}

		JToolBarHelper::custom('export', 'download', 'download', JText::translate('VAPEXPORT'), false);
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return strlen($this->filters['status'])
			|| $this->filters['id_group'] != -1;
	}
}
