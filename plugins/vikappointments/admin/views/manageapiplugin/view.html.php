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
 * VikAppointments API plugin management view.
 *
 * @since 1.7
 */
class VikAppointmentsViewmanageapiplugin extends JViewVAP
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

		$apis = VAPFactory::getApi();
		
		$ids = $input->get('cid', array(''), 'string');

		// search for specified plugin
		$plugins = $apis->getPluginsList($ids[0]);

		if (count($plugins) == 0)
		{
			// plugin not found, back to the list
			$app->enqueueMessage(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'), 'error');
			$app->redirect('index.php?option=com_vikappointments&view=apiplugins');
			exit;
		}
		
		$this->plugin = $plugins[0];

		// display the template
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
		JToolbarHelper::title(JText::translate('VAPMAINTITLEVIEWAPIPLUGINS'), 'vikappointments');
		
		JToolbarHelper::cancel('apiplugin.cancel', 'JTOOLBAR_CLOSE');
	}
}
