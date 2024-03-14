<?php
/**
 * Class responsible for creating notices markup.
 *
 * Author:         Uriahs Victor
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Notices
 */

namespace Lpac_DPS\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Notice.
 */
class Notice {

	/**
	 * Get the current user id
	 *
	 * @return int
	 */
	private function get_user_id() {
		return get_current_user_id();
	}

	/**
	 * Get the notice ids that have been dismissed by user.
	 *
	 * @return mixed
	 */
	private function get_dismissed_notices() {
		return get_user_meta( $this->get_user_id(), 'lpac_dps_dismissed_notices', true );
	}

	/**
	 * Create the dismiss URL for a notice.
	 *
	 * @param string $notice_id The ID of the particular notice.
	 * @return string
	 */
	protected function create_dismiss_url( string $notice_id ) {

		if ( ! function_exists( 'wp_create_nonce' ) ) {
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}
		$nonce = wp_create_nonce( 'lpac_dps_notice_nonce_value' );

		return admin_url( 'admin-ajax.php?action=lpac_dps_dismiss_notice&lpac_dps_notice_id=' . $notice_id . '&lpac_dps_notice_nonce=' . $nonce );
	}

	/**
	 * Create the markup for a notice
	 *
	 * @param string $notice_id The ID of the particular notice.
	 * @param array  $content The content to add to the notice.
	 * @return string
	 */
	protected function create_notice_markup( string $notice_id, array $content ) {

		// Only show the Notice to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$dismissed_notices = $this->get_dismissed_notices();

		// Bail if this notice has been dismissed.
		if ( is_array( $dismissed_notices ) && in_array( $notice_id, $dismissed_notices, true ) ) {
			return;
		}

		$title           = $content['title'] ?? '';
		$body            = $content['body'] ?? '';
		$cta_text        = esc_html( $content['cta'] ?? __( 'Learn more', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
		$learn_more_link = esc_attr( $content['link'] ?? '' );

		$dismiss_url  = esc_html( $this->create_dismiss_url( $notice_id ) );
		$dismiss_text = esc_html__( 'Dismiss', 'delivery-and-pickup-scheduling-for-woocommerce' );
		?>
			<div class="update-nag lpac-dps-admin-notice">
			<div class="lpac-dps-notice-logo"></div> 
			<p class="lpac-dps-notice-title"><?php echo esc_html( $title ); ?></p> 
			<p class="lpac-dps-notice-body"><?php echo esc_html( $body ); ?></p>
			<ul class="lpac-dps-notice-body">
			<?php if ( ! empty( $learn_more_link ) ) { ?>
				<li id='lpac-dps-notice-cta'><a target='_blank' href='<?php echo esc_url( $learn_more_link ); ?>' style='color: #2b4fa3'><span class='dashicons dashicons-share-alt2'></span><?php echo esc_html( $cta_text ); ?></a></li>
			<?php } ?>
			<li id="lpac-dps-notice-dismiss"><a href="<?php echo esc_url( $dismiss_url ); ?>" style="color: #2b4fa3"> <span class="dashicons dashicons-dismiss"></span><?php echo esc_html( $dismiss_text ); ?></a></li>
			</ul>
			</div>
		<?php
	}

	/**
	 * Get the ID of a notice from the URL.
	 *
	 * @return mixed
	 */
	protected function get_notice_id() {

		$notice_id = sanitize_text_field( wp_unslash( $_REQUEST['lpac_dps_notice_id'] ?? '' ) );

		if ( empty( $notice_id ) ) {
			return;
		}

		return $notice_id;
	}

	/**
	 * Dismiss a notice so it doesn't show again.
	 *
	 * @return void
	 */
	public function dismiss_notice() {

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['lpac_dps_notice_nonce'] ?? '' ) ), 'lpac_dps_notice_nonce_value' ) ) {
			exit( 'Failed to verify nonce. Please try going back and refreshing the page to try again.' );
		}

		$notice_id = $this->get_notice_id();

		if ( ! empty( $notice_id ) ) {

			$dismissed_notices = $this->get_dismissed_notices();

			if ( empty( $dismissed_notices ) ) {
				$dismissed_notices = array();
			}

			// Add our new notice ID to the currently dismissed ones.
			array_push( $dismissed_notices, $notice_id );

			$dismissed_notices = array_unique( $dismissed_notices );

			update_user_meta( $this->get_user_id(), 'lpac_dps_dismissed_notices', $dismissed_notices );

			wp_safe_redirect( sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ?? get_admin_url() ) ) );
			exit;

		}
	}
}
