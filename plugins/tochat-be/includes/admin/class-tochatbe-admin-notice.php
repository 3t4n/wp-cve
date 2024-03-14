<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Notice {

    /** Class constructor */
    public function __construct() {
        add_action( 'admin_notices', array( $this, 'just_whatsapp_icon_notice' ) );
    }

    /**
     * Display "Just WhatsApp Icon" notice.
     *
     * @since 1.0.9
     * 
     * @return void
     */
    public function just_whatsapp_icon_notice() {
        if ( isset( $_GET['tochatbe_just_whatsapp_icon_notice_dismiss'] ) ) {
            update_option( 'tochatbe_just_whatsapp_icon_notice_dismiss', 'yes' );
            wp_safe_redirect( wp_get_referer() );
            exit;
        }
        ?>
        <?php if ( 'yes' !== get_option( 'tochatbe_just_whatsapp_icon_notice_dismiss' ) ): ?>
            <div class="notice notice-info">
                <p><strong>TOCHAT.BE: </strong>If you want just a simple button with a direct link to WhatsApp, go to <a href="<?php echo admin_url( 'admin.php?page=to-chat-be-whatsapp_settings&tab=just_whatsapp_icon' ); ?>">settings</a> and activate "Just WhatsApp Icon"<a href="?tochatbe_just_whatsapp_icon_notice_dismiss=true" style="text-decoration:none;margin-left:10px;"><span class="dashicons dashicons-dismiss"></span></a></p>
            </div>
        <?php endif; ?>
        <?php
    }

}

new TOCHATBE_Admin_Notice;
