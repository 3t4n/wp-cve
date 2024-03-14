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
 * @deprecated 1.8 Use VAPCustomFieldsLoader::NO_FILTER instead.
 */
defined('CF_NO_FILTER') or define('CF_NO_FILTER', 0);

/**
 * @deprecated 1.8 Use VAPCustomFieldsLoader::EXCLUDE_REQUIRED_CHECKBOX instead.
 */
defined('CF_EXCLUDE_REQUIRED_CHECKBOX') or define('CF_EXCLUDE_REQUIRED_CHECKBOX', 1);

/**
 * @deprecated 1.8 Use VAPCustomFieldsLoader::EXCLUDE_SEPARATOR instead.
 */
defined('CF_EXCLUDE_SEPARATOR') or define('CF_EXCLUDE_SEPARATOR', 2);

/**
 * @deprecated 1.8 Use VAPCustomFieldsLoader::EXCLUDE_FILE instead.
 */
defined('CF_EXCLUDE_FILE') or define('CF_EXCLUDE_FILE', 4);

VAPLoader::import('libraries.customfields.loader');

/**
 * VikAppointments custom fields class handler.
 *
 * @since 1.6
 * @deprecated 1.8 Rely on the customfields libraries.
 */
abstract class VAPCustomFields
{
	/**
	 * The default country code in case it is not specified.
	 *
	 * @var string
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::$defaultCountry instead.
	 */
	public static $defaultCountry = 'US';

	/**
	 * A list containing all the columns that will be used
	 * in the SELECT query.
	 *
	 * @var array
	 *
	 * @deprecated 1.8 Without replacement. The columns to load are automatically
	 *                 retrieved according to the properties of the fields table.
	 */
	public static $listColumns = array(
		'id',
		'name',
		'formname',
		'description',
		'type',
		'choose',
		'multiple',
		'required',
		'rule',
		'ordering',
		'poplink',
		'id_employee',
		'group',
	);

	/**
	 * Return the list of the custom fields for the specified section.
	 *
	 * @param 	integer  $group  	The section of the program.
	 * @param 	integer  $employee  The employee ID for customers fields.
	 * @param 	mixed  	 $service   The service ID or a list of services (for customers).
	 * @param 	integer  $flag 		A mask to filter the custom fields.
	 *
	 * @return 	array 	 The list of custom fields.
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::fetch() instead.
	 */
	public static function getList($group = 0, $employee = 0, $service = null, $flag = 0)
	{
		$loader = VAPCustomFieldsLoader::getInstance();

		if ($group == 1)
		{
			$loader->employees();
		}
		else
		{
			if ($employee > 0)
			{
				$loader->ofEmployee($employee);
			}

			if ($service)
			{
				$loader->forService($service);
			}
		}

		if ($flag & CF_EXCLUDE_SEPARATOR)
		{
			$loader->noSeparator();
		}

		if ($flag & CF_EXCLUDE_REQUIRED_CHECKBOX)
		{
			$loader->noRequiredCheckbox();
		}

		if ($flag & CF_EXCLUDE_FILE)
		{
			$loader->noInputFile();
		}

		return $loader->fetch();
	}

	/**
	 * Return the default country code assigned to the phone number custom field.
	 *
	 * @param 	string 	$langtag 	The langtag to retrieve the proper country 
	 * 								depending on the current language.
	 * @param 	mixed 	$default 	The default return value in case of unsupported
	 * 								
	 *
	 * @return 	string 	The default country code.
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::getDefaultCountryCode() instead.
	 */
	public static function getDefaultCountryCode($langtag = null, $default = true)
	{
		return VAPCustomFieldsLoader::getDefaultCountryCode($langtag, $default);
	}

	/**
	 * Translates the specified custom fields.
	 * The translation of the name will be placed in a different column 'langname'. 
	 * The original 'name' column won't be altered.
	 *
	 * @param 	array 	$fields  The records to translate.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::doTranslate() instead.
	 */
	public static function translate(array &$fields, $tag = null)
	{
		VAPCustomFieldsLoader::doTranslate($fields, $tag);
	}

	/**
	 * Translates the specified custom fields array data.
	 *
	 * @param 	array 	$data 	 The associative array with the CF data.
	 * @param 	array 	$fields  The custom fields (MUST BE already translated).
	 *
	 * @return 	array 	The translated CF data array.
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::translateObject() instead.
	 */
	public static function translateObject(array $data, array $fields)
	{
		return VAPCustomFieldsLoader::translateObject($data, $fields);
	}

