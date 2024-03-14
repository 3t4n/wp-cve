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
 * VikAppointments custom field textarea handler.
 *
 * @since 1.7
 */
class VAPCustomFieldTextarea extends VAPCustomField
{
	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		return JText::translate('VAPCUSTOMFTYPEOPTION2');
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

		if (empty($settings['editor']))
		{
			// extract value by using the parent class
			return parent::extract($args);
		}

		$input = JFactory::getApplication()->input;

		// retrieve text by stripping any unsafe tag
		return JComponentHelper::filterText($input->get($this->getID(), '', 'raw'));
	}

	/**
	 * Returns the HTML of the field input.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return  string  The HTML of the input.
	 */
	protected function getInput($data)
	{
		// get field settings
		$settings = $this->getSettings();

		if (!empty($settings['editor']))
		{
			// use a different type of input in case of editor
			return JLayoutHelper::render('form.fields.editor', $data);
		}

		// use parent to render input field
		return parent::getInput($data);
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
		// get display data from parent
		$data = parent::getDisplayData($data);

		// get field settings
		$settings = $this->getSettings();

		if (!empty($settings['editor']))
		{
			// do not use the ID equals to the field name because TinyMCE uses
			// the name as ID, which would cause a conflict
			$data['id'] .= '-editor';
		}

		return $data;
	}
}
