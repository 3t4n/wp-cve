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
 * VikAppointments API user management view.
 *
 * @since 1.7
 */
class VikAppointmentsViewmanageapiuser extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$dbo   = JFactory::getDbo();
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$ids  = $input->getUint('cid', array());
		$type = $ids ? 'edit' : 'new';
		
		// set the toolbar
		$this->addToolBar($type);

		// get model
		$model = JModelVAP::getInstance('apiuser');
		
		// load API user details
		$user = $model->getItem($ids ? $ids[0] : 0, $blank = true);

		// use API user data stored in user state
		$this->injectUserStateData($user, $model->getTable()->getUserStateData());
		
		$this->user = $user;

		// load all supported plugins
		$this->plugins = VAPFactory::getApi()->getPluginsList();

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITAPIUSER'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWAPIUSER'), 'vikappointments');
		}

		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('apiuser.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('apiuser.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('apiuser.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		if ($type == 'edit' && $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2copy('apiuser.savecopy', JText::translate('VAPSAVEASCOPY'));
		}
		
		JToolBarHelper::cancel('apiuser.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
