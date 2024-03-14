<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_ThankYou_WC_HTML_Block_Oxy
 */

if ( ! class_exists( 'WFFN_ThankYou_WC_HTML_Block_Oxy' ) ) {
	#[AllowDynamicProperties]
	abstract class WFFN_ThankYou_WC_HTML_Block_Oxy extends WFFN_OXY_Field {

		protected $get_parameter = 'oxy_wffn_thankyou_id';
		static $css_build = false;

		protected function html( $settings, $defaults, $content ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			echo 'Please Override in widget class';
		}

		public static function get_input_fields_sizes() {
			return [
				'6px'  => __( 'Small', 'funnel-builder' ),
				'9px'  => __( 'Medium', 'funnel-builder' ),
				'12px' => __( 'Large', 'funnel-builder' ),
				'15px' => __( 'Extra Large', 'funnel-builder' ),
			];
		}

		protected function get_post_type() {
			return WFFN_Core()->thank_you_pages->get_post_type_slug();
		}

		protected function setup_data( $post = null ) {
			if ( isset( $_GET['ct_builder'] ) && isset( $_GET['oxy_wffn_thankyou_id'] ) && ! isset( $_GET['oxygen_iframe'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return false;
			}
			if ( is_null( $post ) ) {
				return false;
			}
			WFFN_Core()->thank_you_pages->set_id( $post->ID );
			WFFN_Core()->thank_you_pages->setup_options();

			return true;
		}

		public function defaultCSS() {

			if ( self::$css_build === true ) {
				return;
			}
			self::$css_build = true;


			return file_get_contents( __DIR__ . '/css/wffn-oxygen.css' );


		}


	}
}