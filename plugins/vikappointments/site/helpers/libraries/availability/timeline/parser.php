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

VAPLoader::import('libraries.availability.search');
VAPLoader::import('libraries.availability.timeline');

/**
 * Timeline parser abstract interface.
 *
 * @since 1.7
 */
abstract class VAPAvailabilityTimelineParser extends JObject
{
	/**
	 * The search handler.
	 *
	 * @var VAPAvailabilitySearch
	 */
	protected $search;

	/**
	 * Class constructor.
	 *
	 * @param 	VAPAvailabilitySearch  $search  The search handler.
	 */
	public function __construct(VAPAvailabilitySearch $search)
	{
		$this->search = $search;
	}

	/**
	 * Calculates the availability timeline for the specified date.
	 * In case of error, it is possible to retrieve a message by
	 * calling the internal getError() method.
	 *
	 * @param 	string 	 $date    The UTC date in military format.
	 * @param 	integer  $people  The number of participants.
	 * @param 	integer  $id      The selected appointment ID.
	 *
	 * @return 	mixed    The resulting timeline on success, false otherwise.
	 */
	final public function getTimeline($date, $people = 1, $id = 0)
	{
		try
		{
			/**
			 * Trigger hook to check whether the availability timeline should be displayed
			 * or not. Useful in example to avoid showing the timeline to guest users.
			 *
			 * Throw an exception to display an error message to the front-end user.
			 *
			 * @param   JObject  $search  The search handler.
			 * @param   string   $date    The UTC date in military format.
			 * @param   int      $people  The number of participants.
			 * @param   int      $id      The selected appointment ID.
			 *
			 * @return  bool  Return false to prevent the timeline building.
			 *
			 * @throws  Exception
			 *
			 * @since   1.7
			 */
			if (VAPFactory::getEventDispatcher()->false('onBeforeBuildTimeline', [$this->search, $date, $people, $id]))
			{
				// do not build the timeline as instructed by a plugin
				return false;
			}
		}
		catch (Exception $e)
		{
			// register error message thrown by the plugin
			$this->setError($e);
			return false;
		}

		// invoke internal method to build the timeline
		$timeline = $this->buildTimeline($date, $people, $id);

		if ($timeline instanceof VAPAvailabilityTimeline)
		{
			try
			{
				/**
				 * Fire an event to manipulate the timeline without having to create a new parser.
				 * DO NOT trigger in case the timeline building returned an invalid instance.
				 * 
				 * Throw an exception to display an error message to the front-end user.
				 *
				 * @param   VAPAvailabilityTimeline  $timeline  The timeline object.
				 * 
				 * @return  bool  Return false to prevent the timeline building.
				 * 
				 * @throws  Exception
				 * 
				 * @since   1.7.4
				 */
				if (VAPFactory::getEventDispatcher()->false('onAfterBuildTimeline', [$timeline]))
				{
					// do not build the timeline as instructed by a plugin
					return false;
				}
			}
			catch (Exception $e)
			{
				// register error message thrown by the plugin
				$this->setError($e);
				return false;
			}
		}
		
		return $timeline;
	}

	/**
	 * Internal method used by children classes to implement their own logic
	 * to fetch the availability timeline.
	 *
	 * @param 	string 	 $date    The UTC date in military format.
	 * @param 	integer  $people  The number of participants.
	 * @param 	integer  $id      The selected appointment ID.
	 *
	 * @return 	mixed    The resulting timeline on success, false otherwise.
	 */
	abstract protected function buildTimeline($date, $people = 1, $id = 0);
}
