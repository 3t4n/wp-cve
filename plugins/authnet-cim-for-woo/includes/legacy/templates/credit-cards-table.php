<?php
/**
 * Credit cards table template file.
 *
 * @package Authorize.Net CIM for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

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
				<a href="#" data-id="<?php echo esc_attr( $card->ID ); ?>" data-title="
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
				<a href="#" data-id="
				<?php
					echo esc_attr( $card->ID );
				?>
				" data-nonce="
				<?php
					echo esc_attr( wp_create_nonce( 'delete_card_nonce' ) );
				?>
				" class="delete-card">
				<?php
					esc_html_e( 'Delete', 'woocommerce-cardpay-authnet' );
				?>
				</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
