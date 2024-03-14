<?php

/**
 * Billet partial thankyou
 *
 * @package WcGetnet
 */

?>
<div id="getnet-thankyou">
	<div class="getnet-container">
		<fieldset class="getnet-billet-form wc-payment-form">
			<?php if( $args['status_code'] !== "DENIED" ) : ?>
				<p><strong><?php _e( 'Seu pedido foi enviado para a Getnet.' ); ?></strong></p>
			<?php endif; ?>
			<p><?php echo $args['status_msg']; ?></p>
			<?php if( $args['status_code'] !== "DENIED" ) : ?>
					<span class="qrcode-pix">
						<img src="data:image/png;base64,<?php echo $args['qr_code']; ?>" />
					</span>
					<label>
						<div class="linecode-container">
							<span id="linetext"><p><?php _e( 'Chave PIX:' ); ?></p></span>
							<span id="linecode"><?php echo esc_attr( $args['pix_key'] ); ?></span><br/>

							<input type="button" class="btn-linecode-number" value="<?php _e( 'Copiar código' ); ?>"></br>
						</div>
					</label>
				<?php endif ?>
		</fieldset>
	</div>
	<form id="receipt_form">
		<input type="hidden" id="admin-ajax" value="<?php echo admin_url( 'admin-ajax.php' ); ?>">
		<input type="hidden" name="order_id" id="order_id" value="<?php echo esc_attr( $order_id ); ?>" />
		<?php echo wp_nonce_field( 'order-pay'.$order_id, 'wad_thankyou_nonce', true, false ); ?>
		<input type="hidden" id="billet-code" value="<?php echo esc_attr( $args['pix_key'] ); ?>">
	</form>
</div>
