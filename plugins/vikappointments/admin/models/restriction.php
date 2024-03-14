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
 * VikAppointments special restriction model.
 *
 * @since 1.7
 */
class VikAppointmentsModelRestriction extends JModelVAP
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

		// attempt to save the relation
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		if (isset($data['services']))
		{
			// get service-restriction model
			$model = JModelVAP::getInstance('serrestrassoc');
			// define relations
			$model->setRelation($id, $data['services']);
		}
		
		return $id;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any service-restriction relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_restr_assoc'))
			->where($dbo->qn('id_restriction') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get service-restriction model
			$model = JModelVAP::getInstance('serrestrassoc');
			// delete relations
			$model->delete($assoc_ids);
		}

		return true;
	}
}
