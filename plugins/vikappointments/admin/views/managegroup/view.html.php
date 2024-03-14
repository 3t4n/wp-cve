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
 * VikAppointments group management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagegroup extends JViewVAP
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
		
		$ids  = $input->getUint('cid', array());
		$type = $ids ? 'edit' : 'new';

		$pagetype = $input->get('type', 1, 'uint');
		
		// set the toolbar
		$this->addToolBar($type, $pagetype);
		
		$group = null;
		
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true);

			$q->select('*');

			if ($pagetype == 1)
			{
				$q->from($dbo->qn('#__vikappointments_group'));
			}
			else
			{
				$q->from($dbo->qn('#__vikappointments_employee_group'));	
			}

			$q->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$group = $dbo->loadObject();
		}

		if (empty($group))
		{
			$group = (object) $this->getBlankItem();
		}

		// use group data stored in user state
		$this->injectUserStateData($group, 'vap.group.data');
		
		$this->group    = $group;
		$this->pageType = $pagetype;

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string 	 $type 	    The request type (new or edit).
	 * @param 	integer  $pageType  The type of group (1 for services, 2 for employees).
	 *
	 * @return 	void
	 */
	protected function addToolBar($type, $pageType)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			$title = $pageType == 1 ? 'VAPMAINTITLEEDITGROUP' : 'VAPMAINTITLEEDITEMPGROUP';
		}
		else
		{
			$title = $pageType == 1 ? 'VAPMAINTITLENEWGROUP' : 'VAPMAINTITLENEWEMPGROUP';;
		}

		JToolBarHelper::title(JText::translate($title), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('group.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('group.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('group.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		if ($type == 'edit' && $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2copy('group.savecopy', JText::translate('VAPSAVEASCOPY'));
		}
		
		JToolbarHelper::cancel('group.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'          => 0,
			'name'        => '',
			'description' => '',
		);
	}
}
