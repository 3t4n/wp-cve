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
 * VikAppointments service working day management view.
 *
 * @since 1.5
 */
class VikAppointmentsViewmanageserworkday extends JViewVAP
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
		
		$worktime = null;
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$worktime = $dbo->loadObject();
		}

		if (empty($worktime))
		{
			$worktime = (object) $this->getBlankItem();
		}

		// use worktime data stored in user state
		$this->injectUserStateData($worktime, 'vap.serworkday.data');
		
		$this->worktime = $worktime;

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
			JToolBarHelper::title(JText::translate('VAPSERWORKDAYEDITTITLE'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPSERWORKDAYNEWTITLE'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('serworkday.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('serworkday.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('serworkday.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		if ($type == 'edit' && $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2copy('serworkday.savecopy', JText::translate('VAPSAVEASCOPY'));
		}

		JToolBarHelper::cancel('serworkday.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	 A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		$input = JFactory::getApplication()->input;

		return array(
			'id'          => 0,
			'id_employee' => $input->get('id_employee', 0, 'uint'),
			'id_service'  => $input->get('id_service', 0, 'uint'),
			'day'         => 1,
			'fromts'      => 480,
			'endts'       => 780,
			'ts'          => -1,
			'tsdate'      => null,
			'closed'      => 0,
			'id_location' => 0,
		);
	}
}
