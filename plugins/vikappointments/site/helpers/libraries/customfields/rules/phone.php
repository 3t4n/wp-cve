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
 * VikAppointments custom field phone number rule dispatcher.
 *
 * @since 1.7
 */
class VAPCustomFieldRulePhone extends VAPCustomFieldRule
{
	/**
	 * Returns the name of the rule.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAPCUSTFIELDRULE3');
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
		// in case of multiple fields with phone rule, use only
		// the first specified one
		if (empty($args['purchaser_phone']))
		{
			// fill phone column with field value
			$args['purchaser_phone'] = $value;

			$input = JFactory::getApplication()->input;

			// get dial code
			$dial = $input->get($field->getID() . '_dialcode', null, 'string');

			if ($dial)
			{
				// register dial code
				$args['purchaser_prefix'] = $dial;
			}

			// get country code
			$country = $input->get($field->getID() . '_country', null, 'string');

			if ($country)
			{
				// register country code
				$args['purchaser_country'] = $country;
			}
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
		// use a different layout for fields with phone number rule
		$field->set('layout', 'tel');

		// inject class name
		$data['class'] = (empty($data['class']) ? '' : $data['class'] . ' ') . 'phone-field';
	}
}
