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

VAPLoader::import('libraries.order.factory');

/**
 * VikAppointments order info view.
 *
 * @since 1.0
 * @since 1.7 Renamed from VikAppointmentsViewpurchaserinfo.
 */
class VikAppointmentsVieworderinfo extends JViewVAP
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

		$oid  = $input->get('cid', array(0), 'uint');
		$back = $input->get('back', '', 'base64');

		if (count($oid) == 1)
		{
			$order = null;
			
			try
			{
				/**
				 * Load order details into the current language.
				 *
				 * @since 1.7
				 */
				$order = VAPOrderFactory::getAppointments($oid[0], JFactory::getLanguage()->getTag());
			}
			catch (Exception $e)
			{
				/**
				 * Fallback to check if we are looking for a closure.
				 *
				 * @since 1.6
				 */

				$q = $dbo->getQuery(true)
					->select($dbo->qn(array('r.id', 'r.checkin_ts', 'r.duration')))
					->select($dbo->qn('e.nickname'))
					->from($dbo->qn('#__vikappointments_reservation', 'r'))
					->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'))
					->where(array(
						$dbo->qn('r.id') . ' = ' . $oid[0],
						$dbo->qn('r.closure') . ' = 1',
					));

				$dbo->setQuery($q, 0, 1);
				$order = $dbo->loadObject();

				if ($order)
				{
					// switch template layout
					$this->setLayout('closure');
				}
				else
				{
					// order not found, use list layout, which will show
					// a warning message
					$this->setLayout('list');
				}
			}

			$this->order = $order;
		}
		else
		{
			$rows = array();

			// iterate specified IDs
			foreach ($oid as $id)
			{
				try
				{
					// record order details
					$order = VAPOrderFactory::getAppointments($id, JFactory::getLanguage()->getTag());

					// join order
					$rows[] = $order;
				}
				catch (Exception $e)
				{
					// catch silently and go aeahd
				}
			}

			// use list layout
			$this->setLayout('list');

			$this->rows = $rows;
		}
		
		$this->back = $back;

		// display the template
		parent::display($tpl);
	}
}
