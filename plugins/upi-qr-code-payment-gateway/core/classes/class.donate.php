<?php
class DWU_Donate {
    function __construct() {
        //add_action( 'admin_notices', array( $this, 'dwu_donate_admin_notice' ) );
        add_action( 'admin_init', array( $this, 'dwu_dismiss_donate_admin_notice' ) );
    }

    function dwu_donate_admin_notice() {
        // Show notice after 360 hours (15 days) from installed time.
        if ( $this->dwu_installed_time_donate() > strtotime( '-360 hours' )
            || '1' === get_option( 'dwu_dismiss_donate_notice' )
            || ! current_user_can( 'manage_options' )
            || apply_filters( 'dwu_hide_sticky_donate_notice', false ) ) {
            return;
        }

        $dismiss = wp_nonce_url( add_query_arg( 'dwu_donate_notice_action', 'dismiss_donate_true' ), 'dwu_dismiss_donate_true' ); 
        $no_thanks = wp_nonce_url( add_query_arg( 'dwu_donate_notice_action', 'no_thanks_donate_true' ), 'dwu_no_thanks_donate_true' ); ?>
        
        <div class="notice notice-success">
            <p><?php _e( 'Hey, I noticed you\'ve been using UPI QR Code Payment Gateway for WooCommerce for more than 2 week – that’s awesome! If you like UPI QR Code Payment Gatway for WooCommerce and you are satisfied with the plugin, isn’t that worth a coffee or two? Please consider donating. Donations help me to continue support and development of this free plugin! Thank you very much!', 'dew-upi-qr-code' ); ?></p>
            <p><a href="https://www.paypal.me/dewtechnolab" target="_blank" class="button button-secondary"><?php _e( 'Donate Now', 'dew-upi-qr-code' ); ?></a>&nbsp;
            <a href="<?php echo $dismiss; ?>" class="already-did"><strong><?php _e( 'I already donated', 'dew-upi-qr-code' ); ?></strong></a>&nbsp;<strong>|</strong>
            <a href="<?php echo $no_thanks; ?>" class="later"><strong><?php _e( 'Nope&#44; maybe later', 'dew-upi-qr-code' ); ?></strong></a></p>
        </div>
        <?php
    }

    function dwu_dismiss_donate_admin_notice() {
        if( get_option( 'dwu_no_thanks_donate_notice' ) === '1' ) {
            if ( get_option( 'dwu_dismissed_time_donate' ) > strtotime( '-360 hours' ) ) {
                return;
            }
        
            delete_option( 'dwu_dismiss_donate_notice' );
            delete_option( 'dwu_no_thanks_donate_notice' );
        }

        if ( !isset( $_GET['dwu_donate_notice_action'] ) ) {
            return;
        }

        if ( 'dismiss_donate_true' === $_GET['dwu_donate_notice_action'] ) {
            check_admin_referer( 'dwu_dismiss_donate_true' );
            update_option( 'dwu_dismiss_donate_notice', '1' );
        }

        if ( 'no_thanks_donate_true' === $_GET['dwu_donate_notice_action'] ) {
            check_admin_referer( 'dwu_no_thanks_donate_true' );
            update_option( 'dwu_no_thanks_donate_notice', '1' );
            update_option( 'dwu_dismiss_donate_notice', '1' );
            update_option( 'dwu_dismissed_time_donate', time() );
        }

        wp_redirect( remove_query_arg( 'dwu_donate_notice_action' ) );
        exit;
    }

    function dwu_installed_time_donate() {
        $installed_time = get_option( 'dwu_installed_time_donate' );
        if ( ! $installed_time ) {
            $installed_time = time();
            update_option( 'dwu_installed_time_donate', $installed_time );
        }
        return $installed_time;
    }
}
global $dwu_donate;
$dwu_donate = new DWU_Donate();