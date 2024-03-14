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
 * VikAppointments coupons view.
 *
 * @since 1.0
 */
class VikAppointmentsViewcoupons extends JViewVAP
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

		/**
		 * The filters are also handled by the export gateway.
		 *
		 * @see 	libraries.import.classes.coupons
		 */
		$filters = array();
		$filters['keys'] 	 = $app->getUserStateFromRequest($this->getPoolName() . '.keys', 'keys', '', 'string');
		$filters['type'] 	 = $app->getUserStateFromRequest($this->getPoolName() . '.type', 'type', 0, 'uint');
		$filters['value'] 	 = $app->getUserStateFromRequest($this->getPoolName() . '.value', 'value', 0, 'uint');
		$filters['status'] 	 = $app->getUserStateFromRequest($this->getPoolName() . '.status', 'status', 0, 'uint');
		$filters['id_group'] = $app->getUserStateFromRequest($this->getPoolName() . '.group', 'id_group', -1, 'int');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'c.id', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS c.*')
			->from($dbo->qn('#__vikappointments_coupon', 'c'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$q->where($dbo->qn('c.code') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"));
		}

		if ($filters['type'])
		{
			$q->where($dbo->qn('c.type') . ' = ' . $filters['type']);
		}

		if ($filters['value'])
		{
			$q->where($dbo->qn('c.percentot') . ' = ' . $filters['value']);
		}

		$now = JFactory::getDate()->toSql();

		if ($filters['status'] == 1)
		{
			// expired status
			$q->where('(' .
					$dbo->qn('c.dend') . ' IS NOT NULL AND ' .
					$dbo->qn('c.dend') . ' <> ' . $dbo->q($dbo->getNullDate()) . ' AND ' .
					$dbo->qn('c.dend') . ' < ' . $dbo->q($now) .
				') OR (' .
					$dbo->qn('c.type') . ' = 2 AND ' .
					'(' . $dbo->qn('c.max_quantity') . ' - ' . $dbo->qn('c.used_quantity') . ') <= 0' .
			')');
		}
		else if ($filters['status'] == 2)
		{
			// active
			$q->where('(' .
					$dbo->qn('c.dstart') . ' IS NULL OR ' .
					$dbo->qn('c.dstart') . ' = ' . $dbo->q($dbo->getNullDate()) . ' OR ' .
					$dbo->qn('c.dstart') . ' <= ' . $dbo->q($now) .
				') AND (' .
					$dbo->qn('c.dend') . ' IS NULL OR ' .
					$dbo->qn('c.dend') . ' = ' . $dbo->q($dbo->getNullDate()) . ' OR ' .
					$dbo->qn('c.dend') . ' > ' . $dbo->q($now) . 
				') AND (' .
					$dbo->qn('c.type') . ' = 1 OR ' .
					'(' . $dbo->qn('c.max_quantity') . ' - ' . $dbo->qn('c.used_quantity') . ') > 0' .
			')');
		}
		else if ($filters['status'] == 3)
		{
			// not active
			$q->where(array(
				$dbo->qn('c.dstart') . ' IS NOT NULL',
				$dbo->qn('c.dstart') . ' <> ' . $dbo->q($dbo->getNullDate()),
				$dbo->qn('c.dstart') . ' > ' . $dbo->q($now),
			));
		}

		if ($filters['id_group'] != -1)
		{
			$q->where($dbo->qn('c.id_group') . ' = ' . $filters['id_group']);
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWCOUPONS'), 'vikappointments');
		
		$user = JFactory::getUser();

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('coupon.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('coupon.edit');
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::custom('import', 'upload', 'upload', JText::translate('VAPIMPORT'), false);
		}

		JToolBarHelper::custom('export', 'download', 'download', JText::translate('VAPEXPORT'), false);
		
		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'coupon.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return ($this->filters['type'] != 0
			|| $this->filters['value'] != 0
			|| $this->filters['status'] != 0
			|| $this->filters['id_group'] != -1);
	}
}
