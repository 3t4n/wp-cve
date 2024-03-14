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

VAPLoader::import('libraries.widget.input');

/**
 * Base abstract class to implement a radio button.
 *
 * @since 	1.6
 */
abstract class UIRadio implements UIInput
{
	/**
	 * The name of the input.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The possible radio buttons.
	 *
	 * @var array (of objects)
	 */
	private $elements;

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Class constructor.
	 *
	 * @param 	string 	$name
	 * @param 	array 	$elements
	 * @param 	array 	$options
	 */
	public function __construct($name, array $elements = array(), array $options = array())
	{
		$this->name 	= $name;
		$this->elements = array();
		$this->options 	= $options;

		foreach ($elements as $elem)
		{
			$this->addElement($elem);
		}
	}

	/**
	 * Get the name of the input.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the possible radio buttons.
	 *
	 * @return 	array
	 */
	public function getElements()
	{
		return $this->elements;
	}

	/**
	 * Get an array of options.
	 *
	 * @return 	array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Insert a radio button element.
	 *
	 * @param 	object 	 $element 	The radio button element.
	 *
	 * @return 	UIRadio  This object to support chaining.
	 *
	 * @uses 	UIRadio::bind()  	Adapt the given element.
	 */
	public function addElement($element)
	{
		if (is_object($element))
		{
			$this->elements[] = $this->bind($element);
		}

		return $this;
	}

	/**
	 * Adapt the given element.
	 *
	 * Children classes can override this method to adapt
	 * the element depending on their requirements.
	 *
	 * @param 	object 	$element 	The radio button element.
	 *
	 * @return 	object 	The adapted element.
	 */
	protected function bind($element)
	{
		// do something here...

		return $element;
	}

	/**
	 * Call this method to build and return the HTML of the input.
	 *
	 * @return 	string 	The input HTML.
	 */
	public abstract function display();
}
