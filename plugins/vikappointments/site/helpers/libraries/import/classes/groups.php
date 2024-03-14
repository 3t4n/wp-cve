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
 * Class used to handle an import event for the GROUPS.
 *
 * @see   ImportObject
 * @since 1.7.3
 */
class ImportObjectGroups extends ImportObject
{
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
	protected function buildExportQuery($options, $alias = 'g')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');
		
		// recover page type from the request
		$page_type = $app->input->getUint('page_type', 0);

		$filters = array();
		$filters['keysearch'] = $app->getUserStateFromRequest('vapgroups[' . $page_type . '].keysearch', 'keysearch', '', 'string');

		$q = parent::buildExportQuery($options, $alias);

		// init where to prevent errors with andWhere search
		$q->where(1);

		// search filter
		if (strlen($filters['keysearch']))
		{
			$q->andWhere(array(
				$dbo->qn('g.name') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('g.description') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
			), 'OR');
		}

		return $q;
	}
}
