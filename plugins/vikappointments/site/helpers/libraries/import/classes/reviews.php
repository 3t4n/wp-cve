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
 * Class used to handle an import event for the REVIEWS.
 *
 * @see   ImportObject
 * @since 1.7
 */
class ImportObjectReviews extends ImportObject
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
	protected function buildExportQuery($options, $alias = 'r')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');
		
		$filters = array();
		$filters['keys']   = $app->getUserStateFromRequest('vapreviews.keys', 'keys', '', 'string');
		$filters['status'] = $app->getUserStateFromRequest('vapreviews.status', 'status', '', 'string');
		$filters['rating'] = $app->getUserStateFromRequest('vapreviews.rating', 'rating', 0, 'uint');
		$filters['type']   = $app->getUserStateFromRequest('vapreviews.type', 'type', '', 'string');
		$filters['lang']   = $app->getUserStateFromRequest('vapreviews.lang', 'lang', '', 'string');

		$q = parent::buildExportQuery($options, $alias);

		// join reviews with services and employees
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'));

		// init where to prevent errors with andWhere search
		$q->where(1);

		if (strlen($filters['keys']))
		{
			$q->andWhere(array(
				$dbo->qn('r.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('r.title') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('s.name') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
				$dbo->qn('e.nickname') . ' LIKE ' . $dbo->q("%{$filters['keys']}%"),
			), 'OR');
		}

		if (strlen($filters['status']))
		{
			$q->where($dbo->qn('r.published') . ' = ' . (int) $filters['status']);
		}

		if ($filters['rating'])
		{
			$q->where($dbo->qn('r.rating') . ' = ' . $filters['rating']);
		}

		if (!empty($filters['type']))
		{
			$q->where($dbo->qn('r.id_' . $filters['type']) . ' > 0');
		}

		if (!empty($filters['lang']))
		{
			$q->where($dbo->qn('r.langtag') . ' = ' . $dbo->q($filters['lang']));
		}

		return $q;
	}
}
