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
 * Factory application class.
 *
 * @since 1.6
 * @since 1.7 Renamed from UIFactory
 */
final class VAPFactory
{
	/**
	 * Application configuration handlers.
	 *
	 * @var VAPConfig[]
	 */
	private static $config = array();

	/**
	 * Application event dispatcher.
	 *
	 * @var VAPEventDispatcher
	 */
	private static $eventDispatcher = null;

	/**
	 * The currency object.
	 *
	 * @var VAPCurrency
	 *
	 * @since 1.7
	 */
	private static $currency = null;

	/**
	 * The translator object.
	 *
	 * @var VAPLanguageTranslator
	 *
	 * @since 1.7
	 */
	private static $translator = null;

	/**
	 * The API Framework instance.
	 *
	 * @var VAPApiFramework
	 *
	 * @since 1.7
	 */
	private static $api = null;

	/**
	 * The wizard object.
	 *
	 * @var VAPWizard
	 *
	 * @since 1.7.1
	 */
	private static $wizard = null;

	/**
	 * Default configuration class handler.
	 *
	 * @var string
	 */
	public static $defaultConfigClass = 'database';

	/**
	 * Class constructor.
	 * @private This object cannot be instantiated. 
	 */
	private function __construct()
	{
		// never called
	}

	/**
	 * Class cloner.
	 * @private This object cannot be cloned.
	 */
	private function __clone()
	{
		// never called
	}

	/**
	 * Get the current configuration object.
	 *
	 * @param 	string 	$class 	The handler to use.
	 * @param 	array 	$args 	An options array.
	 *
	 * @return 	VAPConfig
	 *
	 * @throws 	Exception 	When the configuration class doesn't exist.
	 */
	public static function getConfig($class = null, array $args = array())
	{
		// if class not set, get the default one
		if ($class === null)
		{
			$class = static::$defaultConfigClass;
		}

		// check if config class is already instantiated
		if (!isset(static::$config[$class]))
		{
			// build classname
			$classname = 'VAPConfig' . ucwords($class[0]) . substr($class, 1);

			// try to import it (on failure, throws exception)
			if (!VAPLoader::import('libraries.config.classes.' . $class)
				|| !class_exists($classname))
			{
				throw new Exception("Config {$class} not found!");
			}

			// cache instantiation
			static::$config[$class] = new $classname($args);
		}

		return static::$config[$class];
	}

	/**
	 * Returns the internal event dispatcher instance.
	 *
	 * @return 	VAPEventDispatcher 	The event dispatcher.
	 */
	public static function getEventDispatcher()
	{
		if (static::$eventDispatcher === null)
		{
			VAPLoader::import('libraries.event.dispatcher');

			// obtain the software version always from the database
			$version = static::getConfig()->get('version', VIKAPPOINTMENTS_SOFTWARE_VERSION);

			// build options array
			$options = array(
				'alias' 	=> 'com_vikappointments',
				'version' 	=> $version,
				'admin' 	=> JFactory::getApplication()->isClient('administrator'),
				'call' 		=> null, // call is useless as it would be always the same
			);

			static::$eventDispatcher = VAPEventDispatcher::getInstance($options);
		}

		return static::$eventDispatcher;
	}

	/**
	 * Instantiate a new currency object.
	 * 
	 * @param 	boolean  $reload  True to refresh the currency settings (@since 1.7.1).
	 *
	 * @return 	VAPCurrency
	 *
	 * @since 	1.7
	 */
	public static function getCurrency($reload = false)
	{
		if (static::$currency === null || $reload)
		{
			$config = static::getConfig();

			VAPLoader::import('libraries.currency.currency');

			// obtain configuration data
			$data = array(
				'currencyname'     => $config->getString('currencyname', 'EUR'),
				'currencysymb'     => $config->getString('currencysymb', '€'),
				'currsymbpos'      => $config->getInt('currsymbpos', 1),
				'currdecimalsep'   => $config->getString('currdecimalsep', '.'),
				'currthousandssep' => $config->getString('currthousandssep', ','),
				'currdecimaldig'   => $config->getUint('currdecimaldig', 2),
			);

			static::$currency = new VAPCurrency(
				$data['currencyname'],
				$data['currencysymb'],
				abs($data['currsymbpos']),
				array($data['currdecimalsep'], $data['currthousandssep']),
				$data['currdecimaldig'],
				// include space if we have position equals to [1,2]
				$data['currsymbpos'] > 0
			);
		}

		return static::$currency;
	}

