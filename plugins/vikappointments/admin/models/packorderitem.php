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
 * VikAppointments package order item model.
 *
 * @since 1.7
 */
class VikAppointmentsModelPackorderitem extends JModelVAP
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

		if (!empty($data['id']) && isset($data['used_app']))
		{
			// get table instance
			$table = $this->getTable();
			
			// try to load the existing record and check whether the number of
			// used appointments is going to change
			if ($table->load($data['id']) && (int) $data['used_app'] != (int) $table->used_app)
			{
				// The modified date is only used to check the last time an appointments
				// has been redeemed with the available packages. So we don't want to
				// have it updated every time the package order gets saved.
				$data['modifiedon'] = JDate::getInstance()->toSql();
			}
		}

		// attempt to save the record
		return parent::save($data);
	}

	/**
	 * Calculates the total discount of the specified items.
	 *
	 * @param   mixed  $ids  Either the record ID or a list of records.
	 *
	 * @return 	float  The resulting amount.
	 */
	public function getTotalDiscount($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('SUM(discount)')
			->from($dbo->qn('#__vikappointments_package_order_item'))
			->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);
		return (float) $dbo->loadResult();
	}
}
