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

VAPLoader::import('libraries.webhook.webhook');

/**
 * VikAppointments web hook management view.
 *
 * @since 1.7
 */
class VikAppointmentsViewmanagewebhook extends JViewVAP
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
		$model = JModelVAP::getInstance('webhook');
		
		// load web hook details
		$webhook = $model->getItem($ids ? $ids[0] : 0, $blank = true);

		// use web hook data stored in user state
		$this->injectUserStateData($user, $model->getTable()->getUserStateData());
		
		$this->webhook = $webhook;

		// load all web hook log files
		$this->logs = $model->getLogFiles($webhook->id);

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
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITWEBHOOK'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWWEBHOOK'), 'vikappointments');
		}

		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('webhook.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('webhook.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('webhook.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		if ($type == 'edit' && $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2copy('webhook.savecopy', JText::translate('VAPSAVEASCOPY'));
		}
		
		JToolBarHelper::cancel('webhook.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
