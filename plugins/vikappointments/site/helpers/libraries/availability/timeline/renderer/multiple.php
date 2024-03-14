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

VAPLoader::import('libraries.availability.timeline.renderer');

/**
 * Multiple timelines renderer.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineRendererMultiple extends VAPAvailabilityTimelineRenderer
{
	/**
	 * Prepares the data to display.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	array   The resulting display data.
	 */
	public function getDisplayData(array $data = array())
	{
		$data['employees'] = array();

		// iterate all timelines
		foreach ($this->timeline as $timeline)
		{
			// get most appropriate renderer
			$renderer = VAPAvailabilityTimelineFactory::getRenderer($timeline->timeline);

			// register display data
			$data['employees'][] = array(
				'id'       => $timeline->id,
				'name'     => $timeline->name,
				'timeline' => $renderer->display(),
			);
		}

		return $data;
	}

	/**
	 * Renders the layout of the specified timeline.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	string  The timeline HTML.
	 */
	protected function render(array $data = array())
	{
		$layout = new JLayoutFile('timeline.multiple');

		// the layout is available only for the back-end

		return $layout->render($data);
	}
}
