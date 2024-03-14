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
 * VikAppointments coupon group model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCoupongroup extends JModelVAP
{
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

		// load any assigned coupon
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_coupon'))
			->where($dbo->qn('id_group') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($coupon_ids = $dbo->loadColumn())
		{
			// get coupon model
			$model = JModelVAP::getInstance('coupon');

			// detach group from coupons
			foreach ($coupon_ids as $coupon_id)
			{
				$data = array(
					'id'       => (int) $coupon_id,
					'id_group' => 0,
				);

				$model->save($data);
			}
		}

		return true;
	}
}
