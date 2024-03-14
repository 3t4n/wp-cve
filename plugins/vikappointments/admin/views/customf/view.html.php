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

VAPLoader::import('libraries.customfields.factory');

/**
 * VikAppointments custom fields view.
 *
 * @since 1.0
 */
class VikAppointmentsViewcustomf extends JViewVAP
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
		$filters['keys']   = $app->getUserStateFromRequest($this->getPoolName() . '.keys', 'keys', '', 'string');
		$filters['status'] = $app->getUserStateFromRequest($this->getPoolName() . '.status', 'status', '', 'string');
		$filters['type']   = $app->getUserStateFromRequest($this->getPoolName() . '.type', 'type', '', 'string');
		$filters['group']  = $app->getUserStateFromRequest($this->getPoolName() . '.group', 'group', 0, 'uint');
		$filters['rule']   = $app->getUserStateFromRequest($this->getPoolName() . '.rule', 'rule', -1, 'int');
		$filters['owner']  = $app->getUserStateFromRequest($this->getPoolName() . '.owner', 'owner', -1, 'int');


		if ($filters['group'] == 1)
		{
			// unset unsupported filters
			$filters['rule']  = -1;
			$filters['owner'] = -1;
		}

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'f.ordering', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters, $filters['group']);
		$navbut	= "";

		$rows = array();

		$inner = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_cf_service_assoc', 'a'))
			->where($dbo->qn('a.id_field') . ' = ' . $dbo->qn('f.id'));

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS f.*')
			->select($dbo->qn('e.nickname', 'ename'))
			->select('(' . $inner . ') AS ' . $dbo->qn('services_count'))
			->from($dbo->qn('#__vikappointments_custfields', 'f'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('f.id_employee'))
			->where($dbo->qn('group') . ' = ' . $filters['group'])
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('f.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('e.nickname') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if (!empty($filters['type']))
		{
			$q->where($dbo->qn('f.type') . ' = ' . $dbo->q($filters['type']));
		}

		if ($filters['rule'] != -1)
		{
			$q->where($dbo->qn('f.rule') . ' = ' . $filters['rule']);
		}

		if ($filters['owner'] != -1)
		{
			if ($filters['owner'] == 1)
			{
				$q->where($dbo->qn('f.id_employee') . ' > 0');
			}
			else if ($filters['owner'] == 2)
			{
				$q->having($dbo->qn('services_count') . ' > 0');
			}
			else
			{
				$q->where($dbo->qn('f.id_employee') . ' <= 0');
				$q->having($dbo->qn('services_count') . ' = 0');
			}
		}

		if (strlen($filters['status']))
		{
			if ($filters['status'] == 2)
			{
				// filter repeatable custom fields
				$q->where($dbo->qn('f.repeat') . ' = 1');
			}
			else
			{
				// filter required/optional custom fields
				$q->where($dbo->qn('f.required') . ' = ' . (int) $filters['status']);
			}
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
		$this->assertListQuery($lim0, $lim, $filters['group']);

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
				'custfield',
				array_map(function($row) {
					return $row['id'];
				}, $rows)
			);

			// assign languages found to the related elements
			foreach ($rows as $k => $row)
			{
				if ($row['locale'] && $row['locale'] != '*')
				{
					// use only the locale set
					$rows[$k]['languages'] = array($row['locale']);
				}
				else
				{
					// the the available translations
					$rows[$k]['languages'] = isset($lang[$row['id']]) ? $lang[$row['id']] : array();
				}
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWCUSTOMFS'), 'vikappointments');
		
		$user = JFactory::getUser();

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('customf.add');
		}
		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('customf.edit');
		}
		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'customf.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return (!empty($this->filters['type'])
			|| $this->filters['rule'] != -1
			|| $this->filters['owner'] != -1
			|| strlen($this->filters['status']));
	}
}
