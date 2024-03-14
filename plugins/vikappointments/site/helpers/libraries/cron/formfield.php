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
 * Class used to represent an HTML standard input field.
 *
 * @since 1.5
 * @since 1.7 Renamed from CronFormField.
 *
 * @see VAPCronFormFieldConstraints
 */
class VAPCronFormField
{
	/**
	 * The name of the field, used to retrieve the value of the setting.
	 * This value must be unique.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The type of the field.
	 *
	 * @var int
	 */
	private $type;

	/**
	 * The label of the field to display.
	 *
	 * @var string
	 */
	private $label;

	/**
	 * An additional label to place next to the input.
	 *
	 * @var string
	 */
	private $label_2;

	/**
	 * The object containing all the attributes of the field.
	 *
	 * @var CronFormFieldConstraints
	 */
	private $constraints = null;

	/**
	 * Indicates whether the field is required or not.
	 *
	 * @var  boolean
	 */
	private $required = 1;

	/**
	 * The associative array containing all the accepted values.
	 * This attribute is considered only for Dropdown fields.
	 *
	 * @var  array
	 */ 
	private $values = array();

	/**
	 * The default value of the field.
	 *
	 * @var  string
	 */
	private $default = '';

	/**
	 * The construct of the cron form field to initialize the required parameters of this object.
	 *
	 * @param 	string 	 $name 		The field name.
	 * @param 	string   $label  	The field label.
	 * @param 	integer  $type 		The field type (default 0: HTML).
	 *
	 * @uses 	setName()
	 * @uses 	setLabel()
	 * @uses 	setType()
	 */
	public function __construct($name, $label, $type = 0)
	{
		$this->setName($name)
			->setLabel($label)
			->setType($type);
	}

	/**
	 * Set the name of the field.
	 *
	 * @param 	string  $name 	The name of the field.
	 *
	 * @return 	self	Returns this object to support chaining.
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the name of the field. If the name is empty, returns a random name.
	 *
	 * @return 	string 	The name of the field.
	 */
	public function getName()
	{
		return !empty($this->name) ? $this->name : 'input-' . rand(1, 100);
	}

	/**
	 * Sets the label of the field. 
	 *
	 * @param 	string  $label 	The label of the field.
	 *
	 * @return 	self	Returns this object to support chaining.
	 */
	public function setLabel($label)
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * Get the label of the field.
	 *
	 * @return 	string 	The label of the field.
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * Sets the additional label of the field.
	 *
	 * @param 	string  $label 	The additional label of the field.
	 *
	 * @return 	self	Returns this object to support chaining.
	 */
	public function setSecondaryLabel($label)
	{
		$this->label_2 = $label;

		return $this;
	}

	/**
	 * Get the additional label of the field.
	 *
	 * @return 	string 	The additional label of the field.
	 */
	public function getSecondaryLabel()
	{
		return $this->label_2;
	}

	/**
	 * Sets the type of the field.
	 *
	 * @param 	integer  $type 	The type of the field. Values must be between 0 and 5, 
	 *							any other value will be revert to 0 (html).
	 *
	 * @return 	self	 Returns this object to support chaining.
	 */
	public function setType($type)
	{
		$this->type = (int) $type;

		return $this;
	}

	/**
	 * Get the type of the field.
	 *
	 * @param 	boolean  $str  True to return the type string.
	 *
	 * @return 	mixed	 The type of the field.
	 */
	public function getType($str = false)
	{
		if (!$str)
		{
			return $this->type;
		}

		/**
		 * Fetch HTML tag type.
		 *
		 * @since 1.7
		 */
		switch ($this->type)
		{
			case static::INPUT_TEXT:
				return 'text';

			case static::INPUT_NUMBER:
				return 'number';

			case static::INPUT_PASSWORD:
				return 'password';

			case static::TEXTAREA:
				return 'textarea';

			case static::DROPDOWN:
				return 'select';

			case static::SEPARATOR:
				return 'separator';

			case static::EDITOR:
				return 'editor';

			default:
				return 'html';
		}
	}

	/**
	 * Sets the constraints of the field.
	 *
	 * @param 	mixed 	The attributes of the field. The object must be a 
	 * 					CronFormFieldConstraints or NULL
	 *
	 * @return 	self	Returns this object to support chaining.
	 */
	public function setConstraints($constraints)
	{
		$this->constraints = $constraints;

		return $this;
	}

	/**
	 * Inserts a new attribute in the constraints list of this field.
	 *
	 * @param 	string  $key 	The standard name of the attribute.
	 * @param 	string  $val 	An acceptable value of the attribute.
	 *
	 * @return 	self	Returns this object to support chaining.
	 */
	public function addConstraint($key, $val)
	{
		// if the constaints object is null, instantiate it
		if ($this->constraints === null)
		{
			$this->constraints = new CronFormFieldConstraints();
		}

		if ($key != 'class')
		{
			$this->constraints->add($key, $val);
		}
		else
		{
			// For class attribute we may concat the value to the existing one.
			// Use an empty space for the concatenation.
			$this->constraints->add($key, $val, ' ');
		}

		return $this;
	}

