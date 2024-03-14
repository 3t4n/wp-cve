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

VAPLoader::import('libraries.import.object');

/**
 * Class used to handle an import event for the COUPONS.
 *
 * @see   ImportObject
 * @since 1.6
 */
class ImportObjectCoupons extends ImportObject
{
	/**
	 * Overloaded bind function.
	 *
	 * @param 	object 	 &$data  The object of the record to import.
	 * @param 	array 	 $args 	 Associative list of additional parameters.
	 *
	 * @return 	boolean  True if the record should be imported, otherwise false.
	 */
	protected function bind(&$data, array $args = array())
	{
		// check whether the start date uses the military format as described in the sample file
		if (preg_match("/^[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2}$/", $data->dstart, $match))
		{
			// yes, convert it in timestamp
			$data->dstart = strtotime($data->dstart);
		}

		// check whether the end date uses the military format as described in the sample file
		if (preg_match("/^[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2}$/", $data->dend, $match))
		{
			// yes, convert it in timestamp
			$data->dend = strtotime($data->dend);
		}

		// call parent method to check the data integrity
		return parent::bind($data);
	}

	/**
	 * Builds the base query to export all the records.
	 * @since 	1.7  $app and $dbo are now included within the $options argument.
	 *
	 * @param 	JObject  $options  A registry of export options.
	 * @param 	string   $alias    The table alias.
	 *
	 * @return 	mixed 	 The query builder object.
	 *
	 * @uses 	getColumns()
	 * @uses 	getTable()
	 * @uses 	getPrimaryKey()
	 */
	protected function buildExportQuery($options, $alias = 'c')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');
		
		$filters = array();
		$filters['keys'] 	 = $app->getUserStateFromRequest('vapcoupons.keys', 'keys', '', 'string');
		$filters['type'] 	 = $app->getUserStateFromRequest('vapcoupons.type', 'type', 0, 'uint');
		$filters['value'] 	 = $app->getUserStateFromRequest('vapcoupons.value', 'value', 0, 'uint');
		$filters['status'] 	 = $app->getUserStateFromRequest('vapcoupons.status', 'status', 0, 'uint');
		$filters['id_group'] = $app->getUserStateFromRequest('vapcoupons.group', 'id_group', -1, 'int');

		$q = parent::buildExportQuery($options, $alias);

		// search filter
		if (strlen($filters['keys']))
		{
			$q->where($dbo->qn('c.code') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"));
		}

		// type filter (GIFT or PERMANENT)
		if ($filters['type'])
		{
			$q->where($dbo->qn('c.type') . ' = ' . $filters['type']);
		}

		// value filter (PERCENT or TOTAL)
		if ($filters['value'])
		{
			$q->where($dbo->qn('c.percentot') . ' = ' . $filters['value']);
		}

		// status filter (ACTIVE, NOT ACTIVE or EXPIRED)
		if ($filters['status'] == 1)
		{
			$q->where(array(
				$dbo->qn('c.dend') . ' > 0',
				$dbo->qn('c.dend') . ' < ' . time(),
			));
		}
		else if ($filters['status'] == 2)
		{
			$q->andWhere(array(
				$dbo->qn('c.dend') . ' <= 0',
				time() . ' BETWEEN ' . $dbo->qn('c.dstart') . ' AND ' . $dbo->qn('c.dend'),
			), 'OR');
		}
		else if ($filters['status'] == 3)
		{
			$q->where(array(
				$dbo->qn('c.dstart') . ' > 0',
				$dbo->qn('c.dstart') . ' > ' . time(),
			));
		}

		// group filter
		if ($filters['id_group'] != -1)
		{
			$q->where($dbo->qn('c.id_group') . ' = ' . $filters['id_group']);
		}

		return $q;
	}
}
