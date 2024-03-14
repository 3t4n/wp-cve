<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Yoast SEO by Team Yoast
 * Plugin Path : https://yoa.st/1uj
 */
#[AllowDynamicProperties] 

  class WFACP_Checkout_Seo_WP {
	public function __construct() {
		$this->seo_news();
		/* checkout page */
		add_action( 'save_post', [ $this, 'save_post' ] );
		add_action( 'wfacp_after_template_found', [ $this, 'remove_actions' ] );
	}

	public function save_post() {
		if ( class_exists( 'Yoast\WP\SEO\Integrations\Watchers\Indexable_Post_Watcher' ) && isset( $_POST['action'] ) && 'wfacp_import_template' === $_POST['action'] ) {
			WFACP_Common::remove_actions( 'wp_insert_post', 'Yoast\WP\SEO\Integrations\Watchers\Indexable_Post_Watcher', 'build_indexable' );
		}
	}

	/**
	 * Remove Call Wpseo Head only at global checkout page. because of this function reset the our Global Post to Native Global Post
	 * @return void
	 */
	public function remove_actions() {
		if ( WFACP_Core()->public->is_checkout_override() ) {
			WFACP_Common::remove_actions( 'wp_head', 'Yoast\WP\SEO\Integrations\Front_End_Integration', 'call_wpseo_head' );
			add_action( 'wp_head', function () {
				\do_action( 'wpseo_head' );
			}, 15 );

		}
		if ( class_exists( 'Yoast_WooCommerce_SEO' ) ) {
			WFACP_Common::remove_actions( 'wp', 'Yoast_WooCommerce_SEO', 'get_product_global_identifiers' );
		}
	}

	public function seo_news() {
		if ( WFACP_Common::is_theme_builder() && function_exists( '__wpseo_news_main' ) ) {
			remove_action( 'plugins_loaded', '__wpseo_news_main' );
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Checkout_Seo_WP(), 'wfacp-seo-wp' );
