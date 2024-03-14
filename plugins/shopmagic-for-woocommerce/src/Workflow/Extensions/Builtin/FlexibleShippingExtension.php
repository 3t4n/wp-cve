<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Extensions\Builtin;

use WPDesk\ShopMagic\Integration\FlexibleShipping\Placeholder\OrderShipmentTrackingLinks;
use WPDesk\ShopMagic\Workflow\Extensions\AbstractExtension;
use WPDesk\ShopMagic\Workflow\Placeholder\TemplateRendererForPlaceholders;

final class FlexibleShippingExtension extends AbstractExtension {

	/** @var TemplateRendererForPlaceholders */
	private $renderer;

	public function __construct( TemplateRendererForPlaceholders $renderer ) {
		$this->renderer = $renderer;
	}

	/**
	 * @return mixed[]
	 */
	public function get_placeholders(): array {
		if ( ! \function_exists( 'fs_get_order_shipments' ) ) {
			return [];
		}

		return [
			new OrderShipmentTrackingLinks( $this->renderer ),
		];
	}
}
