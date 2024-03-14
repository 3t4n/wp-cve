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
 * Class used to handle an import event for the STATES.
 *
 * @see   ImportObject
 * @since 1.6
 */
class ImportObjectStates extends ImportObject
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
		// make sure the country ID is set in the arguments
		if (!isset($args['id_country']))
		{
			return false;
		}

		$data->id_country = $args['id_country'];

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
	protected function buildExportQuery($options, $alias = 's')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');
		
		// recover country ID from the request
		$id_country = $app->input->getUint('id_country', 0);

		$filters = array();
		$filters['keys']   = $app->getUserStateFromRequest('vapstates[' . $id_country . '].keys', 'keys', '', 'string');
		$filters['status'] = $app->getUserStateFromRequest('vapstates[' . $id_country . '].status', 'status', '', 'string');

		$q = parent::buildExportQuery($options, $alias);

		$q->where($dbo->qn('s.id_country') . ' = ' . $id_country);

		// search filter
		if (strlen($filters['keys']))
		{
			$q->where($dbo->qn('s.state_name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"));
		}

		// status filter
		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('s.published') . ' = ' . (int) $filters['status']);
		}

		return $q;
	}
}
