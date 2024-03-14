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
 * Observable action interface.
 * 
 * @since 1.7.3
 */
interface VAPActionObservable
{
	/**
	 * Attaches the specified observer to this entity.
	 * 
	 * @param 	VAPActionObserver  $observer
	 * 
	 * @return 	void
	 */
	public function attach(VAPActionObserver $observer);

	/**
	 * Detaches the specified observer from this entity.
	 * 
	 * @param 	VAPActionObserver  $observer
	 * 
	 * @return 	boolean         True in case of success.
	 */
	public function detach(VAPActionObserver $observer);

	/**
	 * Notifies the subscribers every time the internal state changes.
	 * 
	 * @param 	VAPActionState   $state
	 * 
	 * @return 	VAPActionResult  A list of results returned by the observers.
	 */
	public function notify(VAPActionState $state);
}
