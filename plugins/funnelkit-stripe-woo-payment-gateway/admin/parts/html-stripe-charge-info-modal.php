<?php
/**
 * @var \Stripe\Charge $charge
 */
?>

<div class="data-container">
    <div class="column-6">
        <h3><?php esc_html_e( 'Charge Data', 'funnelkit-stripe-woo-payment-gateway' ); ?></h3>
        <div class="metadata">
            <label><?php esc_html_e( 'Mode', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>:&nbsp;
			<?php $charge->livemode ? esc_html_e( 'Live', 'funnelkit-stripe-woo-payment-gateway' ) : esc_html_e( 'Test', 'funnelkit-stripe-woo-payment-gateway' ); ?>
        </div>
        <div class="metadata">
            <label><?php esc_html_e( 'Status', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>:&nbsp;
			<?php echo esc_html($charge->status); ?>
        </div>
		<?php if ( ( $payment_intent_id = $order->get_meta( '_payment_intent_id', true ) ) ) : ?>
            <div class="metadata">
                <label><?php esc_html_e( 'Payment Intent', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>:&nbsp;
				<?php echo $payment_intent_id; ?>
            </div>
		<?php endif; ?>
		<?php if ( isset( $charge->customer ) ) : ?>
            <div class="metadata">
                <label><?php esc_html_e( 'Customer', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>:&nbsp;
				<?php echo $charge->customer; ?>
            </div>
		<?php endif; ?>
    </div>
    <div class="column-6">
        <h3><?php esc_html_e( 'Payment Method', 'funnelkit-stripe-woo-payment-gateway' ); ?></h3>
        <div class="metadata">
            <label><?php esc_html_e( 'Title', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>:&nbsp;
			<?php echo $order->get_payment_method_title(); ?>
        </div>
        <div class="metadata">
            <label><?php esc_html_e( 'Type', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>:&nbsp;
			<?php echo $charge->payment_method_details->type; ?>
        </div>
		<?php if ( isset( $charge->payment_method_details->card ) ) : ?>
            <div class="metadata">
                <label><?php esc_html_e( 'Exp', 'funnelkit-stripe-woo-payment-gateway' ); ?>:&nbsp;</label>
				<?php printf( '%02d / %s', $charge->payment_method_details->card->exp_month, $charge->payment_method_details->card->exp_year ); ?>
            </div>
            <div class="metadata">
                <label><?php esc_html_e( 'Fingerprint', 'funnelkit-stripe-woo-payment-gateway' ); ?>:&nbsp;</label>
				<?php echo $charge->payment_method_details->card->fingerprint; ?>
            </div>
            <div class="metadata">
                <label><?php esc_html_e( 'CVC check', 'funnelkit-stripe-woo-payment-gateway' ); ?>:&nbsp;</label>
				<?php echo $charge->payment_method_details->card->checks->cvc_check; ?>
            </div>
            <div class="metadata">
                <label><?php esc_html_e( 'Postal check', 'funnelkit-stripe-woo-payment-gateway' ); ?>:&nbsp;</label>
				<?php echo $charge->payment_method_details->card->checks->address_postal_code_check; ?>
            </div>
            <div class="metadata">
                <label><?php esc_html_e( 'Street check', 'funnelkit-stripe-woo-payment-gateway' ); ?>:&nbsp;</label>
				<?php echo $charge->payment_method_details->card->checks->address_line1_check; ?>
            </div>
		<?php endif; ?>
    </div>
    <div class="column-6">
        <h3><?php esc_html_e( 'Risk Data', 'funnelkit-stripe-woo-payment-gateway' ); ?></h3>
		<?php if ( isset( $charge->outcome->risk_score ) ) { ?>
            <div class="metadata">
                <label><?php esc_html_e( 'Score', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>
				<?php echo $charge->outcome->risk_score; ?>
            </div>
		<?php } ?>
        <div class="metadata">
            <label><?php esc_html_e( 'Level', 'funnelkit-stripe-woo-payment-gateway' ); ?></label>
			<?php echo $charge->outcome->risk_level; ?>
        </div>
    </div>
</div>
<?php if ( ! $order->has_status( 'cancelled' ) ) : ?>
	<?php if ( ( $charge->status === 'pending' && ! $charge->captured ) || ( $charge->status === 'succeeded' && ! $charge->captured ) ) : ?>
        <div class="modal-actions">
            <h2><?php esc_html_e( 'Actions', 'funnelkit-stripe-woo-payment-gateway' ); ?></h2>
            <div>
                <input type="text" class="wc_input_price" name="capture_amount"
                       value="<?php echo $order->get_total(); ?>"
                       placeholder="<?php esc_html_e( 'capture amount', 'funnelkit-stripe-woo-payment-gateway' ); ?>" data-error="<?php echo __( 'Please enter the total order amount.', 'funnelkit-stripe-woo-payment-gateway' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'capture_charge' ) ); ?>" data-nonce-void="<?php echo esc_attr( wp_create_nonce( 'void_charge' ) ); ?>"/>
                <button class="button button-primary do-api-capture"><?php esc_html_e( 'Capture', 'funnelkit-stripe-woo-payment-gateway' ); ?></button>
                <button class="button button-secondary do-api-cancel"><?php esc_html_e( 'Void', 'funnelkit-stripe-woo-payment-gateway' ); ?></button>
            </div>
        </div>
	<?php endif; ?>
<?php endif; ?>
