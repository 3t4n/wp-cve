<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Customer\Guest\GuestInOrderContextTrait;

/**
 * Catches guests from newly created orders and puts them into our guest repository.
 */
final class GuestOrderIntegration implements HookProvider {
	use GuestInOrderContextTrait;

	public const PRIORITY_BEFORE_DEFAULT = - 100;

	/** @var GuestInterceptor */
	private $interceptor;

	public function __construct( GuestInterceptor $interceptor ) {
		$this->interceptor = $interceptor;
	}

	public function hooks(): void {
		add_action(
			'woocommerce_new_order',
			[ $this, 'catch_guest' ],
			self::PRIORITY_BEFORE_DEFAULT
		);
		add_action(
			'woocommerce_api_create_order',
			[ $this, 'catch_guest' ],
			self::PRIORITY_BEFORE_DEFAULT
		);
	}

	public function catch_guest( int $order_id ): void {
		$order = wc_get_order( $order_id );

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		try {
			$guest = $this->interceptor->intercept( $order );
			$this->touch_order( $order, $guest->get_raw_id() );
		} catch ( \Exception $e ) {
		}
	}
}
