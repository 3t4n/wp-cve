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
 * VikAppointments employee area service management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpeditservice extends JModelVAP
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
		$dbo = JFactory::getDbo();

		$auth = VAPEmployeeAuth::getInstance();

		// set user state for being recovered again
		JFactory::getApplication()->setUserState('vap.emparea.service.data', $data);

		if (!empty($data['id_group']))
		{
			// make sure the group exists
			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_group'))
				->where($dbo->qn('id') . ' = ' . (int) $data['id_group']);

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				$data['id_group'] = 0;
			}
		}

		// get service model
		$serviceModel = JModelVAP::getInstance('service');

		$prev = null;

		if ($data['id'])
		{
			// get previous details
			$prev = $serviceModel->getItem($data['id']);
		}

		if (!empty($data['name']) && $data['name'] != $prev->name)
		{
			// recalculate alias when the name changes
			$data['alias'] = $data['name'];
		}

		// get media model
		$media = JModelVAP::getInstance('media');

		$image = $oldImage = null;

		// attempt to upload the new specified file
		if ($media->save(['file' => 'image']))
		{
			if (!empty($prev->image))
			{
				// register currently set image
				$oldImage = $prev->image;
			}

			// get saved image data
			$image = $media->getData();
			// replace image file
			$data['image'] = $image['id'];
		}

		if (!$data['id'])
		{
			// force author while creating a new record
			$data['createdby'] = $auth->jid;
		}

		// delegate save to service model
		$result = $serviceModel->save($data);

		if (!$result)
		{
			// obtain error from model
			$error = $serviceModel->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}
		}

		if ($result)
		{
			$assoc = $data;

			$assoc['id'] = 0;
			$assoc['id_service']  = $result;
			$assoc['id_employee'] = $auth->id;

			// create/update service-employee relation too
			JModelVAP::getInstance('serempassoc')->save($assoc);
		}

		if (!$result && $image)
		{
			// delete newly uploaded file in case of error
			$media->delete($image['id']);
		}

		if ($result && $oldImage)
		{
			// delete previous image on success
			$media->delete($oldImage);
		}

		return $result;
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

		$serviceModel = JModelVAP::getInstance('service');
		$assocModel   = JModelVAP::getInstance('serempassoc');

		$result = false;

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		foreach ($ids as $id)
		{
			if ($auth->manageServices($id))
			{
				// the employee is the owner of this service, delete it directly
				$result = $serviceModel->delete($id) || $result;
			}
			else
			{
				// we need to detect the service relation
				$assoc = $assocModel->getItem(array(
					'id_service'  => $id,
					'id_employee' => $auth->id,
				));

				if ($assoc)
				{
					// detect employee from this service
					$result = $assocModel->delete($assoc->id) || $result;
				}
			}
		}

		return $result;
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
	public function getTable($name = 'service', $prefix = '', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}
