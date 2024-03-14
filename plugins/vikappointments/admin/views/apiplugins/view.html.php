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
 * VikAppointments API plugins view.
 *
 * @since 1.7
 */
class VikAppointmentsViewapiplugins extends JViewVAP
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

		// set the toolbar
		$this->addToolBar();
		
		$filters = array();
		$filters['keysearch'] = $app->getUserStateFromRequest($this->getPoolName() . '.keysearch', 'keysearch', '', 'string');

		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'int');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$apis = VAPFactory::getApi();
		$rows = $apis->getPluginsList();

		if (strlen($filters['keysearch']))
		{
			// filter plugins by search keyword
			$rows = array_filter($rows, function($plugin) use ($filters)
			{
				return stripos(strtolower($plugin->getName()), $filters['keysearch']) !== false || stripos(strtolower($plugin->getTitle()), $filters['keysearch']) !== false;
			});

			// do not keep the assoc keys
			$rows = array_values($rows);
		}

		if (($count = count($rows)) > $lim)
		{	
			$rows = array_slice($rows, $lim0, $lim);

			jimport('joomla.html.pagination');
			$pageNav = new JPagination($count, $lim0, $lim);
			$navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);
		}
		
		$this->rows    = $rows;
		$this->navbut  = $navbut;
		$this->filters = $filters;
		
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
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWAPIPLUGINS'), 'vikappointments');

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=editconfigapp');
		
		if (JFactory::getUser()->authorise('core.delete', 'com_vikappointments'))
		{
			JToolbarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'apiplugin.delete');
		}
	}
}
