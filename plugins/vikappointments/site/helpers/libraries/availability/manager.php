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
 * Class responsible to dispatch the availability search handler.
 *
 * @since 1.7
 */
final class VAPAvailabilityManager
{
	/**
	 * The name of the search handler.
	 *
	 * @var string
	 */
	public static $manager = null;

	/**
	 * Creates a new instance of the availability search handler.
	 *
	 * @param 	integer  $id_ser   The service ID.
	 * @param 	integer  $id_emp   The employee ID (optional).
	 * @param 	array    $options  An array of options.
	 *
	 * @return 	VAPAvailabilitySearch
	 */
	public static function getInstance($id_ser, $id_emp = null, array $options = array())
	{
		if (is_null(static::$manager))
		{
			/**
			 * This hook can be used to safely change the class instance
			 * responsible of handling the whole availability system.
			 * In addition to returning the new class name, plugins must
			 * include all the needed resources. The returned object must
			 * also inherit VAPAvailabilitySearch class.
			 *
			 * The hook will be called only once per page session.
			 *
			 * @return 	string  The class name.
			 *
			 * @since 	1.7
			 */
			static::$manager = VAPFactory::getEventDispatcher()->triggerOnce('onCreateAvailabilitySearch');

			if (!static::$manager)
			{
				// plugin didn't specify a custom implementor, use default one
				VAPLoader::import('libraries.availability.implementor');
				static::$manager = 'VAPAvailabilityImplementor';
			}
		}

		// copy in a local variable
		$classname = static::$manager;

		// make sure the object is loaded and exists
		if (!class_exists($classname))
		{
			// class not found, throw an exception
			throw new Exception(sprintf('Availability search [%s] not found', $classname), 404);
		}

		// create new instance
		$handler = new $classname($id_ser, $id_emp, $options);

		// make sure the object is a valid instance
		if (!$handler instanceof VAPAvailabilitySearch)
		{
			// Not a valid instance, unexpected behavior might occur...
			// Prevent them by throwing an exception.
			throw new Exception(sprintf('The class [%s] is not a valid instance', $classname), 500);
		}

		return $handler;
	}

	/**
	 * Class constructor.
	 * Declared with protected visibility to avoid its instantiation.
	 */
	protected function __construct()
	{
		// cannot be publicly instantiated
	}
}
