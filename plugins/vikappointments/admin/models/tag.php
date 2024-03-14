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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments tag model.
 *
 * @since 1.7
 */
class VikAppointmentsModelTag extends JModelVAP
{
	/**
	 * Array used to cache the loaded tags.
	 *
	 * @var array
	 */
	protected static $tags = array();

	/**
	 * Takes the tag names and creates all the missing records.
	 * 
	 * @param   mixed    $tags    Either an array or a string (comma-separated).
	 * @param 	string   $group   The group to which the tags belong.
	 * @param   boolean  $column  The name of the column to return (* for all).
	 *
	 * @return 	array    The resulting tags.
	 */
	public function writeTags($tags, $group = null, $column = 'id')
	{
		if (is_string($tags))
		{
			// explode tags string
			$tags = preg_split("/\s*,\s*/", $tags);
		}
		else
		{
			$tags = (array) $tags;
		}

		$list = array();

		if (!$tags)
		{
			// nothing to commit
			return $list;
		}

		foreach ($tags as $tag)
		{
			// load tag details
			$item = $this->getItem(array('name' => $tag));

			if (!$item)
			{
				// tag not found, create it
				if ($this->save(array('name' => $tag, 'group' => $group)))
				{
					// get saved data
					$item = (object) $this->getData();
				}
			}

			if ($item)
			{
				// tag found/created, register it within the list
				if ($column == '*')
				{
					// return the whole object
					$list[] = $item;
				}
				else if (isset($item->{$column}))
				{
					// return the specified property
					$list[] = $item->{$column};
				}
				else
				{
					// return the tag ID
					$list[] = $item->id;
				}
			}
		}

		return $list;
	}

	/**
	 * Takes the tag IDs and convert them into the related name.
	 * 
	 * @param   mixed    $tags    Either an array or a string (comma-separated).
	 * @param   boolean  $column  The name of the column to return (* for all).
	 *
	 * @return 	array    The resulting tags.
	 */
	public function readTags($tags, $column = 'name')
	{
		if (is_string($tags))
		{
			// explode tags string
			$tags = preg_split("/\s*,\s*/", $tags);
		}
		else
		{
			$tags = (array) $tags;
		}

		$list = array();

		if (!$tags)
		{
			// nothing to read
			return $list;
		}

		foreach (array_map('intval', $tags) as $tag)
		{
			if (!isset(static::$tags[$tag]))
			{
				// load tag details only once
				static::$tags[$tag]	= $this->getItem($tag);
			}

			if (static::$tags[$tag])
			{
				// tag found, register it within the list
				if ($column == '*')
				{
					// return the whole object
					$list[] = static::$tags[$tag];
				}
				else if (isset(static::$tags[$tag]->{$column}))
				{
					// return the specified property
					$list[] = static::$tags[$tag]->{$column};
				}
				else
				{
					// return the tag name
					$list[] = static::$tags[$tag]->name;
				}
			}
		}

		return $list;
	}
}
