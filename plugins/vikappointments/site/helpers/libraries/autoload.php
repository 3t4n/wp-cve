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

// require only once the file containing all the defines
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'defines.php';

// if VAPLoader does not exist, include it
if (!class_exists('VAPLoader'))
{
	include VAPLIB . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . 'loader.php';
	// append helpers folder to the base path
	VAPLoader::$base .= DIRECTORY_SEPARATOR . 'helpers';
}

// fix filenames with dots
VAPLoader::registerAlias('lib.vikappointments', 'lib_vikappointments');

// load factory
VAPLoader::import('libraries.system.factory');
VAPLoader::import('libraries.system.error');

// load adapters
VAPLoader::import('libraries.adapter.version.listener');
VAPLoader::import('libraries.adapter.application');
VAPLoader::import('libraries.adapter.bc');

// load mvc
VAPLoader::import('libraries.mvc.controller');
VAPLoader::import('libraries.mvc.table');
VAPLoader::import('libraries.mvc.view');
VAPLoader::import('libraries.mvc.model');

// load dependencies
VAPLoader::import('libraries.employee.auth');
VAPLoader::import('libraries.helpers.date');
VAPLoader::import('libraries.models.customfields');
VAPLoader::import('libraries.models.locations');
VAPLoader::import('libraries.models.orderstatus');
VAPLoader::import('libraries.models.specialrates');

// load component helper
VAPLoader::import('lib_vikappointments');

$app = JFactory::getApplication();

// configure HTML helpers
if ($app->isClient('administrator'))
{
	JHtml::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
}

if ($app->isClient('site') || $app->input->get('option') !== 'com_vikappointments')
{
	// load admin models for front-end client or if we are outside the component
	JModelLegacy::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'models');
}

JTable::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'tables');
JHtml::addIncludePath(VAPLIB . DIRECTORY_SEPARATOR . 'html');

/**
 * Classes autoloader.
 *
 * The following class "VAPFooBarBaz" will be
 * loaded from "site/helpers/libraries/foo/bar/baz.php".
 * 
 * @since 1.7.3
 */
spl_autoload_register(function($class)
{
	$prefix = 'VAP';

	if (strpos($class, $prefix) !== 0)
	{
		// ignore if we are loading an outsider
		return false;
	}

	// remove prefix from class
	$tmp = preg_replace("/^{$prefix}/", '', $class);
	// separate camel-case intersections
	$tmp = preg_replace("/([a-z0-9])([A-Z])/", addslashes('$1' . DIRECTORY_SEPARATOR . '$2'), $tmp);

	// build path from which the class should be loaded
	$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . strtolower($tmp) . '.php';

	// make sure the file exists
	if (is_file($path))
	{
		// include file and check if the class is now available
		if ((include_once $path) && (class_exists($class) || interface_exists($class) || trait_exists($class)))
		{
			return true;
		}
	}

	return false;
});

/**
 * Composer autoloader.
 * 
 * @since 1.7.3
 */
VAPLoader::import('libraries.vendor.autoload');
