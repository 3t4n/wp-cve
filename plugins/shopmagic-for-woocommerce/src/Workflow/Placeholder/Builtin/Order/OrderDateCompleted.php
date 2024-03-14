<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;
use WPDesk\ShopMagic\Workflow\Placeholder\Helper\DateFormatHelper;

final class OrderDateCompleted extends WooCommerceOrderBasedPlaceholder {

	/** @var DateFormatHelper */
	private $date_format_helper;

	public function __construct( DateFormatHelper $date_format_helper ) {
		$this->date_format_helper = $date_format_helper;
	}

	public function get_description(): string {
		return esc_html__( 'Display the date of order stasus changed to completed.', 'shopmagic-for-woocommerce' );
	}

	public function get_slug(): string {
		return 'date_completed';
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return $this->date_format_helper->get_supported_parameters();
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->date_format_helper->format_date(
				$this->resources->get( \WC_Order::class )->get_date_completed(), $parameters
			);
		}

		return '';
	}
}
