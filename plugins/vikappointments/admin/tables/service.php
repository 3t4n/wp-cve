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
 * VikAppointments service table.
 *
 * @since 1.7
 */
class VAPTableService extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_service', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'name';
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($src, $ignore = array())
	{
		$src = (array) $src;

		// fetch ordering for new record
		if (empty($src['id']))
		{
			$src['ordering'] = $this->getNextOrder();

			if (empty($src['color']))
			{
				// assign random color to service
				$src['color'] = $this->getRandomColor($src['ordering']);
			}

			$src['id'] = 0;
		}

		if (isset($src['max_capacity']))
		{
			// max capacity cannot be lower than 1
			$src['max_capacity'] = max(array($src['max_capacity'], 1));

			if ($src['max_capacity'] == 1)
			{
				// unset related settings in case of single capacity
				$src['app_per_slot']  = 1;
				$src['display_seats'] = 0;
			}
		}

		if (isset($src['max_per_res']) && isset($src['max_capacity']))
		{
			// validate max number of people against capacity
			$src['max_per_res'] = min(array($src['max_per_res'], $src['max_capacity']));	
		}

		if (isset($src['min_per_res']) && isset($src['max_per_res']))
		{
			// validate min number of people against max
			$src['min_per_res']  = min(array($src['min_per_res'], $src['max_per_res']));
		}

		// generate alias in case it is empty when creating or updating
		if (empty($src['alias']) && (empty($src['id']) || isset($src['alias'])))
		{
			// generate unique alias starting from name
			$src['alias'] = $src['name'];
		}
		
		// check if we are going to update an empty alias
		if (isset($src['alias']) && strlen($src['alias']) == 0)
		{
			// avoid to update an empty alias by using a unique ID
			$src['alias'] = uniqid();
		}

		if (!empty($src['alias']))
		{
			VAPLoader::import('libraries.sef.helper');
			// make sure the alias is unique
			$src['alias'] = VAPSefHelper::getUniqueAlias($src['alias'], 'service', $src['id']);
		}

		if (isset($src['attachments']) && !is_string($src['attachments']))
		{
			// stringify attachments list
			$src['attachments'] = json_encode($src['attachments']);
		}

		if (isset($src['metadata']) && !is_string($src['metadata']))
		{
			// stringify metadata array/object
			$src['metadata'] = json_encode($src['metadata']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}

	/**
	 * Fetches a random color to assign to the service
	 * based on the current ordering, so that we can
	 * maintain a progressive logic.
	 *
	 * @param 	integer  $ordering  The current ordering.
	 *
	 * @return 	string   The service color.
	 */
	protected function getRandomColor($ordering = 1)
	{
		// get list of preset colors
		$colors = JHtml::fetch('vaphtml.color.preset', $array = true, $group = false);

		// find next one
		$i = (abs($ordering) - 1) % count($colors);

		return $colors[$i];
	}
}
