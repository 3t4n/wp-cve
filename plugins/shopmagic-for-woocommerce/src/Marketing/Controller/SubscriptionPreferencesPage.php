<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Controller;

use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Marketing\Subscribers\CommunicationPreferencesRenderer;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;
use WPDesk\ShopMagic\Marketing\Util\EmailHasher;
use WPDesk\ShopMagic\Marketing\Util\ShouldUseWooCommercePreferencesPage;

class SubscriptionPreferencesPage {

	/** @var CustomerRepository */
	private $customer_repository;

	/** @var CommunicationPreferencesRenderer */
	private $renderer;

	/** @var ShouldUseWooCommercePreferencesPage */
	private $display_strategy;

	/** @var EmailHasher */
	private $email_hasher;

	public function __construct(
		CustomerRepository $repository,
		CommunicationPreferencesRenderer $renderer,
		ShouldUseWooCommercePreferencesPage $display_strategy,
		EmailHasher $email_hasher
	) {
		$this->customer_repository = $repository;
		$this->renderer            = $renderer;
		$this->display_strategy    = $display_strategy;
		$this->email_hasher        = $email_hasher;
	}

	public function display_preferences(
		string $hash = '',
		?bool $success = null,
		?string $id = null
	): \WP_HTTP_Response {
		if ( is_user_logged_in() && $this->display_strategy->should_use() ) {
			return new \WP_HTTP_Response(
				null,
				\WP_Http::FOUND,
				[ 'location' => wc_get_account_endpoint_url( PreferencesRoute::get_slug() ) ]
			);
		}

		if ( is_user_logged_in() ) {
			$customer_id = get_current_user_id();
		} else {
			$customer_id = sanitize_text_field( wp_unslash( $id ) );
		}

		try {
			$customer = $this->customer_repository->find( $customer_id );
		} catch ( CustomerNotFound $e ) {
			return $this->serve_error_response();
		}

		$hash = sanitize_text_field( wp_unslash( $hash ) );

		if ( ! $this->email_hasher->valid( $customer->get_email(), $hash ) ) {
			return $this->serve_error_response();
		}

		$html = $this->renderer->render_wrap_start();
		$html .= $this->renderer->render( $customer, [ 'success' => $success ] );
		$html .= $this->renderer->render_wrap_end();

		return new \WP_HTTP_Response( $html );
	}

	public function serve_error_response(): \WP_HTTP_Response {
		$html = $this->renderer->render_wrap_start();
		$html .= esc_html__( 'Sorry, but something is wrong with your request.', 'shopmagic-for-woocommerce' );
		$html .= $this->renderer->render_wrap_end();

		return new \WP_HTTP_Response( $html, \WP_Http::FORBIDDEN );
	}


}
