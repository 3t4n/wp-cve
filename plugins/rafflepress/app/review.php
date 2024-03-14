<?php
/**
 * Ask for some love.
 *
 * @package    RafflePress
 * @author     RafflePress
 * @since      1.1.3
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, SeedProd LLC
 */
class rafflepress_lite_Review {
	/**
	 * Primary class constructor.
	 *
	 * @since 7.0.7
	 */
	public function __construct() {
		// Admin notice requesting review.
		add_action( 'admin_notices', array( $this, 'review_request' ) );
		add_action( 'wp_ajax_rafflepress_review_dismiss', array( $this, 'review_dismiss' ) );
	}
	/**
	 * Add admin notices as needed for reviews.
	 *
	 * @since 7.0.7
	 */
	public function review_request() {
		// Only consider showing the review request to admin users.
		if ( ! is_super_admin() ) {
			return;
		}

		// If the user has opted out of product annoucement notifications, don't
		// display the review request.
		if ( get_option( 'rafflepress_hide_review' ) ) {
			return;
		}
		// Verify that we can do a check for reviews.
		$review = get_option( 'rafflepress_review' );
		$time   = time();
		$load   = false;

		if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			update_option( 'rafflepress_review', $review );
		} else {
			// Check if it has been dismissed or not.
			if ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + DAY_IN_SECONDS ) <= $time ) ) ) {
				$load = true;
			}
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}

		$this->review();
	}

	/**
	 * Maybe show review request.
	 *
	 * @since 7.0.7
	 */
	public function review() {
		// Fetch when plugin was initially installed.
		$activated = get_option( 'rafflepress_over_time', array() );
		if ( ! empty( $activated['installed_date'] ) ) {
			//Only continue if plugin has been installed for at least 1 days.
			if ( ( $activated['installed_date'] + ( DAY_IN_SECONDS * 1 ) ) > time() ) {
				return;
			}
			if ( ! empty( $activated['installed_version'] ) && version_compare( $activated['installed_version'], '1.5' ) < 0 ) {
				return;
			}
		} else {
			$data = array(
				'installed_version' => RAFFLEPRESS_VERSION,
				'installed_date'    => time(),
			);

			update_option( 'rafflepress_over_time', $data );
			return;
		}

		$feedback_url = 'https://www.rafflepress.com/plugin-feedback/?utm_source=liteplugin&utm_medium=review-notice&utm_campaign=feedback&utm_content=' . RAFFLEPRESS_VERSION;
		// We have a candidate! Output a review message.
		?>
		<div class="notice notice-info is-dismissible rafflepress-review-notice">
			<div class="rafflepress-review-step rafflepress-review-step-1">
				<p><?php esc_html_e( 'Are you enjoying RafflePress?', 'rafflepress' ); ?></p>
				<p>
					<a href="#" class="rafflepress-review-switch-step" data-step="3"><?php esc_html_e( 'Yes', 'rafflepress' ); ?></a><br />
					<a href="#" class="rafflepress-review-switch-step" data-step="2"><?php esc_html_e( 'Not Really', 'rafflepress' ); ?></a>
				</p>
			</div>
			<div class="rafflepress-review-step rafflepress-review-step-2" style="display: none">
				<p><?php esc_html_e( 'We\'re sorry to hear you aren\'t enjoying RafflePress. We would love a chance to improve. Could you take a minute and let us know what we can do better?', 'rafflepress' ); ?></p>
				<p>
					<a href="<?php echo esc_url( $feedback_url ); ?>" class="rafflepress-dismiss-review-notice rafflepress-review-out"><?php esc_html_e( 'Give Feedback', 'rafflepress' ); ?></a><br>
					<a href="#" class="rafflepress-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'No thanks', 'rafflepress' ); ?></a>
				</p>
			</div>
			<div class="rafflepress-review-step rafflepress-review-step-3" style="display: none">
				<p><?php esc_html_e( 'That’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'rafflepress' ); ?></p>
				<p><strong><?php echo wp_kses( __( '~ John Turner<br>Co-Founder of RafflePress', 'rafflepress' ), array( 'br' => array() ) ); ?></strong></p>
				<p>
					<a href="https://wordpress.org/support/plugin/rafflepress/reviews/?filter=5#new-post" class="rafflepress-dismiss-review-notice rafflepress-review-out" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ok, you deserve it', 'rafflepress' ); ?></a><br>
					<a href="#" class="rafflepress-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Nope, maybe later', 'rafflepress' ); ?></a><br>
					<a href="#" class="rafflepress-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'I already did', 'rafflepress' ); ?></a>
				</p>
			</div>
		</div>
		<script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				$( document ).on( 'click', '.rafflepress-dismiss-review-notice, .rafflepress-review-notice button', function ( event ) {
					if ( ! $( this ).hasClass( 'rafflepress-review-out' ) ) {
						event.preventDefault();
					}
					$.post( ajaxurl, {
						action: 'rafflepress_review_dismiss'
					} );
					$( '.rafflepress-review-notice' ).remove();
				} );

				$( document ).on( 'click', '.rafflepress-review-switch-step', function ( e ) {
					e.preventDefault();
					var target = $( this ).attr( 'data-step' );
					if ( target ) {
						var notice = $( this ).closest( '.rafflepress-review-notice' );
						var review_step = notice.find( '.rafflepress-review-step-' + target );
						if ( review_step.length > 0 ) {
							notice.find( '.rafflepress-review-step:visible').fadeOut( function (  ) {
								review_step.fadeIn();
							});
						}
					}
				})
			} );
		</script>
		<?php
	}
	/**
	 * Dismiss the review admin notice
	 *
	 * @since 7.0.7
	 */
	public function review_dismiss() {
		$review              = get_option( 'rafflepress_review', array() );
		$review['time']      = time();
		$review['dismissed'] = true;
		update_option( 'rafflepress_review', $review );
		die;
	}
}
new rafflepress_lite_Review();
