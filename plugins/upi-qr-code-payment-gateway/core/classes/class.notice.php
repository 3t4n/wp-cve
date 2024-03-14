<?php
class DWU_Notice {
	function __construct() {
		add_action( 'admin_notices', array( $this, 'dwu_sample_admin_notice_success' ), 1 );
		add_action( 'admin_notices', array( $this, 'dwu_rating_admin_notice' ) );
		add_action( 'admin_init', array( $this, 'dwu_dismiss_rating_admin_notice' ) );
	}

	function dwu_sample_admin_notice_success() {
		// Show a warning to sites running PHP < 5.6
		if( version_compare( PHP_VERSION, '5.6', '<' ) ) {
			echo '<div class="error"><p>' . __( 'Your version of PHP is below the minimum version of PHP required by Woo UPI QR Code Payment Gateway plugin. Please contact your host and request that your version be upgraded to 5.6 or later.', 'dew-upi-qr-code' ) . '</p></div>';
		}
		if( get_transient( 'dwu-admin-notice-on-activation' ) ) { ?>
			<div class="notice notice-success">
				<p><strong><?php printf( __( 'Thanks for installing %1$s v%2$s plugin. Click <a href="%3$s">here</a> to configure plugin settings.', 'dew-upi-qr-code' ), 'Woo UPI QR Code Payment Gateway', DEW_WOO_UPI_VERSION, admin_url( 'admin.php?page=wc-settings&tab=checkout&section=dew-wc-upi' ) ); ?></strong></p>
			</div> <?php
			delete_transient( 'dwu-admin-notice-on-activation' );
		}
	}

	function dwu_rating_admin_notice() {
		// Show notice after 240 hours (10 days) from installed time.
		if ( $this->dwu_plugin_get_installed_time() > strtotime( '-240 hours' )
			|| '1' === get_option( 'dwu_dismiss_rating_notice' )
			|| !current_user_can( 'manage_options' )
			|| apply_filters( 'dwu_hide_sticky_notice', false ) ) {
			return;
		}

		$dismiss = wp_nonce_url( add_query_arg( 'dwu_rating_notice_action', 'dismiss_rating_true' ), 'dwu_dismiss_rating_true' ); 
		$no_thanks = wp_nonce_url( add_query_arg( 'dwu_rating_notice_action', 'no_thanks_rating_true' ), 'dwu_no_thanks_rating_true' ); ?>
    
		<div class="notice notice-success">
			<p><?php _e( 'Hey, I noticed you\'ve been using UPI QR Code Payment Gateway for WooCommerce for more than 2 week – that’s awesome! Could you please do me a BIG favor and give it a <strong>5-star</strong> rating on WordPress? Just to help me spread the word and boost my motivation.', 'dew-upi-qr-code' ); ?></p>
			<p><a href="https://wordpress.org/support/plugin/upi-qr-code-payment-gateway/reviews/?filter=5#new-post" target="_blank" class="button button-secondary"><?php _e( 'Ok, you deserve it', 'dew-upi-qr-code' ); ?></a>&nbsp;
			<a href="<?php echo $dismiss; ?>" class="already-did"><strong><?php _e( 'I already did', 'dew-upi-qr-code' ); ?></strong></a>&nbsp;<strong>|</strong>
			<a href="<?php echo $no_thanks; ?>" class="later"><strong><?php _e( 'Nope&#44; maybe later', 'dew-upi-qr-code' ); ?></strong></a></p>
		</div>
		<?php
	}

	function dwu_dismiss_rating_admin_notice() {
		if( get_option( 'dwu_no_thanks_rating_notice' ) === '1' ) {
			if ( get_option( 'dwu_dismissed_time' ) > strtotime( '-360 hours' ) ) {
				return;
			}
			delete_option( 'dwu_dismiss_rating_notice' );
			delete_option( 'dwu_no_thanks_rating_notice' );
		}

		if ( !isset( $_GET['dwu_rating_notice_action'] ) ) {
			return;
		}

		if ( 'dismiss_rating_true' === $_GET['dwu_rating_notice_action'] ) {
			check_admin_referer( 'dwu_dismiss_rating_true' );
			update_option( 'dwu_dismiss_rating_notice', '1' );
		}

		if ( 'no_thanks_rating_true' === $_GET['dwu_rating_notice_action'] ) {
			check_admin_referer( 'dwu_no_thanks_rating_true' );
			update_option( 'dwu_no_thanks_rating_notice', '1' );
			update_option( 'dwu_dismiss_rating_notice', '1' );
			update_option( 'dwu_dismissed_time', time() );
		}

		wp_redirect( remove_query_arg( 'dwu_rating_notice_action' ) );
		exit;
	}

	function dwu_plugin_get_installed_time() {
		$installed_time = get_option( 'dwu_installed_time' );
		if ( ! $installed_time ) {
			$installed_time = time();
			update_option( 'dwu_installed_time', $installed_time );
		}
		return $installed_time;
	}
}
global $dwu_notice;
$dwu_notice = new DWU_Notice();