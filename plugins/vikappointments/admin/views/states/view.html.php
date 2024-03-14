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
 * VikAppointments states view.
 *
 * @since 1.5
 */
class VikAppointmentsViewstates extends JViewVAP
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

		$id_country = $input->get('id_country', 0, 'uint');

		/**
		 * The filters are also handled by the export gateway.
		 *
		 * @see libraries.import.classes.states
		 */
		$filters = array();
		$filters['keys']       = $app->getUserStateFromRequest($this->getPoolName($id_country) . '.keys', 'keys', '', 'string');
		$filters['status']     = $app->getUserStateFromRequest($this->getPoolName($id_country) . '.status', 'status', '', 'string');
		$filters['id_country'] = $id_country;

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest('vapstates.ordering', 'filter_order', 's.state_name', 'string');
		$this->orderDir = $app->getUserStateFromRequest('vapstates.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters, $id_country);
		$navbut	= "";

		$q = $dbo->getQuery(true)
			->select($dbo->qn('country_name'))
			->from($dbo->qn('#__vikappointments_countries'))
			->where($dbo->qn('id') . ' = ' . $filters['id_country']);

		$dbo->setQuery($q, 0, 1);
		$countryName = $dbo->loadResult();

		if (!$countryName)
		{
			// country not found, back to list
			$app->redirect('index.php?option=com_vikappointments&view=countries');
			exit;
		}

		// set the toolbar
		$this->addToolBar($countryName);

		$rows = array();

		$citiesCount = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_cities', 'c'))
			->where($dbo->qn('c.id_state') . ' = ' . $dbo->qn('s.id'));

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS s.*')
			->select('(' . $citiesCount . ') AS ' . $dbo->qn('cities_count'))
			->from($dbo->qn('#__vikappointments_states', 's'))
			->where($dbo->qn('s.id_country') . ' = ' . $id_country)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$q->where($dbo->qn('s.state_name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"));
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('s.published') . ' = ' . (int) $filters['status']);
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
		$this->assertListQuery($lim0, $lim, $id_country);

		if ($dbo->getNumRows() > 0)
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
	protected function addToolBar($country_name)
	{
		// add menu title and some buttons to the page	
		JToolBarHelper::title(JText::sprintf('VAPMAINTITLEVIEWSTATES', $country_name), 'vikappointments');
		
		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=countries');

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('state.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('state.edit');
		}

		if ($user->authorise('core.edit.state', 'com_vikappointments'))
		{
			JToolBarHelper::publishList('state.publish');
			JToolBarHelper::unpublishList('state.unpublish');
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::custom('import', 'upload', 'upload', JText::translate('VAPIMPORT'), false);
		}

		JToolBarHelper::custom('export', 'download', 'download', JText::translate('VAPEXPORT'), false);

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'state.delete');
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
