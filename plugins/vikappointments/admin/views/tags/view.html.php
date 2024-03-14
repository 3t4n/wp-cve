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
 * VikAppointments tags view.
 *
 * @since 1.7
 */
class VikAppointmentsViewtags extends JViewVAP
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

		$filters = array();
		$filters['keys']  = $app->getUserStateFromRequest($this->getPoolName() . '.keys', 'keys', '', 'string');
		$filters['group'] = $input->get('group', '', 'string');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 't.ordering', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// set the toolbar
		$this->addToolBar();

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS t.*')
			->select($dbo->qn('u.name', 'author_name'))
			->from($dbo->qn('#__vikappointments_tag', 't'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('u.id') . ' = ' . $dbo->qn('t.author'))
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if ($filters['group'] == 'usernotes')
		{
			$count = $dbo->getQuery(true)
				->select('COUNT(1)')
				->from($dbo->qn('#__vikappointments_user_notes', 'n'))
				->where(array(
					// only one tag
					$dbo->qn('n.tags') . ' = ' . $dbo->qn('t.id'),
					// tag in the middle
					$dbo->qn('n.tags') . ' LIKE CONCAT(\'%,\', ' . $dbo->qn('t.id') . ', \',%\')',
					// first tag of the list
					$dbo->qn('n.tags') . ' LIKE CONCAT(' . $dbo->qn('t.id') . ', \',%\')',
					// last tag of the list
					$dbo->qn('n.tags') . ' LIKE CONCAT(\'%,\', ' . $dbo->qn('t.id') . ')',
				), 'OR');
		}
		else
		{
			$count = null;
		}

		if ($count)
		{
			$q->select('(' . $count . ') AS ' . $dbo->qn('count'));
		}
		else
		{
			$q->select($dbo->q('/') . ' AS ' . $dbo->qn('count'));
		}

		if ($filters['keys'])
		{
			$q->where($dbo->qn('t.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"));
		}

		if ($filters['group'])
		{
			$q->where($dbo->qn('t.group') . ' = ' . $dbo->q($filters['group']));
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

		if ($dbo->getNumRows() )
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
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWTAGS'), 'vikappointments');

		$user = JFactory::getUser();

		switch ($this->filters['group'])
		{
			case 'usernotes':
				// we can directly point to the customers view because we
				// are no more able to preserve the filters of the notes
				$view = 'customers';
				break;

			default:
				$view = 'dashboard';
		}

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=' . $view);
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::addNew('tag.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolbarHelper::editList('tag.edit');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolbarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'tag.delete');
		}
	}
}
