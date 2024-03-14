<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\FlexibleShipping\Placeholder;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;
use WPDesk\ShopMagic\Workflow\Placeholder\TemplateRendererForPlaceholders;

final class OrderShipmentTrackingLinks extends WooCommerceOrderBasedPlaceholder {

	/** @var TemplateRendererForPlaceholders */
	private $renderer;

	public function __construct( TemplateRendererForPlaceholders $renderer ) {
		$this->renderer = $renderer;
	}

	public function get_slug(): string {
		return 'shipment_tracking_links';
	}

	/**
	 * @return mixed[]
	 */
	private function get_tracking_urls( \WC_Order $order ): array {
		$urls      = [];
		$shipments = fs_get_order_shipments( $order->get_id() );
		foreach ( $shipments as $shipment ) {
			if ( method_exists( $shipment, 'get_tracking_url' ) ) {
				$url = $shipment->get_tracking_url();
				if ( ! empty( $url ) ) {
					$urls[] = $url;
				}
			}
		}

		return $urls;
	}

	public function value( array $parameters ): string {
		if ( ! \function_exists( 'fs_get_order_shipments' ) ) {
			return '';
		}

		return $this->renderer->render(
			'placeholder/shipment_tracking_url/value',
			[
				'urls' => $this->get_tracking_urls( $this->resources->get( \WC_Order::class ) ),
			]
		);
	}

	public function get_description(): string {
		return '';
	}
}
