<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRGRGM_Review {

    public function __construct() {

        // Admin notice requesting review.
		add_action( 'admin_notices', array( $this, 'review_request' ) );
		add_action( 'wp_ajax_wrg_rgm_review_dismiss', array( $this, 'review_dismiss' ) );

		// Admin footer text.
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
    }

    public function review_request() {

        // Only consider showing the review request to admin users.
		if ( ! is_super_admin() ) {
			return;
        }
        
        // Verify that we can do a check for reviews.
		$review = get_option( 'wrg_rgm_review' );
		$time   = time();
        $load   = false;
        
        if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			update_option( 'wrg_rgm_review', $review );
		} else {
			// Check if it has been dismissed or not.
			if ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + DAY_IN_SECONDS ) <= $time ) ) ) {
				$load = true;
			}
        }
        
        // // If we cannot load, return early.
		if ( ! $load ) {
			return;
        }
        
        $this->review();
    }

    public function review() {

        // Fetch when plugin was initially installed.
		$activated = get_option( 'wrg_rgm_activated' );

		if ( ! empty( $activated ) ) {
			// Only continue if plugin has been installed for at least 2 days.
			if ( ( $activated + ( DAY_IN_SECONDS * 2 ) ) > time() ) {
				return;
			}
		} else {
			$activated = time();
			update_option( 'wrg_rgm_activated', $activated );

			return;
        }
        
        // Only proceed with displaying if the user created at least one map.
		$map_count = wp_count_posts( 'wrg_rgm' );
		if ( empty( $map_count->publish ) ) {
			return;
        }
        ?>
        <div class="notice notice-info is-dismissible wrg-rgm-review-notice">
			<p><?php esc_html_e( 'Hey, I noticed you created a map with RGM Maps - that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'wrg_rgm' ); ?></p>
			<p><strong><?php echo wp_kses( __( '~ Jogesh', 'wrg_rgm' ), array( 'br' => array() ) ); ?></strong></p>
			<p>
				<a href="https://wordpress.org/support/plugin/responsive-google-map/reviews/?filter=5#new-post" class="wrg-rgm-dismiss-review-notice wrg-rgm-review-out" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ok, you deserve it', 'wrg_rgm' ); ?></a><br>
				<a href="#" class="wrg-rgm-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Nope, maybe later', 'wrg_rgm' ); ?></a><br>
				<a href="#" class="wrg-rgm-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'I already did', 'wrg_rgm' ); ?></a>
			</p>
        </div>
        <script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				$( document ).on( 'click', '.wrg-rgm-dismiss-review-notice, .wrg-rgm-review-notice button', function ( event ) {
					if ( ! $( this ).hasClass( 'wrg-rgm-review-out' ) ) {
						event.preventDefault();
					}
					$.post( '<?php echo admin_url( 'admin-ajax.php' ) ?>', {
						action: 'wrg_rgm_review_dismiss'
					} );
					$( '.wrg-rgm-review-notice' ).remove();
				} );
			} );
		</script>
        <?php
    }

    public function review_dismiss() {
        $review              = get_option( 'wrg_rgm_review', array() );
		$review['time']      = time();
		$review['dismissed'] = true;

		update_option( 'wrg_rgm_review', $review );
		die;
    }

    public function admin_footer( $text ) {
        global $current_screen;

		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'wrg_rgm' ) !== false ) {
			$url  = 'https://wordpress.org/support/plugin/responsive-google-map/reviews/?filter=5#new-post';
			$text = sprintf(
				wp_kses(
					/* translators: $1$s - RGM Maps plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
					__( 'Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the RGM Maps team!', 'wrg_rgm' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>RGM Maps</strong>',
				$url,
				$url
			);
		}

		return $text;
    }
}

new WRGRGM_Review;