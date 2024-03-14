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
 * VikAppointments custom fields loader class.
 * Usage example:
 * 
 * $fields = VAPCustomFieldsLoader::getInstance()
 *     ->customers()
 *     ->ofEmployee($id_employee)
 *     ->forService($id_services)
 *     ->excludeSeparator()
 *     ->translate()
 *     ->fetch();
 *
 * @since 1.7
 */
class VAPCustomFieldsLoader
{
	/**
	 * Cache of query results.
	 *
	 * @var array
	 */
	protected static $results = array();

	/**
	 * The default country code in case it is not specified.
	 *
	 * @var string
	 */
	public static $defaultCountry = 'US';

	/**
	 * Holds the query builder to fetch the custom fields.
	 *
	 * @var mixed
	 */
	protected $query;

	/**
	 * The group to which the custom fields should belong.
	 *
	 * @var integer
	 */
	protected $group = 0;

	/**
	 * When specified, only the custom fields belonging
	 * to the specified employee will be loaded.
	 *
	 * @var integer
	 */
	protected $employee = 0;

	/**
	 * When specified, only the custom fields belonging
	 * to the specified services will be loaded.
	 *
	 * @var array
	 */
	protected $services = array();

	/**
	 * Filter used to take only the custom fields assigned
	 * to the specified language tag.
	 *
	 * @var string
	 */
	protected $locale = '*';

	/**
	 * Mask used to filter the custom fields.
	 *
	 * @var integer
	 */
	protected $filterMask = 0;

	/**
	 * Flag used to translate the records after loading them.
	 * It is possible to specify the language to use for translations.
	 *
	 * @var mixed
	 */
	protected $translate = false;

	/**
	 * Class constructor proxy for immediate chaining.
	 *
	 * @return 	self
	 */
	public static function getInstance()
	{
		return new static();
	}

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Loads the custom fields for the customers.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function customers()
	{
		$this->group = static::CUSTOMERS;

		return $this;
	}

