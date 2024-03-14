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
 * @param       object $transaction The transaction for which this receipt is being emailed.
 * @return      bool
 */
function church_tithe_wp_send_receipt_email( $transaction ) {

	if ( ! $transaction ) {
		return false;
	}

	// Get the user object.
	$user = get_user_by( 'id', $transaction->user_id );

	$email_from = get_bloginfo( 'admin_email' );
	$email_to   = $user->user_email;
	// translators: The name of the site. The transaction ID.
	$email_subject = sprintf( __( 'Your transaction receipt from %1$s. Transaction ID: %2$s', 'church-tithe-wp' ), get_bloginfo( 'name' ), $transaction->id );

	$email_message = church_tithe_wp_get_html_receipt( $transaction );

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// 'From: ' . get_bloginfo( 'name' ) . ' <' . $email_from . '>',
	);

	// Send an email receipt to the purchaser.
	$email_sent = wp_mail( $email_to, $email_subject, $email_message, $email_headers );

	return $email_sent;
}

/**
 * Get the standalone HTML for a receipt. Useful for emails.
 *
 * @access      public
 * @since       1.0.0.
 * @param       object $transaction $transaction The transaction for which this receipt is being emailed.
 * @return      bool
 */
function church_tithe_wp_get_html_receipt( $transaction = null ) {

	if ( ! $transaction->id ) {
		return false;
	}

	$user = get_user_by( 'id', $transaction->user_id );

	$saved_settings = get_option( 'church_tithe_wp_settings' );
	$image          = church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_image' );

	// Fetch the transaction and arrangement objects fresh.
	$transaction = new Church_Tithe_WP_Transaction( $transaction->id );
	$arrangement = new Church_Tithe_WP_Arrangement( $transaction->arrangement_id );

	// If this is a recurring plan...
	if ( ! empty( $arrangement->renewal_amount ) && ! empty( $arrangement->interval_string ) ) {
		$visible_plan_amount = church_tithe_wp_get_visible_amount( $arrangement->renewal_amount, $arrangement->currency );
		$plan_amount         = __( 'This transaction is part of an automatically recurring plan:', 'church-tithe-wp' ) . ' ' . $visible_plan_amount . ' ' . __( 'per', 'church-tithe-wp' ) . ' ' . $arrangement->interval_string;
	} else {
		$plan_amount = __( 'This transaction is a single, one-time transaction, not part of an automatically recurring plan.', 'church-tithe-wp' );
	}

	ob_get_clean();
	ob_start();

	?>
	<div class="church-tithe-wp-confirmation-message">
	<?php
	echo esc_textarea( church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_thank_you_message' ) );
	?>
	</div>
	<div class="church-tithe-wp-receipt" style="margin: 30px 0px 0px 0px;">
		<div class="church-tithe-wp-receipt-title" style="
		font-size: 17px;
		line-height: 18px;
		font-weight: 700;
		color: #000;
		text-shadow: 0 1px 0 #fff;"
		><?php echo esc_textarea( __( 'Your Receipt', 'church-tithe-wp' ) ); ?></div>
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
			><?php echo esc_textarea( __( 'Paid to:', 'church-tithe-wp' ) ); ?> </span>
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
		<div class="church-tithe-wp-receipt-amount">
			<span class="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-arrangement-amount-title" style="
			margin: 0;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;"
			><?php echo esc_textarea( __( 'Plan:', 'church-tithe-wp' ) ); ?> </span>
			<span class="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-arrangement-amount-value">
			<?php
			// Show the plan amount if a plan exists.
			if ( ! empty( $plan_amount ) ) {
				echo esc_textarea( $plan_amount );
			}
			?>
			</span>
		</div>
		<div class="church-tithe-wp-receipt-statement-descriptor">
			<span class="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-transaction-statement-descriptor-title" style="
			margin: 0;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;"
			><?php echo esc_textarea( __( 'This will show up on your statement as:', 'church-tithe-wp' ) ); ?> </span>
			<span class="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-transaction-statement-descriptor">
			<?php
			echo esc_textarea( $transaction->statement_descriptor );
			?>
			</span>
		</div>
		<div>
			<p>
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'ctwp1'     => 'manage_payments',
							'ctwp2'     => 'transaction',
							'ctwp3'     => $transaction->id,
							'ctwpmodal' => '1',
						),
						$transaction->page_url
					)
				);
				?>
					">
					<?php echo esc_textarea( __( 'View full transaction details', 'church-tithe-wp' ) ); ?>
				</a>
			</p>
			<p>
				<?php
				// Show the plan link, if a plan exists for this transaction.
				if (
					! empty( $plan_amount ) &&
					! empty( $arrangement->renewal_amount ) &&
					! empty( $arrangement->interval_string )
				) {
					?>
					<a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'ctwp1'     => 'manage_payments',
								'ctwp2'     => 'arrangement',
								'ctwp3'     => $arrangement->id,
								'ctwpmodal' => '1',
							),
							$transaction->page_url
						)
					);
					?>
						">
						<?php echo esc_textarea( __( 'View plan details', 'church-tithe-wp' ) ); ?>
					</a>
					<?php
				}
				?>
			</p>
		</div>
	</div>
	<?php
	$body = ob_get_clean();
	return church_tithe_wp_get_html_email( $body );
}
