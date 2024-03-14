<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}

$instance = wfacp_template();

$payment_buttons = $instance->get_smart_buttons();
if ( empty( $payment_buttons ) ) {
	return;
}


$or_title     = apply_filters( 'wfacp_smart_button_or_text', __( 'OR', 'woofunnels-aero-checkout' ) );
$legend_title = apply_filters( 'wfacp_smart_button_legend_title', __( 'Express Checkout', 'woofunnels-aero-checkout' ) );

$button_count = sizeof( $payment_buttons );

$button_class = '';
if ( $button_count > 0 ) {
	$button_class = 'wfacp_button_count_' . $button_count;
}

$show_smart_button_shimmer     = apply_filters( 'wfacp_show_smart_button_shimmer', true, $payment_buttons );
$show_smart_wrapper_visibility = 'display:none';
if ( ! $show_smart_button_shimmer ) {
	$show_smart_wrapper_visibility = '';
}
?>
<style>

    @media (min-width: 768px) {
        #wfacp_smart_buttons .wfacp_smart_button_outer_buttons[count="1"] .wfacp_smart_button_container {
            width: 100%;
            float: none;
        }

        #wfacp_smart_buttons .wfacp_smart_button_outer_buttons[count="2"] .wfacp_smart_button_container {
            width: 50%;
        }


        #wfacp_smart_buttons .wfacp_smart_button_outer_buttons[count="3"] .wfacp_smart_button_container {
            width: 33.33%;

        }

        #wfacp_smart_buttons .wfacp_smart_button_outer_buttons[count="4"] .wfacp_smart_button_container {
            width: 25%;

        }

        #wfacp_smart_buttons .wfacp_smart_button_wrap_st {
            margin: 0 -10px !important;
        }

        #wfacp_smart_buttons.wfacp_smart_buttons .wc-amazon-checkout-message.wc-amazon-payments-advanced-populated {
            display: block;
        }

        #wfacp_smart_buttons.wfacp_smart_buttons div#pay_with_amazon,
        #wfacp_smart_buttons #wfacp_smart_button_stripe_gpay_apay div#wc-stripe-payment-request-wrapper,
        #wfacp_smart_buttons #wfacp_smart_button_stripe_gpay_apay div#wc-stripe-payment-request-wrapper,
        #wfacp_smart_buttons .wfacp_smart_button_wrap_st div#paypal_box_button > div {
            width: 100%;
        }

        .wfacp_smart_button_wrap_st div#paypal_box_button > div {
            max-width: 100%;
        }

        #wfacp_smart_buttons.wfacp_smart_buttons .wfacp_smart_button_container {
            display: block;
            margin: 0 !important;
            padding: 0 10px;
            float: left;
        }

        #wfacp_smart_buttons.wfacp_smart_buttons .wfacp_smart_button_container iframe {
            max-height: 42px !important;
            height: 100% !important;
        }

        #wfacp_smart_buttons.wfacp_smart_buttons .wfacp_smart_button_container:after,
        #wfacp_smart_buttons.wfacp_smart_buttons .wfacp_smart_button_container:before {
            content: '';
            display: block;
        }

        #wfacp_smart_buttons.wfacp_smart_buttons .wfacp_smart_button_container:after {
            clear: both;
        }

        #wfacp_smart_buttons .wfacp_smart_button_wrap_st div#paypal_box_button .paypal-buttons {
            min-width: 1px;
            height: 42px !important;
            display: block !important;
        }
    }

</style>
<div class="wfacp_smart_buttons wfacp-dynamic-checkout-loading" id="wfacp_smart_buttons" style="<?php echo $show_smart_wrapper_visibility ?>">
    <div class="wfacp_smart_button_outer_buttons" count="<?php echo $button_count ?>">
        <div class="wfacp_smart_button_inner wfacp_smart_buttons_placeholder">
            <fieldset>
                <legend><?php echo $legend_title ?></legend>
                <div class="wfacp_smart_button_wrap_st wfacp_clearfix">
                    <div class="dynamic-checkout__skeleton">
                        <div class="placeholder-line placeholder-line--animated"></div>
                    </div>
					<?php
					foreach ( $payment_buttons as $slug => $payment ) {

						$hide_button_container = 'display: none';
						if ( isset( $payment['show_default'] ) ) {
							$hide_button_container = '';
						}

						?>
                        <div class="wfacp_smart_button_container" id="wfacp_smart_button_<?php echo $slug; ?>" style="<?php echo $hide_button_container ?>">
							<?php
							if ( isset( $payment['iframe'] ) ) {
								do_action( 'wfacp_smart_button_container_' . $slug, $payment, $slug );
							} else {
								if ( '' !== $payment['image'] ) {
									?>
                                    <div class="wfacp_smart_button_image_container">
                                        <img src="<?php echo $payment['image'] ?>">
                                    </div>
									<?php
								}
							}
							?>
                        </div>
						<?php
					}
					?>
                </div>
            </fieldset>
        </div>
		<?php
		if ( '' !== $or_title ) {
			?>
            <div class="wfacp_smart_button_inner wfacp_smart_button_or_text_placeholder"><label><?php echo $or_title; ?></label></div>
			<?php
		}
		?>
    </div>
</div>