	/**
	 * Instantiate a new translator object.
	 *
	 * @return 	VAPLanguageTranslator
	 *
	 * @since 	1.7
	 */
	public static function getTranslator()
	{
		if (static::$translator === null)
		{
			VAPLoader::import('libraries.language.translator');

			static::$translator = VAPLanguageTranslator::getInstance();
		}

		return static::$translator;
	}

	/**
	 * Instantiate a new Framework API object.
	 *
	 * @return 	VAPApiFramework
	 *
	 * @since 	1.7
	 */
	public static function getApi()
	{
		if (static::$api === null) {
			
			// include API libraries and implementors
			VAPLoader::import('libraries.api.autoload');
			VAPLoader::import('libraries.api.implementors.login');
			VAPLoader::import('libraries.api.implementors.framework');

			// instantiate API Framework
			// leave constructor empty to select default plugins folder: 
			// components/com_vikappointments/helpers/libraries/api/plugins/
			static::$api = VAPApiFramework::getInstance();

			// get event dispatcher
			$dispatcher = static::getEventDispatcher();

			/**
			 * Trigger event to let the plugins alter the application framework.
			 * It is possible to use this event to include third-party applications.
			 * 
			 * In example:
			 * $api->addIncludePath($path);
			 * $api->addIncludePaths([$path1, $path2, ...]);
			 *
			 * @param  	VAPApiFramework  $api  The API framework instance.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger('onInitApplicationFramework', array(static::$api));

			// get config handler
			$config = static::getConfig();

			// set apis configuration
			static::$api->set('max_failure_attempts', $config->getUint('apimaxfail', 10));

		}

		return static::$api;
	}

	/**
	 * Instantiate a new wizard object.
	 *
	 * @return 	VAPWizard
	 *
	 * @since 	1.7.1
	 */
	public static function getWizard()
	{
		if (static::$wizard === null)
		{
			VAPLoader::import('libraries.wizard.wizard');

			// get global wizard instance
			$wizard = VAPWizard::getInstance();

			// complete setup only if not yet completed
			if (!$wizard->isDone())
			{
				// define list of steps to load
				$steps = array(
					'system',
					'taxes',
					'employees',
					'services',
					'options',
					'locations',
					'locwdays',
					'payments',
					'syspack',
					'packages',
					'syssubscr',
					'subscriptions',
				);

				// set up wizard
				$wizard->setup($steps);

				// set up steps dependencies

				if ($wizard['services'] && $wizard['employees'])
				{
					$wizard['services']->addDependency($wizard['employees']);
				}

				if ($wizard['options'] && $wizard['services'])
				{
					$wizard['options']->addDependency($wizard['services']);	
				}
				
				if ($wizard['locations'] && $wizard['employees'])
				{
					$wizard['locations']->addDependency($wizard['employees']);
				}

				if ($wizard['locwdays'] && $wizard['employees'])
				{
					$wizard['locwdays']->addDependency($wizard['employees']);
				}

				if ($wizard['locwdays'] && $wizard['locations'])
				{
					$wizard['locwdays']->addDependency($wizard['locations']);
				}

				if ($wizard['packages'] && $wizard['syspack'])
				{
					$wizard['packages']->addDependency($wizard['syspack']);
				}
				
				if ($wizard['subscriptions'] && $wizard['syssubscr'])
				{
					$wizard['subscriptions']->addDependency($wizard['syssubscr']);
				}
			}

			// cache wizard
			static::$wizard = $wizard;
		}

		return static::$wizard;
	}
}
