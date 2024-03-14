<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;
use WPDesk\ShopMagic\Workflow\Placeholder\Helper\PlaceholderUTMBuilder;

final class OrderPaymentUrl extends WooCommerceOrderBasedPlaceholder {

	/** @var PlaceholderUTMBuilder */
	private $utm_builder;

	public function __construct( PlaceholderUTMBuilder $utm_builder ) {
		$this->utm_builder = $utm_builder;
	}

	public function get_description(): string {
		return esc_html__( 'Display payment link for current order.', 'shopmagic-for-woocommerce' ) . '<br>' .
		       $this->utm_builder->get_description();
	}

	public function get_slug(): string {
		return 'payment_url';
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return $this->utm_builder->get_utm_fields();
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			$checkout_payment_url = $this->resources->get( \WC_Order::class )->get_checkout_payment_url();

			return $this->utm_builder->append_utm_parameters_to_uri( $parameters, $checkout_payment_url );
		}

		return '';
	}
}
