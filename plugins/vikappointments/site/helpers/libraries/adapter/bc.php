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

// import Joomla controller library
jimport('joomla.application.component.controller');
// import Joomla view library
jimport('joomla.application.component.view');

// this should be already loaded from autoload.php
VAPLoader::import('libraries.adapter.version.listener');

if (class_exists('JViewLegacy'))
{
	/* Joomla 3.x adapters */

	if (!class_exists('JViewBaseUI'))
	{
		class_alias('JViewLegacy', 'JViewBaseUI');
	}

	if (!class_exists('JControllerBaseUI'))
	{
		class_alias('JControllerLegacy', 'JControllerBaseUI');
	}

	if (!class_exists('JModelBaseUI'))
	{
		class_alias('JModelLegacy', 'JModelBaseUI');
	}
}
else
{
	/* Joomla 2.5 adapters */

	if (!class_exists('JViewBaseUI'))
	{
		class_alias('JView', 'JViewBaseUI');
	}

	if (!class_exists('JControllerBaseUI'))
	{
		class_alias('JController', 'JControllerBaseUI');
	}

	if (!class_exists('JModelBaseUI'))
	{
		class_alias('JModel', 'JModelBaseUI');
	}
}

// add class aliases for BC
if (!class_exists('UIFactory'))
{
	class_alias('VAPFactory', 'UIFactory');
}

if (!class_exists('UILoader'))
{
	class_alias('VAPLoader', 'UILoader');
}

if (!class_exists('UIApplication'))
{
	class_alias('VAPApplication', 'UIApplication');
}

/**
 * Just check whether the `JUser` class exists to force Joomla to 
 * use it as an alias of the `Joomla\CMS\User\User` native class.
 * 
 * @since 5.0 (Joomla)
 */
class_exists('JUser', true);
