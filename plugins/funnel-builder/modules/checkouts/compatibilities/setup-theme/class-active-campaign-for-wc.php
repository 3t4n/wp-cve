<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_ActiveCompaign {

	private $wc_ac_obj = null;
	private $field_arg = null;
	private $ac_active = false;

	public function __construct() {

		add_action( 'wfacp_template_load', [ $this, 'remove_actions' ], 4 );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_ac_field' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );

		add_filter( 'wfacp_html_fields_activecampaign_for_woocommerce_accepts_marketing', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_wc_ac_hook' ], 10, 3 );

		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );
	}


	public function remove_actions() {

		$instance = WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'Activecampaign_For_Woocommerce_Public', 'handle_woocommerce_checkout_form' );
		WFACP_Common::remove_actions( 'woocommerce_after_checkout_form', 'Activecampaign_For_Woocommerce_Public', 'handle_woocommerce_checkout_form' );
		if ( ! $instance instanceof Activecampaign_For_Woocommerce_Public ) {
			return '';
		}
		$this->wc_ac_obj = $instance;

	}

	public function add_ac_field( $field ) {
		$field['activecampaign_for_woocommerce_accepts_marketing'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'activecampaign_for_woocommerce_accepts_marketing' ],
			'id'         => 'activecampaign_for_woocommerce_accepts_marketing',
			'field_type' => 'advanced',
			'label'      => __( 'ActiveCampaign', 'woocommerce' ),

		];

		return $field;
	}

	public function actions() {
		$checkbox_display_option = get_option( ACTIVECAMPAIGN_FOR_WOOCOMMERCE_DB_OPTION_NAME );

		if ( ! is_array( $checkbox_display_option ) || count( $checkbox_display_option ) == 0 || ! isset( $checkbox_display_option['checkbox_display_option'] ) ) {
			return;
		}

		if ( 'not_visible' === $checkbox_display_option['checkbox_display_option'] ) {
			return;
		}

		$this->ac_active = true;


		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_styling' ], 10, 2 );
	}


	public function call_wc_ac_hook( $field, $key, $args ) {

		if ( false === $this->ac_active || empty( $key ) || $key !== 'activecampaign_for_woocommerce_accepts_marketing' ) {
			return;
		}

		if ( method_exists( $this->wc_ac_obj, 'handle_woocommerce_checkout_form' ) ) {
			$this->wc_ac_obj->handle_woocommerce_checkout_form();
		} else {
			echo $this->get_field_html( $args, $this->wc_ac_obj );
		}

	}

	public function get_field_html( $args, $activecampaign_for_woocommerce_public_helper ) {

		if ( ! is_null( $args ) ) {
			$all_cls = array_merge( [ 'form-row wfacp-form-control-wrapper wfacp_custom_field_cls wfacp_ac_wrap' ], $args['class'] );
			if ( isset( $this->field_arg['cssready'] ) && is_array( $args['cssready'] ) ) {
				$all_cls = array_merge( $all_cls, $args['cssready'] );
			}
			$args['class'] = $all_cls;
		}

		$ac_checked = $activecampaign_for_woocommerce_public_helper->accepts_marketing_checkbox_is_checked();
		$ac_label   = $activecampaign_for_woocommerce_public_helper->label_for_accepts_marketing_checkbox();

		$value = "";
		if ( $ac_checked ) {
			$value = "1";
		}

		ob_start();
		?>
        <p class="test <?php echo implode( ' ', $args['class'] ); ?>">
            <input
                id="activecampaign_for_woocommerce_accepts_marketing"
                class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                type="checkbox"
                name="activecampaign_for_woocommerce_accepts_marketing"
                value='<?php echo $value; ?>'
				<?php
				if ( $ac_checked ) {
					echo 'checked="checked"';
				}
				?>
            />

            <label for="activecampaign_for_woocommerce_accepts_marketing" class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                <span><?php echo esc_html( $ac_label ); ?></span>
            </label>
        </p>
        <div class="clear"></div>
		<?php

		$result = ob_get_clean();

		return $result;
	}

	public function add_default_styling( $args, $key ) {


		if ( 'activecampaign_for_woocommerce_accepts_marketing' !== $key ) {
			return $args;
		}

		if ( isset( $args['type'] ) && 'checkbox' !== $args['type'] ) {

			$args['input_class'] = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
			$args['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full' ], $args['class'] );
			$args['cssready']    = [ 'wfacp-col-full' ];

		} else {
			$args['class']    = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$args['cssready'] = [ 'wfacp-col-full' ];
		}


		return $args;
	}


	public function add_internal_css() {

		if ( is_null( $this->wc_ac_obj ) || method_exists( $this->wc_ac_obj, 'handle_woocommerce_checkout_form' ) || ! function_exists( 'wfacp_template' ) ) {
			return;
		}

		$instance = wfacp_template();
		if ( is_null( $instance ) ) {
			return;
		}
		$px = $instance->get_template_type_px();
		?>
        <style>
            <?php
          if ( isset( $px ) ) {
            echo 'body .wfacp_main_form #activecampaign_for_woocommerce_accepts_marketing_field {padding: 0 '.$px.'px;}';
            echo 'body .wfacp_main_form.woocommerce .wfacp_ac_wrap input[type=checkbox] {left:'.$px.'px;}';
             }
            ?>
            body #activecampaign_for_woocommerce_accepts_marketing_field span.optional {
                display: inline-block !important;
            }

            body #activecampaign_for_woocommerce_accepts_marketing_field label {
                font-weight: normal;
            }

            body .wfacp_main_form.woocommerce .activecampaign_for_woocommerce_accepts_marketing input[type=checkbox] + label,
            body #wfacp-e-form .wfacp_main_form.woocommerce .activecampaign_for_woocommerce_accepts_marketing input[type=checkbox] + label {
                padding-left: 25px !important;
            }

            body #wfacp-e-form .wfacp_main_form input#activecampaign_for_woocommerce_accepts_marketing {
                left: auto;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_checkbox_field input[type=checkbox].wfacp-form-control {
                min-height: auto;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                -webkit-border-radius: 0;
                -moz-border-radius: 0;
                border-radius: 0;
                font-size: 14px !important;;
                border: 1px solid #b4b9be;
                background: #fff;
                color: #555;
                clear: none;
                cursor: pointer;
                display: inline-block;
                line-height: 0;
                height: 16px;
                margin: 0;
                top: 2px;
                outline: 0;
                padding: 0 !important;
                text-align: center;
                vertical-align: middle;
                width: 16px !important;
                min-width: 16px;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
                transition: .05s border-color ease-in-out;
                position: absolute;
                left: 0;
                z-index: 99;
            }

        </style>
		<?php

	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_ActiveCompaign(), 'wcac' );
