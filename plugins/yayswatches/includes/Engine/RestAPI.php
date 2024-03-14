<?php
namespace Yay_Swatches\Engine;

use Yay_Swatches\Utils\SingletonTrait;

use Yay_Swatches\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Bookster Rest API
 */
class RestAPI {

	use SingletonTrait;

	private $default_swatch_customize_settings;
	private $default_button_customize_settings;
	private $default_sold_out_customize_settings;

	protected function __construct() {

		$this->default_swatch_customize_settings   = Helper::get_default_swatch_customize_settings();
		$this->default_button_customize_settings   = Helper::get_default_button_customize_settings();
		$this->default_sold_out_customize_settings = Helper::get_default_sold_out_settings();

		add_action( 'rest_api_init', array( $this, 'add_yayswatches_endpoint' ) );
	}

	/**
	 * Add Bookster Endpoints
	 */
	public function add_yayswatches_endpoint() {
		register_rest_route(
			'yayswatches/v1',
			'/settings',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'exec_patch_settings' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	public function exec_patch_settings( $request ) {
		$params                      = $request->get_params();
		$attributes_data             = $params['attributesData'];
		$swatch_customize_settings   = $params['swatchCustomizeSettings'];
		$button_customize_settings   = $params['buttonCustomizeSettings'];
		$sold_out_customize_settings = $params['soldOutCustomizeSettings'];

		$merged_swatch_customize_settings   = wp_parse_args( $swatch_customize_settings, $this->default_swatch_customize_settings );
		$merged_button_customize_settings   = wp_parse_args( $button_customize_settings, $this->default_button_customize_settings );
		$merged_sold_out_customize_settings = wp_parse_args( $sold_out_customize_settings, $this->default_sold_out_customize_settings );

		update_option( 'yay-swatches-swatch-customize-settings', $merged_swatch_customize_settings );
		update_option( 'yay-swatches-button-customize-settings', $merged_button_customize_settings );
		update_option( 'yay-swatches-sold-out-customize-settings', $merged_sold_out_customize_settings );

		foreach ( $attributes_data as $attribute ) {
			update_option( 'yay-swatches-attribute-style-' . $attribute['ID'], $attribute['style'] );
			if ( isset( $attribute['terms'] ) ) {
				foreach ( $attribute['terms'] as $term ) {
					if ( isset( $term['swatchColor'] ) ) {
						update_option( 'yay-swatches-swatch-color-' . $term['term_id'], $term['swatchColor'] );
					}
					if ( isset( $term['showHideDual'] ) ) {
						update_option( 'yay-swatches-show-hide-color-' . $term['term_id'], $term['showHideDual'] );
					}

					if ( isset( $term['swatchDualColor'] ) ) {
						update_option( 'yay-swatches-swatch-dual-color-' . $term['term_id'], $term['swatchDualColor'] );
					}

					if ( isset( $term['swatchImage'] ) ) {
						update_option( 'yay-swatches-swatch-image-' . $term['term_id'], $term['swatchImage'] );
					}
				}
			}
		}

		wp_send_json( true );
	}
}