	/**
	 * Loads the custom fields for the employees.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function employees()
	{
		$this->group = static::EMPLOYEES;

		return $this;
	}

	/**
	 * Loads the custom fields of the specified employee.
	 * When this method is called, it forces the group to
	 * be "customers", since the custom fields for the
	 * employees do not support this assignment.
	 *
	 * @param 	integer  $id  The employee ID.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function ofEmployee($id)
	{
		// force "customers" group
		$this->customers();

		$this->employee = max(array(0, (int) $id));

		return $this;
	}

	/**
	 * Loads the custom fields for the specified service(s).
	 * When this method is called, it forces the group to
	 * be "customers", since the custom fields for the
	 * employees do not support this assignment.
	 *
	 * @param 	mixed  $ids  Either a service ID or an array.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function forService($ids)
	{
		// force "customers" group
		$this->customers();

		foreach ((array) $ids as $id)
		{
			// sanitize service ID
			$id = max(array(0, (int) $id));

			// add service only once
			if ($id && !in_array($id, $this->services))
			{
				$this->services[] = $id;
			}
		}

		return $this;
	}

	/**
	 * Sets the language filter.
	 *
	 * @param 	string  $lang  The language tag to set. Use false,
	 *                         null or '*' to ignore this filter.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function setLanguageFilter($lang = 'auto')
	{
		if (strcasecmp($lang, 'auto') == 0 || $lang === '')
		{
			// use the current language tag
			$this->locale = JFactory::getLanguage()->getTag();
		}
		else
		{
			// use the specified language tag
			$this->locale = $lang;
		}

		return $this;
	}

	/**
	 * Exclude the required checkboxes from the query.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function noRequiredCheckbox()
	{
		if (!$this->filterMask)
		{
			// create mask from scratch
			$this->filterMask = static::EXCLUDE_REQUIRED_CHECKBOX;
		}
		else
		{
			// extend existing mask with new value
			$this->filterMask |= static::EXCLUDE_REQUIRED_CHECKBOX;
		}

		return $this;
	}

	/**
	 * Exclude the separators from the query.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function noSeparator()
	{
		if (!$this->filterMask)
		{
			// create mask from scratch
			$this->filterMask = static::EXCLUDE_SEPARATOR;
		}
		else
		{
			// extend existing mask with new value
			$this->filterMask |= static::EXCLUDE_SEPARATOR;
		}

		return $this;
	}

	/**
	 * Exclude the input files from the query.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function noInputFile()
	{
		if (!$this->filterMask)
		{
			// create mask from scratch
			$this->filterMask = static::EXCLUDE_FILE;
		}
		else
		{
			// extend existing mask with new value
			$this->filterMask |= static::EXCLUDE_FILE;
		}

		return $this;
	}

	/**
	 * Sets whether the fields should be translated or not.
	 *
	 * @param 	mixed  Either a boolean or a language tag.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function translate($flag = true)
	{
		$this->translate = $flag;

		if ($this->translate && is_string($this->translate))
		{
			// auto-set language filter
			$this->setLanguageFilter($this->translate);
		}

		return $this;
	}

	/**
	 * Finally executes the query and returns the matching fields.
	 *
	 * @return 	array  An array of custom fields.
	 */
	public function fetch()
	{
		$fields = array();

		// prepare query options
		$options = array(
			'group'    => $this->group,
			'employee' => $this->employee,
			'services' => $this->services,
			'filter'   => $this->filterMask,
		);

		// create signature
		$sign = serialize(array_values($options));

		// check whether the same query has been already executed
		if (!isset(static::$results[$sign]))
		{
			static::$results[$sign] = array();

			$dispatcher = VAPFactory::getEventDispatcher();

			$dbo = JFactory::getDbo();

			if (!$this->query)
			{
				// init query from scratch
				$this->init();
			}

			// filter custom fields by group
			$this->query->where($dbo->qn('c.group') . ' = ' . $this->group);

			// check whether we are looking for the fields of the customers
			if ($this->group == static::CUSTOMERS)
			{
				// filter by employee
				$employee_where = array();
				$employee_where[] = $dbo->qn('c.id_employee') . ' <= 0';

				if ($this->employee)
				{
					$employee_where[] = $dbo->qn('c.id_employee') . ' = ' . $this->employee;

					// extends with OR
					$this->query->andWhere($employee_where, 'OR');
				}
				else
				{
					$this->query->where($employee_where);
				}

				// build query to count services
				$countServices = $dbo->getQuery(true)
					->select('COUNT(1)')
					->from($dbo->qn('#__vikappointments_cf_service_assoc', 'a2'))
					->where($dbo->qn('a2.id_field') . ' = ' . $dbo->qn('c.id'));

				// filter by services
				if ($this->services)
				{
					// loads custom fields without assignments or that belong to the specified services
					$this->query->leftjoin($dbo->qn('#__vikappointments_cf_service_assoc', 'a') . ' ON ' . $dbo->qn('a.id_field') . ' = ' . $dbo->qn('c.id'));
					$this->query->andWhere(array(
						$dbo->qn('a.id_service') . ' IN (' . implode(', ', $this->services) . ')',
						'(' . $countServices . ') = 0',
					), 'OR');
				}
				else
				{
					// otherwise exclude the fields assigned to specific services
					$this->query->where('(' . $countServices . ') = 0');
				}
			}

			// exclude required checkboxes
			if ($this->filterMask & static::EXCLUDE_REQUIRED_CHECKBOX)
			{
				$this->query->andWhere(array(
					$dbo->qn('c.type') . ' <> ' . $dbo->q('checkbox'),
					$dbo->qn('c.required') . ' = 0',
				));
			}

			// exclude separators
			if ($this->filterMask & static::EXCLUDE_SEPARATOR)
			{
				$this->query->where($dbo->qn('c.type') . ' <> ' . $dbo->q('separator'));
			}

			// exclude input file
			if ($this->filterMask & static::EXCLUDE_FILE)
			{
				$this->query->where($dbo->qn('c.type') . ' <> ' . $dbo->q('file'));
			}

			/**
			 * Filter the custom fields by language tag.
			 *
			 * @since 1.7
			 */
			if ($this->locale && $this->locale != '*')
			{
				$this->query->andWhere(array(
					$dbo->qn('c.locale') . ' = ' . $dbo->q($this->locale),
					$dbo->qn('c.locale') . ' = ' . $dbo->q('*'),
					$dbo->qn('c.locale') . ' = ' . $dbo->q(''),
				), 'OR');
			}

			/**
			 * Trigger hook to allow external plugins to manipulate the query used
			 * to load the custom fields through this helper class.
			 *
			 * @param 	mixed  &$query   A query builder object.
			 * @param 	array  $options  An array of query options.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger('onBeforeQueryCustomFields', array(&$this->query, $options));

			$dbo->setQuery($this->query);
			$rows = $dbo->loadAssocList();

			/**
			 * Trigger hook to allow external plugins to manipulate the list of
			 * supported custom fields through this helper class.
			 *
			 * @param 	array  &$rows    An array of custom fields.
			 * @param 	array  $options  An array of query options.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger('onBeforeRegisterCustomFields', array(&$rows, $options));

			// cache results
			static::$results[$sign] = $rows;
		}

		// copy result in a local variable to apply the translation
		$fields = static::$results[$sign];

		if ($this->translate)
		{
			// translate the fields
			static::doTranslate($fields, $this->translate);
		}

		// reset instance to support new queries
		$this->reset();

		return $fields;
	}

	/**
	 * Initialize the class to support a query.
	 *
	 * @return 	self  This object to support chaining.
	 */
	protected function init()
	{
		$dbo = JFactory::getDbo();

		$this->query = $dbo->getQuery(true);

		// query to load all the supported columns
		$columns = $dbo->getTableColumns('#__vikappointments_custfields');

		// select all columns from custom fields table
		foreach ($columns as $field => $type)
		{
			$this->query->select($dbo->qn('c.' . $field));
		}

		$this->query->from($dbo->qn('#__vikappointments_custfields', 'c'));
		$this->query->where(1);

		// group records since the query might use aggregators
		$this->query->group($dbo->qn('c.id'));
		// always sort fields by ascending ordering
		$this->query->order($dbo->qn('c.ordering') . ' ASC');

		return $this;
	}

	/**
	 * Resets the query.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function reset()
	{
		$this->query      = null;
		$this->group      = 0;
		$this->employee   = 0;
		$this->services   = array();
		$this->filterMask = 0;
		$this->translate  = false;

		return $this;
	}

	/**
	 * Translates the specified custom fields.
	 * The translation of the name will be placed in a different column 'langname'. 
	 * The original 'name' column won't be altered.
	 *
	 * @param 	array 	$fields  The records to translate.
	 * @param 	string  $tag     The locale in which the fields will be translated.
	 *                           Leave empty to use the current user locale.
	 *
	 * @return 	void
	 */
	public static function doTranslate(array &$fields, $tag = null)
	{
		$ids = array();

		/**
		 * Added support for missing fields in case the multilingual
		 * feature is disabled.
		 *
		 * @since 1.6.1
		 */
		foreach ($fields as $i => $f)
		{
			if (!isset($f['_choose']))
			{
				// keep original 'choose' for select
				$fields[$i]['_choose']  = $f['choose'];
			}

			if (!isset($f['langname']))
			{
				// backward compatibility for old translation technique
				$fields[$i]['langname'] = JText::translate($f['name']);
			}

			$ids[] = $fields[$i]['id'];
		}

		// do not proceed in case multi-lingual feature is turned off
		if (!VikAppointments::isMultilanguage() || !count($fields))
		{
			return;
		}

		// auto-detect language in case it is missing
		if (empty($tag) || !is_string($tag))
		{
			$tag = JFactory::getLanguage()->getTag();
		}

		// get translator
		$translator = VAPFactory::getTranslator();

		// pre-load fields translations
		$fieldsLang = $translator->load('custfield', array_unique($ids), $tag);

		// apply translations
		foreach ($fields as &$field)
		{
			// get custom field translation
			$tx = $fieldsLang->getTranslation($field['id'], $tag);

			if ($tx)
			{
				// apply translations
				$field['langname']    = JText::translate($tx->name);
				$field['description'] = $tx->description;
				$field['poplink']     = $tx->poplink;
				$field['choose']      = $tx->choose;
			}
		}
	}

	/**
	 * Translates the specified custom fields array data.
	 *
	 * @param 	array 	$data 	  The associative array with the CF data.
	 * @param 	array 	$fields   The custom fields (MUST BE already translated).
	 * @param 	mixed 	$langtag  The language tag to use.
	 *
	 * @return 	array 	The translated CF data array.
	 */
	public static function translateObject($data, array $fields, $langtag = null)
	{
		$tmp = array();

		if ($langtag)
		{
			// reload system language
			VikAppointments::loadLanguage($langtag);
		}

		// import field class
		VAPLoader::import('libraries.customfields.field');

		foreach ($fields as $cf)
		{
			$k = $cf['name'];

			if (!array_key_exists($k, $data))
			{
				// field not found inside the given object, go to next one
				continue;
			}

			// create field instance
			$field = VAPCustomField::getInstance($cf);

			// inject specified language tag
			$field->set('langtag', $langtag);

			// get a more readable text of the saved value
			$tmp[$k] = $field->getReadableValue($data[$k]);
		}

		return $tmp;
	}

	/**
	 * Return the default country code assigned to the phone number custom field.
	 *
	 * @param 	string  $langtag  The langtag to retrieve the proper country depending
	 *                            on the current language.
	 * @param 	mixed   $default  The default return value in case of missing field.
	 *
	 * @return 	string 	The default country code.
	 */
	public static function getDefaultCountryCode($langtag = null, $default = true)
	{
		/**
		 * Auto-detect language tag if not specified.
		 *
		 * @since 1.6.3
		 */
		if (!$langtag)
		{
			$langtag = JFactory::getLanguage()->getTag();
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select(array(
				$dbo->qn('c.id'),
				$dbo->qn('c.choose'),
				$dbo->qn('l.choose', 'lang_choose'),
				$dbo->qn('l.tag'),
			))
			->from($dbo->qn('#__vikappointments_custfields', 'c'))
			->leftjoin($dbo->qn('#__vikappointments_lang_customf', 'l') 
				. ' ON ' . $dbo->qn('l.id_customf') . ' = ' . $dbo->qn('c.id')
				. ' AND ' . $dbo->qn('l.tag') . ' = ' . $dbo->q($langtag))
			->where($dbo->qn('c.rule') . ' = ' . $dbo->q('phone'));

		$dbo->setQuery($q, 0, 1);
		$row = $dbo->loadAssoc();

		if (!$row)
		{
			/**
			 * Evaluate to return default country code or specified value.
			 *
			 * @since 1.6.3
			 */
			return $default === true ? self::$defaultCountry : $default;
		}

		// make sure we found a matching custom field
		if ($row['tag'] == $langtag && strlen($row['lang_choose']))
		{
			// use country code defined in langtag
			$row['choose'] = $row['lang_choose'];
		}
		// check if we should return the specified default value
		else if ($default !== true)
		{
			// unset string to return default value
			$row['choose'] = '';
		}

		$default = $default === true ? self::$defaultCountry : $default;

		// if we have a valid country code, return it, otherwise return the default value
		return strlen($row['choose']) ? $row['choose'] : $default;
	}

	/**
	 * Customers identifier group.
	 *
	 * @var integer
	 */
	const CUSTOMERS = 0;

	/**
	 * Employees identifier group.
	 *
	 * @var integer
	 */
	const EMPLOYEES = 1;

	/**
	 * No filter mask constant.
	 *
	 * @var integer
	 */
	const NO_FILTER = 0;

	/**
	 * Exclude required checkbox filter mask constant.
	 *
	 * @var integer
	 */
	const EXCLUDE_REQUIRED_CHECKBOX = 1;

	/**
	 * Exclude separator filter mask constant.
	 *
	 * @var integer
	 */
	const EXCLUDE_SEPARATOR = 2;

	/**
	 * Exclude input file mask constant.
	 *
	 * @var integer
	 */
	const EXCLUDE_FILE = 4;
}
