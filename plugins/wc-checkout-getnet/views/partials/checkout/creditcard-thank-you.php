<?php
/**
 * Creditcard partial thankyou
 *
 * @package WcGetnet
 */

?>
<div id="getnet-thankyou">
	<div class="getnet-container">
		<fieldset class="getnet-form wc-payment-form">
			<p><?php _e( 'Seu pedido foi enviado para a Getnet.' ); ?></p>
			<label>
				<div class="payment-link-container">
					<p><?php printf( __( 'Status da transação com a Getnet: %s', 'wc_getnet' ), '<strong>' . esc_attr( $args['status'] ) ) . '</strong>'; ?></p>
				</div>
			</label>

		</fieldset>
	</div>
</div>
