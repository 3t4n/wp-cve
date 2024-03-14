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

VAPLoader::import('libraries.menu.abstractlist');

/**
 * Abstract factory used to create menu elements.
 *
 * @since 	1.6.3
 */
abstract class MenuFactory
{
	/**
	 * The default menu type to force.
	 *
	 * @var string
	 */
	public static $menuType = null;

	/**
	 * A list of cached classes.
	 *
	 * @var array
	 */
	protected static $classMap = array();

	/**
	 * Returns the current menu type.
	 *
	 * @return 	string
	 */
	public static function getMenuType()
	{
		// check if the menu type was already fetched
		if (is_null(static::$menuType))
		{
			/**
			 * Trigger event to allow the plugins to choose a specific type to
			 * use for the layout of the back-end menu.
			 * By default, VikAppointments supports only 2 menu types:
			 * - 'leftboard', vertical layout placed on the left side;
			 * - 'horizontal', horizontal layout placed above the component contents.
			 *
			 * @return 	string  The menu type to use.
			 *
			 * @since 	1.6.3
			 */
			static::$menuType = VAPFactory::getEventDispatcher()->triggerOnce('onBeforeDefineVikAppointmentsMenuType');

			// fallback to a default menu type if not specified by a custom plugin
			if (!static::$menuType)
			{
				if (VersionListener::getPlatform() == 'joomla')
				{
					// use Leftboard Menu in case of Joomla
					static::$menuType = 'leftboard';
				}
				else
				{
					// use Horizontal menu in case of WordPress
					static::$menuType = 'horizontal';
				}
			}
		}

		return static::$menuType;
	}

	/**
	 * Creates a new concrete menu shape instance.
	 * @see MenuShape for further details about the supported parameters.
	 *
	 * @return 	MenuShape
	 *
	 * @uses 	create()
	 */
	public static function createMenu()
	{
		return static::create('shape', 'MenuShape', func_get_args());
	}

	/**
	 * Creates a new concrete menu separator instance.
	 * @see SeparatorItemShape for further details about the supported parameters.
	 *
	 * @return 	SeparatorItemShape
	 *
	 * @uses 	create()
	 */
	public static function createSeparator()
	{
		return static::create('separator', 'SeparatorItemShape', func_get_args());
	}

	/**
	 * Creates a new concrete menu item instance.
	 * @see MenuItemShape for further details about the supported parameters.
	 *
	 * @return 	MenuItemShape
	 *
	 * @uses 	create()
	 */
	public static function createItem()
	{
		return static::create('item', 'MenuItemShape', func_get_args());
	}

	/**
	 * Creates a new concrete custom menu item instance.
	 * @see CustomShape for further details about the supported parameters.
	 *
	 * @param 	string 	$custom  The name of the custom item.
	 *
	 * @return 	CustomShape
	 *
	 * @uses 	create()
	 */
	public static function createCustomItem($custom)
	{
		// get arguments
		$args = func_get_args();

		// remove custom name from arguments
		$custom = array_shift($args);

		return static::create('custom.' . $custom, 'CustomShape', $args);
	}

	/**
	 * Creates a new concrete instance.
	 *
	 * @param 	string 	$alias   The alias name ['shape', 'separator', 'item' or 'custom'].
	 * @param 	string 	$parent  The parent class ['MenuShape', 'SeparatorItemShape', 'MenuItemShape' or 'CustomShape'].
	 * @param 	array 	$args    The class constructor arguments.
	 *
	 * @return 	mixed   The instantiated class.
	 *
	 * @uses 	getMenuType()
	 */
	protected static function create($alias, $parent, array $args = array())
	{
		if (!isset(static::$classMap[$alias]))
		{
			// get menu type
			$type = static::getMenuType();

			// try to load concrete instance
			if (!VAPLoader::import('libraries.menu.' . $type . '.' . $alias))
			{
				// menu type not found
				throw new Exception(sprintf('Impossible to load [%s.%s] menu type', $type, $alias), 404);
			}

			// build classname
			$classname = ucfirst($type) . ucfirst($parent);

			if (preg_match("/\.(.*?)$/", $alias, $match))
			{
				// extract class suffix from alias
				$classname .= ucfirst(end($match));
			}

			// make sure the class exists
			if (!class_exists($classname))
			{
				// the loaded file doesn't contain the class we were looking for
				throw new Exception(sprintf('Menu class [%s] not found', $classname), 404);
			}

			// make sure the class is a valid instance
			if (!is_subclass_of($classname, $parent))
			{
				// the class exists but it is not inheriting MenuShape
				throw new Exception(sprintf('Menu class [%s] must be an instance of [%s]', $classname, $parent), 500);
			}

			// cache class name
			static::$classMap[$alias] = $classname;
		}

		// create class reflection
		$reflect = new ReflectionClass(static::$classMap[$alias]);
		// instantiate class by using the specified arguments
		return $reflect->newInstanceArgs($args);
	}
}
