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
 * VikAppointments cities view.
 *
 * @since 1.5
 */
class VikAppointmentsViewcities extends JViewVAP
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

		$id_state = $input->get('id_state', 0, 'uint');

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id_country', 'state_name')))
			->from($dbo->qn('#__vikappointments_states'))
			->where($dbo->qn('id') . ' = ' . $id_state);

		$dbo->setQuery($q, 0, 1);
		$row = $dbo->loadObject();

		if (!$row)
		{
			// state not found, back to countries list
			$app->redirect('index.php?option=com_vikappointments&view=countries');			
			exit;
		}

		/**
		 * The filters are also handled by the export gateway.
		 *
		 * @see libraries.import.classes.states
		 */
		$filters = array();
		$filters['keys']       = $app->getUserStateFromRequest($this->getPoolName($id_state) . '.keys', 'keys', '', 'string');
		$filters['status']     = $app->getUserStateFromRequest($this->getPoolName($id_state) . '.status', 'status', '', 'string');
		$filters['id_state']   = $id_state;
		$filters['id_country'] = $row->id_country;

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest('vapcities.ordering', 'filter_order', 'c.city_name', 'string');
		$this->orderDir = $app->getUserStateFromRequest('vapcities.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters, $id_state);
		$navbut	= "";

		// set the toolbar
		$this->addToolBar($row->state_name);

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS c.*')
			->from($dbo->qn('#__vikappointments_cities', 'c'))
			->where($dbo->qn('c.id_state') . ' = ' . $id_state)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$q->where($dbo->qn('c.city_name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"));
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
		$this->assertListQuery($lim0, $lim, $id_state);

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
	protected function addToolBar($state_name)
	{
		// add menu title and some buttons to the page	
		JToolBarHelper::title(JText::sprintf('VAPMAINTITLEVIEWCITIES', $state_name), 'vikappointments');
		
		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=states&id_country=' . $this->filters['id_country']);

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('city.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('city.edit');
		}

		if ($user->authorise('core.edit.state', 'com_vikappointments'))
		{
			JToolBarHelper::publishList('city.publish');
			JToolBarHelper::unpublishList('city.unpublish');
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::custom('import', 'upload', 'upload', JText::translate('VAPIMPORT'), false);
		}

		JToolBarHelper::custom('export', 'download', 'download', JText::translate('VAPEXPORT'), false);

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'city.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return strlen($this->filters['status']);
	}
}
