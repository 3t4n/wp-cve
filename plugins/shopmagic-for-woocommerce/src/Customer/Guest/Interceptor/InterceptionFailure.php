<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

class InterceptionFailure extends \RuntimeException implements \WPDesk\ShopMagic\Exception\ShopMagicException {

	public static function missing_order(): self {
		return new self(
			esc_html__( 'Guest interceptor needs a valid order to extract data.', 'shopmagic-for-woocommerce' )
		);
	}

	public static function missing_email(): self {
		return new self(
			esc_html__(
				'Interceptor needs to receive at least an email for guest to intercept.',
				'shopmagic-for-woocommerce'
			)
		);
	}

	public static function intercepting_user(): self {
		return new self(
			esc_html__( 'Trying to intercept a registered user.', 'shopmagic-for-woocommerce' )
		);
	}

	public static function saving_failure( object $provider ): self {
		return new self(
			sprintf(
				esc_html__( 'Failed to save intercepted guest from %s provider', 'shopmagic-for-woocommerce' ),
				( new \ReflectionObject( $provider ) )->getShortName()
			)
		);
	}

}
