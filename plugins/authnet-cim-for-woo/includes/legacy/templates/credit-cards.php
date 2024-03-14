<?php
/**
 * Credit cards template file.
 *
 * @package Authorize.Net CIM for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<h2 id="credit-cards" style="margin-top:40px;"><?php esc_html_e( 'My Credit Cards', 'woocommerce-cardpay-authnet' ); ?></h2>
<div class="woocommerce-message authnet-success-message"><?php esc_html_e( 'Your request has been successfully processed.', 'woocommerce-cardpay-authnet' ); ?></div>
<div class="woocommerce-error authnet-error-message"><?php esc_html_e( 'There was an error processing your request.', 'woocommerce-cardpay-authnet' ); ?></div>
<table class="shop_table shop_table_responsive credit_cards" id="credit-cards-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Card Details', 'woocommerce-cardpay-authnet' ); ?></th>
			<th><?php esc_html_e( 'Expires', 'woocommerce-cardpay-authnet' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $cards as $card ) :
			$card_meta = get_post_meta( $card->ID, '_authnet_card', true );
			$card_type = $card_meta['cardtype'];
			if ( 'American Express' === $card_type ) {
				$card_type_img = 'amex';
			} elseif ( 'Diners Club' === $card_type ) {
				$card_type_img = 'diners';
			} else {
				$card_type_img = strtolower( $card_type );
			}
			$cc_last4   = $card_meta['cc_last4'];
			$is_default = $card_meta['is_default'];
			$cc_exp     = $card_meta['expiry'];
			?>
		<tr>
			<td>
				<img src="<?php echo esc_url( WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/' . $card_type_img . '.png' ) ); ?>" alt=""/>
				<?php
					/* translators: 1: card type, 2: card last 4, 3: default */
					printf( __( '%1$s ending in %2$s %3$s', 'woocommerce-cardpay-authnet' ), $card_type, $cc_last4, 'yes' === $is_default ? '(default)' : '' );
				?>
			</td>
			<td>
				<?php
					/* translators: 1: exp month, 2: exp year */
					printf( esc_html__( '%1$s/%2$s' ), esc_html( substr( $cc_exp, 0, 2 ) ), esc_html( substr( $cc_exp, -2 ) ) );
				?>
			</td>
			<td>
				<a href="#" data-id="
				<?php
					echo esc_attr( $card->ID );
				?>
				" data-title="
				<?php
					/* translators: 1: card type, 2: card last 4 */
					printf( esc_attr__( 'Edit %1$s ending in %2$s', 'woocommerce-cardpay-authnet' ), esc_attr( $card_type ), esc_attr( $cc_last4 ) );
				?>
				" data-exp="
				<?php
					/* translators: 1: exp month, 2: exp year */
					printf( esc_attr__( '%1$s / %2$s' ), esc_attr( substr( $cc_exp, 0, 2 ) ), esc_attr( substr( $cc_exp, -2 ) ) );
				?>
				" data-default="
				<?php
					echo esc_attr( $is_default );
				?>
				" class="edit-card">
				<?php
					esc_html_e( 'Edit', 'woocommerce-cardpay-authnet' );
				?>
				</a> |
				<a href="#" data-id="<?php echo esc_attr( $card->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_card_nonce' ) ); ?>" class="delete-card"><?php esc_html_e( 'Delete', 'woocommerce-cardpay-authnet' ); ?></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<p><a href="#" class="button add-card"><?php esc_html_e( 'Add New Card', 'woocommerce-cardpay-authnet' ); ?></a></p>

<h3 class="add-card-heading"><?php esc_html_e( 'Add Credit Card', 'woocommerce-cardpay-authnet' ); ?></h3>
<h3 class="edit-card-heading"></h3>
<div id="credit-card" class="authnet-credit-card">
	<form type="post" action="", id="authnet-cc-form">
		<fieldset id="authnet-cc-fields">
			<input id="_wpnonce" type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'add_card_nonce' ) ); ?>" />
			<input id="authnet-card-id" type="hidden" name="authnet-card-id" value="" />
			<p class="form-row form-row-wide">
				<label for="authnet-card-number"><?php esc_html_e( 'Card Number ', 'woocommerce-cardpay-authnet' ); ?><span class="required">*</span></label>
				<input id="authnet-card-number" class="input-text wc-credit-card-form-card-number" type="text" maxlength="20" autocomplete="off" placeholder="•••• •••• •••• ••••" name="authnet-card-number" />
			</p>
			<p class="form-row form-row-first">
				<label for="authnet-card-expiry"><?php esc_html_e( 'Expiry (MM/YY) ', 'woocommerce-cardpay-authnet' ); ?><span class="required">*</span></label>
				<input id="authnet-card-expiry" class="input-text wc-credit-card-form-card-expiry" type="text" autocomplete="off" placeholder="MM / YY" name="authnet-card-expiry" />
			</p>
			<p class="form-row form-row-last">
				<label for="authnet-card-cvc"><?php esc_html_e( 'Card Code ', 'woocommerce-cardpay-authnet' ); ?><span class="required">*</span></label>
				<input id="authnet-card-cvc" class="input-text wc-credit-card-form-card-cvc" type="text" autocomplete="off" placeholder="CVC" name="authnet-card-cvc" />
			</p>
			<p class="form-row form-row-wide make-default">
				<label for="authnet-make-default">
					<input id="authnet-make-default" class="input-checkbox wc-credit-card-form-make-default" type="checkbox" name="authnet-make-default" />
					<span><?php esc_html_e( 'Make Default? ', 'woocommerce-cardpay-authnet' ); ?></span>
				</label>
			</p>
			<p class="form-row form-row">
				<input type="submit" value="Submit" class="button" />
				<a href="#" class="cc-form-cancel"><?php esc_html_e( 'Cancel ', 'woocommerce-cardpay-authnet' ); ?></a>
			</p>
		</fieldset>
	</form>
</div>
