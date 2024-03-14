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

VAPLoader::import('libraries.customfields.control');

/**
 * VikAppointments custom field holder.
 *
 * @since 1.7
 */
abstract class VAPCustomField extends JObject
{
	/**
	 * Creates a new instance for the specified custom field.
	 *
	 * @param 	mixed  $field  Either an array or an object holding the details
	 *                         of the custom field.
	 *
	 * @return 	self   A new custom field instance.
	 */
	final public static function getInstance($field)
	{
		if (is_string($field))
		{
			$field = array('type' => $field);
		}
		else
		{
			$field = (array) $field;
		}

		if (empty($field['type']))
		{
			// the type is mandatory in order to fetch the correct instance
			throw new Exception('Missing custom field type', 400);
		}

		/**
		 * Trigger hook to allow external plugins to include new types of custom
		 * fields that have been implemented out of this project. Plugins must
		 * include here the file holding the class of the field type.
		 *
		 * @param 	string  $type  The requested custom field type.
		 *
		 * @param 	string  The classname of the object.
		 *
		 * @since 	1.7
		 */
		$classname = VAPFactory::getEventDispatcher()->triggerOnce('onLoadCustomField', array($field['type']));

		if (!$classname)
		{
			// no attached plugins, attempt to load a default type
			if (!VAPLoader::import('libraries.customfields.types.' . $field['type']))
			{
				// unable to find a file for the specified type
				throw new Exception(sprintf('Custom field [%s] type not found', $field['type']), 404);
			}

			// create class name
			$classname = 'VAPCustomField' . ucfirst($field['type']);
		}

		if (!class_exists($classname))
		{
			// unable to find a class for the specified type
			throw new Exception(sprintf('Custom field [%s] class not found', $classname), 404);
		}

		// create instance
		$handler = new $classname($field);

		if (!$handler instanceof VAPCustomField)
		{
			// the class handler must inherit this class
			throw new Exception(sprintf('Custom field [%s] is not a valid instance', $classname), 404);
		}

		return $handler;
	}

	/**
	 * Instructs the custom field to work as a custom field for the attendees.
	 *
	 * @param 	mixed  $attendee  The attendee number or false.
	 *
	 * @return 	self   This object to support chaining. 
	 */
	public function setAttendee($attendee = false)
	{
		$this->set('attendee', $attendee);

		return $this;
	}

	/**
	 * Returns the form field ID.
	 *
	 * @return 	string
	 */
	public function getID()
	{
		// build default ID
		$id = 'vapcf' . $this->get('id', 0);

		if ($attendee = $this->get('attendee'))
		{
			// extend the ID in case of attendee field
			$id .= '_attendee_' . (int) $attendee;
		}

		return $id;
	}

	/**
	 * Returns the form field attribute ID.
	 *
	 * @return 	string
	 */
	public function getFormID()
	{
		// extract suffix from object, useful in case the custom fields have to be
		// displayed more than once within the same page
		$suffix = preg_replace("/[^a-zA-Z0-9_\-]+/", '', $this->get('idsuffix', ''));

		// build default ID
		$id = 'vapcf' . $suffix . $this->get('id', 0);

		if ($attendee = $this->get('attendee'))
		{
			// extend the ID in case of attendee field
			$id .= '_attendee_' . (int) $attendee;
		}

		return $id;
	}

	/**
	 * Returns the form field name.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		if ($this->get('group') == 1)
		{
			// return form name in case the custom field
			// belongs to the employees group
			return $this->formname;
		}

		return $this->name;
	}

	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		$type  = $this->get('type');
		$key = 'VAPCUSTOMFTYPEOPTION' . strtoupper($type);

		// try to translate the given language definition
		$name = JText::translate($key);

		if ($name === $key)
		{
			// translation not found, return plain type
			return $type;
		}

		return $name;
	}

	/**
	 * Children classes can override this method to alter the
	 * value to display.
	 *
	 * @param 	string 	$value  The value stored within the database.
	 *
	 * @param 	string  A readable text.
	 */
	public function getReadableValue($value)
	{
		if (is_array($value))
		{
			// join values
			return implode(', ', $value);
		}

		// return plain value by default
		return (string) $value;
	}

	/**
	 * Loads the input from the request and returns the
	 * manipulated value, ready for saving.
	 *
	 * @param 	array  &$args  The array data to fill-in in case of
	 *                         specific rules (name, e-mail, etc...).
	 *
	 * @return 	mixed  A scalar value of the custom field.
	 */
	final public function save(&$args)
	{
		// extract value from request
		$value = $this->extract($args);

		// validate field value
		if (!$this->validate($value))
		{
			// raise an error, the custom field is not valid
			throw new Exception(JText::translate('VAPERRINSUFFCUSTF'));
		}

		if (!is_null($value) && !is_scalar($value))
		{
			// cannot accept non-scalar values, JSON encode them
			$value = json_encode($value);
		}

		return $value;
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
		$input = JFactory::getApplication()->input;

		// treat by default as string
		return $input->get($this->getID(), '', 'string');
	}

