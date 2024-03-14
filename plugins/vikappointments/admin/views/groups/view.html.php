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
 * VikAppointments groups view.
 *
 * @since 1.0
 */
class VikAppointmentsViewgroups extends JViewVAP
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

		// extract page type from session
		$page_type = $app->getUserStateFromRequest($this->getPoolName() . 'pagetype', 'type', 1, 'uint');
		
		// set the toolbar
		$this->addToolBar($page_type);

		$filters = array();
		$filters['keysearch'] = $app->getUserStateFromRequest($this->getPoolName($page_type) . '.keysearch', 'keysearch', '', 'string');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName($page_type) . '.ordering', 'filter_order', 'g.ordering', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName($page_type) . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		//db object
		$lim  	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters, $page_type);
		$navbut = "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS g.*')
			->group($dbo->qn('g.id'))
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if ($page_type == 1)
		{
			$q->select('COUNT(s.id) AS ' . $dbo->qn('count'))
				->from($dbo->qn('#__vikappointments_group', 'g'))
				->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('s.id_group'));
		}
		else
		{
			$q->select('COUNT(e.id) AS ' . $dbo->qn('count'))
				->from($dbo->qn('#__vikappointments_employee_group', 'g'))
				->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('e.id_group'));
		}

		$q->where(1);

		if (strlen($filters['keysearch']))
		{
			$q->andWhere(array(
				$dbo->qn('g.name') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('g.description') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
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
		$this->assertListQuery($lim0, $lim, $page_type);

		if ($dbo->getNumRows())
		{
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination($dbo->loadResult(), $lim0, $lim);
			$navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);
		}

		if (VikAppointments::isMultilanguage())
		{
			$translator = VAPFactory::getTranslator();

			// find available translations
			$lang = $translator->getAvailableLang(
				$page_type == 1 ? 'group' : 'empgroup',
				array_map(function($row) {
					return $row['id'];
				}, $rows)
			);

			// assign languages found to the related elements
			foreach ($rows as $k => $row)
			{
				$rows[$k]['languages'] = isset($lang[$row['id']]) ? $lang[$row['id']] : array();
			}
		}
		
		$this->rows 	= $rows;
		$this->pageType = $page_type;
		$this->navbut 	= $navbut;
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($page)
	{
		// add menu title and some buttons to the page	
		JToolBarHelper::title(JText::translate($page == 1 ? 'VAPMAINTITLEVIEWGROUPS' : 'VAPMAINTITLEVIEWEMPGROUPS'), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('group.add', JText::translate('VAPNEW'));
			JToolBarHelper::divider();	
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('group.edit', JText::translate('VAPEDIT'));
			JToolBarHelper::spacer();
		}
		
		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'group.delete', JText::translate('VAPDELETE'));
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::custom('import', 'upload', 'upload', JText::translate('VAPIMPORT'), false);
		}

		JToolBarHelper::custom('export', 'download', 'download', JText::translate('VAPEXPORT'), false);
	}
}
