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
 * VikAppointments export view.
 *
 * @since 1.6
 */
class VikAppointmentsViewexport extends JViewVAP
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

		$type = $input->getString('import_type');
		$args = $input->get('import_args', array(), 'array');
		$cid  = $input->get('cid', array(), 'uint');

		VAPLoader::import('libraries.import.factory');

		$handler = ImportFactory::getObject($type);

		if (!$handler)
		{
			throw new Exception('Import type not supported.', 404);
		}

		$rows = $handler->getExportableRows($args);

		// get the list of all the available exportable classes
		$export_list = ImportFactory::getExportList();

		$this->addToolBar($handler);

		$this->type       = $type;
		$this->args       = $args;
		$this->cid        = $cid;
		$this->rows       = $rows;
		$this->handler    = $handler;
		$this->exportList = $export_list;
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($handler)
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWEXPORT'), 'vikappointments');

		if ($task = $handler->getCancelTask())
		{
			JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_vikappointments&task=' . $task);
		}

		JToolBarHelper::custom('export', 'download', 'download', JText::translate('VAPDOWNLOAD'), false);
	}
}
