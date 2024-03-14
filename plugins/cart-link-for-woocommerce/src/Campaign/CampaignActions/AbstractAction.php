<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions;

use IC\Plugin\CartLinkWooCommerce\Campaign\Campaign;
use SplObserver;
use WC_Cart;

/**
 * Abstract action.
 */
abstract class AbstractAction implements SplObserver {

	/**
	 * @var WC_Cart
	 */
	protected $cart;

	/**
	 * @var Campaign
	 */
	protected $campaign;

	/**
	 * @param WC_Cart  $cart     .
	 * @param Campaign $campaign .
	 */
	public function __construct( WC_Cart $cart, Campaign $campaign ) {
		$this->cart     = $cart;
		$this->campaign = $campaign;
	}
}
