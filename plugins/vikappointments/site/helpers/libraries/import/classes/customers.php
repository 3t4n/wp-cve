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
 * Class used to handle an import event for the CUSTOMERS.
 *
 * @see   ImportObject
 * @since 1.6
 */
class ImportObjectCustomers extends ImportObject
{
	/**
	 * Builds the base query to export all the records.
	 * @since 	1.7  $app and $dbo are now included within the $options argument.
	 *
	 * @param 	JObject  $options  A registry of export options.
	 * @param 	string   $alias    The table alias.
	 *
	 * @return 	mixed    The query builder object.
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
		$filters['keys'] 	= $app->getUserStateFromRequest('vapcustomers.keys', 'keys', '', 'string');
		$filters['type'] 	= $app->getUserStateFromRequest('vapcustomers.type', 'utype', 0, 'int');
		$filters['country'] = $app->getUserStateFromRequest('vapcustomers.country', 'country', '', 'string');

		$q = parent::buildExportQuery($options, $alias);

		// init where to prevent errors with andWhere search
		$q->where(1);

		// search filter
		if (strlen($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('c.billing_name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('c.billing_mail') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('c.billing_phone') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('c.company') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('c.vatnum') . ' = ' . $dbo->q("{$filters['keys']}"),
			));
		}

		// type filter
		if ($filters['type'] == 1)
		{
			// registered
			$q->where($dbo->qn('c.jid') . ' > 0');
		}
		else if ($filters['type'] == -1)
		{
			// guest
			$q->where($dbo->qn('c.jid') . ' <= 0');
		}

		// country filter
		if (strlen($filters['country']))
		{
			$q->where($dbo->qn('c.country_code') . ' = ' . $dbo->q($filters['country']));
		}

		return $q;
	}
}
