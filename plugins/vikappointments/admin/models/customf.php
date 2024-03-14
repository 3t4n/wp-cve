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
 * VikAppointments custom field model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCustomf extends JModelVAP
{
	/**
	 * Returns a list of services assigned to the specified field.
	 *
	 * @param 	integer  $id  The field id.
	 *
	 * @return 	array
	 */
	public function getServices($id)
	{
		if ($id)
		{
			$dbo = JFactory::getDbo();

			// load any field-service relation
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id_service'))
				->from($dbo->qn('#__vikappointments_cf_service_assoc'))
				->where($dbo->qn('id_field') . ' = ' . (int) $id);

			$dbo->setQuery($q);
			return $dbo->loadColumn();
		}

		return array();
	}

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$table = $this->getTable();

		// manually register here the specified data into the user state
		$table->setUserStateData($data);

		// bind data into table
		if (!$table->bind($data))
		{
			// something went wrong, while binding
			$error = $table->getError($get_last = null, $string = true);

			if ($error)
			{
				// error found, register it within the model
				$this->setError($error);
			}

			return false;
		}

		// get employee model
		$employeeModel = JModelVAP::getInstance('employee');

		// in case of form name, make sure the employee database table
		// doesn't own yet a column with the same name
		if ($table->formname && $employeeModel->hasColumn($table->formname))
		{
			// column already occupied, register error
			$this->setError(JText::translate('VAPCUSTOMFFORMNAMEERR'));

			return false;
		}
		
		// attempt to save data
		if (!$table->save(array()))
		{
			// something went wrong, try to obtain an error
			$error = $table->getError($get_last = null, $string = true);

			if ($error)
			{
				// error found, register it within the model
				$this->setError($error);
			}

			return false;
		}

		// register save data within the internal state
		$this->set('data', $table->getProperties());

		$id = $table->id;

		if ($table->formname)
		{
			// finalize by creating the column on the employees database table
			if (!$employeeModel->createColumn($table->formname, $table->type))
			{
				// something went wrong, try to obtain an error from model
				$error = $employeeModel->getError($get_last = null, $string = true);

				if ($error)
				{
					// error found, register it within the model
					$this->setError($error);
				}

				// DO NOT break the flow becase the custom field has been
				// saved successfully. The caller of this method should now
				// look for an error even if the model returned a successful
				// response.
			}
		}

		if (isset($data['services']))
		{
			// get custom field-service model
			$model = JModelVAP::getInstance('customfservice');
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

		$dbo = JFactory::getDbo();

		// load the form name of all the custom fields that
		// we are going to delete (only for employees group)
		$q = $dbo->getQuery(true)
			->select($dbo->qn('formname'))
			->from($dbo->qn('#__vikappointments_custfields'))
			->where($dbo->qn('group') . ' = 1')
			->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($fields = $dbo->loadColumn())
		{
			// get employee model
			$model = JModelVAP::getInstance('employee');

			// drop fields one by one
			foreach ($fields as $field)
			{
				$model->dropColumn($field);
			}
		}

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		// load any assigned translation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_lang_customf'))
			->where($dbo->qn('id_customf') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langcustomf');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		// load any custom field-service relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_cf_service_assoc'))
			->where($dbo->qn('id_field') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get custom field-service model
			$model = JModelVAP::getInstance('customfservice');
			// delete relations
			$model->delete($assoc_ids);
		}

		return true;
	}
}
