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
 * VikAppointments custom fields requestor class.
 *
 * @since 1.7
 */
abstract class VAPCustomFieldsRequestor
{
	/**
	 * Returns the custom fields values specified in the REQUEST.
	 *
	 * @param 	mixed 	 $fields 	The custom fields list to check for.
	 * 								If the list is not an array, the method will load
	 * 								all the custom fields that belong to the specified group.
	 * @param 	array 	 &$args 	The array data to fill-in in case of specific rules (name, e-mail, etc...).
	 * @param 	boolean  $strict 	True to raise an error when a mandatory field is missing.
	 *
	 * @return 	array 	 The lookup array containing the values of the custom fields.
	 *
	 * @throws 	Exception 	When a custom field is not valid.
	 */
	public static function loadForm($fields = 0, array &$args = null, $strict = true)
	{
		$lookup = array();

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

		// return an empty list in case there are no published fields
		if (!count($fields))
		{
			return $lookup;
		}

		if (is_null($args))
		{
			$args = array();
		}

		// if not exists, declare 'uploads' property to avoid warnings
		if (!isset($args['uploads']))
		{
			$args['uploads'] = array();
		}

		foreach ($fields as $cf)
		{
			// create field instance
			$field = VAPCustomField::getInstance($cf);

			// get form field name
			$name = $field->getName();

			try
			{
				// extract custom field from request
				$lookup[$name] = $field->save($args);

				// dispatch rule assigned to the custom field, if any
				VAPCustomFieldsFactory::dispatchRule($field, $lookup[$name], $args);
			}
			catch (Exception $e)
			{
				if ($strict)
				{
					// propagate exception
					throw $e;
				}

				if (!isset($lookup[$name]))
				{
					// register field with an empty string in order
					// to have it filled in within the resulting array
					$lookup[$name] = '';
				}
			}
		}

		return $lookup;
	}

	/**
	 * Returns the custom fields values specified in the REQUEST for the current attendee number.
	 *
	 * @param 	integer  $attendee  The attendee number (starts from 1).
	 * @param 	mixed 	 $fields 	The custom fields list to check for.
	 * 								If the list is not an array, the method will load
	 * 								all the custom fields that belong to the specified group.
	 * @param 	array 	 &$args 	The array data to fill-in in case of specific rules (name, e-mail, etc...).
	 * @param 	boolean  $strict 	True to raise an error when a mandatory field is missing.
	 *
	 * @return 	array 	 The lookup array containing the values of the custom fields.
	 *
	 * @throws 	Exception 	When a custom field is not valid.
	 */
	public static function loadFormAttendee($attendee, $fields = 0, array &$args = null, $strict = true)
	{
		$lookup = array();

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

		// return an empty list in case there are no published fields
		if (!count($fields))
		{
			return $lookup;
		}

		if (is_null($args))
		{
			$args = array();
		}

		// if not exists, declare 'uploads' property to avoid warnings
		if (!isset($args['uploads']))
		{
			$args['uploads'] = array();
		}

		foreach ($fields as $cf)
		{
			// create field instance
			$field = VAPCustomField::getInstance($cf);

			// use the custom field for attendee form
			$field->set('attendee', (int) $attendee);

			if (!$field->get('repeat'))
			{
				// skip field in case it shouldn't be repeated
				continue;
			}

			// get form field name
			$name = $field->getName();

			try
			{
				// extract custom field from request
				$lookup[$name] = $field->save($args);

				// dispatch rule assigned to the custom field, if any
				VAPCustomFieldsFactory::dispatchRule($field, $lookup[$name], $args);
			}
			catch (Exception $e)
			{
				if ($strict)
				{
					// propagate exception
					throw $e;
				}

				if (!isset($lookup[$name]))
				{
					// register field with an empty string in order
					// to have it filled in within the resulting array
					$lookup[$name] = '';
				}
			}
		}

		return $lookup;
	}
}
