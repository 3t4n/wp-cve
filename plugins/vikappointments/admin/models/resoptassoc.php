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
 * VikAppointments reservation-option relation model.
 *
 * @since 1.7
 */
class VikAppointmentsModelResoptassoc extends JModelVAP
{
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
			->from($dbo->qn('#__vikappointments_res_opt_assoc'))
			->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);
		return (float) $dbo->loadResult();
	}
}
