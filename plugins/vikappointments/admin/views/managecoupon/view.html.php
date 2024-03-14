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
 * VikAppointments manage coupon view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagecoupon extends JViewVAP
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
		
		$coupon = null;
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_coupon'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$coupon = $dbo->loadObject();

			if ($coupon)
			{
				// get coupon employees
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id_employee'))
					->from($dbo->qn('#__vikappointments_coupon_employee_assoc'))
					->where($dbo->qn('id_coupon') . ' = ' . $coupon->id);

				$dbo->setQuery($q);
				$coupon->employees = $dbo->loadColumn();

				// get coupon services
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id_service'))
					->from($dbo->qn('#__vikappointments_coupon_service_assoc'))
					->where($dbo->qn('id_coupon') . ' = ' . $coupon->id);
				
				$dbo->setQuery($q);
				$coupon->services = $dbo->loadColumn();
			}
		}

		if (empty($coupon))
		{
			$coupon = (object) $this->getBlankItem();
		}

		// use coupon data stored in user state
		$this->injectUserStateData($coupon, 'vap.coupon.data');
		
		$this->coupon = $coupon;

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
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITCOUPON'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWCOUPON'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('coupon.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('coupon.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('coupon.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolbarHelper::cancel('coupon.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	 A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'            => 0,
			'code'          => VikAppointments::generateSerialCode(12, 'coupon'),
			'type'          => 1,
			'percentot'     => 2,
			'value'         => 0.0,
			'mincost'       => 0.0,
			'pubmode'       => 1,
			'dstart'        => '',
			'dend'          => '',
			'lastminute'    => 0,
			'max_quantity'  => 1,
			'used_quantity' => 0,
			'maxperuser'    => 0,
			'remove_gift'   => 0,
			'applicable'    => '',
			'notes'         => '',
			'id_group'      => 0,
			'services'      => array(),
			'employees'     => array(),
		);
	}
}
