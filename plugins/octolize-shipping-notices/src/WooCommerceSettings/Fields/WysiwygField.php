<?php
/**
 * Class WysiwygField
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Fields;

use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Adding new field.
 */
class WysiwygField implements Hookable {
	public const FIELD_TYPE = 'wysiwyg';

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
		require __DIR__ . '/views/html-wysiwyg-field.php';
	}
}
