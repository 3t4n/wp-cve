<?php
    /** @var $stripeStatus */
?>
<a class="wpfs-btn wpfs-btn-outline-primary wpfs-page-header__accounts js-tooltip" href="<?php echo $stripeStatus->manageStripeAccountsUrl; ?>" data-tooltip-content="stripe-tooltip">
    <span class="wpfs-icon-stripe"></span><?php echo esc_html( $stripeStatus->apiModeLabel ); ?>
</a>
<div class="wpfs-tooltip-content" data-tooltip-id="stripe-tooltip">
    <div class="wpfs-info-tooltip"><?php esc_html_e( 'Manage Stripe account', 'wp-full-stripe-admin' ); ?></div>
</div>
