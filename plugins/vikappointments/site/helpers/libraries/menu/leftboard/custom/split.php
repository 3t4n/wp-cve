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

VAPLoader::import('libraries.menu.custom');

/**
 * Extends the CustomShape class to display a button to collapse the menu.
 *
 * @since 1.5
 * @since 1.6.3 Renamed from LeftBoardMenuSplit to LeftboardCustomShapeSplit.
 */
class LeftboardCustomShapeSplit extends CustomShape
{
	/**
	 * @override
	 * Builds and returns the html structure of the custom menu item.
	 * This method must be implemented to define a specific graphic of the custom item.
	 *
	 * @return 	string 	The html of the custom item.
	 */
	public function buildHtml()
	{
		$layout = new JLayoutFile('menu.leftboard.custom.split');

		return $layout->render();
	}
}
