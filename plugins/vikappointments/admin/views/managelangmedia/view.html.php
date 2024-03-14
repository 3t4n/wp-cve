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
 * VikAppointments translation media management view.
 *
 * @since 1.7.2
 */
class VikAppointmentsViewmanagelangmedia extends JViewVAP
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
		
		$image = $input->getString('image', 0);
		
		$ids  = $input->get('cid', array(), 'uint');
		$type = $ids ? 'edit' : 'new';

		// set the toolbar
		$this->addToolBar($type);
		
		$language = null;

		// load translation details in case of management
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true);
			
			$q->select('*')
				->from($dbo->qn('#__vikappointments_lang_media'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$language = $dbo->loadObject();

			if ($language)
			{
				// retrieve media image from translation object
				$image = $language->image;
			}
		}

		// get default details
		$default = null;

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'image', 'alt', 'title', 'caption')))
			->from($dbo->qn('#__vikappointments_media'))
			->where($dbo->qn('image') . ' = ' . $dbo->q($image));

		$dbo->setQuery($q, 0, 1);
		$default = $dbo->loadObject();

		if (!$default)
		{
			$app->enqueueMessage(JText::translate('VAPMANAGEMEDIANOTRX'), 'warning');
			$app->redirect('index.php?option=com_vikappointments&task=media.edit&cid[]=' . $image);
			$app->close();
		}
		
		$this->default  = $default;
		$this->language = $language;

		// display the template
		parent::display($tpl);	
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string  $type  The view type ('edit' or 'new').
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	private function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolbarHelper::title(JText::translate('VAP_TRX_EDIT_TITLE'), 'vikappointments');
		}
		else
		{
			JToolbarHelper::title(JText::translate('VAP_TRX_NEW_TITLE'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('langmedia.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('langmedia.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('langmedia.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		JToolbarHelper::cancel('langmedia.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