	/**
	 * Searches a custom field using the specified query string.
	 *
	 * @param 	mixed 	 $key 	  The query params (the value to search for or an array
	 * 							  containing the column and the value).
	 * @param 	array 	 $fields  The custom fields list.
	 * @param 	integer  $fields  The maximum number of records to get (0 to ignore the limit).
	 *
	 * @return 	mixed 	 The custom fields that match the query.
	 */
	protected static function findField($key, array $fields, $lim = 0)
	{
		$list = array();

		// if the key is a string, search by ID column
		if (is_string($key))
		{
			$key = array('id', $key);
		}

		foreach ($fields as $cf)
		{
			// check if the column value is equals to the key
			if (self::getColumnValue($cf, $key[0], null) == $key[1])
			{
				// push the custom field in the list
				$list[] = $cf;

				// stop iterating if we reached the limit
				if (count($list) == $lim)
				{
					break;
				}
			}
		}

		// return false if no matches
		if (!count($list))
		{
			return false;
		}
		// return the CF if the limit was set to 1
		else if ($lim == 1)
		{
			return reset($list);
		}

		// return the list of custom fields found (never empty)
		return $list;
	}

	/**
	 * Returns the custom fields values specified in the REQUEST.
	 *
	 * @param 	mixed 	 $fields 	The custom fields list to check for.
	 * 								If the list is not an array, the method will load
	 * 								all the custom fields that belong to the specified group.
	 * @param 	array 	 &$args 	The array data to fill-in in case of specific rules (name, e-mail, etc...).
	 * @param 	boolean  $strict 	True to raise an error when a mandatory field is missing.
	 *
	 * @return 	array 	The lookup array containing the values of the custom fields.
	 *
	 * @throws 	Exception 	When a mandatory field is empty or when a file hasn't been uploaded.
	 *
	 * @uses 	getList()
	 * @uses 	sanitizeFieldValue()
	 * @uses 	validateField()
	 * @uses 	dispatchRule()
	 * @uses 	helper methods to access fields properties
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsRequestor::loadForm() instead.
	 */
	public static function loadFromRequest($fields = 0, array &$args = null, $strict = true)
	{
		return VAPCustomFieldsRequestor::loadForm($fields, $args, $strict);
	}

	/**
	 * Sanitize the field value.
	 *
	 * @param 	mixed 	$field 	The custom field.
	 * @param 	string 	$value 	The value to sanitize.
	 *
	 * @return 	mixed 	The sanitized value.
	 */
	protected static function sanitizeFieldValue($field, $value)
	{
		// sanitize a input number
		if (static::isInputNumber($field))
		{
			// decode the settings
			$settings = json_decode(static::getColumnValue($field, 'choose', '{}'), true);

			// convert the string to float
			$value = floatval($value);
			
			// if min setting exists, make sure the value is not lower
			if (strlen($settings['min']))
			{
				$value = max(array($value, (float) $settings['min']));
			}

			// if max setting exists, make sure the value is not higher
			if (strlen($settings['max']))
			{
				$value = min(array($value, (float) $settings['max']));
			}

			// if decimals are not supported, round the value
			if (!$settings['decimals'])
			{
				$value = round($value);
			}
		}

		return $value;
	}

	/**
	 * Checks if the value of the field is accepted.
	 *
	 * @param 	mixed 	 $field  The custom field to evaluate.
	 * @param 	string 	 $value  The value of the field.
	 *
	 * @return 	boolean  True if valid, otherwise false.
	 */
	protected static function validateField($field, $value)
	{
		return (!static::isRequired($field)
			|| (!static::isInputFile($field) && strlen($value))
			|| (static::isInputFile($field) && !empty($value['name'])));
	}

