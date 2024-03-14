<?php
/**
 * Compatibility Name: WooCommerce Checkout Add-Ons
 * Plugin URI: http://www.woocommerce.com/products/woocommerce-checkout-add-ons/
 *
 */


#[AllowDynamicProperties] 

  class WFACP_Checkout_addons {
	private $label_separator = ' - ';
	private $is_checkout_order_review = false;

	public function __construct() {

		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_action( 'process_wfacp_html', [ $this, 'call_checkout_add_on' ], 10, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
		add_filter( 'wfacp_html_fields_wc_checkout_add_on', '__return_false' );

		add_action( 'woocommerce_review_order_after_cart_contents', function () {
			$this->is_checkout_order_review = true;
		} );

		add_action( 'woocommerce_review_order_after_order_total', function () {
			$this->is_checkout_order_review = false;
		} );

		add_action( 'wfacp_before_order_total_field', function () {
			$this->is_checkout_order_review = true;
		} );

		add_action( 'wfacp_after_order_total_field', function () {
			$this->is_checkout_order_review = false;
		} );

		add_action( 'wfacp_after_template_found', [ $this, 'after_template_load' ] );
		add_filter( 'wfacp_checkout_fields', [ $this, 'remove_addons_field' ] );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'refresh_checkout_fields_frag' ], 11 );
		add_filter( 'wp_footer', [ $this, 'add_js' ], 11 );
	}

	public function after_template_load() {
		add_action( 'woocommerce_before_checkout_form', [ $this, 'actions' ] );
		add_filter( 'esc_html', array( $this, 'display_add_on_value_in_checkout_order_review' ), 10, 2 );
	}

	public function add_js() {

		if ( ! $this->is_enable() ) {
			return;
		}
		?>
        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {
                    $(document.body).on('wfacp_step_switching', function () {
                        let wfacp_add_select21 = $('.form-row');
                        wfacp_add_select21.each(function () {
                            var wfacp_add_select2 = $(this);
                            if (wfacp_add_select2.length > 0 && wfacp_add_select2.find('select').hasClass('select2-hidden-accessible')) {
                                setTimeout(function () {
                                    wfacp_add_select2.find('select').select2();
                                }, 600);
                            }
                        });

                    });

                    $(document.body).on('wfacp_coupon_apply', function () {
                        $(document.body).trigger('update_checkout');
                    });

                    $(document.body).on('wfacp_coupon_form_removed', function () {
                        $(document.body).trigger('update_checkout');
                    });
                })(jQuery);
            });
        </script>
		<?php

	}

	public function actions() {

		$position = apply_filters( 'wc_checkout_add_ons_position', get_option( 'wc_checkout_add_ons_position', 'woocommerce_checkout_after_customer_details' ) );
		if ( class_exists( 'SkyVerge\WooCommerce\Checkout_Add_Ons\Frontend\Frontend' ) ) {
			WFACP_Common::remove_actions( $position, 'SkyVerge\WooCommerce\Checkout_Add_Ons\Frontend\Frontend', 'render_add_ons' );
			WFACP_Common::remove_actions( 'esc_html', 'SkyVerge\WooCommerce\Checkout_Add_Ons\Frontend\Frontend', 'display_add_on_value_in_checkout_order_review' );
		}
	}

	private function is_enable() {
		return function_exists( 'wc_checkout_add_ons' );
	}

	public function add_fields( $field ) {

		if ( ! $this->is_enable() ) {
			return $field;
		}

		$field['wc_checkout_add_on'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wc_checkout_add_on' ],
			'id'         => 'wc_checkout_add_on',
			'field_type' => 'advanced',
			'label'      => __( 'Checkout Addons', 'woocommerce' ),
		];

		return $field;
	}

	public function call_checkout_add_on( $field, $key, $args ) {
		if ( ! $this->is_enable() ) {
			return;
		}
		if ( ! empty( $key ) && $key == 'wc_checkout_add_on' ) {
			$this->render_add_ons();
		}
	}


	private function render_add_ons() {

		$checkout_add_on_fields = isset( WC()->checkout()->checkout_fields['add_ons'] ) ? WC()->checkout()->checkout_fields['add_ons'] : null;
		if ( is_array( $checkout_add_on_fields ) && count( $checkout_add_on_fields ) > 0 ) {
			foreach ( $checkout_add_on_fields as $key => $field ) {
				$type = $field['type'];

				if ( $type == 'wc_checkout_add_ons_checkbox' || $type == 'wc_checkout_add_ons_multicheckbox' ) {
					$field['type'] = 'checkbox';
				}

				if ( $type == 'wc_checkout_add_ons_radio' ) {
					$field['type'] = 'wc_checkout_add_ons_radio';

				}
				if ( $type == 'select' ) {
					$field['class'] = [ 'wfacp_custom_wrap' ];
				}


				if ( $type == 'text' || $type == 'select' || $type == 'textarea' ) {
					$class = [ 'wfacp_checkout_addon_wrap' ];
					if ( isset( $field['default'] ) && $field['default'] != '' ) {
						$class[] = 'wfacp-anim-wrap';
					}

					if ( isset( $field['description'] ) && $field['description'] != '' ) {
						$class[] = 'wfacp_default_checkout_addon';
					}
					if ( count( $class ) > 0 ) {
						$field['class'] = $class;
					}
					if ( isset( $field['custom_attributes'] ) && ( is_array( $field['custom_attributes'] ) && count( $field['custom_attributes'] ) > 0 ) ) {
						if ( $field['custom_attributes']['data-description'] ) {
							$field['class'] [] = 'wfacp_label_normal';
						}
					}

				} elseif ( $type == 'wc_checkout_add_ons_multicheckbox' ) {
					$field['class'] = [ 'wfacp_default_checkout_addon_multicheckbox', 'wfacp_checkout_addon_wrap' ];
				} elseif ( $type == 'wc_checkout_add_ons_file' ) {
					$class          = [ 'wc_checkout_add_ons_fileupload', 'wfacp_checkout_addon_wrap' ];
					$field['class'] = $class;
				} elseif ( $type == 'wc_checkout_add_ons_multiselect' ) {
					$field['class'] = [ 'wc_checkout_add_ons_multiselect', 'wfacp_checkout_addon_wrap' ];
				} elseif ( $type == 'wc_checkout_add_ons_radio' ) {
					$field['class'] = [ 'wc_checkout_add_ons_radio', 'wfacp_checkout_addon_wrap' ];
				}


				$field = apply_filters( 'wfacp_forms_field', $field, $key );

				if ( $type == 'wc_checkout_add_ons_checkbox' || $type == 'wc_checkout_add_ons_multicheckbox' ) {
					$field['type'] = $type;

				}

				$checkout_add_on_fields[ $key ] = $field;
			}
		}
		echo '<div id="wc_checkout_add_ons">';
		if ( is_array( $checkout_add_on_fields ) && count( $checkout_add_on_fields ) > 0 ) {
			foreach ( $checkout_add_on_fields as $key => $field ) :
				woocommerce_form_field( $key, $field, WC()->checkout()->get_value( $key ) );
			endforeach;
		}
		echo '</div>';
	}

	public function display_add_on_value_in_checkout_order_review( $safe_text, $text ) {

		if ( ! $this->is_enable() ) {
			return $safe_text;
		}

		// Bail out if not in checkout order review area
		if ( ! $this->is_checkout_order_review ) {
			return $safe_text;
		}
		$text = sanitize_title( $text );

		if ( isset( WC()->session->checkout_add_ons['fees'][ $text ] ) ) {

			$session_data = WC()->session->checkout_add_ons['fees'][ $text ];

			// Get add-on value from session and set it for add-on
			$add_on = SkyVerge\WooCommerce\Checkout_Add_Ons\Add_Ons\Add_On_Factory::get_add_on( $session_data['id'] );

			// removes our own filtering to account for the rare possibility that an option value is named the same way as the add on
			remove_filter( 'esc_html', array( $this, 'display_add_on_value_in_checkout_order_review' ), 10 );

			// Format add-on value
			$value = $add_on ? $add_on->normalize_value( $session_data['value'], true ) : null;

			// re-add back our filter after normalization is done
			add_filter( 'esc_html', array( $this, 'display_add_on_value_in_checkout_order_review' ), 10, 2 );

			// Append value to add-on name
			if ( $value ) {

				if ( 'text' === $add_on->get_type() || 'textarea' === $add_on->get_type() ) {
					$value = $add_on->truncate_label( $value );
				}

				$safe_text .= $this->label_separator . $value;
			}
		}

		return $safe_text;
	}

	public function remove_addons_field( $fields ) {
		if ( ! $this->is_enable() ) {
			return $fields;
		}
		if ( ! isset( $fields['advanced']['wc_checkout_add_on'] ) ) {
			WFACP_Common::remove_actions( 'woocommerce_checkout_fields', 'SkyVerge\WooCommerce\Checkout_Add_Ons\Frontend\Frontend', 'add_checkout_fields' );
		}

		return $fields;
	}

	public function refresh_checkout_fields_frag( $fragments ) {

		if ( ! $this->is_enable() ) {
			return $fragments;
		}

		ob_start();

		$this->render_add_ons();

		$fragments['#wc_checkout_add_ons'] = ob_get_clean();

		return $fragments;
	}

	public function wfacp_internal_css( $slug ) {
		if ( ! $this->is_enable() ) {
			return;
		}
		$instance = wfacp_template();


		?>

        <style>

            body .wfacp_main_form.woocommerce .wfacp_error_border {
                transition: all .4s ease-out !important;
                border-color: #d50000 !important;
            }


            body:not(.wfacp_cls_layout_1) .wfacp_main_form #wc_checkout_add_ons p.wfacp_label_normal:not(.wfacp-anim-wrap) input[type=text],
            body:not(.wfacp_cls_layout_1) .wfacp_main_form #wc_checkout_add_ons p.wfacp_label_normal:not(.wfacp-anim-wrap) input[type=number],
            body:not(.wfacp_cls_layout_1) .wfacp_main_form #wc_checkout_add_ons p.wfacp_label_normal:not(.wfacp-anim-wrap) input[type=email] {
                padding-top: 12px;
                padding-bottom: 10px;
            }

            body:not(.wfacp_cls_layout_1) .wfacp_main_form #wc_checkout_add_ons p.wfacp_label_normal:not(.wfacp-anim-wrap) label {
                top: 19px;
                bottom: auto;
                margin: 0;
                line-height: 1.5;
            }

            body.wfacp_cls_layout_2 .wfacp_main_form #wc_checkout_add_ons p.wfacp_label_normal:not(.wfacp-anim-wrap) label,
            body.wfacp_cls_layout_4 .wfacp_main_form #wc_checkout_add_ons p.wfacp_label_normal:not(.wfacp-anim-wrap) label {
                top: 14px;

            }


            /*----------------------------------WC CHECKOUT ADD ON---------------------------------------*/

            body .wfacp_main_form #wc_checkout_add_ons .description {
                margin-bottom: 5px;
                margin-top: 2px;
            }

            body .wfacp_main_form.woocommerce .wfacp_default_checkout_addon_multicheckbox input[type=checkbox] {
                position: relative;
                left: auto;
                right: auto;
                bottom: auto;
                top: auto;
                margin-right: 10px;
                margin-bottom: 0;
                vertical-align: middle;

            }

            body .wfacp_main_form.woocommerce .wc_checkout_add_ons_fileupload + .wc-checkout-add-ons-input-file-plupload,
            body .wfacp_main_form.woocommerce .wc_checkout_add_ons_fileupload .wc-checkout-add-ons-input-file-plupload + .description {
                margin: 0 15px;
                width: auto;
            }

            body .wfacp_main_form.woocommerce .wc_checkout_add_ons_multiselect label {
                position: relative;
                top: auto;
                left: 0;
                bottom: auto;
                margin: 0 0 5px;
            }

            body .wfacp_main_form.woocommerce .wc_checkout_add_ons_radio label {
                padding-left: 0 !important;
            }

            body .wfacp_main_form.woocommerce .wc_checkout_add_ons_radio label.wfacp-form-control-label {
                position: relative;
                margin: 0;
                padding-left: 0 !important;
                left: 0;
                right: auto;
                top: auto;
                bottom: auto;
                display: inline !important;
                pointer-events: visible;
            }

            body .wfacp_main_form.woocommerce .wc_checkout_add_ons_radio input[type="radio"] {
                position: relative;
                top: auto;
                left: auto;
                bottom: auto;
                vertical-align: middle !important;
                margin: 0px 10px 0 0;
            }

            <?php
            if ( 'pre_built' !== $instance->get_template_type() ) {

            ?>

            /* WC Checkout Add on */

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_radio label {
                padding-left: 0 !important;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_radio label.wfacp-form-control-label {
                position: relative;
                margin: 0;
                padding-left: 0 !important;
                left: 0;
                right: auto;
                top: auto;
                bottom: auto;
                display: inline !important;
                pointer-events: visible;
                background: transparent;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_radio input[type="radio"] {
                position: relative;
                top: auto;
                left: auto;
                bottom: auto;
                vertical-align: middle !important;
                margin: 0 10px 0 0;
                float: none;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_checkout_addon_wrap br {
                content: "";
                margin: 0 0 10px;
                display: block;
                font-size: 24%;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_checkout_addon_wrap br:last-child {
                margin: 0;
            }


            body #wfacp-e-form .wfacp_main_form .wfacp_default_checkout_addon.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label {
                top: 20px;
                font-size: 12.5px;
                bottom: auto;
                right: auto;
                margin-top: 0;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_multiselect label {
                position: relative;
                top: auto;
                left: 0;
                bottom: auto;
                margin: 0 0 5px;
                background: transparent;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_fileupload + .wc-checkout-add-ons-input-file-plupload,
            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_fileupload + .wc-checkout-add-ons-input-file-plupload + .description {
                margin: 0 12px;
            }

            body #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .wc_checkout_add_ons_fileupload {
                margin: 0;
            }

            body #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .wc_checkout_add_ons_fileupload > label {
                position: relative;
                left: 0;
                top: 0;
                margin: 0;
                background: transparent;
            }

            body #wfacp-e-form table.shop_table {
                margin-bottom: 0 !important;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_fileupload + .wc-checkout-add-ons-input-file-plupload {
                padding: 10px 12px;
                width: auto;
            }

            body #wfacp-e-form .wc-checkout-add-ons-input-file-plupload.wfacp-form-control {
                margin-bottom: 20px !important;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .wc_checkout_add_ons_fileupload + .wc-checkout-add-ons-input-file-plupload + .description {
                margin-top: -20px;
            }

            body #wfacp-e-form .wfacp_main_form p.wc_checkout_add_ons_multiselect li,
            body #wfacp-e-form .wfacp_main_form p.wc_checkout_add_ons_multiselect ul {
                line-height: 1.5;
                height: auto;
            }

            body #wfacp-e-form .wfacp_main_form p.wc_checkout_add_ons_multiselect input {
                background: transparent !important;
            }

            body #wfacp-e-form .wfacp_main_form p.wfacp_checkout_addon_wrap textarea {
                min-height: 100px;
            }

            body #wfacp-e-form .wfacp_main_form p.wfacp_checkout_addon_wrap span.amount {
                font-weight: normal;
                color: inherit;
            }

            body #wfacp-e-form .wfacp_main_form p.wc_checkout_add_ons_multiselect .select2-container .select2-selection,
            body #wfacp-e-form .wfacp_main_form p.wc_checkout_add_ons_multiselect select {
                background: transparent;
                height: auto;
                padding: 0;
            }

            body #wfacp-e-form .wfacp_main_form p.wc_checkout_add_ons_multiselect select {
                border-radius: 4px;
            }

            body #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .description {
                font-size: 13px;
                line-height: 1.5;
                margin-bottom: 4px;
            }

            body .wfacp_main_form #wc_checkout_add_ons .description {
                margin-top: 0;
            }

            body #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .wc_checkout_add_ons_multiselect > label {
                padding: 0;
            }

            body #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .wc_checkout_add_ons_multiselect .select2-container {
                width: 100% !important;
            }

            body #wfacp-e-form .wfacp_main_form .wc_checkout_add_ons_fileupload > label {
                padding: 0;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce #wc_checkout_add_ons input[type=checkbox] + label {
                padding-left: 0 !important;
            }

            <?php
			}
			?>

        </style>
		<?php
	}

}

add_action( 'plugins_loaded', function () {
	if ( ! class_exists( 'WC_Checkout_Add_Ons_Loader' ) ) {
		return;
	}
	WFACP_Plugin_Compatibilities::register( new WFACP_Checkout_addons(), 'checkout_addons' );
} );

