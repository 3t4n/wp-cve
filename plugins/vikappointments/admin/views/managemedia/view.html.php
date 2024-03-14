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
 * VikAppointments media management view.
 *
 * @since 1.2
 */
class VikAppointmentsViewmanagemedia extends JViewVAP
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
		
		$filename = $input->get('cid', array(''), 'string');
		$filename = $filename[0];

		if (empty($filename) || !file_exists(VAPMEDIA . DIRECTORY_SEPARATOR . $filename))
		{
			$app->redirect('index.php?option=com_vikappointments&view=media');
			exit;
		}

		$media = AppointmentsHelper::getFileProperties(VAPMEDIA . DIRECTORY_SEPARATOR . $filename);
		$thumb = AppointmentsHelper::getFileProperties(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $filename);

		// fetch media attributes
		$attrs = JModelVAP::getInstance('media')->getItem($media['name'], $new = true);

		// inject media attributes within the array
		foreach ($attrs as $k => $v)
		{
			// do not overwrite an existing attribute
			if (!isset($media[$k]))
			{
				$media[$k] = $v;
			}
		}
		
		$this->media = &$media;
		$this->thumb = &$thumb;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	private function addToolBar() {
		// add menu title and some buttons to the page
		JToolbarHelper::title(JText::translate('VAPMAINTITLEEDITMEDIA'), 'vikappointments');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_vikappointments'))
		{
			JToolbarHelper::apply('media.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('media.saveclose', JText::translate('VAPSAVEANDCLOSE'));
			JToolbarHelper::save2new('media.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolbarHelper::cancel('media.cancel', 'JTOOLBAR_CLOSE');
	}
}
