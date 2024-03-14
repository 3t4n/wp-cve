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
 * VikAppointments cron jobs configuration model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfigcron extends VikAppointmentsModelConfiguration
{
	/**
	 * Hook identifier for triggers.
	 *
	 * @var string
	 */
	protected $hook = 'ConfigCron';

	/**
	 * Validates and prepares the settings to be stored.
	 *
	 * @param 	array 	&$args  The configuration associative array.
	 *
	 * @return 	void
	 */
	protected function validate(&$args)
	{
		if (isset($args['cron_secure_key']) && strlen($args['cron_secure_key']) == 0)
		{
			// generate a new cron key
			$args['cron_secure_key'] = VikAppointments::generateSerialCode(12, 'cron-key');
		}
	}
}
