<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions;

use SplObserver;
use SplSubject;

class CampaignActions implements SplSubject {

	/**
	 * @var SplObserver[]
	 */
	protected $observers = [];

	/**
	 * @param SplObserver $observer
	 *
	 * @return void
	 */
	public function attach( SplObserver $observer ) {
		$this->observers[ spl_object_hash( $observer ) ] = $observer;
	}

	/**
	 * @param SplObserver $observer .
	 *
	 * @return void
	 */
	public function detach( SplObserver $observer ) {
		unset( $this->observers[ spl_object_hash( $observer ) ] );
	}

	/**
	 * @return void
	 */
	public function notify() {
		foreach ( $this->observers as $observer ) {
			$observer->update( $this );
		}
	}
}