	/**
	 * Validates the field value.
	 *
	 * @param 	mixed    $value  The field raw value.
	 *
	 * @return 	boolean  True if valid, false otherwise.
	 */
	protected function validate($value)
	{
		if ((int) $this->get('required') == 0)
		{
			// always return true in case of optional field
			return true;
		}

		// make sure the value is not empty
		return (is_array($value) && count($value)) || strlen((string) $value);
	}

	/**
	 * Sets a custom path in which the system should search for
	 * the layout files to display.
	 *
	 * @param 	string 	$path  The folder path.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function setLayoutPath($path = null)
	{
		$this->set('layoutpath', $path);

		return $this;
	}

	/**
	 * Renders the input field.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	string  The resulting HTML.
	 */
	public function render(array $data = array())
	{
		// prepare layout data
		$data = $this->getDisplayData($data);

		// try to dispatch the assigned rule to render a different layout
		$input = VAPCustomFieldsFactory::renderRule($this, $data);

		if (!$input)
		{
			// rules didn't define a specific layout, fallback to the default one
			$input = $this->getInput($data);
		}

		/**
		 * Do not display the field control in case of hidden input.
		 * 
		 * @since 1.7.2
		 */
		if ($this->get('type') === 'hidden' || $this->get('hidden'))
		{
			return $input;
		}

		// create field control
		return $this->getControl($data, $input);
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
		// attempt to use the specified layout, otherwise
		// use the layout related to the given field type
		$layout = $this->get('layout', $this->get('type'));

		// create layout file
		$layoutFile = new JLayoutFile('form.fields.' . $layout);

		if ($path = $this->get('layoutpath'))
		{
			// search layout files also within the specified path
			$layoutFile->addIncludePath($path);
		}

		// render input field
		return $layoutFile->render($data);
	}

	/**
	 * Sets the control handler to allow the style rewriting.
	 *
	 * @param 	mixed  $control  The handler or null.
	 *
	 * @return 	self   This instance to support chaining.
	 */
	public function setControl(VAPCustomFieldControl $control = null)
	{
		$this->set('control', $control);

		return $this;
	}

	/**
	 * Returns the HTML of the field.
	 *
	 * @param 	array   $data   An array of display data.
	 * @param 	string  $input  The HTML of the input to wrap.
	 *
	 * @return  string  The HTML of the input.
	 */
	protected function getControl($data, $input = null)
	{
		// check if we should use a specific wrapper
		$control = $this->get('control', null);

		// render input if not specified
		$input = is_null($input) ? $this->getInput($data) : $input;

		if ($control instanceof VAPCustomFieldControl)
		{
			// use custom renderer
			$html = $control->render($data, $input);
		}
		else
		{
			// create layout to open the control
			$openControl = new JLayoutFile('form.control.open');
			// create layout to close the control
			$closeControl = new JLayoutFile('form.control.close');

			if ($path = $this->get('layoutpath'))
			{
				// search layout files also within the specified path
				$openControl->addIncludePath($path);
				$closeControl->addIncludePath($path);
			}

			// use native rendering
			$html  = $openControl->render($data) . $input . $closeControl->render($data);
		}

		return $html;
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
		$data['value']       = isset($data['value']) ? $data['value'] : '';
		$data['label']       = $this->get('langname');
		$data['name']        = $this->getID();
		$data['id']          = !empty($data['id']) ? $data['id'] : $this->getFormID();
		$data['description'] = isset($data['description']) ? $data['description'] : $this->get('description');
		$data['field']       = $this->getProperties();
		$data['required']    = isset($data['required']) ? (bool) $data['required'] : $this->get('required', false);
		$data['class']       = isset($data['class']) ? $data['class'] : '';

		// add class to recognize custom fields
		$data['class'] = trim('custom-field' . ' ' . $data['class']);

		if ($data['required'])
		{
			$data['class'] .= ' required';
		}

		if ($this->get('multiple'))
		{
			// set multiple attribute
			$data['multiple'] = true;

			// normalize name to support arrays
			if (!preg_match("/\[\]$/", $data['name']))
			{
				$data['name'] .= '[]';
			}

			if (is_string($data['value']) && preg_match("/^\[/", $data['value']))
			{
				// JSON decode stored value
				$data['value'] = json_decode($data['value']);
			}
			else
			{
				// attempt to cast the value as fallback
				$data['value'] = $data['value'] ? (array) $data['value'] : array();
			}
		}

		// make ID safe
		$data['id'] = preg_replace("/[^a-zA-Z0-9_\-]+/", '_', $data['id']);

		return $data;
	}

	/**
	 * Returns an array of field settings.
	 *
	 * @return 	array
	 */
	protected function getSettings()
	{
		return (array) json_decode($this->get('choose', '{}'), true);
	}
}
