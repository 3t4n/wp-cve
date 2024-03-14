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
 * VikAppointments backups view.
 *
 * @since 1.7.1
 */
class VikAppointmentsViewbackups extends JViewVAP
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

		$model = JModelVAP::getInstance('backup');

		// set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['date']  = $app->getUserStateFromRequest($this->getPoolName() . '.date', 'date', '', 'string');
		$filters['type']  = $app->getUserStateFromRequest($this->getPoolName() . '.type', 'type', '', 'string');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'createdon', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'DESC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		// load all the export types
		$this->exportTypes = $model->getExportTypes();

		$rows = array();

		// fetch folder in which the backup are stored
		$folder = VAPFactory::getConfig()->get('backupfolder');

		if (!$folder)
		{
			// use temporary folder if not specified
			$folder = JFactory::getApplication()->get('tmp_path');
		}

		if ($folder && JFolder::exists($folder))
		{
			// load all backup archives
			$rows = JFolder::files($folder, 'backup_', $recurse = false, $fullpath = true);
		}

		// fetch backup details
		$rows = array_map(function($file) use ($model)
		{
			return $model->getItem($file);
		}, $rows);

		// filter the backups
		$rows = array_values(array_filter($rows, function($r) use ($filters)
		{
			if ($filters['type'] && $r->type->id !== $filters['type'])
			{
				return false;
			}

			if (VAPDateHelper::isNull($filters['date']) === false)
			{
				// get locale SQL strings to have dates adjusted to the current
				// timezone. This way the dates will be refactored for being
				// used in UTC, even if the locale is different.
				$start = VAPDateHelper::getDate($filters['date']);

				if ($start->format('Y-m-d') !== JFactory::getDate($r->date)->format('Y-m-d'))
				{
					return false;
				}
			}

			return true;
		}));

		$ordering  = $this->ordering;
		$direction = $this->orderDir;

		// fetch the type of ordering
		usort($rows, function($a, $b) use ($ordering, $direction)
		{
			switch ($ordering)
			{
				case 'filesize':
					// sort by file size
					$factor = $a->size - $b->size;
					break;

				default:
					// sort by creation date
					$factor = $a->timestamp - $b->timestamp;
			}

			// in case of descending direction, reverse the ordering factor
			if (preg_match("/desc/i", $direction))
			{
				$factor *= -1;
			}

			return $factor;
		});

		$tot_count = count($rows);

		if ($tot_count > $lim)
		{
			if ($lim0 >= $tot_count)
			{
				// We exceeded the pagination, probably because we deleted all the records of the last page.
				// For this reason, we need to go back to the previous one.
				$lim0 = max(array(0, $lim0 - $lim));
			}

			$rows = array_slice($rows, $lim0, $lim);

			jimport('joomla.html.pagination');
			$pageNav = new JPagination($tot_count, $lim0, $lim);
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWBACKUPS'), 'vikappointments');

		$user = JFactory::getUser();

		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&view=editconfigapp');

		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('backup.add');
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'backup.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return !empty($this->filters['type']);
	}
}
