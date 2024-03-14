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
 * VikAppointments user note management view.
 *
 * @since 1.7
 */
class VikAppointmentsViewmanageusernote extends JViewVAP
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
		$model = JModelVAP::getInstance('usernote');
		
		// load user note details
		$note = $model->getItem($ids ? $ids[0] : 0, $blank = true);

		// use user note data stored in user state
		$this->injectUserStateData($note, 'vap.usernote.data');

		// replace tag IDs with their names
		$note->tags = implode(',', JModelVAP::getInstance('tag')->readTags($note->tags));

		$this->note = $note;

		// register media model for attachments rendering
		$this->mediaModel = JModelVAP::getInstance('media');

		$this->isTmpl = $input->get('tmpl') === 'component';

		if ($this->isTmpl && $this->note->id)
		{
			// add pretty modify date in case of blank template, so that the caller can use it
			$this->note->modifiedon_pretty = JHtml::fetch(
				'date',
				VAPDateHelper::isNull($this->note->modifiedon) ? $this->note->createdon : $this->note->modifiedon,
				JText::translate('DATE_FORMAT_LC2')
			);
		}

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
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITUSERNOTE'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWUSERNOTE'), 'vikappointments');
		}

		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('usernote.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('usernote.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('usernote.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolBarHelper::cancel('usernote.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
