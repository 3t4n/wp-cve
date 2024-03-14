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
 * Encapsulates the information held by a column, providing
 * further consistency to the properties access.
 *
 * @since 1.7
 */
#[\AllowDynamicProperties]
class ImportColumn
{
	/**
	 * Tries to look for a column matching the specified type.
	 *
	 * @param 	mixed 	$data  Either a SimpleXMLElement or a non-scalar value.
	 *
	 * @return 	ImportColumns
	 *
	 * @throws  RuntimeException
	 */
	final public static function getInstance($data)
	{
		if ($data instanceof SimpleXMLElement)
		{
			// extract type from XML
			$type = (string) $data->attributes()->type;
		}
		else
		{
			// extract type from array
			$data = (array) $data;
			$type = isset($data['type']) ? $data['type'] : '';
		}

		if (!$type)
		{
			// no type given, use base class
			return new static($data);
		}

		// attempt to load the column type
		if (!VAPLoader::import('libraries.import.forms.columns.' . $type))
		{
			// import column file not found
			throw new RuntimeException(sprintf('Import column [%s] not found', $type), 404);
		}

		// capitalize type
		$type = str_replace('_', ' ', $type);
		$type = str_replace(' ', '', ucwords($type));

		// build classname
		$classname = 'ImportColumn' . $type;

		if (!$classname)
		{
			// class not found
			throw new RuntimeException(sprintf('Import column class [%s] not found', $classname), 404);
		}

		// instantiate column
		return new $classname($data);
	}

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$data  Either a SimpleXMLElement or a non-scalar value.
	 */
	public function __construct($data)
	{
		if ($data instanceof SimpleXMLElement)
		{
			// set up as XML
			$this->setupXML($data);
		}
		else
		{
			// set up as array/object
			$this->setup($data);
		}
	}

	/**
	 * Binds the columns properties by extracting them
	 * from the given XML element.
	 *
	 * @param 	SimpleXMLElement  $data  The XML element.
	 *
	 * @return 	void
	 */
	protected function setupXML(SimpleXMLElement $data)
	{
		$obj = new stdClass;
		$obj->name     = (string) $data->attributes()->name;
		$obj->label    = (string) $data->attributes()->label;
		$obj->required = (int)    $data->attributes()->required;
		$obj->default  = (string) $data->attributes()->default;
		$obj->filter   = (string) $data->attributes()->filter;
		$obj->type     = (string) $data->attributes()->type;
		$obj->options  = array();

		foreach ($data->option as $opt)
		{
			$k = (string) $opt->attributes()->value;
			$v = (string) JText::translate($opt);

			$obj->options[$k] = $v;
		}

		// attempt to translate the label
		$obj->label = JText::translate($obj->label);

		// now finalise by binding it as an array
		$this->setup($obj);
	}

	/**
	 * Binds the internal properties with the given array/object.
	 *
	 * @param 	mixed  $data  Either an array or an object.
	 *
	 * @return 	void
	 */
	protected function setup($data)
	{
		foreach ($data as $k => $v)
		{
			$this->{$k} = $v;
		}
	}

	/**
	 * Magic method used to directly access the properties
	 * without raising any errors or warnings.
	 *
	 * @param 	string 	$name  The property to access.
	 *
	 * @return 	mixed   The property value if exists, null otherwise.
	 */
	public function __get($name)
	{
		if (isset($this->{$name}))
		{
			return $this->{$name};
		}

		return null;
	}

	/**
	 * Helper method used to manipulate the given value before
	 * binding the object to save.
	 *
	 * @param 	mixed   $value  The default import value.
	 *
	 * @return 	string  The value to bind
	 */
	public function onImport($value)
	{
		return $value;
	}

	/**
	 * Helper method used to format the values under this column.
	 *
	 * @param 	mixed   $value  The default column value.
	 *
	 * @return 	string  The formatted value
	 */
	public function format($value)
	{
		// look by default into the options array
		if (isset($this->options[$value]))
		{
			// option found, return the stored value
			return $this->options[$value];
		}

		// use the default value
		return (string) $value;
	}
}
