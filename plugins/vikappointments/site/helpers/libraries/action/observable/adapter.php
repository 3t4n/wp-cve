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
 * Observable action adapter.
 * 
 * @since 1.7.3
 */
class VAPActionObservableAdapter implements VAPActionObservable
{
	/**
	 * An array of subscribers.
	 * 
	 * @var VAPActionObserver[]
	 */
	private $listeners = [];

	/**
	 * Class constructor.
	 * 
	 * @param 	array  $listeners
	 */
	public function __construct(array $listeners = [])
	{
		foreach ($listeners as $l)
		{
			$this->attach($l);
		}
	}

	/**
	 * Attaches the specified observer to this entity.
	 * 
	 * @param 	VAPActionObserver  $observer
	 * 
	 * @return 	void
	 */
	public function attach(VAPActionObserver $observer)
	{
		$this->listeners[] = $observer;
	}

	/**
	 * Detaches the specified observer from this entity.
	 * 
	 * @param 	VAPActionObserver  $observer
	 * 
	 * @return 	boolean            True in case of success.
	 */
	public function detach(VAPActionObserver $observer)
	{
		// search the specified observer
		$index = array_search($observer, $this->listeners);

		if ($index !== false)
		{
			// remove the observer from the array
			array_splice($this->listeners, $index, 1);
			return true;
		}

		return false;
	}

	/**
	 * Notifies the subscribers every time the internal state changes.
	 * 
	 * @param 	VAPActionState   $state
	 * 
	 * @return 	VAPActionResult  A list of results returned by the observers.
	 */
	public function notify(VAPActionState $state)
	{
		$results = [];

		// iterate subscribers
		foreach ($this->listeners as $l)
		{
			// trigger state change
			$results[] = $l->trigger($state);

			if ($state->isPropagationStopped())
			{
				// break the cycle in case the last subscriber
				// stopped the action propagation
				break;
			}
		}

		return new VAPActionResult($results);
	}
}
