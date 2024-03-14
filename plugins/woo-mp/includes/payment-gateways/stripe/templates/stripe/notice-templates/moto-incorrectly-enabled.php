<?php defined( 'ABSPATH' ) || die; ?>

<script type="text/template" id="woo-mp-stripe-notice-template-moto-incorrectly-enabled">
    <p>The <a href="<?= esc_url( WOO_MP_SETTINGS_URL . '&section=stripe' ) ?>" target="_blank">Mark Payments as MOTO</a> setting is enabled, however your Stripe account does not have the <a href="https://support.stripe.com/questions/mail-order-telephone-order-moto-transactions-when-to-categorize-transactions-as-moto" target="_blank">MOTO</a> feature enabled. You will need to contact Stripe to get this feature enabled for your account.</p>
</script>
