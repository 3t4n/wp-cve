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
 * VikAppointments API users view.
 *
 * @since 1.7
 */
class VikAppointmentsViewapiusers extends JViewVAP
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
		$filters['active']    = $app->getUserStateFromRequest($this->getPoolName() . '.active', 'active', -1, 'int');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'a.id', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'int');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS a.*')
			->from($dbo->qn('#__vikappointments_api_login', 'a'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if ($filters['keysearch'])
		{
			$q->andWhere(array(
				$dbo->qn('a.application') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('a.username') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
			), 'OR');
		}

		if ($filters['active'] != -1)
		{
			$q->where($dbo->qn('a.active') . ' = ' . $filters['active']);
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

		foreach ($rows as &$r)
		{
			$q = "SELECT `l`.* FROM `#__vikappointments_api_login_logs` AS `l` WHERE `l`.`createdon` = (
				SELECT MAX(`l2`.`createdon`) FROM `#__vikappointments_api_login_logs` AS `l2` WHERE `l2`.`id_login` = {$r['id']}
			)";

			$dbo->setQuery($q, 0, 1);
			$r['log'] = $dbo->loadAssoc();
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
	private function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWAPIUSERS'), 'vikappointments');

		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=editconfigapp');
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::addNew('apiuser.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolbarHelper::editList('apiuser.edit');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolbarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'apiuser.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return $this->filters['active'] != -1;
	}
}
