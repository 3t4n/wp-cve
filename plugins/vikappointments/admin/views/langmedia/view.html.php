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
 * VikAppointments media translations view.
 *
 * @since 1.7.2
 */
class VikAppointmentsViewlangmedia extends JViewVAP
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
		$filters['image'] = $input->get('image', '', 'string');

		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'int');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);
		
		$q->select('SQL_CALC_FOUND_ROWS *')
			->from($dbo->qn('#__vikappointments_lang_media'))
			->where($dbo->qn('image') . ' = ' . $dbo->q($filters['image']));

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
		JToolbarHelper::title(JText::translate('VAP_TRX_LIST_TITLE'), 'vikappointments');

		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=media');
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::addNew('langmedia.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolbarHelper::editList('langmedia.edit');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolbarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'langmedia.delete');
		}
	}
}
