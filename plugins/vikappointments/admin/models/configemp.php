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
 * VikAppointments employees configuration model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfigemp extends VikAppointmentsModelConfiguration
{
	/**
	 * Hook identifier for triggers.
	 *
	 * @var string
	 */
	protected $hook = 'Configemp';

	/**
	 * Validates and prepares the settings to be stored.
	 *
	 * @param 	array 	&$args  The configuration associative array.
	 *
	 * @return 	void
	 */
	protected function validate(&$args)
	{
		if (isset($args['empassignser']) && is_array($args['empassignser']))
		{
			// stringify selected services
			$args['empassignser'] = implode(',', $args['empassignser']);
		}

		if (isset($args['empmaxser']))
		{
			// cannot be lower than 1
			$args['empmaxser'] = max(array(1, $args['empmaxser']));
		}

		if (!empty($args['empcreate']))
		{
			// if employees can create, then they can also attach existing services
			$args['empattachser'] = 1;
		}
	}
}
