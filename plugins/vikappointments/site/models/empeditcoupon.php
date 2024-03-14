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
 * VikAppointments employee area coupon management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpeditcoupon extends JModelVAP
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

		$auth = VAPEmployeeAuth::getInstance();

		JFactory::getApplication()->setUserState('vap.emparea.coupon.data', $data);

		// get coupon model
		$couponModel = JModelVAP::getInstance('coupon');

		// validation
		$data['max_quantity'] = max(array(1, $data['max_quantity']));

		// assign to this employee only
		$data['employees'] = array($auth->id);

		if (!empty($data['services']))
		{
			// load all the services assigned to this employee
			$services = JModelVAP::getInstance('employee')->getServices($auth->id, $strict = false);

			// take only the ID of the services
			$services = array_map(function($elem)
			{
				return $elem->id;
			}, $services);

			// get rid of those services that do not belong to the employee
			$data['services'] = array_intersect($data['services'], $services);
		}

		// delegate save to coupon model
		$id = $couponModel->save($data);

		if (!$id)
		{
			// obtain error from model
			$error = $couponModel->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}

			return false;
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
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$result = false;

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// get rid of those records that do not belong to this employee
		$ids = array_values(array_filter($ids, function($id) use ($auth)
		{
			// make sure the employee can manage this record
			return $auth->manageCoupons($id);
		}));

		if (!$ids)
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// delegate delete to coupon model
		return JModelVAP::getInstance('coupon')->delete($ids);
	}
}
