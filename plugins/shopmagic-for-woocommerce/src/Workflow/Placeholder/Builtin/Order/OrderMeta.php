<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\FormField\Field\InputTextField;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;


final class OrderMeta extends WooCommerceOrderBasedPlaceholder {
	/**
	 * @var string
	 */
	public const PARAM_KEY_NAME = 'key';

	public function get_slug(): string {
		return 'meta';
	}

	public function get_description(): string {
		return esc_html__(
			       'Display any meta value associated with current order.',
			       'shopmagic-for-woocommerce'
		       ) . ' ' .
		       esc_html__( 'You can find more about using this placeholder in ' ) .
		       '<a target="_blank" href="https://docs.shopmagic.app/article/1163-meta-placeholders">' . esc_html__(
			       'documentation',
			       'shopmagic-for-woocommerce'
		       ) . '</a>.';
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return [
			( new InputTextField() )
				->set_required()
				->set_name( self::PARAM_KEY_NAME )
				->set_label( __( 'The meta key to retrieve', 'shopmagic-for-woocommerce' ) ),
		];
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( \WC_Order::class ) ) {
			return '';
		}

		$key = $parameters[ self::PARAM_KEY_NAME ];
		if ( $key === '' ) {
			return '';
		}
		if ( $key === '0' ) {
			return '';
		}

		$value = $this->resources->get( \WC_Order::class )->get_meta( $key, true );

		if ( empty( $value ) ) {
			$value = get_post_meta( $this->resources->get( \WC_Order::class )->get_id(), $key, true );
		}

		return (string) $value;
	}
}
