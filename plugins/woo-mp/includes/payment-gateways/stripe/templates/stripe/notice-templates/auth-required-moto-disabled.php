<?php defined( 'ABSPATH' ) || die; ?>

<script type="text/template" id="woo-mp-stripe-notice-template-auth-required-moto-disabled">
    <p>
        Strong Customer Authentication (<a href="https://stripe.com/guides/strong-customer-authentication" target="_blank">SCA</a>) is required for this payment. This means that the customer will have to visit your website to pay for the order.
    </p>

    <p>You may be able to prevent this error for future payments by using the Mail Order / Telephone Order (<a href="https://support.stripe.com/questions/mail-order-telephone-order-moto-transactions-when-to-categorize-transactions-as-moto" target="_blank">MOTO</a>) exemption. You will need to contact Stripe to get this feature enabled for your account. Once the feature is enabled, you will be able to use the <a href="<?= esc_url( WOO_MP_SETTINGS_URL . '&section=stripe' ) ?>" target="_blank">Mark Payments as MOTO</a> setting.</p>
</script>
