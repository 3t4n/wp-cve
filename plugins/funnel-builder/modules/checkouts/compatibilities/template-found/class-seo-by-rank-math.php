<?php

/**
 * Plugin Name:       Rank Math SEO
 * Version:           1.0.56.1
 * Plugin URI:        https://s.rankmath.com/home
 * Author:            Rank Math
 */


#[AllowDynamicProperties] 

  class WFACP_Seo_By_Rank_Math {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'add_action' ] );
	}

	public function add_action() {
		WFACP_Common::remove_actions( 'rank_math/frontend/robots', 'RankMath\WooCommerce\WooCommerce', 'robots' );
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Seo_By_Rank_Math(), 'wfacp-seo-by-rank-math' );
