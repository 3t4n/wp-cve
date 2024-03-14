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
 * Action observer interface.
 * 
 * @since 1.7.3
 */
interface VAPActionObserver
{
	/**
	 * This method is invoked every time the observed
	 * entity changes its status.
	 * 
	 * @param 	VAPActionState  $state
	 * 
	 * @return 	mixed
	 */
	public function trigger(VAPActionState $state);
}
