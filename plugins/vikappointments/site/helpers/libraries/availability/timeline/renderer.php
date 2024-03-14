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

VAPLoader::import('libraries.availability.timeline');

/**
 * Timeline renderer abstract interface.
 *
 * @since 1.7
 */
abstract class VAPAvailabilityTimelineRenderer
{
	/**
	 * The timeline wrapper.
	 *
	 * @var VAPAvailabilityTimeline|array
	 */
	protected $timeline;

	/**
	 * Class constructor.
	 *
	 * @param 	mixed   $timeline  Either a timeline instance or an array of timelines.
	 */
	public function __construct($timeline)
	{
		$this->timeline = $timeline;
	}

	/**
	 * Returns the internal timeline.
	 *
	 * @return 	 VAPAvailabilityTimeline|array
	 */
	public function getTimeline()
	{
		return $this->timeline;
	}

	/**
	 * Prepares the data to display.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	array   The resulting display data.
	 */
	public function getDisplayData(array $data = array())
	{
		// inject timeline within display data
		$data['timeline'] = $this->timeline;

		return $data;
	}

	/**
	 * Creates the layout of the specified timeline.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	string  The timeline HTML.
	 */
	public function display(array $data = array())
	{
		return $this->render($this->getDisplayData($data));
	}

	/**
	 * Magic method to render the timeline when this object
	 * is casted into a string.
	 *
	 * @return 	string  The timeline HTML. 
	 */
	public function __toString()
	{
		// display without passing any display data
		return $this->display();
	}

	/**
	 * Renders the layout of the specified timeline.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	string  The timeline HTML.
	 */
	abstract protected function render(array $data = array());
}
