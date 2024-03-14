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
 * VikAppointments sms api configuration model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfigsmsapi extends VikAppointmentsModelConfiguration
{
	/**
	 * Hook identifier for triggers.
	 *
	 * @var string
	 */
	protected $hook = 'ConfigSmsApi';

	/**
	 * Validates and prepares the settings to be stored.
	 *
	 * @param 	array 	&$args  The configuration associative array.
	 *
	 * @return 	void
	 */
	protected function validate(&$args)
	{
		if (isset($args['smsapito']) && is_array($args['smsapito']))
		{
			// stringify SMS API TO
			$args['smsapito'] = implode(',', $args['smsapito']);
		}

		if (isset($args['smsapifields']) && !is_string($args['smsapifields']))
		{
			// stringify SMS API fields
			$args['smsapifields'] = json_encode($args['smsapifields']);
		}

		if (isset($args['smstmplcust']) && !is_string($args['smstmplcust']))
		{
			// stringify SMS API contents (customer - single orders)
			$args['smstmplcust'] = json_encode($args['smstmplcust']);
		}

		if (isset($args['smstmplcustmulti']) && !is_string($args['smstmplcustmulti']))
		{
			// stringify SMS API contents (customer - multiple orders)
			$args['smstmplcustmulti'] = json_encode($args['smstmplcustmulti']);
		}
	}
}
