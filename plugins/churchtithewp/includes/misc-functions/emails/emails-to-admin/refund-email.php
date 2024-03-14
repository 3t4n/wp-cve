<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Send the email receipt for a transaction.
 *
 * @access      public
 * @since       1.0.0.
 * @param       object $transaction The transaction being refunded.
 * @return      bool
 */
function church_tithe_wp_send_refund_email_to_admin( $transaction ) {

	if ( ! $transaction ) {
		return false;
	}

	// Get the user object.
	$user = get_user_by( 'id', $transaction->user_id );

	$email_from = get_bloginfo( 'admin_email' );
	$email_to   = get_bloginfo( 'admin_email' );
	// translators: The name of the site. The transaction ID.
	$email_subject = sprintf( __( 'A refund has been given on %1$s. Transaction ID: %2$s', 'church-tithe-wp' ), get_bloginfo( 'name' ), $transaction->id );

	$email_message = church_tithe_wp_get_html_refund_for_admin( $transaction );

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// 'From: ' . get_bloginfo( 'name' ) . ' <' . $email_from . '>',
	);

	// Send an email receipt to the purchaser.
	$email_sent = wp_mail( $email_to, $email_subject, $email_message, $email_headers );

	return $email_sent;
}

/**
 * Get the standalone HTML for a refund receipt. Useful for emails.
 *
 * @access      public
 * @since       1.0.0.
 * @param       object $transaction The transaction being refunded.
 * @return      bool
 */
function church_tithe_wp_get_html_refund_for_admin( $transaction = null ) {

	if ( ! $transaction->id ) {
		return false;
	}

	$user = get_user_by( 'id', $transaction->user_id );

	ob_get_clean();
	ob_start();

	?>
	<div class="church-tithe-wp-receipt" style="margin: 30px 0px 0px 0px;">
		<div class="church-tithe-wp-receipt-title" style="
		font-size: 17px;
		line-height: 18px;
		font-weight: 700;
		color: #000;
		text-shadow: 0 1px 0 #fff;"
		><?php echo esc_textarea( __( 'A refund was given', 'church-tithe-wp' ) ); ?></div>
		<div class="church-tithe-wp-receipt-email" style="
		margin-bottom: 15px;"
		><?php echo esc_textarea( $user->user_email ); ?></div>
		<div class="church-tithe-wp-receipt-payee">
			<span class="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-payee-title" style="
			margin: 0;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;"
			><?php echo esc_textarea( __( 'Refund from:', 'church-tithe-wp' ) ); ?> </span>
			<span class="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-payee-value"><?php echo esc_textarea( get_bloginfo( 'Name' ) ); ?></span>
		</div>
		<div class="church-tithe-wp-receipt-transaction-id">
			<span class="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-transaction-id-title" style="
			margin: 0;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;"
			><?php echo esc_textarea( __( 'Transaction ID:', 'church-tithe-wp' ) ); ?> </span>
			<span class="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-transaction-id-value"><?php echo esc_textarea( $transaction->id ); ?></span>
		</div>
		<div class="church-tithe-wp-receipt-transaction-date">
			<span class="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-date-title" style="
			margin: 0;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;"
			><?php echo esc_textarea( __( 'Date:', 'church-tithe-wp' ) ); ?> </span>
			<span class="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-date-value"><?php echo esc_textarea( $transaction->date_created ); ?></span>
		</div>
		<div class="church-tithe-wp-receipt-amount">
			<span class="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-transaction-amount-title" style="
			margin: 0;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;"
			><?php echo esc_textarea( __( 'Amount:', 'church-tithe-wp' ) ); ?> </span>
			<span class="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-transaction-amount-value">
			<?php
			echo esc_textarea( church_tithe_wp_get_visible_amount( $transaction->charged_amount, $transaction->charged_currency ) );
			echo ' ';
			echo esc_textarea( strtoupper( $transaction->charged_currency ) );
			?>
			</span>
		</div>
		<div>
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=transactions&mpwpadmin2=single_data_view&mpwpadmin3=' . $transaction->id ) ); ?>">
					<?php echo esc_textarea( __( 'View transaction details', 'church-tithe-wp' ) ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
	$body = ob_get_clean();
	return church_tithe_wp_get_html_email( $body );
}
