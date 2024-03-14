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

VAPLoader::import('libraries.menu.leftboard.separator');

/**
 * Extends the SeparatorItemShape class to handle a menu group.
 *
 * @since 1.6.3
 */
class HorizontalSeparatorItemShape extends SeparatorItemShape
{
	/**
	 * @override
	 * Builds and returns the html structure of the separator that wraps the children.
	 * This method must be implemented to define a specific graphic of the separator.
	 *
	 * @param 	string 	$html 	The full structure of the children of the separator.
	 *
	 * @return 	string 	The html of the separator.
	 */
	protected function buildHtml($html)
	{
		$data = array(
			'selected'  => $this->isSelected(),
			'collapsed' => $this->isCollapsed(),
			'href'      => $this->getHref(),
			'icon'      => $this->getCustom(),
			'title'     => $this->getTitle(),
			'children'  => $this->children(),
			'html'      => $html,
		);

		$layout = new JLayoutFile('menu.horizontal.separator');

		return $layout->render($data);
	}
}
