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
 * VikAppointments custom field rules dispatcher.
 *
 * @since 1.7
 */
abstract class VAPCustomFieldRule
{
	/**
	 * Creates a new instance for the specified field rule.
	 *
	 * @param 	string  $rule  The requested rule.
	 *
	 * @return 	self    A new instance.
	 */
	final public static function getInstance($rule)
	{
		// attempt to load a default rule
		if (!VAPLoader::import('libraries.customfields.rules.' . $rule))
		{
			// unable to find a file for the specified rule
			throw new Exception(sprintf('Custom field [%s] rule not found', $rule), 404);
		}

		// create class name
		$classname = 'VAPCustomFieldRule' . ucfirst($rule);

		if (!class_exists($classname))
		{
			// unable to find a class for the specified type
			throw new Exception(sprintf('Custom field [%s] rule class not found', $classname), 404);
		}

		// create instance
		$handler = new $classname();

		if (!$handler instanceof VAPCustomFieldRule)
		{
			// the class handler must inherit this class
			throw new Exception(sprintf('Custom field [%s] rule is not a valid instance', $classname), 404);
		}

		return $handler;
	}

	/**
	 * Returns a unique ID for this rule.
	 *
	 * @return 	string
	 */
	public function getID()
	{
		// create ID from class name
		return strtolower(preg_replace("/^VAPCustomFieldRule/i", '', get_class($this)));
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		$id  = $this->getID();
		$key = 'VAPCUSTFIELDRULE' . strtoupper($id);

		// try to translate the given language definition
		$name = JText::translate($key);

		if ($name === $key)
		{
			// translation not found, return plain ID
			return $id;
		}

		return $name;
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
	abstract public function dispatch($value, &$args, $field);

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
		// always define this method because the rules
		// might not have to display anything
		return '';
	}
}
