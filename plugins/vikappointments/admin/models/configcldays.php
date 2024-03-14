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
 * VikAppointments closing days configuration model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfigcldays extends VikAppointmentsModelConfiguration
{
	/**
	 * Hook identifier for triggers.
	 *
	 * @var string
	 */
	protected $hook = 'ConfigClosingDays';

	/**
	 * Validates and prepares the settings to be stored.
	 *
	 * @param 	array 	&$args  The configuration associative array.
	 *
	 * @return 	void
	 */
	protected function validate(&$args)
	{
		// stringify closing days
		if (isset($args['closingdays']) && is_array($args['closingdays']))
		{
			$list = $args['closingdays'];
			$args['closingdays'] = array();

			foreach ($list as $day)
			{
				// try to JSON decode
				$json = json_decode($day);

				if ($json)
				{
					// JSON found, build string to save
					$chunks = array(
						$json->ts,
						$json->freq,
						$json->services ? implode(',', (array) $json->services) : '*',
					);

					$args['closingdays'][] = implode(':', $chunks);
				}
			}

			$args['closingdays'] = implode(';;', $args['closingdays']);
		}

		// stringify closing periods
		if (isset($args['closingperiods']) && is_array($args['closingperiods']))
		{
			$list = $args['closingperiods'];
			$args['closingperiods'] = array();

			foreach ($list as $period)
			{
				// try to JSON decode
				$json = json_decode($period);

				if ($json)
				{
					// JSON found, build string to save
					$chunks = array(
						$json->start,
						$json->end,
						$json->services ? implode(',', (array) $json->services) : '*',
					);

					$args['closingperiods'][] = implode(':', $chunks);
				}
			}

			$args['closingperiods'] = implode(';;', $args['closingperiods']);
		}
	}
}
