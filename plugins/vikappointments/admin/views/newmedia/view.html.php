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
 * VikAppointments media upload view.
 *
 * @since 1.7
 */
class VikAppointmentsViewnewmedia extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$app = JFactory::getApplication();

		// set the toolbar
		$this->addToolBar();
	
		$prop = VikAppointments::getMediaProperties();

		$this->properties = $prop;

		// Check if we should prompt a message to guide the user about
		// chaning the default size of the thumbnails.
		$this->showHelp = $app->input->getBool('configure');

		// display the template
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLENEWMEDIA'), 'vikappointments');
		
		if (JFactory::getUser()->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::apply('media.saveclose', JText::translate('VAPSAVE'));
		}
		
		JToolBarHelper::cancel('media.cancel');
	}
}
