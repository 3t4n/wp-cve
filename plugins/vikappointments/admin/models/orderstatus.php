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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments order status model.
 *
 * @since 1.7
 */
class VikAppointmentsModelOrderstatus extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		if (empty($data['id']))
		{
			$dbo = JFactory::getDbo();

			/**
			 * Check whether the last order status registered for the given order owns
			 * the same status code. In that case, instead of creating a new record, we
			 * should update the latest one. This is actually a workaround used to 
			 * prevent the creation of duplicate order statuses.
			 *
			 * @since 1.7
			 */
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'status')))
				->from($dbo->qn('#__vikappointments_order_status'))
				->where($dbo->qn('id_order') . ' = ' . (int) @$data['id_order'])
				->where($dbo->qn('type') . ' = ' . $dbo->q(@$data['type']))
				->order($dbo->qn('id') . ' DESC');

			$dbo->setQuery($q, 0, 1);
			$prev = $dbo->loadObject();

			// make sure the status didn't change
			if ($prev && $prev->status == @$data['status'])
			{
				// just do an update
				$data['id'] = $prev->id;
			}
		}

		// attempt to save the order status
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		// get array data and cast to object for backward compatibility
		$saveData = (object) $this->getData();

		// trigger change event only while creating a new order status
		if (empty($data['id']))
		{
			// get rid of any underscore
			$type = str_replace('_', '', $data['type']);

			/**
			 * Trigger event to let the plugins be notified every time the status
			 * of the orders change. The event name is based on the instance
			 * type, such as "onStatusChangeReservation".
			 *
			 * @param 	object 	 $data  The order status details.
			 *
			 * @return 	void
			 *
			 * @since 	1.6.6
			 */
			VAPFactory::getEventDispatcher()->trigger('onStatusChange' . ucfirst($type), array($saveData));
		}
		
		return $id;
	}
}
