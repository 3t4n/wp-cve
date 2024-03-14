<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;
use WPDesk\ShopMagic\Workflow\Placeholder\Helper\PlaceholderUTMBuilder;
use WPDesk\ShopMagic\Workflow\Placeholder\TemplateRendererForPlaceholders;


final class OrderCrossSells extends WooCommerceOrderBasedPlaceholder {

	/** @var TemplateRendererForPlaceholders */
	private $renderer;

	/** @var PlaceholderUTMBuilder */
	private $utm_builder;

	public function __construct( TemplateRendererForPlaceholders $renderer, PlaceholderUTMBuilder $utm_builder ) {
		$this->renderer    = $renderer;
		$this->utm_builder = $utm_builder;
	}

	public function get_description(): string {
		return esc_html__( "Display cross sell products associated with of current order's products.", 'shopmagic-for-woocommerce' ) . '\n' .
				$this->utm_builder->get_description();
	}

	public function get_slug(): string {
		return 'cross_sells';
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return array_merge( $this->utm_builder->get_utm_fields(), $this->renderer->get_template_selector_field() );
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( \WC_Order::class ) ) {
			return '';
		}

		$order_items              = $this->resources->get( \WC_Order::class )->get_items();
		$cross_sell_products_id   = $this->get_cross_sell_products_id( $order_items );
		$cross_sell_product_names = [];
		$cross_sell_products      = [];

		foreach ( $cross_sell_products_id as $id ) {
			$product = wc_get_product( $id );
			if ( $product instanceof \WC_Product ) {
				$cross_sell_products[]      = $product;
				$cross_sell_product_names[] = $product->get_name();
			}
		}

		return $this->renderer->render(
			'placeholder/products_ordered/' . $parameters['template'] ?? 'comma_separated_list',
			[
				'order_items'   => $order_items,
				'products'      => $cross_sell_products,
				'product_names' => $cross_sell_product_names,
				'parameters'    => $parameters,
				'utm_builder'   => $this->utm_builder,
			]
		);
	}

	/**
	 * @param \WC_Order_Item[] $order_items
	 *
	 * @return int[]
	 */
	private function get_cross_sell_products_id( array $order_items ): array {
		$cross_sell_products_id = [];

		foreach ( $order_items as $order_item ) {
			if ( $order_item instanceof \WC_Order_Item_Product ) {
				$product = $order_item->get_product();
				if ( $product instanceof \WC_Product ) {
					$cross_sell_ids = $product->get_cross_sell_ids();
					if ( ! empty( $cross_sell_ids ) ) {
						array_push( $cross_sell_products_id, ...$cross_sell_ids );
					}
				}
			}
		}

		return array_unique( $cross_sell_products_id );
	}
}
