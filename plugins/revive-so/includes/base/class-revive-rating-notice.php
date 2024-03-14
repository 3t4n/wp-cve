<?php 
/**
 * Rating notice.
 *
 */


defined( 'ABSPATH' ) || exit;

/**
 * Rating notice class.
 */
class REVIVESO_RatingNotice
{
	use REVIVESO_Hooker;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'admin_notices', 'show_notice', 8 );
		$this->action( 'admin_init', 'dismiss_notice', 8 );
	}
	
	/**
	 * Show admin notices.
	 */
	public function show_notice() {
		// Show notice after 240 hours (10 days) from installed time.
		if ( $this->calculate_time() > strtotime( '-7 days' )
	    	|| '1' === get_option( 'reviveso_plugin_dismiss_rating_notice' )
            || ! current_user_can( 'manage_options' )
			|| apply_filters( 'reviveso_hide_sticky_rating_notice', false ) ) {
            return;
        }
    
        $dismiss = wp_nonce_url( add_query_arg( 'revs_rating_notice', 'dismiss' ), 'revs_rating_nonce' ); 
        $later = wp_nonce_url( add_query_arg( 'revs_rating_notice', 'later' ), 'revs_rating_nonce' ); ?>

        <div class="notice notice-success">
            <p>
				<?php esc_html_e( 'Hi there! Stoked to see you\'re using Revive.so for a few days now - hope you like it! And if you do, please consider rating it. It would mean the world to us. Keep on rocking!', 'revive-so' ); ?>
			
			</p>
            <p>
				<a href="https://wordpress.org/support/plugin/revive-so/reviews/?filter=5#new-post" target="_blank" class="button button-secondary"><?php esc_html_e( 'Ok, you deserve it', 'revive-so' ); ?></a>&nbsp;
            	<a href="<?php echo esc_url( $dismiss ); ?>" class="revs-already-did"><strong><?php esc_html_e( 'I already did', 'revive-so' ); ?></strong></a>&nbsp;<strong>|</strong>
            	<a href="<?php echo esc_url( $later ); ?>" class="revs-later"><strong><?php esc_html_e( 'Nope&#44; maybe later', 'revive-so' ); ?></strong></a>
			</p>
        </div>
	<?php
	}
	
	/**
	 * Dismiss admin notices.
	 */
	public function dismiss_notice() {
		if ( get_option( 'reviveso_plugin_no_thanks_rating_notice' ) === '1' ) {
			if ( get_option( 'reviveso_plugin_dismissed_time' ) > strtotime( '-10 days' ) ) {
				return;
			}
			delete_option( 'reviveso_plugin_dismiss_rating_notice' );
			delete_option( 'reviveso_plugin_no_thanks_rating_notice' );
		}
	
		if ( ! isset( $_REQUEST['revs_rating_notice'] ) ) {
			return;
		}

		check_admin_referer( 'revs_rating_nonce' );

		if ( 'dismiss' === $_REQUEST['revs_rating_notice'] ) {
			update_option( 'reviveso_plugin_dismiss_rating_notice', '1' );
		}
	
		if ( 'later' === $_REQUEST['revs_rating_notice'] ) {
			update_option( 'reviveso_plugin_no_thanks_rating_notice', '1' );
			update_option( 'reviveso_plugin_dismiss_rating_notice', '1' );
			update_option( 'reviveso_plugin_dismissed_time', time() );
		}
	
		wp_safe_redirect( remove_query_arg( array( 'revs_rating_notice', '_wpnonce' ) ) );
		exit;
	}
	
	/**
	 * Calculate install time.
	 */
	private function calculate_time() {
		$installed_time = get_option( 'reviveso_plugin_installed_time' );
		
        if ( ! $installed_time ) {
            $installed_time = time();
            update_option( 'reviveso_plugin_installed_time', $installed_time );
        }

        return $installed_time;
	}
}