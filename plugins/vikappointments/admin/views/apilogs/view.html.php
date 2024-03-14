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
 * VikAppointments API logs view.
 *
 * @since 1.7
 */
class VikAppointmentsViewapilogs extends JViewVAP
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
		$filters['id_login']  = $input->getUint('id_login', 0);

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'l.createdon', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'DESC', 'string');

		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'int');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS l.*')
			->select($dbo->qn(array('u.application', 'u.username')))
			->from($dbo->qn('#__vikappointments_api_login_logs', 'l'))
			->leftjoin($dbo->qn('#__vikappointments_api_login', 'u') . ' ON ' . $dbo->qn('l.id_login') . ' = ' . $dbo->qn('u.id'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if ($filters['keysearch'])
		{
			$q->andWhere(array(
				$dbo->qn('u.application') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('u.username') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('l.content') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
			), 'OR');
		}

		if ($filters['id_login'])
		{
			$q->where($dbo->qn('l.id_login') . ' = ' . $filters['id_login']);
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
	private function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWAPILOGS'), 'vikappointments');

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=editconfigapp');
		
		if (JFactory::getUser()->authorise('core.delete', 'com_vikappointments'))
		{
			JToolbarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'apilog.delete');

			JToolbarHelper::custom('apilog.truncate', 'trash', 'trash', JText::translate('VAPDELETEALL'), false);
		}
	}
}
