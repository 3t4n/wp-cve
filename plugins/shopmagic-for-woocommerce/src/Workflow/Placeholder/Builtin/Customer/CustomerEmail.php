<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\CustomerBasedPlaceholder;

final class CustomerEmail extends CustomerBasedPlaceholder {
	public function get_slug(): string {
		return 'email';
	}

	public function get_description(): string {
		return esc_html__( 'Display email address of current Customer.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WP_Comment::class ) ) {
			$fallback = $this->resources->get( \WP_Comment::class )->comment_author_email;
		} else {
			$fallback = '';
		}

		if ( $this->resources->has( Customer::class ) ) {
			return $this->resources->get( Customer::class )->get_email() ?: $fallback;
		}

		return $fallback;
	}
}
