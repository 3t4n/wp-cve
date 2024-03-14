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
 * VikAppointments employee area settings management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpsettingsman extends JModelVAP
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
		$auth = VAPEmployeeAuth::getInstance();

		$settings = $auth->getSettings();

		// validate ZIP code
		if ($data['zip_field_id'] > 0 && !$auth->manageCustomFields($data['zip_field_id']))
		{
			$data['zip_field_id'] = 0;
		}

		// validate list limit
		if (!in_array($data['listlimit'], array(5, 10, 15, 20, 50)))
		{
			$data['listlimit'] = $settings->listlimit;
		}

		// validate list position
		if (!in_array($data['listposition'], array(1, 2)))
		{
			$data['listposition'] = $settings->listposition;
		}

		// validate list ordering
		if (!in_array($data['listordering'], array('ASC', 'DESC')))
		{
			$data['listordering'] = $settings->listordering;
		}

		// validate number of calendars
		if (!in_array($data['numcals'], array(1, 3, 6, 9, 12)))
		{
			$data['numcals'] = $settings->numcals;
		}

		// validate first month
		if ($data['firstmonth'] < 1 || $data['firstmonth'] > 12)
		{
			$data['firstmonth'] = 0;
		}

		// fill SYNC KEY if empty
		if (empty($data['synckey']))
		{
			$data['synckey'] = VikAppointments::generateSerialCode(12, 'employee-synckey');
		}

		if (isset($data['zipcodesfrom']))
		{
			$data['zipcodes'] = array();

			// stringify ZIP codes
			foreach ($data['zipcodesfrom'] as $i => $from)
			{
				$to = !empty($data['zipcodesto'][$i]) ? $data['zipcodesto'][$i] : $from;

				if (empty($from) && !empty($to))
				{
					// from empty, to filled-in
					$from = $to;
				}

				if ($from && $to)
				{	
					$data['zipcodes'][] = array(
						'from' => $from,
						'to'   => $to,
					);
				}
			}

			unset($data['zipcodesfrom']);
			unset($data['zipcodesto']);	
		}

		if (isset($data['zipcodes']) && !is_string($data['zipcodes']))
		{
			// stringify ZIP codes
			$data['zipcodes'] = json_encode($data['zipcodes']);
		}

		$dispatcher = VAPFactory::getEventDispatcher();

		/**
		 * Trigger event to allow the plugins to bind the object that
		 * is going to be saved.
		 *
		 * @param 	array 	 &$data  The array to bind.
		 * @param 	JModel   $model  The current model instance. (@since 1.7)
		 *
		 * @return 	boolean  True on success, false otherwise.
		 *
		 * @since 	1.6.6
		 */
		if ($dispatcher->false('onBeforeSaveEmployeeSettings', array(&$data, $this)))
		{
			// a plugin prevented the saving process
			return false;
		}

		// inject employee ID and settings ID
		$data['id']          = $settings->id;
		$data['id_employee'] = $auth->id;

		// delegate save to employee settings model (save settings)
		$result = JModelVAP::getInstance('empsettings')->save($data);

		$employee = array(
			'id'       => $auth->id,
			'timezone' => $data['timezone'],
			'synckey'  => $data['synckey'],
		);

		// delegate save to employee model (save timezone and sync key)
		$result = JModelVAP::getInstance('employee')->save($employee) || $result;

		/**
		 * Trigger event to allow the plugins to make something after saving
		 * a record in the database.
		 *
		 * @param 	array 	 $data   The saved record.
		 * @param 	JModel   $model  The current model instance. (@since 1.7)
		 *
		 * @return 	void
		 *
		 * @since 	1.6.6
		 */
		$dispatcher->trigger('onAfterSaveEmployeeSettings', array($data, $this));

		return $result;
	}
}
