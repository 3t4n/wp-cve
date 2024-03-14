<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Product;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceProductBasedPlaceholder;
use WPDesk\ShopMagic\Workflow\Placeholder\Helper\PlaceholderUTMBuilder;

final class ProductLink extends WooCommerceProductBasedPlaceholder {

	/** @var PlaceholderUTMBuilder */
	private $utm_builder;

	public function __construct( PlaceholderUTMBuilder $utm_builder ) {
		$this->utm_builder = $utm_builder;
	}

	public function get_slug(): string {
		return 'link';
	}

	public function get_description(): string {
		return esc_html__( 'Display link to current product.', 'shopmagic-for-woocommerce' ) . '<br>' .
				$this->utm_builder->get_description();
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return $this->utm_builder->get_utm_fields();
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Product::class ) ) {
			return $this->utm_builder->append_utm_parameters_to_uri(
				$parameters,
				$this->resources->get( \WC_Product::class )->get_permalink()
			);
		}

		return '';
	}
}
