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

VAPLoader::import('models.configuration', VAPADMIN);

/**
 * VikAppointments applications configuration model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfigapp extends VikAppointmentsModelConfiguration
{
	/**
	 * Hook identifier for triggers.
	 *
	 * @var string
	 */
	protected $hook = 'ConfigApp';

	/**
	 * Validates and prepares the settings to be stored.
	 *
	 * @param 	array 	&$args  The configuration associative array.
	 *
	 * @return 	void
	 */
	protected function validate(&$args)
	{
		$app = JFactory::getApplication();

		// make sure the logs path exists
		if (empty($args['webhookslogspath']) || !is_dir($args['webhookslogspath']))
		{
			// logs path not provided or invalid, use the default one
			$args['webhookslogspath'] = $app->get('log_path', '');
		}

		if (isset($args['backupfolder']))
		{
			$tmp = $app->get('tmp_path');

			if (!$args['backupfolder'])
			{
				// path not specified, use temporary folder
				$args['backupfolder'] = $tmp;
			}

			$current = VAPFactory::getConfig()->get('backupfolder');

			if (!$current)
			{
				// path was missing, use temporary folder
				$current = $tmp;
			}

			// check whether the backup folder has been moved
			if ($current && $args['backupfolder'] && rtrim($current, DIRECTORY_SEPARATOR) !== rtrim($args['backupfolder'], DIRECTORY_SEPARATOR))
			{
				$backupModel = JModelVAP::getInstance('backup');

				// backup folder moved, try to copy all the existing overrides
				if (!$backupModel->moveArchives($args['backupfolder']))
				{
					// iterate all errors and display them
					foreach ($backupModel->getErrors() as $error)
					{
						$app->enqueueMessage($error, 'warning');
					}
				}
			}
		}
	}
}
