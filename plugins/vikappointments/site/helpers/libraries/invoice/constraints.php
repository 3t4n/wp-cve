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
 * Class used to wrap the invoice constraints.
 *
 * @since 1.7
 */
class VAPInvoiceConstraints implements JsonSerializable, IteratorAggregate
{
	/**
	 * The page orientation (landscape or portrait).
	 *
	 * @var string
	 */
	protected $pageOrientation = self::PAGE_ORIENTATION_PORTRAIT;

	/**
	 * The PDF measure unite (mm, cm, point or inch).
	 *
	 * @var string
	 */
	protected $unit = self::UNIT_MILLIMETER;

	/**
	 * The page format (A4, A5, or A6).
	 *
	 * @var string
	 */
	protected $pageFormat = self::PAGE_FORMAT_A4;

	/**
	 * The margins of the pages.
	 *
	 * @var array
	 */
	protected $margins = array(
		'top'    => 10,
		'bottom' => 10,
		'right'  => 10,
		'left'   => 10,
		'header' => 5,
		'footer' => 5,
	);

	/**
	 * Ratio used to adjust the conversion of pixels to user units.
	 *
	 * @var float
	 */
	protected $imageScaleRatio = 1.25;

	/**
	 * The default family font.
	 *
	 * @var string
	 *
	 * @since 1.7
	 */
	protected $font = 'courier';
	
	/**
	 * The font sizes to use for each specified section.
	 *
	 * @var array
	 */
	protected $fontSizes = array(
		'header' => 10,
		'body'   => 10,
		'footer' => 10,
	);

	/**
	 * The header title. If empty, the header will be hidden.
	 *
	 * @var string
	 *
	 * @since 1.7
	 */
	protected $headerTitle = null;

	/**
	 * Flag used to check whether the footer should be displayed.
	 *
	 * @var boolean
	 *
	 * @since 1.7
	 */
	protected $showFooter = false;

	/**
	 * Class constructor.
	 *
	 * @param   mixed  $data  An array/object of properties to bind.
	 */
	public function __construct($data = array())
	{
		// iterate properties
		foreach ($data as $k => $v)
		{
			// do not inject new properties
			$this->__set($k, $v);
		}
	}

	/**
	 * Magic method used to access the internal protected properties.
	 *
	 * @param   string  $name  The property name.
	 *
	 * @return  mixed   The propery value if exists, null otherwise.
	 */
	public function __get($name)
	{
		if (!property_exists($this, $name))
		{
			// missing property
			return null;
		}

		if (is_array($this->{$name}))
		{
			// always cast arrays to objects
			return (object) $this->{$name};
		}

		return $this->{$name};
	}

	/**
	 * Magic method used to check if the instance owns the specified property.
	 *
	 * @param   string   $name  The property name.
	 *
	 * @return  boolean  True if set, false otherwise.
	 */
	public function __isset($name)
	{
		return property_exists($this, $name);
	}

	/**
	 * Magic method used to prevent the assignment of new properties.
	 *
	 * @param   string  $name   The property name.
	 * @param   mixed   $value  The property value.
	 *
	 * @return  void
	 */
	public function __set($name, $value)
	{
		// set only if the property exists
		if (property_exists($this, $name))
		{
			// check if our property is a scalar value
			if (is_scalar($this->{$name}) || is_null($this->{$name}))
			{
				$this->{$name} = $value;
			}
			else
			{
				// iterate elements and assign them
				foreach ((array) $value as $pk => $pv)
				{
					if (array_key_exists($pk, $this->{$name}))
					{
						$this->{$name}[$pk] = $pv;
					}
				}

				// Do not need to recursively assign the attributes
				// because the array properties of this class don't
				// exceed the limit of one-level.
			}
		}
	}

	 /**
	 * Creates an associative array, containing all the supported constraints.
	 *
	 * @return  array
	 */
	public function toArray()
	{
		$data = array();

		// get object variables
		foreach (get_object_vars($this) as $k => $v)
		{
			// get value that should be used outside the class
			$data[$k] = $this->__get($k);

			if (is_object($data[$k]))
			{
				// cast to array in case the attribute is an object
				$data[$k] = (array) $data[$k];
			}
		}

		return $data;
	}

	/**
	 * Creates a standard object, containing all the supported constraints,
	 * to be used when this class is passed to "json_encode()".
	 *
	 * @return  object
	 *
	 * @see     JsonSerializable
	 */
	#[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		$obj = new stdClass;

		// get object variables
		foreach (get_object_vars($this) as $k => $v)
		{
			// get value that should be used outside the class
			$obj->{$k} = $this->__get($k);
		}

		return $obj;
	}

	/**
	 * Returns the iterator interface.
	 *
	 * @return  Traversable
	 *
	 * @see     IteratorAggregate
	 */
	#[ReturnTypeWillChange]
	public function getIterator()
	{
		return new ArrayIterator($this->toArray());
	}
	
	// PAGE ORIENTATION
	const PAGE_ORIENTATION_LANDSCAPE = 'L';
	const PAGE_ORIENTATION_PORTRAIT  = 'P';
	
	// UNIT
	const UNIT_POINT      = 'pt';
	const UNIT_MILLIMETER = 'mm';
	const UNIT_CENTIMETER = 'cm';
	const UNIT_INCH       = 'in';
	
	// PAGE FORMAT
	const PAGE_FORMAT_A4 = 'A4';
	const PAGE_FORMAT_A5 = 'A5';
	const PAGE_FORMAT_A6 = 'A6';
}
