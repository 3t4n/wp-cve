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
			<p><?php _e( 'Seu pedido foi enviado para a Getnet.' ); ?></p>
			<label>
				<div class="linecode-container">
					<span id="linetext"><?php _e( 'Linha digitável:' ); ?></span>
					<span id="linecode"><?php echo esc_attr( $args['typeful_line'] ); ?></span><br/>

					<input type="button" class="btn-linecode-number" value="<?php _e( 'Copiar código' ); ?>"></br>
				</div>

				<div class="payment-link-container">
					<p><?php _e( 'Para imprimir o boleto clique no botão abaixo. Seu pedido será processado assim que recebermos a confirmação do pagamento do seu boleto.' ); ?></p>

					<a href="<?php echo esc_url( $args['html_link'] ); ?>" target="_blank" id="payment-link"><?php _e( 'Pagar agora' ); ?></a></br>
				</div>
			</label>
		</fieldset>
	</div>
	<form id="receipt_form">
		<input type="hidden" id="admin-ajax" value="<?php echo admin_url( 'admin-ajax.php' ); ?>">
		<input type="hidden" name="order_id" id="order_id" value="<?php echo esc_attr( $order_id ); ?>" />
		<?php echo wp_nonce_field( 'order-pay'.$order_id, 'wad_thankyou_nonce', true, false ); ?>
		<input type="hidden" id="billet-code" value="<?php echo esc_attr( $args['typeful_line'] ); ?>">
	</form>
</div>
