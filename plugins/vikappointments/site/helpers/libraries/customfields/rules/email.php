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
 * VikAppointments custom field e-mail rule dispatcher.
 *
 * @since 1.7
 */
class VAPCustomFieldRuleEmail extends VAPCustomFieldRule
{
	/**
	 * Returns the name of the rule.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAPCUSTFIELDRULE2');
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
		// in case of multiple fields with e-mail rule, use only
		// the first specified one
		if (empty($args['purchaser_mail']))
		{
			// fill e-mail column with field value
			$args['purchaser_mail'] = $value;
		}
	}

	/**
	 * Renders the field rule.
	 *
	 * @param 	array   &$data  An array of display data.
	 * @param 	mixed   $field  The custom field object.
	 *
	 * @return 	string  The HTML that will be used in place of the layout
	 *                  defined by the field. Omit this value to keep using
	 *                  the default HTML of the field.
	 */
	public function render(&$data, $field)
	{
		// use a different type for input with e-mail rule
		$data['type'] = 'email';

		// inject class name
		$data['class'] = (empty($data['class']) ? '' : $data['class'] . ' ') . 'mail-field';
	}
}
