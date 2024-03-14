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
 * VikAppointments coupon groups view.
 *
 * @since 1.6
 */
class VikAppointmentsViewcoupongroups extends JViewVAP
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
		$filters['keysearch'] = $app->getUserStateFromRequest($this->getPoolName() . '.keysearch', 'keysearch', '', 'string');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'g.ordering', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS g.*, COUNT(c.id) AS `count`')
			->select($dbo->qn('c.id', 'coupon_id'))
			->from($dbo->qn('#__vikappointments_coupon_group', 'g'))
			->leftjoin($dbo->qn('#__vikappointments_coupon', 'c') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('c.id_group'))
			->group($dbo->qn('g.id'))
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keysearch']))
		{
			$q->where($dbo->qn('g.name') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"));
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWCOUPONGROUPS'), 'vikappointments');
		
		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=coupons');

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('coupongroup.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('coupongroup.edit');
		}
		
		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'coupongroup.delete');
		}
	}
}
