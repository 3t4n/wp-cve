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
 * VikAppointments custom field number handler.
 *
 * @since 1.7
 */
class VAPCustomFieldNumber extends VAPCustomField
{
	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		return JText::translate('VAPCUSTOMFTYPEOPTION8');
	}

	/**
	 * Extracts the value of the custom field and applies any
	 * sanitizing according to the settings of the field.
	 *
	 * @param 	array  &$args  The array data to fill-in in case of
	 *                         specific rules (name, e-mail, etc...).
	 *
	 * @return 	mixed  A scalar value of the custom field.
	 */
	protected function extract(&$args)
	{
		// get field settings
		$settings = $this->getSettings();

		// extract value by using the parent class
		$value = (float) parent::extract($args);

		// if min setting exists, make sure the value is not lower
		if (isset($settings['min']) && strlen($settings['min']))
		{
			$value = max(array($value, (float) $settings['min']));
		}

		// if max setting exists, make sure the value is not higher
		if (isset($settings['max']) && strlen($settings['max']))
		{
			$value = min(array($value, (float) $settings['max']));
		}

		// if decimals are not supported, round the value
		if (empty($settings['decimals']))
		{
			$value = round($value);
		}

		return $value;
	}

	/**
	 * Returns an array of display data.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	array
	 */
	protected function getDisplayData(array $data)
	{
		// get field settings
		$settings = $this->getSettings();

		// set input range
		$data['min'] = isset($settings['min']) ? $settings['min'] : '';
		$data['max'] = isset($settings['max']) ? $settings['max'] : '';
		// set input step
		$data['step'] = !empty($settings['decimals']) ? 'any' : 1;

		return parent::getDisplayData($data);
	}
}