	/**
	 * Dispatched the rule of the field.
	 *
	 * @param 	mixed 	$field 	The custom field to evaluate.
	 * @param 	string 	$value  The value of the field.
	 * @param 	array 	&$args 	The array data to fill-in in case of specific rules (name, e-mail, etc...).
	 *
	 * @return 	void
	 *
	 * @uses 	isNominative()
	 * @uses 	isEmail()
	 * @uses 	isPhoneNumber()
	 */
	protected static function dispatchRule($field, $value, array &$args)
	{
		// check if the field is a nominative
		if (static::isNominative($field))
		{
			if (!empty($args['purchaser_nominative']))
			{
				$args['purchaser_nominative'] .= ' ';
			}
			else
			{
				$args['purchaser_nominative'] = '';
			}

			$args['purchaser_nominative'] .= $value;
		}
		// check if the field is an e-mail
		else if (static::isEmail($field))
		{
			$args['purchaser_mail'] = $value;
		}
		// check if the field is a phone number
		else if (static::isPhoneNumber($field))
		{
			// get the prefix country (ID_C2CODE)
			$country_key = JFactory::getApplication()->input->getString('vapcf' . static::getID($field) . '_prfx', '');

			if (!empty($country_key))
			{
				// explode the string
				$country_key = explode('_', $country_key);

				// get the country using the 2 letters code
				$country = VAPLocations::getCountryFromCode($country_key[1]);
				if ($country !== false)
				{
					$args['purchaser_prefix'] 	= $country['phone_prefix'];
					$args['purchaser_country'] 	= $country['country_2_code'];
				}
			}

			// sanitize phone number
			$args['purchaser_phone'] = str_replace(' ', '', $value);
		}
		// check if the field is a state or a province
		else if (static::isStateProvince($field))
		{
			$args['billing_state'] = $value;
		}
		// check if the field is a city
		else if (static::isCity($field))
		{
			$args['billing_city'] = $value;
		}
		// check if the field is an address
		else if (static::isAddress($field))
		{
			$args['billing_address'] = $value;
		}
		// check if the field is a ZIP code
		else if (static::isZipCode($field))
		{
			$args['billing_zip'] = $value;
		}
		// check if the field is a company name
		else if (static::isCompanyName($field))
		{
			$args['company'] = $value;
		}
		// check if the field is a VAT number
		else if (static::isVatNumber($field))
		{
			$args['vatnum'] = $value;
		}
	}

	/**
	 * Get the ID property of the specified custom field object.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @return 	integer  The ID of the custom field.
	 *
	 * @uses 	getColumnValue()
	 */
	public static function getID($cf)
	{
		return static::getColumnValue($cf, 'id', 0);
	}

	/**
	 * Get the NAME property of the specified custom field object.
	 *
	 * @param 	mixed 	$cf  The array or the object of the custom field.
	 *
	 * @return 	string 	The name of the custom field.
	 *
	 * @uses 	getColumnValue()
	 */
	public static function getName($cf)
	{
		return static::getColumnValue($cf, 'name', '');
	}

	/**
	 * Checks if the specified custom field is required.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @return 	boolean  True if required, otherwise false.
	 *
	 * @uses 	getColumnValue()
	 */
	public static function isRequired($cf)
	{
		return (bool) static::getColumnValue($cf, 'required', 0);
	}

	/**
	 * Get the TYPE property of the specified custom field object.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @return 	integer  The type of the custom field.
	 *
	 * @uses 	getColumnValue()
	 */
	public static function getType($cf)
	{
		return static::getColumnValue($cf, 'type', 'text');
	}

	/**
	 * Get the RULE property of the specified custom field object.
	 *
	 * @param 	mixed 	$cf  The array or the object of the custom field.
	 *
	 * @return 	string  The rule of the custom field, 
	 * 					'text' if it is not possible to establish it.
	 *
	 * @uses 	getColumnValue()
	 */
	public static function getRule($cf)
	{
		return static::getColumnValue($cf, 'rule', self::NONE);
	}

