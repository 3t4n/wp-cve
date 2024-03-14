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
 * Class used to handle an import event for the SERVICES.
 *
 * @see   ImportObject
 * @since 1.7.3
 */
class ImportObjectServices extends ImportObject
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
	protected function buildExportQuery($options, $alias = 's')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');

		$filters = array();
		$filters['keys']     = $app->getUserStateFromRequest('vapservices.keys', 'keys', '', 'string');
		$filters['status']   = $app->getUserStateFromRequest('vapservices.status', 'status', '', 'string');
		$filters['id_group'] = $app->getUserStateFromRequest('vapservices.group', 'id_group', -1, 'int');

		$q = parent::buildExportQuery($options, $alias);

		// init where to prevent errors with andWhere search
		$q->where(1);

		if (!empty($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('s.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('s.description') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('s.published') . ' = ' . (int) $filters['status']);
		}

		if ($filters['id_group'] != -1)
		{
			$cmp = $filters['id_group'] == 0 ? '<=' : '=';

			$q->where($dbo->qn('s.id_group') . ' ' . $cmp . ' ' . $filters['id_group']);
		}

		return $q;
	}
}
