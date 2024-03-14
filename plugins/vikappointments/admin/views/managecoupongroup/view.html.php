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
 * @since 1.6
 */
class VikAppointmentsViewmanagecoupongroup extends JViewVAP
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
		
		// set the toolbar
		$this->addToolBar($type);
		
		$group = null;
		
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_coupon_group'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$group = $dbo->loadObject();
		}

		if (empty($group))
		{
			$group = (object) $this->getBlankItem();
		}

		// use group data stored in user state
		$this->injectUserStateData($group, 'vap.coupongroup.data');
		
		$this->group = $group;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'name'        => '',
			'description' => '',
			'id'          => 0,
		);
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
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITCOUPONGROUP'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWCOUPONGROUP'), 'vikappointments');
		}

		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('coupongroup.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('coupongroup.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('coupongroup.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolbarHelper::cancel('coupongroup.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
