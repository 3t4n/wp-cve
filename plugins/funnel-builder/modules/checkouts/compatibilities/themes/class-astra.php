<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Astra {

	public function __construct() {
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_actions' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_astra_addon' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
		/*--------------------Remove Action of Astra Addon-------------------- */
		add_action( 'wp', [ $this, 'actions' ], 8 );
		add_action( 'wp_footer', [ $this, 'remove_script' ], 9999 );

	}

	public function actions() {

		if ( ! class_exists( 'ASTRA_Ext_WooCommerce_Markup' ) || ! class_exists( 'WFACP_Common' ) ) {
			return;
		}
		$id = WFACP_Common::get_id();
		if ( absint( $id ) <= 0 ) {
			return;
		}

		WFACP_Common::remove_actions( 'wp', 'ASTRA_Ext_WooCommerce_Markup', 'modern_checkout' );
		WFACP_Common::remove_actions( 'wp', 'ASTRA_Ext_WooCommerce_Markup', 'multistep_checkout' );
		WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'ASTRA_Ext_WooCommerce_Markup', 'checkout_collapsible_order_review' );


	}

	public function remove_actions() {
		remove_action( 'woocommerce_checkout_before_customer_details', 'astra_two_step_checkout_form_wrapper_div', 1 );
		remove_action( 'woocommerce_checkout_before_customer_details', 'astra_two_step_checkout_form_ul_wrapper', 2 );
		remove_action( 'woocommerce_checkout_order_review', 'astra_woocommerce_div_wrapper_close', 30 );
		remove_action( 'woocommerce_checkout_order_review', 'astra_woocommerce_ul_close', 30 );
		remove_action( 'woocommerce_checkout_before_customer_details', 'astra_two_step_checkout_address_li_wrapper', 5 );
		remove_action( 'woocommerce_checkout_after_customer_details', 'astra_woocommerce_li_close' );
	}


	public function remove_astra_addon() {

		$template = wfacp_template();

		if ( is_null( $template ) || 'pre_built' !== $template->get_template_type() ) {
			return;
		}

		if ( class_exists( 'Astra_Ext_Nav_Menu_Loader' ) ) {
			WFACP_Common::remove_actions( 'wp_nav_menu_args', 'Astra_Ext_Nav_Menu_Loader', 'modify_nav_menu_args' );
			WFACP_Common::remove_actions( 'astra_theme_defaults', 'Astra_Ext_Nav_Menu_Loader', 'theme_defaults' );
			WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'Astra_Ext_Nav_Menu_Loader', 'load_scripts' );
			WFACP_Common::remove_actions( 'customize_register', 'Astra_Ext_Nav_Menu_Loader', 'customize_register' );
			WFACP_Common::remove_actions( 'wp_footer', 'Astra_Ext_Nav_Menu_Loader', 'megamenu_style' );
			WFACP_Common::remove_actions( 'customize_preview_init', 'Astra_Ext_Nav_Menu_Loader', 'customize_preview_init' );
		}

		add_action( 'wp_print_styles', [ $this, 'remove_theme_css_and_scripts' ], 100 );

	}


	public function remove_theme_css_and_scripts() {
		global $wp_scripts, $wp_styles;

		/* Unwanted folder for dequeue css and js */
		$us = [ '/astra-addon/', 'astra-' ];

		$registered_script = $wp_scripts->registered;
		if ( ! empty( $registered_script ) ) {

			foreach ( $registered_script as $handle => $data ) {

				if ( false !== strpos( $data->src, $us[0] ) || ( false !== strpos( $data->src, $us[1] ) ) ) {

					unset( $wp_scripts->registered[ $handle ] );
					wp_dequeue_script( $handle );
				}
			}
		}

		$registered_style = $wp_styles->registered;

		if ( ! empty( $registered_style ) ) {
			foreach ( $registered_style as $handle => $data ) {

				if ( false !== strpos( $data->src, $us[0] ) || ( false !== strpos( $data->src, $us[1] ) ) ) {

					unset( $wp_styles->registered[ $handle ] );
					wp_dequeue_style( $handle );
				}
			}
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

		$cssHtml = "<style>";
		$cssHtml .= "body{overflow-x: visible;}";
		$cssHtml .= ".ast-separate-container .ast-article-post {padding: 0 !important;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .woocommerce-checkout #payment ul.payment_methods li .form-row.form-row-last{clear: none;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .woocommerce-checkout #payment ul.payment_methods li .form-row.form-row-first{clear: none;}";
		$cssHtml .= $bodyClass . ".woocommerce-page form .form-row-quart-first, .woocommerce form .form-row-quart-first {margin-right: 0 !important;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .woocommerce-form-coupon-toggle{display: block;}";
		$cssHtml .= "body:not(.cartflows-canvas):not(.cartflows-default) .woocommerce form .form-row label:not(.checkbox):not(.woocommerce-form__label-for-checkbox){opacity: inherit;max-width: 100%;position: relative;margin: 0;padding: 0;white-space: inherit;line-height: 1.5;}";
		$cssHtml .= "body:not(.cartflows-canvas):not(.cartflows-default) .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__arrow b{display:initial;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}

	public function remove_script() {
		if ( ! class_exists( 'WFACP_Common' ) || WFACP_Common::get_id() == 0 ) {
			return;
		}
		?>
        <script>
            if (typeof modernLayoutInputs == "function") {
                function modernLayoutInputs() {

                }
            }

        </script>
		<?php
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Astra(), 'Astra' );

