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
 * VikAppointments employee area custom fields management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpeditcustfield extends JModelVAP
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

		// always force the group to "shop"
		$data['group'] = 0;

		$auth = VAPEmployeeAuth::getInstance();

		JFactory::getApplication()->setUserState('vap.emparea.field.data', $data);

		// get field model
		$fieldModel = JModelVAP::getInstance('customf');

		if (@$data['type'] == 'file' && isset($data['choose']))
		{
			// get media model to validate file types
			$media = JModelVAP::getInstance('media');
				
			// convert extensions string into an array
			$data['choose'] = preg_split("/\s*,\s*/", $data['choose']);

			// validate specified extensions against supported media types
			$data['choose'] = array_filter($data['choose'], function($type) use ($media)
			{
				// make sure the file type is allowed
				return $type && $media->detectMediaType($type);
			});

			if (empty($data['choose']))
			{
				// use default allowed extensions
				$data['choose'] = 'png, jpg, jpeg, pdf';
			}
			else
			{
				// merge valid types again
				$data['choose'] = implode(', ', $data['choose']);
			}
		}

		// auto assign to this employee
		$data['id_employee'] = $auth->id;

		// delegate save to field model
		$id = $fieldModel->save($data);

		if (!$id)
		{
			// obtain error from model
			$error = $fieldModel->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}

			return false;
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
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$result = false;

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// get rid of those records that do not belong to this employee
		$ids = array_values(array_filter($ids, function($id) use ($auth)
		{
			// make sure the employee can manage this record
			return $auth->manageCustomFields($id);
		}));

		if (!$ids)
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// delegate delete to field model
		return JModelVAP::getInstance('customf')->delete($ids);
	}

	/**
	 * Basic publish/unpublish implementation.
	 *
	 * @param   mixed    $ids    Either the record ID or a list of records.
	 * @param 	integer  $state  The publishing status.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function publish($ids, $state = 1, $alias = null)
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}
		
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// get rid of those records that do not belong to this employee
		$ids = array_values(array_filter($ids, function($id) use ($auth)
		{
			// make sure the employee can manage this record
			return $auth->manageCustomFields($id);
		}));

		if (!$ids)
		{
			// there's noting to publish/unpublish
			return false;
		}

		// delegate publish to field model
		return JModelVAP::getInstance('customf')->publish($ids, $state, $alias);
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @throws  Exception
	 */
	public function getTable($name = 'customf', $prefix = '', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}
