<?php
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_items
 * @author      Alessio Gaggii - E4J s.r.l
 * @copyright   Copyright (C) 2020 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

jimport('adapter.module.widget');

/**
 * Items Module implementation for WP
 *
 * @see 	JWidget
 * @since 	1.0
 */
class ModVikrentitemsItems_Widget extends JWidget
{
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		// attach the absolute path of the module folder
		parent::__construct(dirname(__FILE__));
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param 	array 	$new_instance 	Values just sent to be saved.
	 * @param 	array 	$old_instance 	Previously saved values from database.
	 *
	 * @return 	array 	Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$new_instance['title'] 			= !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$new_instance['showcatname'] 	= intval($new_instance['showcatname']);
		$new_instance['showdetailsbtn'] = intval($new_instance['showdetailsbtn']);
		$new_instance['showitemdesc'] 	= intval($new_instance['showitemdesc']);
		$new_instance['show_carats'] 	= intval($new_instance['show_carats']);
		$new_instance['numb'] 			= intval($new_instance['numb']);
		$new_instance['numb_itemrow'] 	= intval($new_instance['numb_itemrow']);
		$new_instance['pagination'] 	= intval($new_instance['pagination']);
		$new_instance['navigation'] 	= intval($new_instance['navigation']);
		$new_instance['autoplay'] 		= intval($new_instance['autoplay']);
		$new_instance['get_loop'] 		= intval($new_instance['get_loop']);
		$new_instance['catid'] 			= intval($new_instance['catid']);

		return $new_instance;
	}
}
