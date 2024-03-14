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
 * VikAppointments manage import view.
 *
 * @since 1.6
 */
class VikAppointmentsViewmanageimport extends JViewVAP
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

		$this->addToolBar();

		$type = $input->getString('import_type');
		$args = $input->get('import_args', array(), 'array');

		VAPLoader::import('libraries.import.factory');
		$handler = ImportFactory::getObject($type);

		if (!$handler)
		{
			throw new Exception('Import type not supported.', 404);
		}

		if (!$handler->hasFile())
		{
			// file not uploaded, back to import view
			$url = 'index.php?option=com_vikappointments&view=import&import_type=' . $type;

			if ($args)
			{
				$url .= '&' . http_build_query(array('import_args' => $args));
			}

			$app->redirect($url);
			exit;
		}

		$this->type    = $type;
		$this->args    = $args;
		$this->handler = $handler;
		
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEMANAGEIMPORT'), 'vikappointments');
		
		if (JFactory::getUser()->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::apply('import.save', JText::translate('VAPSAVE'));
		}
		
		JToolBarHelper::cancel('import.cancel');
	}
}
