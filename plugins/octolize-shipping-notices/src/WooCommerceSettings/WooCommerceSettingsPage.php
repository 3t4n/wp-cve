<?php
/**
 * Class WooCommerceSettingsPage
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings;

use Octolize\Shipping\Notices\CustomPostType;
use Octolize\Shipping\Notices\SettingsFields;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class WooCommerceSettingsPage implements Hookable {
	public const SECTION_ID  = 'shipping-notices-settings';
	public const OPTION_NAME = 'woocommerce_shipping_notices';

	/**
	 * @var SettingsFields
	 */
	private $archive_settings_fields;

	/**
	 * @var SingleSectionSettingsFields
	 */
	private $single_settings_fields;

	/**
	 * @param SettingsFields              $archive_settings_fields .
	 * @param SingleSectionSettingsFields $single_settings_fields  .
	 */
	public function __construct(
		SettingsFields $archive_settings_fields,
		SettingsFields $single_settings_fields
	) {
		$this->archive_settings_fields = $archive_settings_fields;
		$this->single_settings_fields  = $single_settings_fields;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_filter( 'woocommerce_get_sections_shipping', [ $this, 'add_section_to_array' ] );
		add_filter( 'woocommerce_get_settings_shipping', [ $this, 'get_section_settings_fields' ], 10, 2 );
	}

	/**
	 * @param array<string, string> $sections .
	 *
	 * @return array<string, string>
	 */
	public function add_section_to_array( $sections ): array {
		$sections[ self::SECTION_ID ] = __( 'Shipping Notices', 'octolize-shipping-notices' );

		return $sections;
	}

	/**
	 * @param array<string, array<string, array<string, float|string>|float|string>> $settings        .
	 * @param string                                                                 $current_section .
	 *
	 * @return array<string, array<string, array<string, float|string>|float|string>>
	 */
	public function get_section_settings_fields( $settings, string $current_section ): array {
		if ( self::SECTION_ID !== $current_section ) {
			return $settings;
		}

		$notice_id = $this->get_notice_id();

		if ( $notice_id ) {
			$fields = $this->single_settings_fields->get_settings_fields();
		} else {
			$fields = $this->archive_settings_fields->get_settings_fields();
		}

		array_walk(
			$fields,
			function ( array &$field ) use ( $notice_id ) {
				$field['id'] = $this->prepare_settings_field_id( $field['id'], $notice_id );
			}
		);

		return $fields;
	}

	/**
	 * @return int
	 */
	private function get_notice_id(): int {
		return (int) sanitize_text_field( wp_unslash( $_GET[ SettingsActionLinks::NOTICE_ID ] ?? '0' ) );
	}

	/**
	 * @param string $field_name  .
	 * @param int    $name_suffix .
	 *
	 * @return string
	 */
	private function prepare_settings_field_id( string $field_name, int $name_suffix = 0 ): string {
		return sprintf( '%1$s%2$s[%3$s]', self::OPTION_NAME, empty( $name_suffix ) ? '' : '_' . $name_suffix, $field_name );
	}
}
