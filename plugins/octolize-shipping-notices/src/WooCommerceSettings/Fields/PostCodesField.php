<?php
/**
 * Class PostCodesField
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Fields;

use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Adding new field.
 */
class PostCodesField implements Hookable {
	public const FIELD_TYPE = 'post_codes';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_admin_field_' . self::FIELD_TYPE, [ $this, 'render_field' ] );
		add_filter( 'woocommerce_admin_settings_sanitize_option', [ $this, 'sanitize_value' ], 10, 3 );
	}

	/**
	 * @param mixed    $value     .
	 * @param string[] $option    .
	 * @param mixed    $raw_value .
	 *
	 * @return mixed
	 */
	public function sanitize_value( $value, array $option, $raw_value ) {
		if ( $option['type'] === self::FIELD_TYPE ) {
			// @phpstan-ignore-next-line
			return wp_kses_post( trim( $raw_value ) );
		}

		return $value;
	}

	/**
	 * @param mixed $value .
	 *
	 * @return void
	 */
	public function render_field( $value ): void {
		// Custom attribute handling.
		$custom_attributes = [];

		// @phpstan-ignore-next-line
		if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
			// @phpstan-ignore-next-line
			foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
				// @phpstan-ignore-next-line
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		require __DIR__ . '/views/html-post-codes-field.php';
	}
}
