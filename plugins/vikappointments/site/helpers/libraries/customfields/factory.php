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

VAPLoader::import('libraries.customfields.field');
VAPLoader::import('libraries.customfields.rule');

/**
 * VikAppointments custom fields factory.
 *
 * @since 1.7
 */
final class VAPCustomFieldsFactory
{
	/**
	 * Returns a list of supported custom fields types.
	 *
	 * @return 	array
	 */
	public static function getSupportedTypes()
	{
		$types = array();

		// load all files inside types folder
		$files = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'types' . DIRECTORY_SEPARATOR . '*.php');

		foreach ($files as $file)
		{
			// extract type from file name
			$type = preg_replace("/\.php$/i", '', basename($file));

			try
			{
				// try to instantiate the type
				$field = VAPCustomField::getInstance($type);

				// attach type to list
				$types[$type] = $field->getType();
			}
			catch (Exception $e)
			{
				// catch error and go ahead
			}
		}

		/**
		 * Trigger hook to allow external plugins to support custom types.
		 * New types have to be appended to the given associative array.
		 * The key of the array is the unique ID of the type, the value is
		 * a readable name of the type.
		 *
		 * @param 	array  &$types  An array of types.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onLoadCustomFieldsTypes', array(&$types));

		// sort types by ascending name and preserve keys
		asort($types);

		return $types;
	}

	/**
	 * Returns a list of supported rules.
	 *
	 * @return 	array
	 */
	public static function getSupportedRules()
	{
		$rules = array();

		// load all files inside rules folder
		$files = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'rules' . DIRECTORY_SEPARATOR . '*.php');

		foreach ($files as $file)
		{
			// extract rule from file name
			$rule = preg_replace("/\.php$/i", '', basename($file));

			try
			{
				// try to instantiate the rule
				$rule = VAPCustomFieldRule::getInstance($rule);

				// attach rule to list
				$rules[$rule->getID()] = $rule->getName();
			}
			catch (Exception $e)
			{
				// catch error and go ahead
			}
		}

		/**
		 * Trigger hook to allow external plugins to support custom rules.
		 * New rules have to be appended to the given associative array.
		 * The key of the array is the unique ID of the rule, the value is
		 * a readable name of the rule.
		 *
		 * @param 	array  &$rules  An array of rules.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onLoadCustomFieldsRules', array(&$rules));

		// sort rules by ascending name
		asort($rules);

		return $rules;
	}

	/**
	 * Dispatches a field rule.
	 *
	 * @param 	VAPCustomField  $field  The custom field instance.
	 * @param 	mixed           $value  The value of the field set in request.
	 * @param 	array           &$args  The array data to fill-in in case of
	 *                                  specific rules (name, e-mail, etc...).
	 *
	 * @return 	void
	 */
	public static function dispatchRule(VAPCustomField $field, $value, &$args)
	{
		if (!$field->get('rule'))
		{
			// missing rule, do not go ahead...
			return;
		}

		/**
		 * Trigger hook to allow external plugins to dispatch a custom rule.
		 * It is possible to access the rule of the field with:
		 * $field->get('rule');
		 *
		 * @param 	VAPCustomField  $field  The custom field instance.
		 * @param 	mixed           $value  The value of the field set in request.
		 * @param 	array           &$args  The array data to fill-in in case of
		 *                                  specific rules (name, e-mail, etc...).
		 *
		 * @return 	boolean  True to avoid dispatching the default system rules.
		 *
		 * @since 	1.7
		 */
		$did = VAPFactory::getEventDispatcher()->true('onDispatchCustomFieldRule', array($field, $value, &$args));

		if ($did)
		{
			// do not need to go ahead, a plugin did all the needed stuff
			return;
		}

		try
		{
			// create rule instance
			$rule = VAPCustomFieldRule::getInstance($field->get('rule'));
		}
		catch (Exception $e)
		{
			// the rule has been probably added by a third party plugin, which does not
			// need to dispatch a custom action
			return null;
		}

		// dispatch the rule
		$rule->dispatch($value, $args, $field);
	}

	/**
	 * Renders a field rule.
	 *
	 * @param 	VAPCustomField  $field  The custom field instance.
	 * @param 	mixed           $value  The value of the field set in request.
	 * @param 	array           &$args  The array data to fill-in in case of
	 *                                  specific rules (name, e-mail, etc...).
	 *
	 * @return 	null|string
	 */
	public static function renderRule(VAPCustomField $field, &$data)
	{
		if (!$field->get('rule'))
		{
			// missing, rule do not go ahead...
			return null;
		}

		/**
		 * Trigger hook to allow external plugins to manipulate the data to
		 * display or the type of layout to render. In case one of the attached
		 * plugins returned a string, then the field will use it as HTML in
		 * place of the default layout.
		 *
		 * It is possible to access the rule of the field with:
		 * $field->get('rule');
		 *
		 * @param 	VAPCustomField  $field  The custom field instance.
		 * @param 	array           &$data  An array of display data.
		 *
		 * @param 	mixed  The new layout of the field. Do not return anything
		 *                 to keep using the layout defined by the field.
		 *
		 * @since 	1.7
		 */
		$result = VAPFactory::getEventDispatcher()->trigger('onRenderCustomFieldRule', array($field, &$data));

		// implode list of returned HTML
		$input = implode("\n", array_filter($result));

		if ($input)
		{
			// return the input fetched by the plugin
			return $input;
		}

		try
		{
			// create rule instance
			$rule = VAPCustomFieldRule::getInstance($field->get('rule'));
		}
		catch (Exception $e)
		{
			// the rule has been probably added by a third party plugin, which does not
			// need to display a custom input
			return null;
		}

		// render the rule
		return $rule->render($data, $field);
	}
}
