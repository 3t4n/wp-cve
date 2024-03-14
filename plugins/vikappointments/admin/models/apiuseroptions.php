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
 * VikAppointments API user-event config model.
 *
 * @since 1.7
 */
class VikAppointmentsModelApiuseroptions extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param   mixed  $data  Either an array or an object of data to save.
	 *
	 * @return  mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		// in case of new record, look for an existing one
		if (empty($data['id']) && !empty($data['id_login']) && !empty($data['id_event']))
		{
			// load existing record, if any
			$existing = $this->getOptions($data['id_login'], $data['id_event']);

			if ($existing)
			{
				// override record ID
				$data['id'] = $existing->id;
			}
		}

		// attempt to save the record
		return parent::save($data);
	}
	
	/**
	 * Returns the record assigned to the specified login/event.
	 *
	 * @param 	integer  $id_login  The login primary key.
	 * @param 	string   $id_event  The event unique name.
	 *
	 * @return 	mixed    The record object if exists, null otherwise.
	 */
	public function getOptions($id_login, $id_event)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_api_login_event_options'))
			->where($dbo->qn('id_login') . ' = ' . (int) $id_login)
			->where($dbo->qn('id_event') . ' = ' . $dbo->q($id_event));

		$dbo->setQuery($q, 0, 1);
		$data = $dbo->loadObject();

		if (!$data)
		{
			return null;
		}

		$data->options = (array) json_decode($data->options);

		return $data;
	}
}
