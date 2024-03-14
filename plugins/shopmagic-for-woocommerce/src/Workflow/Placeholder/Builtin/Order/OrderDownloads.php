<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;


final class OrderDownloads extends WooCommerceOrderBasedPlaceholder {
	/**
	 * @var string
	 */
	private const PLAINTEXT = 'plaintext';

	public function get_slug(): string {
		return 'downloads';
	}

	public function get_description(): string {
		return esc_html__(
			'Display links to download files associated with current order.',
			'shopmagic-for-woocommerce'
		);
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return [
			( new SelectField() )
				->set_label( __( 'Is email in plain text', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PLAINTEXT )
				->set_options(
					[
						'no'  => __( 'No', 'shopmagic-for-woocommerce' ),
						'yes' => __( 'Yes', 'shopmagic-for-woocommerce' ),
					]
				),
		];
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( \WC_Order::class ) ) {
			return '';
		}

		ob_start();

		\WC_Emails::instance()->order_downloads(
			$this->resources->get( \WC_Order::class ),
			false,
			isset( $parameters[ self::PLAINTEXT ] ) && $parameters[ self::PLAINTEXT ] === 'yes'
		);

		return ob_get_clean();
	}
}
