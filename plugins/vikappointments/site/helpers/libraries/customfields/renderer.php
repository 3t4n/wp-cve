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

VAPLoader::import('libraries.customfields.factory');
VAPLoader::import('libraries.customfields.loader');

/**
 * VikAppointments custom fields renderer class.
 *
 * @since 1.7
 */
abstract class VAPCustomFieldsRenderer
{
	/**
	 * Instance used to rewrite the style of the fields wrapper.
	 *
	 * @var VAPCustomFieldControl|null
	 */
	protected static $controlWrapper = null;

	/**
	 * Registers an additional path in which the layout files
	 * of the fields might be stored.
	 *
	 * @var string
	 */
	protected static $fieldsLayoutPath = null;

	/**
	 * Tries to populate the custom fields values according to the details
	 * of the currently logged-in user.
	 *
	 * @param 	mixed 	 &$data   Where to inject the fetched data.
	 * @param 	mixed 	 $fields  The custom fields list to display.
	 * 							  If the list is not an array, the method will load
	 * 							  all the custom fields that belong to the specified group.
	 * @param 	mixed    $user    The details of the user. If not specified, they will be loaded
	 *                            from the account of the currently logged-in user.
	 * @param 	boolean  $first   True whether the first name is usually
	 * 							  specified before the last name.
	 *
	 * @return 	void
	 */
	public static function autoPopulate(&$data, $fields = 0, $user = null, $first = true)
	{
		// make sure the custom fields specify at least a value
		if (is_array($data) && array_filter($data))
		{
			// user details already populated, we don't need to go ahead
			return;
		}

		$dispatcher = VAPFactory::getEventDispatcher();

		if (is_null($user))
		{
			/**
			 * Go ahead even if the user might not be logged in, so that we can
			 * properly fire the "onAutoPopulateCustomFields" hook.
			 * 
			 * @since 1.7.4
			 */
			$user = JFactory::getUser();
		}

		// treat user as object
		$user = (object) $user;

		// clone the user object
		$tmp = clone $user;

		if (empty($tmp->name))
		{
			// extract name from billing details
			$tmp->name = isset($user->purchaser_nominative) ? $user->purchaser_nominative : '';
		}

		if (empty($tmp->email))
		{
			// extract e-mail from billing details
			$tmp->email= isset($user->purchaser_mail) ? $user->purchaser_mail : '';
		}

		if (empty($tmp->phone))
		{
			// extract phone from billing details
			$tmp->phone = isset($user->purchaser_phone) ? $user->purchaser_phone : '';
		}

		$user = $tmp;

		/**
		 * Trigger hook to allow external plugins to prepare the user data
		 * before auto-populating the custom fields.
		 *
		 * @param 	mixed  &$user  The details of the user.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.4
		 */
		$dispatcher->trigger('onBeforeAutoPopulateCustomFields', array(&$user));

		// if not an array, get the fields from the DB using the specified section
		if (!is_array($fields))
		{	
			// get custom fields loader
			$loader = VAPCustomFieldsLoader::getInstance();
			
			if ($fields == 1)
			{
				// load custom fields for the employees
				$loader->employees();
			}
			else
			{
				// otherwise load default custom fields (for customers)
				$loader->customers();
			}

			$fields = $loader->fetch();
		}

		$nameFields = $mailFields = $phoneFields = array();

		foreach ($fields as $cf)
		{
			if ($cf['rule'] == 'nominative')
			{
				$nameFields[] = $cf;
			}
			else if ($cf['rule'] == 'email')
			{
				$mailFields[] = $cf;
			}
			else if ($cf['rule'] == 'phone')
			{
				$phoneFields[] = $cf;
			}
		}

		// check if we have only one nominative custom field
		if (count($nameFields) == 1)
		{
			// we have a generic nominative, use the full name
			$data[$nameFields[0]['name']] = $user->name;
		}
		else if (count($nameFields) > 1)
		{
			// get name chunks
			$chunks = preg_split("/\s+/", $user->name);

			// extract last name from the list
			$lname = array_pop($chunks);
			// join remaining chunks into the first name
			$fname = implode(' ', $chunks);

			if (!$fname)
			{
				// first name missing, switch with last name because
				// the customers usually writes the first name instead
				// of the last name
				$fname = $lname;
				$lname = '';
			}

			if ($first)
			{
				// show first name and last name
				$data[$nameFields[0]['name']] = $fname;
				$data[$nameFields[1]['name']] = $lname;
			}
			else
			{
				// show last name and first name
				$data[$nameFields[0]['name']] = $lname;
				$data[$nameFields[1]['name']] = $fname;	
			}
		}

		if ($mailFields)
		{
			// auto-populate only the first available field with the
			// e-mail address of the current user
			$data[$mailFields[0]['name']] = $user->email;
		}

		if ($phoneFields && !empty($user->phone))
		{
			// auto-populate only the first available field with the
			// phone number of the current user
			$data[$phoneFields[0]['name']] = $user->phone;
		}

		/**
		 * Trigger hook to allow external plugins to auto-populate the custom fields
		 * with other details that are not supported by default by the user instance.
		 *
		 * @param 	array  &$data   Where to inject the fetched data.
		 * @param 	array  $fields  The custom fields list to display.
		 * @param 	mixed  $user    The details of the user.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onAutoPopulateCustomFields', array(&$data, $fields, $user));
	}

	/**
	 * Returns the form HTML of the specified custom fields.
	 *
	 * @param 	mixed 	 $fields   The custom fields list to display.
	 * 							   If the list is not an array, the method will load
	 * 							   all the custom fields that belong to the specified group.
	 * @param 	mixed    $data     Either an array or an object containing the values to bind.
	 * @param 	mixed    $options  An array of display options (replaced "strict" arg @since 1.7).
	 *                             - strict       bool    True to take care of the "required" flag of the fields.
	 *                                                    Use false to make all the fields optional.
	 *                             - suffix       string  An optional suffix to append to the fields ID.
	 *                             - control      mixed   An optional control wrapper to use.
	 *                             - includepath  string  An optional path to use to include the layouts.
	 *
	 * @return 	string   The resulting HTML.
	 */
	public static function display($fields = 0, $data = array(), $options = true)
	{
		if (is_bool($options))
		{
			// BC. "strict" mode was given
			$options = array('strict' => $options);
		}
		else if (!isset($options['strict']))
		{
			// strict mode not specified, use default one
			$options['strict'] = true;
		}

		// if not an array, get the fields from the DB using the specified section
		if (!is_array($fields))
		{	
			// get custom fields loader
			$loader = VAPCustomFieldsLoader::getInstance();
			
			if ($fields == 1)
			{
				// load custom fields for the employees
				$loader->employees();
			}
			else
			{
				// otherwise load default custom fields (for customers)
				$loader->customers();
			}

			$fields = $loader->fetch();
		}

		$form = '';

		// check whether a specific control wrapper has been specified
		if (!isset($options['control']))
		{
			// nope, use the default one (if any)
			$options['control'] = static::$controlWrapper;
		}

		// check whether a specific path to include the fields layout
		// has been specified
		if (!isset($options['includepath']))
		{
			// nope, use the default one (if any)
			$options['includepath'] = static::$fieldsLayoutPath;
		}

		// treat data to bind as array
		$data = (array) $data;

		foreach ($fields as $cf)
		{
			if (isset($options['suffix']))
			{
				// include ID suffix
				$cf['idsuffix'] = $options['suffix'];
			}

			// create field instance
			$field = VAPCustomField::getInstance($cf);

			// overwrite control wrapper
			$field->setControl($options['control']);

			// include a custom layout path
			$field->setLayoutPath($options['includepath']);

			// get form field name
			$name = $field->getName();

			// prepare display data
			$args = array();

			if (isset($data[$name]))
			{
				// inject field value
				$args['value'] = $data[$name];
			}
			else if (isset($data['field_' . $name]))
			{
				// inject (employee) field value
				$args['value'] = $data['field_' . $name];
			}

			if (!$options['strict'])
			{
				// ignore required status
				$args['required'] = false;
			}

			/**
			 * Prevent the customers from editing a read-only custom field, which.
			 * can be filled in only once. Ignore if we are not in strict mode.
			 * 
			 * @since 1.7.2
			 */
			if ($options['strict'] && $field->get('readonly', false))
			{
				if ($field->get('type') === 'separator')
				{
					// avoid displaying a separator more than once
					if (array_filter($data))
					{
						continue;
					}
				}
				else
				{
					$has_value = false;

					// check whether a value was specified for this field
					if (isset($args['value']))
					{
						if (is_scalar($args['value']))
						{
							// check the number of characters
							$has_value = strlen((string) $args['value']);
						}
						else
						{
							// directly cast to bool
							$has_value = (bool) $args['value'];
						}
					}
					
					if ($has_value)
					{
						// force hidden type to prevent the manipulation of the custom field
						$field->set('type', 'hidden');
						$field->set('layout', 'hidden');
						$field->set('rule', '');
					}
				}
			}

			// render field
			$form .= $field->render($args);
		}

		return $form;
	}

	/**
	 * Returns the form HTML of the specified custom fields, which are used to collect
	 * the details of all the other attendees.
	 *
	 * @param 	integer  $attendee  The attendee number (starts from 1).
	 * @param 	mixed 	 $fields    The custom fields list to display.
	 * 							    If the list is not an array, the method will load
	 * 							    all the custom fields that belong to the specified group.
	 * @param 	mixed    $data      Either an array or an object containing the values to bind.
	 * @param 	mixed    $options   An array of display options (replaced "strict" arg @since 1.7).
	 *
	 * @return 	string   The resulting HTML.
	 */
	public static function displayAttendee($attendee, $fields = 0, $data = array(), $options = true)
	{
		if (is_bool($options))
		{
			// BC. "strict" mode was given
			$options = array('strict' => $options);
		}
		else if (!isset($options['strict']))
		{
			// strict mode not specified, use default one
			$options['strict'] = true;
		}

		// if not an array, get the fields from the DB using the specified section
		if (!is_array($fields))
		{	
			// get custom fields loader
			$loader = VAPCustomFieldsLoader::getInstance();
			
			if ($fields == 1)
			{
				// load custom fields for the employees
				$loader->employees();
			}
			else
			{
				// otherwise load default custom fields (for customers)
				$loader->customers();
			}

			$fields = $loader->fetch();
		}

		$form = '';

		// check whether a specific control wrapper has been specified
		if (!isset($options['control']))
		{
			// nope, use the default one (if any)
			$options['control'] = static::$controlWrapper;
		}

		// check whether a specific path to include the fields layout
		// has been specified
		if (!isset($options['includepath']))
		{
			// nope, use the default one (if any)
			$options['includepath'] = static::$fieldsLayoutPath;
		}

		// treat data to bind as array
		$data = (array) $data;

		foreach ($fields as $cf)
		{
			if (isset($options['suffix']))
			{
				// include ID suffix
				$cf['idsuffix'] = $options['suffix'];
			}

			// create field instance
			$field = VAPCustomField::getInstance($cf);

			// overwrite control wrapper
			$field->setControl($options['control']);

			// include a custom layout path
			$field->setLayoutPath($options['includepath']);

			// use the custom field for attendee form
			$field->set('attendee', (int) $attendee);

			if (!$field->get('repeat'))
			{
				// skip field in case it shouldn't be repeated
				continue;
			}

			// get form field name
			$name = $field->getName();

			// prepare display data
			$args = array();

			if (isset($data[$name]))
			{
				// inject field value
				$args['value'] = $data[$name];
			}

			if (!$options['strict'])
			{
				// ignore required status
				$args['required'] = false;
			}

			// render field
			$form .= $field->render($args);
		}

		return $form;
	}

	/**
	 * Checks whether there's at least an editable custom field.
	 *
	 * @param 	mixed 	 $fields  The custom fields list to display.
	 * 							  If the list is not an array, the method will load
	 * 							  all the custom fields that belong to the specified group.
	 * @param 	mixed    $data    Either an array or an object containing the values to bind.
	 *
	 * @return 	boolean  True if available, false otherwise.
	 * 
	 * @since 	1.7.2
	 */
	public static function hasEditableCustomFields($fields = 0, $data = [])
	{
		// if not an array, get the fields from the DB using the specified section
		if (!is_array($fields))
		{	
			// get custom fields loader
			$loader = VAPCustomFieldsLoader::getInstance();
			
			if ($fields == 1)
			{
				// load custom fields for the employees
				$loader->employees();
			}
			else
			{
				// otherwise load default custom fields (for customers)
				$loader->customers();
			}

			$fields = $loader->fetch();
		}

		foreach ($fields as $cf)
		{
			// create field instance
			$field = VAPCustomField::getInstance($cf);

			// check whether the custom field is readonly
			if (!$field->get('readonly'))
			{
				// field editable
				return true;
			}

			if ($field->get('type') === 'separator')
			{
				// ignore field
				continue;
			}

			$name = $field->getName();

			$value = '';

			if (isset($data[$name]))
			{
				$value = $data[$name];
			}
			else if (isset($data['field_' . $name]))
			{
				// inject (employee) field value
				$value = $data['field_' . $name];
			}

			$has_value = false;

			// check whether a value was specified for this field
			if (is_scalar($value))
			{
				// check the number of characters
				$has_value = strlen((string) $value);
			}
			else
			{
				// directly cast to bool
				$has_value = (bool) $value;
			}
			
			if (!$has_value)
			{
				// missing value... can edit the field
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks whether there's at least a repeatable custom field.
	 *
	 * @param 	mixed 	 $fields  The custom fields list to display.
	 * 							  If the list is not an array, the method will load
	 * 							  all the custom fields that belong to the specified group.
	 *
	 * @return 	boolean  True if available, false otherwise.
	 */
	public static function hasRepeatableFields($fields = 0)
	{
		// if not an array, get the fields from the DB using the specified section
		if (!is_array($fields))
		{	
			// get custom fields loader
			$loader = VAPCustomFieldsLoader::getInstance();
			
			if ($fields == 1)
			{
				// load custom fields for the employees
				$loader->employees();
			}
			else
			{
				// otherwise load default custom fields (for customers)
				$loader->customers();
			}

			$fields = $loader->fetch();
		}

		foreach ($fields as $cf)
		{
			// treat custom field as array
			$cf = (array) $cf;

			// check whether the custom field is repeatable
			if (!empty($cf['repeat']))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Sets the control handler to allow the style rewriting.
	 *
	 * @param 	mixed  $control  The handler or null.
	 *
	 * @return 	void
	 */
	public static function setControl(VAPCustomFieldControl $control = null)
	{
		static::$controlWrapper = $control;
	}

	/**
	 * Sets a custom path in which the system should search for
	 * the layout files to display.
	 *
	 * @param 	string 	$path  The folder path.
	 *
	 * @return 	void
	 */
	public static function setLayoutPath($path = null)
	{
		static::$fieldsLayoutPath = $path;
	}
}
