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
 * Class used to represent the shape of a separator menu.
 *
 * @since 	1.5
 *
 * @see 	MenuAbstractList
 * @see 	MenuItemShape 
 */
abstract class SeparatorItemShape extends MenuAbstractList
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
	 * @param 	string 	 $title 	The title of the separator.
	 * @param 	string 	 $href 		Default empty.
	 * @param 	boolean  $selected 	Default false.
	 *
	 * @uses 	setTitle()
	 * @uses 	setHref()
	 * @uses 	setSelected()
	 */
	public function __construct($title, $href = '', $selected = false)
	{
		$this->setTitle($title)
			->setHref($href)
			->setSelected($selected);

		parent::__construct();
	}

	/**
	 * Sets the title of the separator.
	 *
	 * @param 	string 	$title 	The title of the separator.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Returns the title of the separator.
	 *
	 * @return 	string 	The title of the separator.
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Sets the url of the separator.
	 *
	 * @param 	string 	$href  The url of the separator.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setHref($href)
	{
		$this->href = $href;

		return $this;
	}

	/**
	 * Returns the url of the separator.
	 *
	 * @return 	string 	The url of the separator.
	 */
	public function getHref()
	{
		return $this->href;
	}

	/**
	 * Sets the custom value of the separator
	 *
	 * @param 	string 	$custom  The custom value of the separator.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setCustom($custom)
	{
		$this->custom = $custom;

		return $this;
	}

	/**
	 * Returns the custom value of the separator.
	 *
	 * @return 	string 	The custom value of the separator.
	 */
	public function getCustom()
	{
		return $this->custom;
	}

	/**
	 * Sets if the separator is selected or not.
	 *
	 * @param 	boolean  $selected 	The selection of the separator.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function setSelected($selected)
	{
		$this->selected = $selected;

		return $this;
	}

	/**
	 * Returns true if the separator is selected, otherwise false.
	 *
	 * @return 	boolean  If the separator is selected.
	 */
	public function isSelected()
	{
		return $this->selected;
	}

	/**
	 * Returns true if the separator is collapsed, otherwise false.
	 * A separator is collapsed when it contains at least a selected child.
	 *
	 * @return 	boolean  If the separator is collapsed.
	 *
	 * @uses 	MenuItemShape::isSelected()
	 */
	public function isCollapsed()
	{
		foreach ($this->menu as $c)
		{
			if ($c instanceof SeparatorItemShape && $c->isCollapsed())
			{
				return true;
			}
			else if ($c instanceof MenuItemShape && $c->isSelected())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Adds a child into the separator.
	 *
	 * @param 	MenuItemShape  $child  The child to add.
	 *
	 * @return 	self 		   This object to support chaining.
	 */
	public function addChild(MenuItemShape $child)
	{
		return parent::push($child);
	}

	/**
	 * Sets a child at the specified position. Returns true on success, otherwise false.
	 *
	 * @param 	int 		   $i 		The position of the child to replace or add.
	 * @param 	MenuItemShape  $child 	The child to add.
	 *
	 * @return 	boolean		   True on success.
	 */
	public function setChild($i, MenuItemShape $child)
	{
		return parent::set($i, $child);
	}

	/**
	 * Deletes the requested item.
	 * It is possible to delete an item by index, reference or through
	 * an associative array that determines the query arguments.
	 *
	 * @param 	mixed 	 $item  The item index, reference or a search query.
	 *
	 * @return 	boolean  True if removed, false otherwise.
	 */
	public function unsetChild($i)
	{
		return parent::delete($i);
	}

	/**
	 * Empties the children list.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function clearChildren()
	{
		return parent::setMenu(array());
	}

	/**
	 * Returns the list of the children of the separator.
	 *
	 * @return 	array 	The MenuItemShape array list of the children.
	 */
	public function children()
	{
		return parent::getMenu();
	}

	/**
	 * Builds and returns the html structure of the separator and its children.
	 * @usedby MenuShape::build
	 *
	 * @return 	string 		The html structure.
	 *
	 * @uses 	buildHtml()
	 */
	public function build()
	{
		$html = "";

		// get the HTML structure from each child of the separator
		foreach ($this->menu as $c)
		{
			if ($c instanceof SeparatorItemShape)
			{
				// get the HTML if the child is a separator
				$html .= $c->build();
			}
			else if ($c instanceof MenuItemShape)
			{
				// get the HTML if the child is a menu item
				$html .= $c->buildHtml();
			}
		}

		// build the structure of the separator, which will contain the evaluated $html
		return $this->buildHtml($html);
	}

	/**
	 * Builds and returns the html structure of the separator that wraps the children.
	 * This method must be implemented to define a specific graphic of the separator.
	 *
	 * @param 	string 	$html 	The full structure of the children of the separator.
	 *
	 * @return 	string 	The html of the separator.
	 */
	abstract protected function buildHtml($html);
}
