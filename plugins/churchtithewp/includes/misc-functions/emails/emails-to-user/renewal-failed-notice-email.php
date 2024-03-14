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
 * Send a "Renewal Failed" email to the user when their subscription has a failed invoice.
 *
 * @access      public
 * @since       1.0.0.
 * @param       object $arrangement The arrangement attempting to be renewed.
 * @return      bool
 */
function church_tithe_wp_send_renewal_failed_email( $arrangement ) {

	if ( ! $arrangement ) {
		return false;
	}

	// Get the user object.
	$user = get_user_by( 'id', $arrangement->user_id );

	$email_from = get_bloginfo( 'admin_email' );
	$email_to   = $user->user_email;
	// translators: The name of the site. The arrangement ID.
	$email_subject = sprintf( __( 'Your plan could not be paid %1$s. Plan ID: %2$s', 'church-tithe-wp' ), get_bloginfo( 'name' ), $arrangement->id );

	$email_message = church_tithe_wp_get_html_renewal_failed( $arrangement );

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// 'From: ' . get_bloginfo( 'name' ) . ' <' . $email_from . '>',
	);

	// Send the email to the purchaser.
	$email_sent = wp_mail( $email_to, $email_subject, $email_message, $email_headers );

	return $email_sent;
}

/**
 * Get the standalone HTML for a "renewal failed" email notification.
 *
 * @param       object $arrangement The arrangement being cancelled.
 * @return      bool
 */
function church_tithe_wp_get_html_renewal_failed( $arrangement ) {

	if ( ! $arrangement->id ) {
		return false;
	}

	$action_text = __( 'View plan details', 'church-tithe-wp' );

	switch ( $arrangement->status_reason ) {

		// It could not be renewed because there was a card error.
		case 'card_declined':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) could not be paid due to your card being declined. If needed, you can log in below to update your card.', 'church-tithe-wp' ), $arrangement->id );
			$action_text         = __( 'Update Card Details', 'church-tithe-wp' );
			break;

		// It could not be renewed because there was a card error.
		case 'card_error':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) could not be paid due to a card error. If needed, you can log in below to update your card.', 'church-tithe-wp' ), $arrangement->id );
			$action_text         = __( 'Update Card Details', 'church-tithe-wp' );
			break;

		// Another payment failure took place.
		case 'payment_failure':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) could not be paid. If needed, you can log in below to update your payment method.', 'church-tithe-wp' ), $arrangement->id );
			$action_text         = __( 'Update Payment Method', 'church-tithe-wp' );
			break;

		case 'unknown':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) could not be paid due to an error. If needed, you can log in below to update your payment method.', 'church-tithe-wp' ), $arrangement->id );
			$action_text         = __( 'Update Payment Method', 'church-tithe-wp' );
			break;

		// It could not be renewed because a PaymentIntent needed to be confirmed.
		case 'authentication_required':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your payment needs to be confirmed before your plan (id: %s) can be renewed. Click the link below to log in and confirm.', 'church-tithe-wp' ), $arrangement->id );
			$action_text         = __( 'Log in', 'church-tithe-wp' );
			break;

		default:
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) could not be paid due to an error. If needed, you can log in below to update your payment method.', 'church-tithe-wp' ), $arrangement->id );
			$action_text         = __( 'Update Payment Method', 'church-tithe-wp' );
			break;
	}

	$user                = get_user_by( 'id', $arrangement->user_id );
	$initial_transaction = new Church_Tithe_WP_Transaction( $arrangement->initial_transaction_id );

	$saved_settings = get_option( 'church_tithe_wp_settings' );
	$image          = church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_image' );

	ob_get_clean();
	ob_start();

	?>
	<div class="church-tithe-wp-receipt" style="margin: 30px 0px 0px 0px;">
		<div class="church-tithe-wp-receipt-title" style="
			font-size: 17px;
			line-height: 18px;
			font-weight: 700;
			color: #000;
			text-shadow: 0 1px 0 #fff;
			margin-bottom:10px;"
		><?php echo esc_textarea( __( 'Your plan could not be renewed!', 'church-tithe-wp' ) ); ?></div>
		<div class="church-tithe-wp-receipt-email" style="
			margin-bottom: 15px;"
		><?php echo esc_textarea( $cancellation_string ); ?></div>
		<div>
			<p>
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
						$initial_transaction->page_url
					)
				);
				?>
					">
					<?php echo esc_textarea( $action_text ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
	$body = ob_get_clean();
	return church_tithe_wp_get_html_email( $body );
}
