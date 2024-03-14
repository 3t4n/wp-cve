<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Themify_Ultra {
	private $px = 7;

	public function __construct() {
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'add_theme_path' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function is_enabled() {
		return themify_is_themify_theme();
	}

	public function add_theme_path( $path ) {

		if ( ! $this->is_enabled() ) {
			return $path;
		}

		if ( is_array( $path ) && count( $path ) > 0 ) {
			unset( $path[0] );
		}

		return array_values( $path );
	}


	public function internal_css() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		if ( 'pre_built' !== $instance->get_template_type() ) {
			$this->px = "7";
		} else {
			$this->px = $instance->get_template_type_px();
		}
		?>
        <style>
            body .wfacp_main_form.woocommerce input[type=checkbox]:checked:before {
                transform: none;
                border: none;
            }

            .woocommerce-error,
            .woocommerce-info,
            .woocommerce-message {
                margin-bottom: 0;
            }

            body .wfacp_main_form.woocommerce form .form-row {

            }

            .woocommerce-error:before,
            .woocommerce-info:before,
            .woocommerce-message:before {
                display: none;
            }


            input[type=number] {
                border-radius: 0;
            }

            body .wfacp_main_form.woocommerce .woocommerce-error,
            body .wfacp_main_form.woocommerce .woocommerce-info,
            body .wfacp_main_form.woocommerce .woocommerce-message {

                background-color: transparent;
                border-radius: 0;
                padding: 0;
            }

            body .wfacp_main_form.woocommerce form .form-row {
                padding: 0 <?php echo $this->px ?>px;
                margin: 0 0 15px;


            }

            body .wfacp_main_form.woocommerce #add_payment_method #payment div.payment_box::before,
            body .wfacp_main_form.woocommerce #payment div.payment_box::before,
            body .wfacp_main_form.woocommerce .woocommerce-checkout #payment div.payment_box::before {
                display: none;
            }
        </style>
		<?php

	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Themify_Ultra(), 'wfacp-themify-ultra' );
