<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Components\Mailer\MailerException;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\Guest\GuestFactory;
use WPDesk\ShopMagic\Customer\Guest\GuestManager;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ConfirmationDispatcher;
use WPDesk\ShopMagic\Marketing\Subscribers\CustomerSubscriberService;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\SubscriptionFormShortcode;

/**
 * Handle saving subscriber data incoming from subscription form shortcode.
 */
final class FrontendListSubscription implements HookProvider {
	use HookTrait;

	/** @var CustomerRepository */
	private $customers_repository;

	/** @var AudienceListRepository */
	private $newsletter_repository;

	/** @var GuestManager */
	private $guest_manager;

	/** @var GuestFactory */
	private $guest_factory;

	/** @var CustomerSubscriberService */
	private $subscriber_service;

	/** @var SubscriberObjectRepository */
	private $subscriber_repository;

	/** @var ConfirmationDispatcher */
	private $confirmation_dispatcher;

	public function __construct(
		CustomerSubscriberService $subscriber_service,
		SubscriberObjectRepository $subscriber_repository,
		CustomerRepository $customers_repository,
		GuestFactory $guest_factory,
		GuestManager $guest_manager,
		AudienceListRepository $newsletter_repository,
		ConfirmationDispatcher $confirmation_dispatcher
	) {
		$this->subscriber_service      = $subscriber_service;
		$this->subscriber_repository   = $subscriber_repository;
		$this->customers_repository    = $customers_repository;
		$this->guest_factory           = $guest_factory;
		$this->guest_manager           = $guest_manager;
		$this->newsletter_repository   = $newsletter_repository;
		$this->confirmation_dispatcher = $confirmation_dispatcher;
	}

	public function hooks(): void {
		$this->add_action(
			'wp_ajax_' . SubscriptionFormShortcode::ACTION,
			[ $this, 'try_signup_customer' ]
		);
		$this->add_action(
			'wp_ajax_nopriv_' . SubscriptionFormShortcode::ACTION,
			[ $this, 'try_signup_customer' ]
		);
		$this->add_action(
			'admin_post_nopriv_' . SubscriptionFormShortcode::ACTION,
			[ $this, 'try_signup_from_post' ]
		);
		$this->add_action(
			'admin_post_' . SubscriptionFormShortcode::ACTION,
			[ $this, 'try_signup_from_post' ]
		);
	}

	private function try_signup_from_post(): void {
		check_admin_referer( SubscriptionFormShortcode::ACTION );

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$list_id = isset( $_POST['list_id'] ) ? absint( wp_unslash( $_POST['list_id'] ) ) : 0;

		if ( empty( $email ) ) {
			wp_safe_redirect(
				add_query_arg( [ 'error' => '1' ], wp_get_referer() )
			);
			//wp_send_json_error( esc_html__( 'Please, enter a valid email address!', 'shopmagic-for-woocommerce' ) );
		}

		if ( $this->subscriber_repository->is_subscribed_to_list( $email, $list_id ) ) {
			wp_safe_redirect(
				add_query_arg( [ 'error' => '1' ], wp_get_referer() )
			);
			//wp_send_json_error( esc_html__( 'You are already subscribed.', 'shopmagic-for-woocommerce' ) );
		}

		$customer = $this->retrieve_customer( $email, $name );

		if ( isset( $_POST['double_optin'] ) ) {
			$list = $this->newsletter_repository->find( $list_id );

			try {
				$this->confirmation_dispatcher->dispatch_confirmation_email( $customer, $list );
				wp_send_json_success( esc_html__( 'Check your messages box to confirm your sign up.', 'shopmagic-for-woocommerce' ) );
			} catch ( MailerException $e ) {
				wp_send_json_error( esc_html__( 'An error occurred, while sending confirmation message. Ensure, you have entered correct email address.', 'shopmagic-for-woocommerce' ) );
			} finally {
				die;
			}
		}

		$result = $this->subscriber_service->subscribe( $customer->get_email(), $list_id );

		if ( $result === false ) {
			wp_safe_redirect(
				add_query_arg( [ 'error' => '1' ], wp_get_referer() )
			);
			//wp_send_json_error( esc_html__( 'An error occurred during sign up.', 'shopmagic-for-woocommerce' ) );
		}

		wp_safe_redirect(
			add_query_arg( [ 'error' => '0' ], wp_get_referer() )
		);
		//wp_send_json_success( esc_html__( 'You have been successfully subscribed!', 'shopmagic-for-woocommerce' ) );
	}

	private function try_signup_customer(): void {
		check_ajax_referer( SubscriptionFormShortcode::ACTION );

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$list_id = isset( $_POST['list_id'] ) ? absint( wp_unslash( $_POST['list_id'] ) ) : 0;

		if ( empty( $email ) ) {
			wp_send_json_error( esc_html__( 'Please, enter a valid email address!', 'shopmagic-for-woocommerce' ) );
		}

		if ( $this->subscriber_repository->is_subscribed_to_list( $email, $list_id ) ) {
			wp_send_json_error( esc_html__( 'You are already subscribed.', 'shopmagic-for-woocommerce' ) );
		}

		$customer = $this->retrieve_customer( $email, $name );
		$list     = $this->newsletter_repository->find( $list_id );

		if ( isset( $_POST['double_optin'] ) ) {
			try {
				$this->confirmation_dispatcher->dispatch_confirmation_email( $customer, $list );
				wp_send_json_success( esc_html__( 'Check your messages box to confirm your sign up.', 'shopmagic-for-woocommerce' ) );
			} catch ( MailerException $e ) {
				wp_send_json_error( esc_html__( 'An error occurred, while sending confirmation message. Ensure, you have entered correct email address.', 'shopmagic-for-woocommerce' ) );
			} finally {
				die;
			}
		}

		$result = $this->subscriber_service->subscribe( $customer->get_email(), $list->get_id() );

		if ( $result === false ) {
			wp_send_json_error( esc_html__( 'An error occurred during sign up.', 'shopmagic-for-woocommerce' ) );
		}

		wp_send_json_success( esc_html__( 'You have been successfully subscribed!', 'shopmagic-for-woocommerce' ) );
	}

	private function retrieve_customer( string $email, string $name ): Customer {
		try {
			return $this->customers_repository->find_by_email( $email );
		} catch ( CustomerNotFound $e ) {
			$guest = $this->guest_factory->from_email( $email );
			$guest->add_meta( 'first_name', $name );

			$this->guest_manager->save( $guest );

			return $guest;
		}
	}

}
