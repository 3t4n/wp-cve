<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Components\UrlGenerator\UrlGenerator;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Exception\CannotCreateGuestException;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Marketing\Subscribers\CustomerSubscriberService;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;
use WPDesk\ShopMagic\Marketing\Util\EmailHasher;

/**
 * On double opt in action (user's sign up confirmation) handle subscribing customer to
 * selected marketing list.
 */
final class ConfirmedSubscriptionSaver implements Hookable {
	use HookTrait;

	const ACTION = 'double_opt_in';

	/** @var CustomerRepository */
	private $customer_repository;

	/** @var CustomerSubscriberService */
	private $subscriber_service;

	/** @var EmailHasher */
	private $email_hasher;

	/** @var UrlGenerator */
	private $url_generator;

	public function __construct(
		CustomerSubscriberService $subscriber_service,
		CustomerRepository $customer_repository,
		EmailHasher $email_hasher,
		UrlGenerator $url_generator
	) {
		$this->subscriber_service  = $subscriber_service;
		$this->customer_repository = $customer_repository;
		$this->email_hasher        = $email_hasher;
		$this->url_generator       = $url_generator;
	}

	public function hooks(): void {
		$this->add_action( 'admin_post_nopriv_' . self::ACTION, [ $this, 'try_signup_customer' ] );
		$this->add_action( 'admin_post_' . self::ACTION, [ $this, 'try_signup_customer' ] );
	}

	private function try_signup_customer(): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		try {
			$customer = $this->retrieve_customer( isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '' );
		} catch ( CustomerNotFound $e ) {
			wp_safe_redirect(
				$this->url_generator->generate( PreferencesRoute::get_slug(), [ 'success' => 0 ] )
			);
			die;
		}

		$hash = isset( $_GET['hash'] ) ? sanitize_text_field( wp_unslash( $_GET['hash'] ) ) : '';
		if ( ! $this->email_hasher->valid( $customer->get_email(), $hash ) ) {
			wp_safe_redirect(
				$this->url_generator->generate(
					PreferencesRoute::get_slug(),
					[
						'hash'    => $hash,
						'success' => 0,
						'id'      => $customer->get_id(),
					]
				)
			);
			die;
		}

		$target_list = isset( $_GET['list_id'] ) ? absint( wp_unslash( $_GET['list_id'] ) ) : 0;
		$this->subscriber_service->subscribe( $customer->get_email(), $target_list );

		wp_safe_redirect(
			$this->url_generator->generate(
				PreferencesRoute::get_slug(),
				[
					'hash'    => $hash,
					'success' => 1,
					'id'      => $customer->get_id(),
				]
			)
		);
		die;
		// phpcs:enable
	}

	private function retrieve_customer( string $id ): Customer {
		try {
			return $this->customer_repository->find( $id );
		} catch ( CannotCreateGuestException $e ) {
			throw new CustomerNotFound( 'Invalid ID.' );
		}
	}
}
