<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions\AddProductsAction;
use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions\CampaignActions;
use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions\ClearCartAction;
use Exception;
use WP_Query;

/**
 * Trigger Action in cart.
 */
class TriggerAction {

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'template_redirect', [ $this, 'trigger' ], 5 );
	}

	public function trigger() {
		global $wp;

		if ( ! is_404() ) {
			return;
		}

		$campaign = get_page_by_path( $wp->request, OBJECT, RegisterPostType::POST_TYPE );

		if ( ! $campaign ) {
			return;
		}

		nocache_headers();

		$campaign = new Campaign( $campaign->ID );

		if ( ! $campaign->is_active() ) {
			return;
		}

		$cart = WC()->cart;

		$campaign_actions = new CampaignActions();
		$campaign_actions->attach( new ClearCartAction( $cart, $campaign ) );
		$campaign_actions->attach( new AddProductsAction( $cart, $campaign ) );

		try {
			$campaign_actions->notify();
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
		}

		wp_safe_redirect( add_query_arg( $_GET, $campaign->get_redirect_url() ) );
		die();
	}

	/**
	 * @return WP_Query
	 */
	protected function get_query(): WP_Query {
		global $wp_query;

		return $wp_query;
	}
}