	/**
	 * Get the constraints of the field.
	 *
	 * @return 	CronFormFieldConstraints  The constraints object or null.
	 */
	public function getConstraints()
	{
		return $this->constraints;
	}

	/**
	 * Sets whether the field is required or not.
	 *
	 * @param 	boolean  $required 	The status of the field.
	 *
	 * @return 	self	 Returns this object to support chaining.
	 */
	public function setRequired($required)
	{
		$this->required = $required;

		return $this;
	}

	/**
	 * Returns true if the field is required, otherwise false.
	 *
	 * @return 	boolean  If the field is required.
	 */
	public function isRequired()
	{
		return $this->required;
	}

	/**
	 * Sets the default value of the field.
	 *
	 * @param 	string  $default 	The initial value of the field.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	isRequired()
	 */
	public function setDefaultValue($default)
	{
		// if the default is empty and the field is optional
		// the default (value) of the field can be empty
		// a required field cannot have empty values.
		if (strlen($default) || !$this->isRequired())
		{
			$this->default = $default;
		}

		return $this;
	}

	/**
	 * Get the default value of the field.
	 *
	 * @return 	string 	The default value of the field.
	 */
	public function getDefaultValue()
	{
		return $this->default;
	}

	/**
	 * Sets all the possible values of a dropdown field.
	 *
	 * @param 	array 	$values  The values to insert in a dropdown.
	 *							 The key of a row will be used as value
	 *							 and the content will be used as label.
	 *
	 * @return 	self 	Returns this object to support chaining.
	 */
	public function setListValues($values)
	{
		$this->values = $values;

		return $this;
	}

	/**
	 * Returns the list with all the values of the dropdown field.
	 *
	 * @return 	array 	The list of the values of the field.
	 */
	public function getListValues()
	{
		return $this->values;
	}

	/**
	 * Sets the type of the field as HTML: 0.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isHtml()
	{
		$this->setType(self::HTML);

		return $this;
	}

	/**
	 * Sets the type of the field as Input Text: 1.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isInputText()
	{
		$this->setType(self::INPUT_TEXT);

		return $this;
	}

	/**
	 * Sets the type of the field as Input Number: 2.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isInputNumber()
	{
		$this->setType(self::INPUT_NUMBER);

		return $this;
	}

	/**
	 * Sets the type of the field as Input Password: 3.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isInputPassword()
	{
		$this->setType(self::INPUT_PASSWORD);

		return $this;
	}

	/**
	 * Sets the type of the field as Textarea: 4.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isTextarea()
	{
		$this->setType(self::TEXTAREA);

		return $this;
	}

	/**
	 * Sets the type of the field as Select: 5.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isDropdown()
	{
		$this->setType(self::DROPDOWN);

		return $this;
	}

	/**
	 * Sets the type of the field as Separator: 6.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isSeparator()
	{
		$this->setType(self::SEPARATOR);

		return $this;
	}

	/**
	 * Sets the type of the field as advanced Editor: 7.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	setType()
	 */
	public function isEditor()
	{
		$this->setType(self::EDITOR);

		return $this;
	}

	/**
	 * Returns the fields attributes as array.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	public function toArray()
	{
		// create base attributes
		$data = array(
			'type'        => $this->getType(true),
			'name'        => $this->getName(),
			'label'       => $this->getLabel(),
			'description' => $this->getSecondaryLabel(),
			'required'    => $this->isRequired(),
			'value'       => $this->getDefaultValue(),
			'options'     => $this->getListValues(),
		);

		// include constraints, if any
		if ($this->getConstraints())
		{
			foreach ($this->getConstraints() as $k => $v)
			{
				$data[$k] = $v;
			}
		}

		return $data;
	}

	/**
	 * The identifier for html fields type.
	 *
	 * @var integer
	 */
	const HTML = 0;

	/**
	 * The identifier for text fields type.
	 *
	 * @var integer
	 */
	const INPUT_TEXT = 1;

	/**
	 * The identifier for number fields type.
	 *
	 * @var integer
	 */
	const INPUT_NUMBER = 2;

	/**
	 * The identifier for password fields type.
	 *
	 * @var integer
	 */
	const INPUT_PASSWORD = 3;

	/**
	 * The identifier for textarea fields type.
	 *
	 * @var integer
	 */
	const TEXTAREA = 4;

	/**
	 * The identifier for dropdown fields type.
	 *
	 * @var integer
	 */
	const DROPDOWN = 5;

	/**
	 * The identifier for separator fields type.
	 *
	 * @var integer
	 */
	const SEPARATOR = 6;

	/**
	 * The identifier for editor fields type.
	 *
	 * @var integer
	 */
	const EDITOR = 7;
}

/**
 * Register a class alias for backward compatibility.
 *
 * @deprecated 1.8
 */
class_alias('VAPCronFormField', 'CronFormField');
