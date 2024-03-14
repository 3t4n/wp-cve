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
 * VikAppointments file management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagefile extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;

		// check if we should use a blank component layout
		$blank = $input->get('tmpl') == 'component';

		if (!$blank)
		{
			// set the toolbar
			$this->addToolBar();
		}
		
		// get files
		$file = $input->get('cid', array(), 'string');

		// keep only the first one
		$file = array_shift($file);

		// get file model
		$model = JModelVAP::getInstance('file');

		// load file details
		$item = $model->getItem($file);

		if (!$item)
		{
			$error = $model->getError();

			if (!$error instanceof Exception)
			{
				$error = new Exception($error, 500);
			}

			// throw exception with error found
			throw $error;
		}
		
		$this->file    = $item->id;
		$this->content = $item->content;
		$this->blank   = $blank;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	private function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolbarHelper::title('VikAppointments - Manage File', 'vikappointments');
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.admin', 'com_vikappointments'))
		{
			JToolbarHelper::apply('file.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('file.savecopy', JText::translate('VAPSAVEASCOPY'));
		}
		
		JToolbarHelper::cancel('file.cancel', 'JTOOLBAR_CLOSE');
	}
}
