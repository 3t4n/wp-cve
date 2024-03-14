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
 * VikAppointments API plugin model.
 *
 * @since 1.7
 */
class VikAppointmentsModelApiplugin extends JModelVAP
{
	/**
	 * Method to delete one or more records.
	 *
	 * @param   mixed    $ids   Either the record ID or a list of records.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($ids = null)
	{
		if (!$ids)
		{
			return false;
		}

		$apis = VAPFactory::getApi();

		$res = false;

		foreach ($ids as $id)
		{
			// check if the file exists
			$path = $apis->getEventPath($id);

			if ($path)
			{
				$base = dirname($path);

				// just rename the plugin to make it inaccessible instead of drastically deleting it
				$res = rename($path, $base . DIRECTORY_SEPARATOR . '$__' . $id . '.php') || $res;
			}
		}

		return $res;
	}
}
