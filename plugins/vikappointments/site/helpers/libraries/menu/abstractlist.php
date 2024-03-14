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
 * Abstract list menu class that implements common methods.
 *
 * @since 	1.6.3
 */
class MenuAbstractList
{
	/**
	 * A list of elements.
	 *
	 * @var array
	 */
	protected $menu;

	/**
	 * The construct sets all the items contained in the menu.
	 *
	 * @param 	array  	$menu 	The menu to push.
	 *
	 * @uses 	setMenu()
	 */
	public function __construct(array $menu = array())
	{
		$this->setMenu($menu);
	}

	/**
	 * Pushes an item into the menu.
	 *
	 * @param 	mixed 	$item 	The item to push.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function push($item)
	{
		$this->menu[] = $item;

		return $this;
	}

	/**
	 * Returns the index of the requested item.
	 * It is possible to obtain an item by reference or through
	 * an associative array that determines the query arguments.
	 *
	 * @param 	mixed 	$item  The item reference or a search query.
	 *
	 * @return 	mixed 	The item index, false otherwise.
	 */
	public function indexOf($item)
	{
		// iterate items
		foreach ($this->menu as $index => $tmp)
		{
			// look for the item reference
			if (is_object($item))
			{
				// check if the reference is the same
				if ($tmp === $item)
				{
					// return item index
					return $index;
				}
			}
			// look for a query
			else if (is_array($item))
			{
				$found = true;

				// iterate query
				foreach ($item as $k => $v)
				{
					$match = null;

					// access item value
					if (isset($tmp->{$k}))
					{
						// use direct property
						$match = $tmp->{$k};
					}
					else
					{
						// fallback to getter method
						$getter = 'get' . ucfirst($k);

						if (method_exists($tmp, $getter))
						{
							$match = $tmp->{$getter}();
						}
					}

					// make sure there is something to match and the return value matches the specified one
					$found = $found && ($match && $tmp->{$getter}() == $v);
				}

				if ($found === true)
				{
					// item found, return index
					return $index;
				}
			}
		}

		return false;
	}

	/**
	 * Returns the reference of the requested item.
	 * It is possible to obtain an item by index or through
	 * an associative array that determines the query arguments.
	 *
	 * @param 	mixed 	$item  The item index or a search query.
	 *
	 * @return 	mixed 	The item found, null otherwise.
	 *
	 * @uses    indexOf()
	 */
	public function get($item)
	{
		// look for an item at index (if numeric)
		if (is_numeric($item))
		{
			if (isset($this->menu[$item]))
			{
				return $this->menu[$item];
			}
		}
		else
		{
			// get item index
			$index = $this->indexOf($item);

			if ($index !== false)
			{
				// item found, return it
				return $this->menu[$index];
			}
		}

		return null;
	}

	/**
	 * Changes the item at the specified position.
	 *
	 * @param 	mixed 	 $item  The item index, reference or a search query.
	 * @param 	mixed 	 $set   The item to set.
	 *
	 * @return 	boolean  True if changed, false otherwise.
	 *
	 * @uses    indexOf()
	 */
	public function set($item, $set)
	{
		if (!is_numeric($item))
		{
			// access item index
			$item = $this->indexOf($item);
		}

		// make sure the menu exists
		if ($item === false || !isset($this->menu[$item]))
		{
			// item not found
			return false;
		}

		// replace item at position found
		$this->menu[$item] = $set;

		return true;
	}

	/**
	 * Deletes the requested item.
	 * It is possible to delete an item by index, reference or through
	 * an associative array that determines the query arguments.
	 *
	 * @param 	mixed 	 $item  The item index, reference or a search query.
	 *
	 * @return 	boolean  True if removed, false otherwise.
	 *
	 * @uses 	indexOf()
	 *
	 * @since 	1.6.3
	 */
	public function delete($item)
	{
		if (!is_numeric($item))
		{
			// access item index
			$item = $this->indexOf($item);
		}

		// make sure the menu exists
		if ($item === false || !isset($this->menu[$item]))
		{
			// item not found
			return false;
		}

		// splice menu and remove item found
		return (bool) array_splice($this->menu, $item, 1);
	}

	/**
	 * Sets the items in the menu.
	 *
	 * @param 	array 	$menu 	The array containing the items to push.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function setMenu(array $menu = array())
	{	
		$this->menu = $menu;

		return $this;
	}

	/**
	 * Returns the list of the items in the menu.
	 *
	 * @return 	array 	The list of the items.
	 */
	public function getMenu()
	{
		return $this->menu;
	}
}
