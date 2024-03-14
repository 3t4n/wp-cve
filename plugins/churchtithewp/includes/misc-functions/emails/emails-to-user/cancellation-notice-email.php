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
 * Send the cancellation notice to the user when their subscription has been cancelled.
 *
 * @access      public
 * @since       1.0.0.
 * @param       object $arrangement The arrangement being cancelled.
 * @return      bool
 */
function church_tithe_wp_send_cancellation_notice_email( $arrangement ) {

	if ( ! $arrangement ) {
		return false;
	}

	// Get the user object.
	$user = get_user_by( 'id', $arrangement->user_id );

	$email_from = get_bloginfo( 'admin_email' );
	$email_to   = $user->user_email;
	// translators: The name of the site. The arrangement ID.
	$email_subject = sprintf( __( 'Your plan has been cancelled on %1$s. Plan ID: %2$s', 'church-tithe-wp' ), get_bloginfo( 'name' ), $arrangement->id );

	$email_message = church_tithe_wp_get_html_cancellation( $arrangement );

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// 'From: ' . get_bloginfo( 'name' ) . ' <' . $email_from . '>',
	);

	// Send an email receipt to the purchaser.
	$email_sent = wp_mail( $email_to, $email_subject, $email_message, $email_headers );

	return $email_sent;
}

/**
 * Get the standalone HTML for a cancellation notice email
 *
 * @param       object $arrangement The arrangement being cancelled.
 * @return      bool
 */
function church_tithe_wp_get_html_cancellation( $arrangement ) {

	if ( ! $arrangement->id ) {
		return false;
	}

	switch ( $arrangement->status_reason ) {

		// A subscription was successfully cancelled by the user.
		case 'cancelled_by_user':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'You have successfully cancelled your plan (id: %s). You will no longer be charged for it.', 'church-tithe-wp' ), $arrangement->id );
			break;

		// A subscription was successfully cancelled because of a refunded transaction.
		case 'refunded':
			// translators: The id of the plan (arrangement) being cancelled.
				$cancellation_string = sprintf( __( 'Along with your refund, your plan (id: %s) has been cancelled. You will no longer be charged for it.', 'church-tithe-wp' ), $arrangement->id );
			break;

		// A subscription was successfully cancelled by the user.
		case 'payment_failure':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your credit card was unable to be charged for plan (id: %s). It has been cancelled and you will no longer be charged for it.', 'church-tithe-wp' ), $arrangement->id );
			break;

		case 'general':
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) has been cancelled and you will no longer be charged for it.', 'church-tithe-wp' ), $arrangement->id );
			break;

		default:
			// translators: The id of the plan (arrangement) being cancelled.
			$cancellation_string = sprintf( __( 'Your plan (id: %s) has been cancelled and you will no longer be charged for it.', 'church-tithe-wp' ), $arrangement->id );
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
		><?php echo esc_textarea( __( 'Your plan has been cancelled.', 'church-tithe-wp' ) ); ?></div>
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
					<?php echo esc_textarea( __( 'View plan details', 'church-tithe-wp' ) ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
	$body = ob_get_clean();
	return church_tithe_wp_get_html_email( $body );
}
