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
 * VikAppointments coupon model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCoupon extends JModelVAP
{
	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		// load item through parent
		$item = parent::getItem($pk, $new);

		if ($item && !$item->id)
		{
			// use random code
			$item->code = VikAppointments::generateSerialCode(12, 'coupon');
		}

		return $item;
	}

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

		// attempt to save the relation
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		if (isset($data['services']))
		{
			// get coupon-service model
			$model = JModelVAP::getInstance('couponservice');
			// define relations
			$model->setRelation($id, $data['services']);
		}

		if (isset($data['employees']))
		{
			// get coupon-employee model
			$model = JModelVAP::getInstance('couponemployee');
			// define relations
			$model->setRelation($id, $data['employees']);
		}
		
		return $id;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any coupon-service relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_coupon_service_assoc'))
			->where($dbo->qn('id_coupon') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get coupon-service model
			$model = JModelVAP::getInstance('couponservice');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any coupon-employee relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_coupon_employee_assoc'))
			->where($dbo->qn('id_coupon') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get coupon-employee model
			$model = JModelVAP::getInstance('couponemployee');
			// delete relations
			$model->delete($assoc_ids);
		}

		return true;
	}

	/**
	 * Returns the details of the specified coupon code.
	 *
	 * @param 	string  $code  The coupon code.
	 *
	 * @return 	mixed   The coupon details on success, null otherwise.
	 */
	public function getCoupon($code)
	{
		return $this->getItem(array('code' => $code));
	}

	/**
	 * Returns a list of services assigned to the specified coupon code.
	 *
	 * @param 	integer  $id  The coupon id.
	 *
	 * @return 	array
	 */
	public function getServices($id)
	{
		if ($id)
		{
			$dbo = JFactory::getDbo();

			// load any coupon-service relation
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id_service'))
				->from($dbo->qn('#__vikappointments_coupon_service_assoc'))
				->where($dbo->qn('id_coupon') . ' = ' . (int) $id);

			$dbo->setQuery($q);
			return $dbo->loadColumn();
		}

		return [];
	}

	/**
	 * Marks the specified coupon as used.
	 * In addition, removes the coupon if it should be deleted once
	 * the maximum number of usages is reached.
	 * 
	 * @param 	mixed 	 $coupon  Either a coupon code or an array/object.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function redeem($coupon)
	{
		if (is_string($coupon))
		{
			// coupon code given, recover details
			$coupon = $this->getCoupon($coupon);
		}
		else
		{
			// cast to object
			$coupon = (object) $coupon;
		}

		if (!$coupon || empty($coupon->id))
		{
			// invalid coupon
			return false;
		}

		// increase total usages
		$coupon->used_quantity++;

		// check whether we reached the maximum number of usages, the coupon
		// is a GIFT and it should be removed from the system
		if ($coupon->max_quantity - $coupon->used_quantity <= 0 && $coupon->remove_gift && $coupon->type == 2)
		{
			// delete coupon ID
			$result = $this->delete($coupon->id);
		}
		else
		{
			// prepare save data
			$data = array(
				'id'            => $coupon->id,
				'used_quantity' => $coupon->used_quantity,
			);

			// commit changes
			$result = (bool) $this->save($data);
		}

		return $result;
	}

	/**
	 * Restores the number of usages by one.
	 * 
	 * @param 	mixed 	 $coupon  Either a coupon code or an array/object.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function unredeem($coupon)
	{
		if (is_string($coupon))
		{
			// coupon code given, recover details
			$coupon = $this->getCoupon($coupon);
		}
		else
		{
			// cast to object
			$coupon = (object) $coupon;
		}

		if (!$coupon || empty($coupon->id))
		{
			// invalid coupon
			return false;
		}

		// decrease total usages
		$coupon->used_quantity--;


		// prepare save data
		$data = array(
			'id'            => $coupon->id,
			'used_quantity' => max(array(0, $coupon->used_quantity)),
		);

		// commit changes
		return (bool) $this->save($data);
	}
}
