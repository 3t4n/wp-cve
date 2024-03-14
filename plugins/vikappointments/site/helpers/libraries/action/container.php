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
 * Action container interface.
 * 
 * @since 1.7.3
 */
interface VAPActionContainer
{
	/**
	 * Attaches the specified observer to the given event.
	 * 
	 * @param 	string 	           $event     The event to observe.
	 * @param 	VAPActionObserver  $observer  The subsriber instance.
	 * 
	 * @return 	void
	 */
	public function attach($event, VAPActionObserver $observer);

	/**
	 * Detaches the specified observer from the given event.
	 * 
	 * @param 	string 	           $event     The observed event.
	 * @param 	VAPActionObserver  $observer  The subsriber instance.
	 * 
	 * @return 	boolean            True in case of success.
	 */
	public function detach($event, VAPActionObserver $observer);

	/**
	 * Detaches all the same instances of the specified observer from the
	 * given event. In case the observer instance is omitted, the method
	 * will clear all the subscribers attached to the given event.
	 * 
	 * @param 	string 	           $event     The observed event.
	 * @param 	VAPActionObserver  $observer  The subsriber instance.
	 * 
	 * @return 	boolean            True in case of success.
	 */
	public function detachAll($event, VAPActionObserver $observer = null);

	/**
	 * Notifies the subscribers every time the internal state of the
	 * given event changes.
	 * 
	 * @param 	string           $event  The involved event.
	 * @param 	VAPActionState   $data   The state wrapper.
	 * 
	 * @return 	VAPActionResult  A list of results returned by the observers.
	 */
	public function notify($event, VAPActionState $state);
}
