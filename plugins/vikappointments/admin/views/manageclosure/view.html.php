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
 * VikAppointments closure management view.
 *
 * @since 1.5
 */
class VikAppointmentsViewmanageclosure extends JViewVAP
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
		
		$closure = null;
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_reservation'))
				->where($dbo->qn('id') . ' = ' . $ids[0])
				->where($dbo->qn('closure') . ' = 1');

			$dbo->setQuery($q, 0, 1);
			$tmp = $dbo->loadObject();
			
			if ($tmp)
			{
				$checkin  = VAPDateHelper::sql2date($tmp->checkin_ts);
				$checkout = VAPDateHelper::sql2date(VikAppointments::getCheckout($tmp->checkin_ts, $tmp->duration));

				$closure = new stdClass;
				$closure->id           = $tmp->id;
				$closure->id_employees = array($tmp->id_employee);
				$closure->fromdate     = $tmp->checkin_ts;
				$closure->fromtime     = JHtml::fetch('vikappointments.time2min', $checkin->format('H:i', true));
				$closure->totime       = JHtml::fetch('vikappointments.time2min', $checkout->format('H:i', true));
			}
			else
			{
				// we probably tried to edit a real reservation, back to creation mode
				$type = 'new';
			}
		}

		if (empty($closure))
		{
			$closure = (object) $this->getBlankItem();
		}

		// use closure data stored in user state
		$this->injectUserStateData($closure, 'vap.closure.data');
		
		// set the toolbar
		$this->addToolBar($type);

		$this->closure = $closure;

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
		JToolBarHelper::title(JText::translate('VAPMAINTITLENEWCLOSURE'), 'vikappointments');	
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('closure.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('closure.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('closure.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		JToolBarHelper::cancel('closure.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 *
	 * @since 	1.7
	 */
	protected function getBlankItem()
	{
		return array(
			'id'           => 0,
			'id_employees' => array(),
			'fromdate'     => 'now',
			'fromtime'     => 480,
			'totime' 	   => 1020,
		);
	}
}
