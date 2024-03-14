<?php
/**
 * Credit Card partial view.
 *
 * @package WcGetnet
 */

use WcGetnet\Services\WcGetnetPayment;
use WcGetnet\Services\WcGetnetApi;
use WcGetnet\WooCommerce\GateWays\WcGetnet_CreditCard;

?>

<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">

	<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>

	<input type="hidden" id="getnetEnvironment" value="<?php echo WcGetnetApi::getAntifraudCode(); ?>"> </input>
	<!--CARD HOLDER-->
	<div class="form-row form-row-wide">
		<label><?php esc_html_e( 'Nome' ); ?> <small>(<?php esc_html_e( 'como impresso no cartão' ); ?>)</small> <span class="required">*</span></label>

		<input id="<?php echo esc_attr( $this->id ); ?>_holder_name" data-element="<?php echo esc_attr( $this->id ); ?>_holder_name"
			class="input-text wc-getnet-credit-card-form-holder"
			type="text"
			minlength="3"
			name="<?php echo esc_attr( $this->id ); ?>_holder_name"
			placeholder="<?php esc_html_e( 'Nome impresso no cartão' ); ?>">
	</div>

	<!--CARD NUMBER-->
	<div class="form-row form-row-wide cc-number">
		<label><?php esc_html_e( 'Número do cartão' ); ?><span class="required">*</span></label>
		<input id="<?php echo esc_attr( $this->id ); ?>_number" data-element="<?php echo esc_attr( $this->id ); ?>_number"
			class="input-text wc-getnet-credit-card-form-number"
			type="text"
			autocomplete="off"
			placeholder="•••• •••• •••• ••••"
			maxlength="19">
		<span id="<?php echo esc_attr( $this->id ); ?>_brand" class="cc-number-brand"></span>
	</div>

	<!--CARD EXPIRY-->
	<div class="form-row form-row-first">
		<label><?php esc_html_e( 'Validade (MM/AA)' ); ?> <span class="required">*</span></label>
		<input id="<?php echo esc_attr( $this->id ); ?>_expiry" data-element="<?php echo esc_attr( $this->id ); ?>_expiry"
			class="input-text wc-credit-card-form-expiry"
			type="text"
			autocomplete="off"
			placeholder= "MM/AA"
			name="<?php echo esc_attr( $this->id ); ?>_expiry"
			maxlength="5">
	</div>

	<!--CARD CVC-->
	<div class="form-row form-row-last">
		<label><?php esc_html_e( 'Código do cartão' ); ?> <span class="required">*</span></label>
		<input id="<?php echo esc_attr( $this->id ); ?>_cvc" data-element="<?php echo esc_attr( $this->id ); ?>_cvc"
			class="input-text wc-credit-card-form-cvc"
			type="password"
			autocomplete="off"
			style="width:100px"
			placeholder="••••"
			maxlength="4"
			pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)">
	</div>

	<?php if ( $this->is_min_value_from_installments() && $this->installments && 0 < intval( $this->installments ) ) : ?>
		<div class="form-row form-row-wide">
			<label for="<?php echo esc_attr( $this->id ); ?>_number_installments">
				<?php esc_html_e( 'Parcelas', 'wc_getnet' ); ?><span class="required">*</span>
			</label>

			<select id="<?php echo esc_attr( $this->id ) . '_number_installments'; ?>"
				name="<?php echo esc_attr( $this->id ) . '_number_installments'; ?>"
				data-total="<?php echo esc_html( WC()->cart->cart_contents_total ); ?>"
				data-action="select2"
				data-required="true"
				data-element="installments">

				<?php echo WcGetnetPayment::render_installments_options( WC()->cart->total, $this->installments, $this->installments_initial_interest, $this->installments_increase_interest, $this->installments_interest ); ?>

			</select>
		</div>
	<?php endif; ?>


	<div class="clear"></div>

	<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>

	<div class="clear"></div>
</fieldset>
