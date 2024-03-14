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
 * Action container map implementor.
 * 
 * @since 1.7.3
 */
class VAPActionContainerMap implements VAPActionContainer
{
	/**
	 * An event-subscribers lookup.
	 * 
	 * @var VAPActionObservable[]
	 */
	protected $map = [];

	/**
	 * Attaches the specified observer to the given event.
	 * 
	 * @param 	string 	           $event     The event to observe.
	 * @param 	VAPActionObserver  $observer  The subsriber instance.
	 * 
	 * @return 	void
	 */
	public function attach($event, VAPActionObserver $observer)
	{
		if (!isset($this->map[$event]))
		{
			// create new observale instance
			$this->map[$event] = new VAPActionObservableAdapter();
		}

		// attach subscriber to the given event
		$this->map[$event]->attach($observer);
	}

	/**
	 * Detaches the specified observer from the given event.
	 * 
	 * @param 	string 	           $event     The observed event.
	 * @param 	VAPActionObserver  $observer  The subsriber instance.
	 * 
	 * @return 	boolean            True in case of success.
	 */
	public function detach($event, VAPActionObserver $observer)
	{
		if (!isset($this->map[$event]))
		{
			// event not yet registered
			return false;
		}

		// try to detach the subscriber from the given event
		return $this->map[$event]->detach($observer);
	}

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
	public function detachAll($event, VAPActionObserver $observer = null)
	{
		if (!isset($this->map[$event]))
		{
			// event not yet registered
			return false;
		}

		if (!$observer)
		{
			// clear all subscribed elements
			unset($this->map[$event]);
		}
		else
		{
			// iterate as long as the given instance is attached
			// to the observable event
			while ($this->map[$event]->detach($observer));
		}

		return true;
	}

	/**
	 * Notifies the subscribers every time the internal state of the
	 * given event changes.
	 * 
	 * @param 	string           $event  The involved event.
	 * @param 	VAPActionState   $data   The state wrapper.
	 * 
	 * @return 	VAPActionResult  A list of results returned by the observers.
	 */
	public function notify($event, VAPActionState $state)
	{
		if (!isset($this->map[$event]))
		{
			// event not yet registered
			return new VAPActionResult();
		}

		// notifies all the listeners attached to the given event
		return $this->map[$event]->notify($state);
	}
}
