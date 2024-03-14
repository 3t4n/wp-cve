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
 * Timeline default renderer.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineRendererDefault extends VAPAvailabilityTimelineRenderer
{
	/**
	 * Renders the layout of the specified timeline.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	string  The timeline HTML.
	 */
	protected function render(array $data = array())
	{
		$layout = new JLayoutFile('timeline.default');

		if (JFactory::getApplication()->isClient('administrator'))
		{
			// use front-end layout path in case the back-end doesn't specify it
			$layout->addIncludePath(VAPBASE . DIRECTORY_SEPARATOR . 'layouts');
		}

		return $layout->render($data);
	}
}
