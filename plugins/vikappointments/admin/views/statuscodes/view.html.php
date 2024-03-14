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
 * VikAppointments status codes view.
 *
 * @since 1.7
 */
class VikAppointmentsViewstatuscodes extends JViewVAP
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

		$model = JModelVAP::getInstance('statuscode');

		// set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['keys']  = $app->getUserStateFromRequest($this->getPoolName() . '.keys', 'keys', '', 'string');
		$filters['group'] = $app->getUserStateFromRequest($this->getPoolName() . '.group', 'group', '', 'string');	

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 's.ordering', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'ASC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS s.*')
			->from($dbo->qn('#__vikappointments_status_code', 's'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if (strlen($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('s.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('s.description') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if ($filters['group'])
		{
			$q->where($dbo->qn('s.' . $filters['group']) . ' = 1');
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
		
		if (VikAppointments::isMultilanguage())
		{
			$translator = VAPFactory::getTranslator();

			// find available translations
			$lang = $translator->getAvailableLang(
				'statuscode',
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

		/**
		 * Run some tests to make sure the status codes are properly configured.
		 * 
		 * @since 1.7.1
		 */
		if ($model->runTests() === false)
		{
			// display all the error messages
			foreach ($model->getErrors() as $error)
			{
				$app->enqueueMessage($error, 'error');
			}
		}
		
		$this->rows   = $rows;
		$this->navbut = $navbut;
		
		// Display the template (default.php)
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWSTATUSCODES'), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('statuscode.add', JText::translate('VAPNEW'));
			JToolBarHelper::divider();	
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::editList('statuscode.edit', JText::translate('VAPEDIT'));
			JToolBarHelper::spacer();
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'statuscode.delete', JText::translate('VAPDELETE'));
		}

		/**
		 * Added the possibility to restore the status codes at the factory settings.
		 * 
		 * @since 1.7.1
		 */
		if ($user->authorise('core.admin', 'com_vikappointments'))
		{
			JToolBarHelper::custom('statuscode.restore', 'loop', 'loop', JText::translate('VAPRESTORE'), false);
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return ($this->filters['group']);
	}
}
