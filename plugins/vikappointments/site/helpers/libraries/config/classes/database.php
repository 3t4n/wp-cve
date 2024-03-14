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

// include parent class in order to extend the configuration without errors
VAPLoader::import('libraries.config.abstract');

/**
 * Utility class working with a physical configuration stored into the Joomla database.
 *
 * @since  1.6
 * @since  1.7  Renamed from UIConfigDatabase
 */
class VAPConfigDatabase extends VAPConfig
{
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$options 	An array of options.
	 */
	public function __construct(array $options = array())
	{
		if (!isset($options['table']))
		{
			$options['table'] = '#__vikappointments_config';
		}

		if (!isset($options['key']))
		{
			$options['key'] = 'param';
		}

		if (!isset($options['value']))
		{
			$options['value'] = 'setting';
		}

		parent::__construct($options);
	}

	/**
	 * @override
	 * Retrieves the value of the setting stored in the Joomla database.
	 *
	 * @param   string 	$key 	The name of the setting.
	 *
	 * @return  mixed 	The value of the setting if exists, otherwise false.
	 */
	protected function retrieve($key)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn($this->options['value']))
			->from($dbo->qn($this->options['table']))
			->where($dbo->qn($this->options['key']) . ' = ' . $dbo->q($key));

		$dbo->setQuery($q, 0, 1);
		return $dbo->loadResult() ?? false;
	}

	/**
	 * @override
	 * Registers the value of the setting into the Joomla database.
	 * All the array and objects will be stringified in JSON.
	 *
	 * @param   string  $key 	The name of the setting.
	 * @param   mixed   $val 	The value of the setting.
	 *
	 * @return  bool 	True in case of success, otherwise false.
	 */
	protected function register($key, $val)
	{
		// get config table
		$config = JModelVAP::getInstance('configuration');

		if (!$config)
		{
			// Models not yet loaded...
			// Auto include the default models folder.
			JModelLegacy::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'models');

			// try again to load the configuration model
			$config = JModelVAP::getInstance('configuration');
		}

		// save configuration setting
		return $config->save(array(
			'param'   => $key,
			'setting' => $val,
		));
	}
}
