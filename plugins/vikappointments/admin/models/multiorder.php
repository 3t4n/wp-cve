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
 * VikAppointments multi-order (appointments) model.
 *
 * @since 1.7
 */
class VikAppointmentsModelMultiorder extends JModelVAP
{
	/**
	 * Internal property used to keep the reservation saved data.
	 *
	 * @var arary|null
	 */
	protected $_reservationData = null;

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		// check whether we should send a notification to the customer
		$notify = isset($data['notifycust']) ? (bool) $data['notifycust'] : false;
		// clear flag to prevent double notifications
		$data['notifycust'] = false;

		// look for add discount action
		$add_discount = isset($data['add_discount']) ? $data['add_discount'] : null;
		// clear flag to avoid applying the discount through reservation model
		$data['add_discount'] = null;

		// look for remove discount action
		$remove_discount = isset($data['remove_discount']) ? $data['remove_discount'] : null;
		// clear flag to avoid removing the discount through reservation model
		$data['remove_discount'] = null;

		if (empty($data['id']))
		{
			// force multi-order identifier
			$data['id_parent'] = -1;
		}

		// attempt to save data of parent order
		$id = $model->save($data);

		if (!$id)
		{
			// get error from model
			$error = $model->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}

			// an error occurred, do not go ahead
			return false;
		}

		// register saved data
		$this->_reservationData = $model->getData();

		// check whether we should apply or delete a discount
		if ($add_discount)
		{
			$this->addDiscount($id, $add_discount);
		}
		else if ($remove_discount)
		{
			$this->removeDiscount($id);
		}

		if ($notify)
		{
			// send e-mail notification
			$model->sendMailNotification($id);
		}

		return $id;
	}

	/**
	 * Returns a list of children assigned to the specified multi-order.
	 *
	 * @param 	integer  $id       The parent ID.
	 * @param 	mixed    $columns  Either a string or an array containing all the
	 *                             columns to return.
	 *
	 * @return 	array    An array of children.
	 */
	public function getChildren($id, $columns = null)
	{
		$dbo = JFactory::getDbo();

		if (!$columns)
		{
			// return only the ID
			$columns = 'id';
		}

		$q = $dbo->getQuery(true);

		if (is_string($columns))
		{
			if ($columns === '*')
			{
				// select all columns
				$q->select('*');
			}
			else
			{
				// select only the specified column
				$q->select($dbo->qn($columns));
			}
		}
		else
		{
			foreach ($columns as $k => $v)
			{
				if (is_numeric($k))
				{
					// linear array, use value as column name
					$q->select($dbo->qn($v));
				}
				else
				{
					// associative array, use the key as column
					// name and the value as alias
					$q->select($dbo->qn($k, $v));
				}
			}
		}
	
		$q->from($dbo->qn('#__vikappointments_reservation'));
		$q->where($dbo->qn('id_parent') . ' = ' . (int) $id);
		$q->where($dbo->qn('id_parent') . ' <> ' . $dbo->qn('id'));
		$q->order($dbo->qn('id') . ' ASC');

		$dbo->setQuery($q);
		
		if ($columns === '*' || is_array($columns))
		{
			// return a list of objects
			return $dbo->loadObjectList();
		}
		
		// returns an array of scalar values matching the given column
		return $dbo->loadColumn() ?: [];
	}

	/**
	 * Adds a discount to the specified reservation.
	 *
	 * @param 	integer  $id      The order ID.
	 * @param 	mixed    $coupon  Either a coupon code or an array/object
	 *                            containing its details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function addDiscount($id, $coupon)
	{
		// get coupon model
		$couponModel = JModelVAP::getInstance('coupon');

		if (is_string($coupon))
		{
			// get coupon code details
			$coupon = $couponModel->getCoupon($coupon);
		}
		else
		{
			// treat as object
			$coupon = (object) $coupon;
		}

		// make sure we have a valid coupon code
		if (!$coupon || !isset($coupon->value))
		{
			// invalid/missing coupon
			$this->setError('Missing coupon code');

			return false;
		}

		// load all children appointments
		$children = $this->getChildren($id, array('id', 'total_cost'));

		if (!$children)
		{
			// no appointments assigned to the parent order
			$this->setError(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));

			return false;
		}

		$total_c = 0;

		// calculate total number of quantity
		foreach ($children as $child)
		{
			$total_c += (float) $child->total_cost;
		}

		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		// load reservation details
		$table = $model->getTable();
		$table->load((int) $id);

		// create a clone of the coupon
		$_discount = clone $coupon;
		// unset coupon code from discount to avoid redeeming it more than once
		$_discount->code = '';

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon'     => '',
		);

		// apply discount one by one
		foreach ($children as $i => $child)
		{
			// check if we have a fixed amount
			if (!empty($coupon->percentot) && $coupon->percentot == 2)
			{
				if ($i < count($children) - 1)
				{
					// fixed discount, apply proportionally according to
					// the total cost of the items
					$percentage = $child->total_cost * 100 / $total_c;
					$disc_val = round($coupon->value * $percentage / 100, 2);

					// the discount cannot exceed the total price
					$disc_val = min(array($child->total_cost, $disc_val));
				}
				else
				{
					// We are fetching the last element of the list, instead of calculating the
					// proportional discount, we should subtract the total discount from the coupon
					// value, in order to avoid rounding issues. Let's take as example a coupon of
					// EUR 10 applied on 3 items. The final result would be 3.33 + 3.33 + 3.33,
					// which won't match the initial discount value of the coupon. With this
					// alternative way, the result would be: 10 - 3.33 - 3.33 = 3.34.
					$disc_val = $coupon->value - $orderData['discount'];
				}

				// overwrite discount object to properly apply a proportional discount
				$_discount->value     = $disc_val;
				$_discount->percentot = 2;
			}

			// apply discount to child appointment
			$model->addDiscount($child->id, $_discount);
			// load item details
			$item = $model->getItem($child->id);

			if ($item)
			{
				// update order totals
				$orderData['total_net']  += $item->total_net;
				$orderData['total_tax']  += $item->total_tax;
				$orderData['total_cost'] += $item->total_cost;
				$orderData['discount']   += $item->discount;
			}
		}

		if (!empty($coupon->code))
		{
			// save coupon data
			$orderData['coupon'] = $coupon;

			// redeem coupon usage
			$couponModel->redeem($coupon);
		}

		// update order details
		return $this->save($orderData);
	}

	/**
	 * Removes discount from the specified reservation.
	 *
	 * @param 	integer  $id  The order ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function removeDiscount($id)
	{
		// load all children appointments
		$children = $this->getChildren($id);

		if (!$children)
		{
			// no appointments assigned to the parent order
			$this->setError(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));

			return false;
		}

		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		// load reservation details
		$table = $model->getTable();
		$table->load((int) $id);

		if ($table->coupon_str)
		{
			// decode coupon string
			$coupon = explode(';;', $table->coupon_str);

			// unredeem coupon usage
			JModelVAP::getInstance('coupon')->unredeem($coupon[0]);
		}

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon_str' => '',
		);

		// remove discount one by one
		foreach ($children as $id_child)
		{
			// remove discount from child appointment
			$model->removeDiscount($id_child);
			// load item details
			$item = $model->getItem($id_child);

			if ($item)
			{
				// update order totals
				$orderData['total_net']  += $item->total_net;
				$orderData['total_tax']  += $item->total_tax;
				$orderData['total_cost'] += $item->total_cost;
				$orderData['discount']   += $item->discount;
			}
		}

		// update order details
		return $this->save($orderData);
	}

	/**
	 * Recalculates the totals of a multi-order.
	 *
	 * @param 	integer  $id  The order ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function recalculateTotals($id)
	{
		// load all children appointments
		$children = $this->getChildren($id);

		if (!$children)
		{
			// no appointments assigned to the parent order
			$this->setError(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));

			return false;
		}

		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		// load reservation details
		$table = $model->getTable();
		$table->load((int) $id);

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
		);

		// remove discount one by one
		foreach ($children as $id_child)
		{
			// load item details
			$item = $model->getItem($id_child);

			if ($item)
			{
				// update order totals
				$orderData['total_net']  += $item->total_net;
				$orderData['total_tax']  += $item->total_tax;
				$orderData['total_cost'] += $item->total_cost;
				$orderData['discount']   += $item->discount;
			}
		}

		// update order details
		return $this->save($orderData);
	}

	/**
	 * Returns the table properties, useful to retrieve the information
	 * that have been registered while saving a record.
	 *
	 * @return 	array
	 */
	public function getData()
	{
		return $this->_reservationData;
	}
}
