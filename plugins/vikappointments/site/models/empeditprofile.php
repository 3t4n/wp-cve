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
 * VikAppointments employee area profile management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpeditprofile extends JModelVAP
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

		$data['id'] = $auth->id;

		JFactory::getApplication()->setUserState('vap.emparea.profile.data', $data);

		// extend fields validation
		$required = array(
			'firstname' => JText::translate('VAPMANAGEEMPLOYEE2'),
			'lastname'  => JText::translate('VAPMANAGEEMPLOYEE3'),
			'nickname'  => JText::translate('VAPMANAGEEMPLOYEE4'),
			'email'     => JText::translate('VAPMANAGEEMPLOYEE8'),
			'phone'     => JText::translate('VAPMANAGEEMPLOYEE10'),
		);

		foreach ($required as $key => $fieldName)
		{
			if (empty($data[$key]))
			{
				// register error message
				$this->setError(JText::sprintf('VAP_MISSING_REQ_FIELD', $fieldName));

				return false;
			}
		}

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->employees()
			->noRequiredCheckbox()
			->fetch();

		try
		{
			// load custom fields from request
			$cust_req = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = true);
		}
		catch (Exception $e)
		{
			// we probably have a missing field
			$this->setError($e);

			return false;
		}

		if (!empty($tmp['uploads']))
		{
			// inject uploads within the custom fields array
			$cust_req = array_merge($cust_req, $tmp['uploads']);
		}

		// inject custom fields within the employee table
		foreach ($cust_req as $k => $v)
		{
			$data['field_' . $k] = $v;
		}

		if (!empty($data['id_group']))
		{
			// make sure the group exists
			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_employee_group'))
				->where($dbo->qn('id') . ' = ' . (int) $data['id_group']);

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				$data['id_group'] = 0;
			}
		}

		// get media model
		$media = JModelVAP::getInstance('media');

		$image = $oldImage = null;

		// attempt to upload the new specified file
		if ($media->save(['file' => 'image']))
		{
			// register previous image
			$oldImage = $data['image'];

			// get saved image data
			$image = $media->getData();
			// replace image file
			$data['image'] = $image['id'];
		}

		$employeeModel = JModelVAP::getInstance('employee');

		// delegate save to employee model
		$result = $employeeModel->save($data);

		if (!$result)
		{
			// obtain error from model
			$error = $employeeModel->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}
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
}
