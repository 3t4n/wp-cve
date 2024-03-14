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

/**
 * VikAppointments custom field city rule dispatcher.
 *
 * @since 1.7
 */
class VAPCustomFieldRuleCity extends VAPCustomFieldRule
{
	/**
	 * Returns the name of the rule.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAPCUSTFIELDRULE5');
	}

	/**
	 * Dispatches the field rule.
	 *
	 * @param 	mixed  $value  The value of the field set in request.
	 * @param 	array  &$args  The array data to fill-in in case of
	 *                         specific rules (name, e-mail, etc...).
	 * @param 	mixed  $field  The custom field object.
	 *
	 * @return 	void
	 */
	public function dispatch($value, &$args, $field)
	{
		// in case of multiple fields with city rule, use only
		// the first specified one
		if (empty($args['billing_city']))
		{
			// fill city column with field value
			$args['billing_city'] = $value;
		}
	}
}
