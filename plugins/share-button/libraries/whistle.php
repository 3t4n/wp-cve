<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

/// ssst. Plugin communicating
class whistle
{

	protected $listeners = array();
	protected $told = array();
	protected $asked = array();
	protected $offers = array();
	protected $offer_order = array();

	protected $log_active = false;
	//protected $timer_active = false;
	//protected $time_start = 0;

	public static function getInstance()
	{
		return new whistle();

	}

	public function __construct()
	{

	}

	public function tell($msg, $args = array(), $priority='')
	{


		$this->told[$msg][] = $args;
		$this->checkListeners($msg, "tell");
		//$this->timer($msg, array('tell') );

	}

	// if there is only one good answer
	public function ask($msg, $respond = null)
	{
		$return = null;
		//$this->timer($msg, array('ask') );
		if (isset($this->told[$msg]))
		{
			$response = end($this->told[$msg]);

			if (is_null($respond))
				$return = $response;
			elseif (is_callable($respond))
			{
				$return = call_user_func($respond, $response);
			}
		}
		elseif (! is_null($respond)) // if not now, and there is a respond callback, tell them later.
		{
			$this->listen($msg, $respond, 'tell');
		}

		$checked = $this->checkListeners($msg, "ask");
		if (! is_null($checked)) // if the listener has a better answer.
 			$return = $checked;

		return $return;
	}

	// direction: if to listen to somebody telling something, or asking something.
	public function listen($msg, $callback, $direction)
	{
		$this->listeners[$direction][$msg][] = $callback; // wtf!
	}

	// Callback structure for more complicated data.
	public function offer($msg, $callback, $args = array(), $priority = 10)
	{

		if ( isset($this->offers[$msg]) )
			$count = count($this->offers[$msg]);
		else
			$count = 0;

		$this->offer_order[$msg][ $count ] = $priority;
		$this->offers[$msg][] = array('callback' => $callback,
									 'args' => $args);

		$this->checkListeners($msg, 'ask');
	}

	// Check if there are any offers
	public function hasOffer($msg)
	{
		if (isset($this->offers[$msg]) && $this->offers[$msg] > 0)
		{
			return true;
		}
		else
			return false;
	}

	public function collect($msg, $collect_args = array() )
	{
		$results = array();
		$this->checkListeners($msg, 'tell');

		if (isset($this->offers[$msg]))
		{
			$offers = $this->offers[$msg];

			$offer_order = $this->offer_order[$msg];

			asort($offer_order, SORT_NUMERIC); // sort by priority

			if (count ($offer_order) == count($offers) )
			{
				$new_offers = array();
				foreach($offer_order as $index => $prio)
				{
					$new_offers[] = $offers[$index];
				}
				$offers = $new_offers;
			}
			else
			{
				//MI()->errors()->add( new \Exception('Whistle collect - Offer order and Offers are of different size') );
			}

			foreach($offers as $offer)
			{
				$call = $offer['callback'];
				$args = $offer['args']; // perhaps this is useless, of this should be returned if no callback is given?

				if(is_callable($call) )
					$results[] = call_user_func($call, $args, $collect_args);
				else
				{
					//MI()->errors()->add( new \Exception('Offer ' . $msg . ' has an invalid callback') );
				}

			}
		}

		$this->checkListeners($msg . '_done', 'tell'); // our option to plug after.
		return $results;
	}

	protected function checkListeners($msg, $direction)
	{
		if (isset($this->listeners[$direction][$msg]))
		{
			$response = null;

			foreach($this->listeners[$direction][$msg] as $listener)
			{
				if (isset($this->told[$msg]))
				{
					$response = end($this->told[$msg]);
				}


				if(is_callable($listener) )
				{
					$response = call_user_func( $listener, $response);
				}
				else
				{
					//MI()->errors()->add( new \Exception('Listener is not a callback') );
				}
			}
			return $response; // this will return only the last response, possibly also not great.
		}
	}

	/** Send message to log file
	*
	* 	Function will tell log function to record statement
	* 	@param @msg String The message to log
	*	@param @args Array Extra variables to show in log file
	*/

	public function log($msg, $args)
	{
		$logger = $this->ask('system/logger');
		try {
			$logger->debug($msg . ":" . var_export($args, true) );
		}
		catch (Exception $e)
		{
			$logger->error('Logger ran into trouble: ', $e);
		}
	}

} // class
