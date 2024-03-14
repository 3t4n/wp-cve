<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $instance WFACP_Template_Common
 */
if ( apply_filters( 'wfacp_skip_form_printing', false ) ) {
	return;
}

if ( ! WFACP_Core()->public->is_checkout_override() && true == WC()->cart->is_empty() ) {
	$product = WFACP_Core()->public->get_product_list();
	if ( count( $product ) == 0 ) {
		wc_print_notice( 'Sorry, no product(s) added to checkout', 'error' );

		return;
	}
}
$checkout = WC()->checkout();
do_action( 'wfacp_checkout_preview_form_start', $checkout );
$permalink = get_the_permalink();
?>
<div class="wfacp_main_form woocommerce">
	<?php
	do_action( 'wfacp_outside_header' );
	if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
		echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );

		return;
	}
	$instance  = wfacp_template();
	$checkout  = WC()->checkout();
	$fieldsets = $instance->get_fieldsets();
	if ( ! is_array( $fieldsets ) ) {
		return;
	}
	$current_step           = $instance->get_current_step();
	$selected_template_slug = $instance->get_template_slug();
	$template_type          = $instance->get_template_type();

	include_once __DIR__ . '/form_internal_css.php';
	do_action( 'woocommerce_before_checkout_form', $checkout );
	?>
    <style>
        .wfacp_payment {
            display: block;
        }

        #amazon_addressbook_widget iframe, #amazon_wallet_widget iframe {
            height: 100%;
        }

        .amazon_shipping_wrap {
            clear: both;
        }

        .amazon_shipping_wrap + .wfacp_payment {
            clear: both;
        }

        .amazon_shipping_wrap:after, .amazon_shipping_wrap:before {
            display: block;
            content: '';
        }

        .amazon_shipping_wrap:after {
            clear: both;
        }

        .amazon_shipping_wrap {
            margin-top: 15px;

        }

        #amazon_customer_details + .amazon_shipping_wrap + .wfacp_payment .payment_methods li.wc_payment_method.payment_method_amazon_payments_advanced label {
            display: block !important;
            width: 100% !important;
            text-align: center;
        }

        #amazon_customer_details + .amazon_shipping_wrap + .wfacp_payment .payment_methods li.wc_payment_method.payment_method_amazon_payments_advanced label img {
            display: block;
            margin-top: 10px;
            max-width: 200px;
        }

        .woocommerce-billing-fields h3, .woocommerce-shipping-fields h3 {
            display: none;
        }

    </style>
    <form name="checkout" method="post" class="checkout woocommerce-checkout wfacp_amazon_checkout" action="<?php echo esc_url( get_the_permalink() ); ?>" enctype="multipart/form-data" id="wfacp_checkout_form">
        <input type="hidden" name="_wfacp_post_id" class="_wfacp_post_id" value="<?php echo WFACP_Common::get_id(); ?>">
        <div class="wfacp-section  wfacp-hg-by-box">
			<?php
			do_action( 'woocommerce_checkout_before_customer_details' );
			$amazon_setting = WC_Amazon_Payments_Advanced_API::get_settings();

			if ( isset( $amazon_setting['enable_login_app'] ) && 'yes' == $amazon_setting['enable_login_app'] ) {
				?>
                <div class="col2-set wfacp_billing_shipping_fields" id="customer_details">
                    <div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>
                    <div class="col-2">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                </div>
				<?php
				WFACP_Common::remove_actions( 'woocommerce_checkout_after_customer_details', 'WFACP_handle_billing_address', 'print_billing_fields' );
				WFACP_Common::remove_actions( 'wp_footer', 'WFACP_handle_billing_address', 'enable_js' );
			}
			$data = $instance->get_checkout_fields();

			if ( isset( $data['billing']['billing_phone'] ) ) {
				echo "<div class=amazon_shipping_wrap>";
				woocommerce_form_field( 'billing_phone', $data['billing']['billing_phone'], '' );
				echo "</div>";
			}

			if ( isset( $data['advanced']['shipping_calculator'] ) ) {
				echo "<div class=amazon_shipping_wrap>";
				woocommerce_form_field( 'shipping_calculator', $data['advanced']['shipping_calculator'], '' );
				echo "</div>";
			}


			do_action( 'woocommerce_checkout_after_customer_details' );
			?>
        </div>
		<?php
		do_action( 'wfacp_before_payment_section' );
		include __DIR__ . '/payment.php';
		do_action( 'wfacp_after_payment_section' );
		?>
        <input type="hidden" id="wfacp_source" name="wfacp_source" value="<?php echo esc_url( $permalink ); ?>">

        <input type="hidden" id="wfacp_exchange_keys" name="wfacp_exchange_keys" class="wfacp_exchange_keys" value="">
        <input type="hidden" id="wfacp_input_hidden_data" name="wfacp_input_hidden_data" class="wfacp_input_hidden_data" value="{}">
    </form>
</div>
<?php
do_action( 'wfacp_checkout_preview_form_end', $checkout );
?>
