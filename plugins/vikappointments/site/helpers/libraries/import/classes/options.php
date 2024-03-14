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
 * Class used to handle an import event for the OPTIONS.
 *
 * @see   ImportObject
 * @since 1.7.3
 */
class ImportObjectOptions extends ImportObject
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
	protected function buildExportQuery($options, $alias = 'o')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');

		$filters = array();
		$filters['keys']     = $app->getUserStateFromRequest('vapoptions.keys', 'keys', '', 'string');
		$filters['status']   = $app->getUserStateFromRequest('vapoptions.status', 'status', '', 'string');
		$filters['type']     = $app->getUserStateFromRequest('vapoptions.type', 'type', '', 'string');
		$filters['id_group'] = $app->getUserStateFromRequest('vapoptions.group', 'id_group', -1, 'int');

		$q = parent::buildExportQuery($options, $alias);

		// init where to prevent errors with andWhere search
		$q->where(1);

		if (strlen($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('o.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('o.description') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('o.published') . ' = ' . (int) $filters['status']);
		}

		if (strlen($filters['type']))
		{
			$q->where($dbo->qn('o.required') . ' = ' . (int) $filters['type']);
		}

		if ($filters['id_group'] != -1)
		{
			$q->where($dbo->qn('o.id_group') . ' = ' . $filters['id_group']);
		}

		return $q;
	}
}
