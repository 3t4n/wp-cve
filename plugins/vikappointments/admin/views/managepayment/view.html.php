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
 * VikAppointments payment management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagepayment extends JViewVAP
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
		
		$payment = null;
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_gpayments'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$payment = $dbo->loadObject();
		}

		if (empty($payment))
		{
			$payment = (object) $this->getBlankItem();
		}

		// use payment data stored in user state
		$this->injectUserStateData($payment, 'vap.payment.data');

		// get rid of file extension to properly select the correct driver
		$payment->file = preg_replace("/\.php$/", '', $payment->file);

		if ($payment->selfconfirm)
		{
			// always turn on auto-confirm in case of self-confirmation
			$payment->setconfirmed = 1;
		}

		// check if the current user is the owner of the payment:
		// global payment OR new payment OR current user equals to payment author
		$owner = $payment->id_employee == 0 || $payment->id <= 0 || $payment->createdby == JFactory::getUser()->id;
		
		$this->payment = $payment;
		$this->isOwner = $owner;
		
		// display the template (default.php)
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
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITPAYMENT'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWPAYMENT'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('payment.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('payment.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('payment.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolBarHelper::cancel('payment.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @param 	integer  $id_emp 	The ID of the employee.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'           => 0,
			'name'         => '',
			'file'         => '',
			'published'    => 0,
			'prenote'      => '',
			'note'         => '',
			'charge'       => 0,
			'id_tax'       => 0,
			'icontype'     => 0,
			'icon'         => '',
			'setconfirmed' => 0,
			'selfconfirm'  => 0,
			'appointments' => 1,
			'subscr'       => 0,
			'position'     => '',
			'level'        => 1,
			'trust'        => 0,
			'id_employee'  => 0,
			'createdby'    => 0,
		);
	}
}
