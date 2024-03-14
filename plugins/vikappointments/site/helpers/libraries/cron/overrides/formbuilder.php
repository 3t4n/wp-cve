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
 * This class extends the CronFormBuilder methods to create
 * a custom structure of the configuration fields.
 *
 * @see 	VAPCronFormBuilder
 * @since 	1.5
 */
class VikAppointmentsCronFormBuilder extends VAPCronFormBuilder
{
	/**
	 * Builds the settings form based on the specified fields.
	 * The form is returned as string, nothing is echoed inside this function.
	 *
	 * @param 	array   $args  The associative array containing the 
	 *						   stored values of the settings.
	 *
	 * @return 	string 	The Html structure of the form.
	 */
	public function build($args = array())
	{
		$fields = array();

		foreach ($this->getFields() as $field)
		{
			if ($field->getType() == VAPCronFormField::HTML)
			{
				// if the type of the field is HTML register constraint
				// to display the specified value
				$field->addConstraint('html', $field->getDefaultValue());
			}
			else if ($field->getType() == VAPCronFormField::SEPARATOR)
			{
				// if the type of the field is SEPARATOR register constraints
				// to hide the label and use a specific style
				$field->addConstraint('class', 'custom-field');
				$field->addConstraint('hidden', true);
			}

			// register HTML attributes of field within the list
			$fields[$field->getName()] = $field->toArray();
		}

		// prepare display data
		$data = array(
			'fields' => $fields,
			'params' => $args,
			'prefix' => $this->classname ? rtrim($this->classname, '_') . '_' : '',
		);

		// render by using the apposite layout
		return JLayoutHelper::render('form.fields', $data);
	}
}
