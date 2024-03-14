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
 * Class used to handle an import event for the EMPLOYEES.
 *
 * @see   ImportObject
 * @since 1.7.3
 */
class ImportObjectEmployees extends ImportObject
{
	/**
	 * Builds the base query to export all the records.
	 * @since   1.7  $app and $dbo are now included within the $options argument.
	 *
	 * @param   JObject  $options  A registry of export options.
	 * @param   string   $alias    The table alias.
	 *
	 * @return  mixed    The query builder object.
	 *
	 * @uses    getColumns()
	 * @uses    getTable()
	 * @uses    getPrimaryKey()
	 */
	protected function buildExportQuery($options, $alias = 'e')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');

		$filters = array();
		$filters['keys']     = $app->getUserStateFromRequest('vapemployees.keys', 'keys', '', 'string');
		$filters['status']   = $app->getUserStateFromRequest('vapemployees.status', 'status', '', 'string');
		$filters['id_group'] = $app->getUserStateFromRequest('vapemployees.group', 'id_group', -1, 'int');

		$q = parent::buildExportQuery($options, $alias);

		// init where to prevent errors with andWhere search
		$q->where(1);

		/**
		 * Added status filter.
		 *
		 * @since 1.7
		 */
		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('e.listable') . ' = ' . (int) $filters['status']);
		}

		if ($filters['id_group'] != -1)
		{
			$cmp = $filters['id_group'] == 0 ? '<=' : '=';

			$q->where($dbo->qn('e.id_group') . ' ' . $cmp . ' ' . $filters['id_group']);
		}

		if (strlen($filters['keys']))
		{
			$key = $dbo->q("%{$filters['keys']}%");

			$where = array(
				$dbo->qn('e.firstname') . ' LIKE ' . $key,
				$dbo->qn('e.lastname') . ' LIKE ' . $key,
				$dbo->qn('e.email') . ' LIKE ' . $key,
			);

			$sprintf = 'CONCAT_WS(\' \', %s, %s) LIKE %s';

			/**
			 * Search also by full name (first + last and last + first).
			 *
			 * @since 1.7
			 */
			$where[] = sprintf($sprintf, $dbo->qn('e.firstname'), $dbo->qn('e.lastname'), $key);
			$where[] = sprintf($sprintf, $dbo->qn('e.lastname'), $dbo->qn('e.firstname'), $key);

			$q->andWhere($where, 'OR');
		}

		return $q;
	}
}
