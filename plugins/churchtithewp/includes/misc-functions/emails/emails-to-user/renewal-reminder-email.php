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
 * Send the upcoming renewal reminder for a subscription
 *
 * @since  1.0.0.
 * @param  object $arrangement The arrangement that will be renewing.
 * @param  string $renewal_time The time of the renewal, when it will happen.
 * @param  int    $amount_to_be_charged The amount that will be charged at renewal time.
 * @return bool
 */
function church_tithe_wp_send_renewal_reminder_email( $arrangement, $renewal_time, $amount_to_be_charged ) {

	if ( ! $arrangement ) {
		return false;
	}

	// Get the user object.
	$user = get_user_by( 'id', $arrangement->user_id );

	$email_from = get_bloginfo( 'admin_email' );
	$email_to   = $user->user_email;
	// translators: The name of the site. The arrangement ID.
	$email_subject = sprintf( __( 'Your subscription is about to renew for %1$s. Plan ID: %2$s.', 'church-tithe-wp' ), get_bloginfo( 'name' ), $arrangement->id );

	$email_message = church_tithe_wp_get_html_reminder( $arrangement, $renewal_time, $amount_to_be_charged );

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// 'From: ' . get_bloginfo( 'name' ) . ' <' . $email_from . '>',
	);

	// Send an email receipt to the purchaser.
	$email_sent = wp_mail( $email_to, $email_subject, $email_message, $email_headers );

	return $email_sent;
}

/**
 * Get the standalone HTML for an upcoming reminder
 *
 * @since       1.0.0.
 * @param  object $arrangement The arrangement that will be renewing.
 * @param  string $renewal_date The time of the renewal, when it will happen.
 * @param  int    $amount_to_be_charged The amount that will be charged at renewal time.
 * @return      bool
 */
function church_tithe_wp_get_html_reminder( $arrangement, $renewal_date, $amount_to_be_charged ) {

	if ( ! $arrangement->id ) {
		return false;
	}

	$user                = get_user_by( 'id', $arrangement->user_id );
	$initial_transaction = new Church_Tithe_WP_Transaction( $arrangement->initial_transaction_id );

	$saved_settings   = get_option( 'church_tithe_wp_settings' );
	$image            = church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_image' );
	$date_format      = get_option( 'date_format' );
	$formatted_amount = church_tithe_wp_get_visible_amount( $amount_to_be_charged, $arrangement->currency ) . ' (' . strtoupper( $arrangement->currency ) . ')';

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
		><?php echo esc_textarea( __( 'Just a quick reminder', 'church-tithe-wp' ) ); ?></div>
		<div class="church-tithe-wp-receipt-email" style="
			margin-bottom: 15px;"
		>
		<?php
		// translators: The id of the subscription/plan arrangement.
		echo esc_textarea( sprintf( __( 'Your Plan (Plan ID: %1$s) is set to automatically renew on %2$s for %3$s.', 'church-tithe-wp' ), $arrangement->id, date( $date_format, $renewal_date ), $formatted_amount ) );
		?>
		</div>
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
					<?php echo esc_textarea( __( 'Manage plan', 'church-tithe-wp' ) ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
	$body = ob_get_clean();
	return church_tithe_wp_get_html_email( $body );
}
