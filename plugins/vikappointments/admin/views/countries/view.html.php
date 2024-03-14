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
 * VikAppointments countries view.
 *
 * @since 1.5
 */
class VikAppointmentsViewcountries extends JViewVAP
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
		$filters['keys']   = $app->getUserStateFromRequest('vapcountries.keys', 'keys', '', 'string');
		$filters['status'] = $app->getUserStateFromRequest('vapcountries.status', 'status', '', 'string');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest('vapcountries.ordering', 'filter_order', 'c.country_name', 'string');
		$this->orderDir = $app->getUserStateFromRequest('vapcountries.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$statesCount = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_states', 's'))
			->where($dbo->qn('s.id_country') . ' = ' . $dbo->qn('c.id'));

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS `c`.*')
			->select('(' . $statesCount . ') AS ' . $dbo->qn('states_count'))
			->from($dbo->qn('#__vikappointments_countries', 'c'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$where = [];

			// search by country name
			$where[] = $dbo->qn('c.country_name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%");

			if (strlen($filters['keys']) == 2)
			{
				$where[] = $dbo->qn('c.country_2_code') . ' = ' . $dbo->q($filters['keys']);
			}
			else if (strlen($filters['keys']) == 3)
			{
				$where[] = $dbo->qn('c.country_3_code') . ' = ' . $dbo->q($filters['keys']);
			}

			$q->andWhere($where, 'OR');
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('c.published') . ' = ' . (int) $filters['status']);
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWCOUNTRIES'), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('country.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('country.edit');
		}

		if ($user->authorise('core.edit.state', 'com_vikappointments'))
		{
			JToolbarHelper::publishList('country.publish');
			JToolbarHelper::unpublishList('country.unpublish');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'country.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return (strlen($this->filters['status']));
	}
}
