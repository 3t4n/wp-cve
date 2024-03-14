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
 * VikAppointments status code management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagestatuscode extends JViewVAP
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
		
		$status = null;
		
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_status_code'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$status = $dbo->loadObject();
		}

		if (empty($status))
		{
			$status = (object) $this->getBlankItem();
		}

		// use status code data stored in user state
		$this->injectUserStateData($status, 'vap.statuscode.data');
		
		$this->status = $status;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string 	$type 	The request type (new or edit).
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITSTATUSCODE'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWSTATUSCODE'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('statuscode.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('statuscode.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('statuscode.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolBarHelper::cancel('statuscode.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'            => 0,
			'name'          => '',
			'description'   => '',
			'code'          => '',
			'color'         => ltrim(JHtml::fetch('vaphtml.color.preset'), '#'),
			'appointments'  => 1,
			'packages'      => 0,
			'subscriptions' => 0,
			'approved'      => 0,
			'reserved'      => 0,
			'expired'       => 0,
			'cancelled'     => 0,
			'paid'          => 0,
		);
	}
}
