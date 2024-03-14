<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	core
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

/**
 * VikRentItems shortcode table.
 *
 * @since 1.0.5
 */
class JTableShortcode extends JTable
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikrentitems_wpshortcodes', 'id', $db);
	}
}