	/**
	 * Checks if the custom field is a nominative.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if nominative, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isNominative($cf)
	{
		return static::getRule($cf) == self::NOMINATIVE;
	}

	/**
	 * Checks if the custom field is an e-mail.
	 *
	 * @param 	mixed 	$cf  The array or the object of the custom field.
	 *
	 * @param 	boolean 	 True if e-mail, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isEmail($cf)
	{
		return static::getRule($cf) == self::EMAIL;
	}

	/**
	 * Checks if the custom field is a phone number.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if phone number, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isPhoneNumber($cf)
	{
		return static::getRule($cf) == self::PHONE_NUMBER;
	}

	/**
	 * Checks if the custom field is a state or a province.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if a state, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isStateProvince($cf)
	{
		return static::getRule($cf) == self::STATE;
	}

	/**
	 * Checks if the custom field is a city.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if city, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isCity($cf)
	{
		return static::getRule($cf) == self::CITY;
	}

	/**
	 * Checks if the custom field is an address.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if address, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isAddress($cf)
	{
		return static::getRule($cf) == self::ADDRESS;
	}

	/**
	 * Checks if the custom field is a ZIP code.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if ZIP code, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isZipCode($cf)
	{
		return static::getRule($cf) == self::ZIP;
	}

	/**
	 * Checks if the custom field is a company name.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if company name, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isCompanyName($cf)
	{
		return static::getRule($cf) == self::COMPANY;
	}

	/**
	 * Checks if the custom field is a VAT number.
	 *
	 * @param 	mixed 	 $cf  The array or the object of the custom field.
	 *
	 * @param 	boolean  True if VAT number, otherwise false.
	 *
	 * @uses 	getRule()
	 */
	public static function isVatNumber($cf)
	{
		return static::getRule($cf) == self::VATNUM;
	}

	/**
	 * Checks if the custom field is an input text.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if input text, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isInputText($cf)
	{
		return static::getType($cf) == 'text';
	}

	/**
	 * Checks if the custom field is a textarea.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if textarea, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isTextArea($cf)
	{
		return static::getType($cf) == 'textarea';
	}

	/**
	 * Checks if the custom field is an input number.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if input number, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isInputNumber($cf)
	{
		return static::getType($cf) == 'number';
	}

	/**
	 * Checks if the custom field is a select.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if select, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isSelect($cf)
	{
		return static::getType($cf) == 'select';
	}

	/**
	 * Checks if the custom field is a datepicker.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if datepicker, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isCalendar($cf)
	{
		return static::getType($cf) == 'date';
	}

	/**
	 * Checks if the custom field is an input file.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if input file, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isInputFile($cf)
	{
		return static::getType($cf) == 'file';
	}

	/**
	 * Checks if the custom field is a checkbox.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if checkbox otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isCheckbox($cf)
	{
		return static::getType($cf) == 'checkbox';
	}

	/**
	 * Checks if the custom field is a separator.
	 *
	 * @param 	mixed 	 $cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean  True if separator, otherwise false.
	 *
	 * @uses 	getType()
	 */
	public static function isSeparator($cf)
	{
		return static::getType($cf) == 'separator';
	}

	/**
	 * Method used to access the attributes and properties of the given
	 * custom field. Useful if we don't know if we are handling an array or an object.
	 *
	 * @param 	mixed 	$cf 	  The custom field array/object.
	 * @param 	string 	$column   The column to access.
	 * @param 	mixed 	$default  The default value in case the column does not exist.
	 *
	 * @return 	mixed 	The value at the specified column if exists, otherwise the default one.
	 */
	protected static function getColumnValue($cf, $column, $default = null)
	{
		// check if the field is an array
		if (is_array($cf))
		{
			// if the column key exists, return the value
			if (array_key_exists($column, $cf))
			{
				return $cf[$column];
			}
		}
		// check if the field is an object
		else if (is_object($cf))
		{
			// if the property exists, return the value
			if (property_exists($cf, $column))
			{
				return $cf->{$column};
			}
		}

		// otherwise return the default one
		return $default;
	}

	/**
	 * Customers identifier group.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::CUSTOMERS instaed.
	 */
	const GROUP_CUSTOMERS = 0;

	/**
	 * Employees identifier group.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Use VAPCustomFieldsLoader::EMPLOYEES instaed.
	 */
	const GROUP_EMPLOYEES = 1;

	/**
	 * NONE identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const NONE = '';

	/**
	 * NOMINATIVE identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const NOMINATIVE = 'nominative';

	/**
	 * EMAIL identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const EMAIL = 'email';

	/**
	 * PHONE NUMBER identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const PHONE_NUMBER = 'phone';

	/**
	 * STATE/PROVINCE identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const STATE = 'state';

	/**
	 * CITY identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const CITY = 'city';

	/**
	 * ADDRESS identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const ADDRESS = 'address';

	/**
	 * ZIP/CAP identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const ZIP = 'zip';

	/**
	 * COMPANY NAME identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const COMPANY = 'company';

	/**
	 * VAT NUMBER identifier rule.
	 *
	 * @var integer
	 *
	 * @deprecated 1.8 Without replacement.
	 */
	const VATNUM = 'vatnum';
}
