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
 * Class used to represent the shape of a menu item.
 *
 * @since 	1.5
 */
abstract class MenuItemShape
{
	/**
	 * The title of the separator.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The url of the separator. This value can be ignored.
	 *
	 * @var string
	 */
	private $href;

	/**
	 * A custom value to use during the building.
	 *
	 * @var string
	 */
	private $custom;

	/**
	 * If the separator is selected.
	 *
	 * @var boolean
	 */
	private $selected;

	/**
	 * The construct sets all the required attributes of this class.
	 *
	 * @param  	string 	$title 			The title of the menu item.
	 * @param  	string 	$href 			Default empty.
	 * @param  	boolean $selected 		Default false.
	 *
	 * @uses 	setTitle()
	 * @uses 	setHref()
	 * @uses 	setSelected()
	 */
	public function __construct($title, $href, $selected = false)
	{
		$this->setTitle($title)
			->setHref($href)
			->setSelected($selected);
	}

	/**
	 * Sets the title of the menu item.
	 *
	 * @param 	string 	$title 	The title of the menu item.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Returns the title of the menu item.
	 *
	 * @return 	string 	The title of the menu item.
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Sets the url of the menu item. 
	 *
	 * @param 	string 	$href 	The url of the menu item.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function setHref($href)
	{
		$this->href = $href;

		return $this;
	}

	/**
	 * Returns the url of the menu item.
	 *
	 * @return 	string 	The url of the menu item.
	 */
	public function getHref()
	{
		return $this->href;
	}

	/**
	 * Sets the custom value of the menu item
	 *
	 * @param 	string 	$custom  The custom value of the menu item.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function setCustom($custom)
	{
		$this->custom = $custom;

		return $this;
	}

	/**
	 * Returns the custom value of the menu item.
	 *
	 * @return 	string 	The custom value of the menu item.
	 */
	public function getCustom()
	{
		return $this->custom;
	}

	/**
	 * Sets if the menu item is selected or not.
	 *
	 * @param 	boolean  $selected 	The selection of the menu item.
	 *
	 * @return 	self	 This object to support chaining.
	 */
	public function setSelected($selected)
	{
		$this->selected = $selected;

		return $this;
	}

	/**
	 * Returns true if the menu item is selected, otherwise false.
	 *
	 * @return 	boolean  True if selected.
	 */
	public function isSelected()
	{
		return $this->selected;
	}

	/**
	 * Builds and returns the html structure of the menu item.
	 * This method must be implemented to define a specific graphic of the menu item.
	 *
	 * @return  string 	The html of the menu item.
	 */
	abstract public function buildHtml();
}
