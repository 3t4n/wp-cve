<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Porto {

	public function __construct() {
		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'dequeue_actions' ] );

		add_action( 'init', [ $this, 'remove_customizer_fields' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function dequeue_actions() {
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_style' ], 99999 );
		// for version 4.9.5 and above

		add_filter( 'wfacp_css_js_removal_paths', function ( $paths, $template ) {


			if ( 'pre_built' == $template->get_template_type() ) {
				$paths[] = 'porto_styles';
			}

			return $paths;
		}, 10, 2 );
	}

	public function dequeue_style() {

		$instance = wfacp_template();

		if ( ! function_exists( 'porto_setup' ) || $instance === null ) {
			return;
		}

		$instancType = $instance->get_template_type();

		if ( $instancType === 'pre_built' ) {
			wp_dequeue_style( 'porto-bootstrap' );
			wp_dequeue_style( 'porto-dynamic-style' );
			wp_dequeue_style( 'porto-shortcodes' );
		}

	}

	public function remove_customizer_fields() {
		global $reduxPortoSettings;

		if ( ! function_exists( 'porto_setup' ) ) {
			return;
		}

		if ( ! WFACP_Common::is_customizer() ) {
			return;
		}
		// for version 4.9.5 and above
		remove_action( 'customize_controls_print_styles', 'porto_customizer_enqueue_stylesheets' );
		remove_action( 'customize_preview_init', 'porto_customizer_live_scripts' );
		if ( class_exists( 'Redux_Framework_porto_settings' ) && ( $reduxPortoSettings instanceof Redux_Framework_porto_settings ) ) {

			if ( ! $reduxPortoSettings->ReduxFramework instanceof ReduxFramework ) {

				return;
			}

			if ( ! isset( $reduxPortoSettings->ReduxFramework->extensions['customizer'] ) || ! $reduxPortoSettings->ReduxFramework->extensions['customizer'] instanceof ReduxFramework_extension_customizer ) {
				return;
			}

			$instance = $reduxPortoSettings->ReduxFramework->extensions['customizer'];
			remove_action( 'customize_register', [ $instance, '_register_customizer_controls' ] );

		}
	}

	public function internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
		}
		$px = $instance->get_template_type_px() . "px";

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_affiliate_referrals_wc {padding-left:$px;padding-right:$px;}";
		$cssHtml .= $bodyClass . '#shipping_calculator_field .border {border: none !important;}';
		$cssHtml .= $bodyClass . '#product_switching_field.shop_table {border: none;}';
		$cssHtml .= $bodyClass . '#wfacp_qr_model_wrap .single_variation_wrap label.description_label_head {font-weight: bold;}';
		$cssHtml .= $bodyClass . '#wfacp_qr_model_wrap .single_variation_wrap {padding-top: 0;margin: 10px 0 0;}';
		$cssHtml .= $bodyClass . '#wfacp_checkout_form .wfacp-form-control-wrapper .woocommerce-input-wrapper [class*=flag-] {width: auto; height: auto; background: none;}';
		$cssHtml .= $bodyClass . '#wfacp_checkout_form .wfacp-form-control-wrapper[class*=flag-] {height: auto; background: none;}';
		$cssHtml .= $bodyClass . '#wfacp_checkout_form .porto-radio .porto-control-label:after {display: none;}';
		$cssHtml .= $bodyClass . '#wfacp_checkout_form .porto-radio {padding-left: 0;}';
		$cssHtml .= "</style>";
		echo $cssHtml;


	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Porto(), 'porto' );
