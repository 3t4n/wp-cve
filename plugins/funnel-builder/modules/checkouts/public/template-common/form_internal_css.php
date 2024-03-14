<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$instance     = wfacp_template();
$current_open = $instance->get_current_open_step();
$count        = $instance->get_step_count();

echo '<style>';

if ( $template_type == 'elementor' && $template_type != 'elementor-minimalist-step-2' ) {
	?>
    body.wfacpef_page #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-section.wfacp_order_coupon_box,
    body.wfacpef_page #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-section.wfacp_order_summary_box {
    margin-bottom: 0;
    }
	<?php
}
if ( $count > 1 ) {
	?>
    .wfacp_page.<?php echo $template_type; ?> {
    display: none;
    }
	<?php
}
?>
html {
overflow: auto !important;
}
.wfacp_firefox_android .pac-container .pac-item:first-child {
margin-top: 20px;
}
span.wfacp_input_error_msg {
color: red;
font-size: 13px;
}

.wfacp_custom_field_multiselect select {
<?php
if ( ! WFACP_Common::is_theme_builder() ) {
	echo 'visibility: hidden;';
}
?>
}

<?php

if ( 'single_step' !== $current_open ) {
	?>
    .wfacp_page.<?php echo $current_open ?> {
    display: block;
    }

	<?php
} else {
	?>
    .wfacp_page.single_step {
    display: block;
    }

	<?php
}
?>

.wfacp_payment {
display: block;
}

.wfacp_payment.wfacp_hide_payment_part {
visibility: hidden;
position: fixed;
z-index: -600;
left: -200%;
}

.wfacp_payment.wfacp_show_payment_part {
visibility: visible;
}

.wfacp_page.<?php echo $current_step; ?> .wfacp_payment {
display: block;
}

.wfacp_page.<?php echo $current_step; ?> .wfacp_next_page_button {
display: none;
}

p#shipping_same_as_billing_field .optional {
display: none;
}

p#billing_same_as_shipping_field .optional {
display: none;
}

.wfacp_shipping_fields.wfacp_shipping_field_hide {
display: none !important;
}

.wfacp_billing_fields.wfacp_billing_field_hide {
display: none !important;
}


span.wfacp_required_field_message {
display: none;
}

.woocommerce-invalid-required-field span.wfacp_required_field_message {
display: inline;
}

.wfacp_country_field_hide {
display: none !important;
}

.wfacp_main_form .wfacp_shipping_table tr.shipping.wfacp_single_methods td.wfacp_shipping_package_name > p {
padding: 0 0 10px;
}

.wfacp_main_form .wfacp_shipping_table tr.shipping.wfacp_single_methods td.wfacp_shipping_package_name {
padding: 0 0 15px;
}
body.wfacp_do_not_show_block .blockUI.blockOverlay{
    display: none !important;
}
#wfacp_checkout_form.checkout.processing .blockUI.blockOverlay{
    display: block !important;
}
#wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content {
border-top: none;
}

<?php
if ( WFACP_Common::is_cart_is_virtual() ) {
	if ( 'pre_built' == $instance->get_template_type() ) {

		?>
        #shipping_same_as_billing_field {
        display: none;
        }
		<?php

	} else {
		?>
        #wfacp-e-form  #shipping_same_as_billing_field {
        display: none;
        }
		<?php
	}
}
if ( WFACP_Core()->pay->is_order_pay() ) {
	?>
    body.wfacp_main_wrapper.woocommerce-order-pay header.wfacp-header.wfacp_header {
    margin-bottom: 50px !important;
    }
    body.wfacp_main_wrapper.woocommerce-order-pay .woocommerce-form-login-toggle {
    display: none;
    }


    body.wfacp_main_wrapper.woocommerce-order-pay  .wfacp-login-wrapper .woocommerce-form-login {
    display: block !important;
    margin-top: 10px;
    }

    body.wfacp_main_wrapper.woocommerce-order-pay .woocommerce-info {
    color: #737373;
    }

	<?php
}
echo '</style>';
do_action( 'wfacp_internal_css', $selected_template_slug );
?>
<style>
    .loader {
        color: #fff;
        position: fixed;
        box-sizing: border-box;
        left: -9999px;
        top: -9999px;
        width: 0;
        height: 0;
        overflow: hidden;
        z-index: 999999;

    }

    .loader:after,
    .loader:before {
        box-sizing: border-box;

    }

    .loader.is-active {
        background-color: rgba(0, 0, 0, 0.85);
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
    }

    .loader.is-active:after,
    .loader.is-active:before {
        display: block;
    }

    .blockUI:before {

        display: none;
    }


    @keyframes rotation {
        0% {
            transform: rotate(0);
        }
        to {
            transform: rotate(359deg);
        }
    }


    .loader[data-text]:before {
        position: fixed;
        left: 0;
        top: 50%;
        color: currentColor;

        text-align: center;
        width: 100%;
        font-size: 14px;
    }

    .loader[data-text=""]:before {
        content: "Loading";
    }

    .loader[data-text]:not([data-text=""]):before {
        content: attr(data-text);
    }


    .loader-default[data-text]:before {
        top: calc(50% - 63px);
    }

    .loader-default:after {
        content: "";
        position: fixed;
        width: 48px;
        height: 48px;
        border: 8px solid #fff;
        border-left-color: transparent;
        border-radius: 50%;
        top: calc(50% - 24px);
        left: calc(50% - 24px);
        animation: rotation 1s linear infinite;

    }


    .wfacp_firefox_android .pac-container .pac-item:first-child {
        margin-top: 20px;
    }

    span.wfacp_input_error_msg {
        color: red;
        font-size: 13px;
    }

</style>
