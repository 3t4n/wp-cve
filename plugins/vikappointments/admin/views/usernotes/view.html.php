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
 * VikAppointments user notes view.
 *
 * @since 1.7
 */
class VikAppointmentsViewusernotes extends JViewVAP
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

		$layout = $input->get('layout');

		$filters = array();
		$filters['id_user']   = $input->getUint('id_user', 0);
		$filters['id_parent'] = $input->getUint('id_parent', 0);
		$filters['group']     = $input->get('group');

		if ($layout != 'modal')
		{
			// set the toolbar
			$this->addToolBar();

			$filters['keys']   = $app->getUserStateFromRequest($this->getPoolName() . '.keys', 'keys', '', 'string');
			$filters['status'] = $app->getUserStateFromRequest($this->getPoolName() . '.status', 'status', '', 'string');
			$filters['tag']    = $app->getUserStateFromRequest($this->getPoolName() . '.tag', 'tag', 0, 'uint');		

			$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'n.createdon', 'string');
			$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'DESC', 'string');

			$lim0 = $this->getListLimitStart($filters);
		}
		else
		{
			// ignore filters
			$filters['keys'] = $filters['status'] = $filters['tag'] = '';

			// always sort in descending order when using the modal layout
			$this->ordering = 'n.modifiedon';
			$this->orderDir = 'DESC';

			$this->headingTitle = '';

			// reset list limit
			$lim0 = 0;

			if ($filters['id_user'])
			{
				$q = $dbo->getQuery(true)
					->select('billing_name')
					->from($dbo->qn('#__vikappointments_users'))
					->where($dbo->qn('id') . ' = ' . (int) $filters['id_user']);

				$dbo->setQuery($q, 0, 1);
				$customerName = $dbo->loadResult();

				if ($customerName)
				{
					$this->headingTitle = JText::sprintf('VAPMODALTITLEUSERNOTES', $customerName);
				}
			}

			$this->setLayout('modal');
		}

		$this->filters = $filters;

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS n.*')
			->select($dbo->qn('u.name', 'author_name'))
			->from($dbo->qn('#__vikappointments_user_notes', 'n'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('u.id') . ' = ' . $dbo->qn('n.author'))
			->where(1);

		if ($this->ordering == 'n.modifiedon')
		{
			// sort by modify date (use creation date if null)
			$q->order(sprintf(
				'IFNULL(%s, %s) %s',
				$dbo->qn('n.modifiedon'),
				$dbo->qn('n.createdon'),
				$this->orderDir
			));
		}
		else
		{
			$q->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);
		}

		if (!empty($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('n.title') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('n.content') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if ($filters['id_user'])
		{
			// filter by user ID
			$q->where($dbo->qn('n.id_user') . ' = ' . $filters['id_user']);
		}

		if ($filters['id_parent'])
		{
			// filter by parent ID
			$q->where($dbo->qn('n.id_parent') . ' = ' . $filters['id_parent']);
		}

		if ($filters['group'])
		{
			// filter by user ID
			$q->where($dbo->qn('n.group') . ' = ' . $dbo->q($filters['group']));
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('n.status') . ' = ' . (int) $filters['status']);
		}

		if ($filters['tag'])
		{
			$q->andWhere(array(
				// only one tag
				$dbo->qn('n.tags') . ' = ' . $dbo->q($filters['tag']),
				// tag in the middle
				$dbo->qn('n.tags') . ' LIKE ' . $dbo->q("%,{$filters['tag']},%"),
				// first tag of the list
				$dbo->qn('n.tags') . ' LIKE ' . $dbo->q("{$filters['tag']},%"),
				// last tag of the list
				$dbo->qn('n.tags') . ' LIKE ' . $dbo->q("%,{$filters['tag']}"),
			), 'OR');
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

		$this->tagModel = JModelVAP::getInstance('tag');
		
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWUSERNOTES'), 'vikappointments');

		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=customers');
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('usernote.add');
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('usernote.edit');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'usernote.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return strlen($this->filters['status'])
			|| $this->filters['tag'];
	}
}
