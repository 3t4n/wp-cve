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

VAPLoader::import('libraries.menu.shape');

/**
 * Extends the MenuShape class to handle a collapsable left-board menu.
 *
 * @since 1.5
 * @since 1.6.3 Renamed from LeftBoardMenu to LeftboardMenuShape.
 */
class LeftboardMenuShape extends MenuShape
{
	/**
	 * Flag used to check if the menu is compressed or not.
	 *
	 * @var boolean
	 */
	private $compressed = false;

	/**
	 * The constructor sets all the items to contain in the menu.
	 *
	 * @param 	array  	$menu 	The menu to push.
	 */
	public function __construct(array $menu = array())
	{
		parent::__construct($menu);

		/**
		 * Retrieve main menu status from browser cookie.
		 * If the menu status is not set, use "expanded"
		 * status by default (1).
		 *
		 * @since 1.7
		 */
		$status = JFactory::getApplication()->input->cookie->getUint('vikappointments_mainmenu_status', 1);

		// return true if collapsed (2)
		$compressed = $status == 2 ? true : false;

		// set/unset compression mode
		$this->compress($compressed);
	}

	/**
	 * Mark the menu as compressed or collapsed.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function compress($compressed)
	{
		$this->compressed = $compressed;

		return $this;
	}

	/**
	 * @overrides
	 * Builds and returns the html structure of the menu that wraps the children.
	 * This method must be implemented to define a specific graphic of the menu.
	 *
	 * @param 	string 	$html 	The full structure of the children of the menu.
	 *
	 * @return 	string 	The html of the menu.
	 */
	protected function buildHtml($html)
	{
		$layout = new JLayoutFile('menu.leftboard.menu');

		return $layout->render(array('html' => $html, 'compressed' => $this->compressed));
	}

	/**
	 * @override
	 * Builds and returns the html opening that will wrap the body contents.
	 * This html will be displayed after the menu.
	 *
	 * @return 	string 	The body opening html.
	 *
	 * @since 	1.6.3
	 */
	public function openBody()
	{
		$layout = new JLayoutFile('menu.leftboard.body.open');

		return $layout->render(array('compressed' => $this->compressed));
	}

	/**
	 * @override
	 * Builds and returns the html closing that will wrap the body contents.
	 * This html will be displayed after the menu and the body opening.
	 *
	 * @return 	string 	The body closing html.
	 *
	 * @since 	1.6.3
	 */
	public function closeBody()
	{
		$layout = new JLayoutFile('menu.leftboard.body.close');

		return $layout->render();
	}
}
