<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_FooEvent {

	private $instance = null;

	public function __construct() {

		/* checkout page */

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'register_action' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'register_action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function register_action() {
		if ( ! is_null( $this->instance ) ) {
			return;
		}

		$position                      = get_option( 'globalWooCommerceEventsAttendeeFieldsPos', 'default' );
		$theme_name                    = wp_get_theme();
		$woocommerce_checkout_position = array(
			'default'           => 'woocommerce_after_order_notes',
			'beforeordernotes'  => 'woocommerce_before_order_notes',
			'afterbillingform'  => 'woocommerce_after_checkout_billing_form',
			'aftershippingform' => 'woocommerce_after_checkout_shipping_form',
		);
		if ( empty( $position ) && 'Divi' === $theme_name ) {
			$position = 'afterbillingform';
		}
		if ( ( empty( $position ) || 1 === (int) $position ) || ! array_key_exists( $position, $woocommerce_checkout_position ) ) {
			$position = 'default';
		}
		$this->instance = WFACP_Common::remove_actions( $woocommerce_checkout_position[ $position ], 'FooEvents_Checkout_Helper', 'attendee_checkout' );
		if ( ! $this->instance instanceof FooEvents_Checkout_Helper ) {
			return;
		}


		add_action( 'wfacp_after_order_comments_field', [ $this, 'get_attendee_checkout' ] );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'register_fragment' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function internal_css() {

		if ( function_exists( 'wfacp_template' ) ) {
			$instance = wfacp_template();
		}

		if ( is_null( $instance ) ) {
			return;
		}
		$px = $instance->get_template_type_px();

		if ( ! isset( $px ) || $px == '' ) {
			return;
		}
		?>
        <style>
            body .wfacp_main_form .foo_event_wrap h3 {
                font-size: 20px;
                line-height: 1.5;
                margin: 0 0 15px;
                padding-left: <?php echo $px; ?>px;
                padding-right: <?php echo $px; ?>px;
            }

            body .wfacp_main_form .foo_event_wrap h4 {
                font-size: 15px;
                line-height: 1.5;
                margin: 0 0 15px;
                padding-left: <?php echo $px; ?>px;
                padding-right: <?php echo $px; ?>px;
            }

        </style>
        <script>
            window.addEventListener('bwf_checkout_js_load', function () {
                (function ($) {
                    let timeout = null;

                    function fill_attendee() {
                        timeout = setTimeout(function () {
                            let inputs = $('.foo_event_wrap input');
                            inputs.each(function () {
                                let row = $(this).parents('p.form-row');
                                row.removeClass('wfacp-anim-wrap');
                                if ("" !== $(this).val()) {
                                    row.addClass('wfacp-anim-wrap')
                                }
                            })
                        }, 300);
                    }

                    $('.woocommerce-billing-fields input').on('change', fill_attendee);
                    $(document.body).on('wfacp_step_switching', function () {
                        $('.woocommerce-billing-fields input').on('change');
                    });
                    fill_attendee();
                })(jQuery)
            })
        </script>
		<?php

	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( $args['id'] == 'wfacp_divider_shipping' ) {
			$args['label_class'] = array_merge( [ 'wfacp-form-control-label', 'woocommerce-shipping-fields' ], $args['label_class'] );;
		}
		if ( $args['id'] == 'wfacp_divider_billing' ) {
			$args['label_class'] = array_merge( [ 'wfacp-form-control-label', 'woocommerce-billing-fields' ], $args['label_class'] );;
		}

		if ( strpos( $key, 'attendee' ) !== false ) {

			$all_cls     = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$input_class = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$label_class = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );

			$args['class']       = $all_cls;
			$args['cssready']    = [ 'wfacp-col-full' ];
			$args['input_class'] = $input_class;
			$args['label_class'] = $label_class;
		}

		return $args;
	}

	public function register_fragment( $fragments ) {

		ob_start();
		$this->get_attendee_checkout();
		$attendee_html                = ob_get_clean();
		$fragments['.foo_event_wrap'] = $attendee_html;

		return $fragments;

	}

	public function get_attendee_checkout() {
		echo '<div class=foo_event_wrap>';

		$this->instance->attendee_checkout( WC()->checkout() );
		echo '</div>';

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_FooEvent(), 'fooevents' );
