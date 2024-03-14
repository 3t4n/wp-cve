<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for admin notices
 */
class EventM_Admin_Notices {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_print_styles', array( __CLASS__, 'ep_print_notices' ) );
    }

    /**
     * Print notices
     */
    public static function ep_print_notices() {
        $notices = self::ep_get_notices();
        if ( ! empty( $notices ) ) {
			foreach ( $notices as $type => $notice ) {
                if( $type == 'success' ) {?>
                    <div class="ep-admin-notices notice notice-success notice notice-success settings-error">
                        <?php echo wp_kses_post( wpautop( $notice ) ); ?>
                    </div><?php
                }
                if( $type == 'error' ) {?>
                    <div class="ep-admin-notices notice notice-error">
                        <?php echo wp_kses_post( wpautop( $notice ) ); ?>
                    </div><?php
                }
                if( $type == 'warning' ) {?>
                    <div class="ep-admin-notices notice notice-warning">
                        <?php echo wp_kses_post( wpautop( $notice ) ); ?>
                    </div><?php
                }
			}
		}
        self::ep_remove_notices();
    }

    /**
     * Get notices
     */
    public static function ep_get_notices() {
        $notices = get_option( 'eventprime_admin_notices' );
        return $notices;
    }

    /**
     * Add notice
     * 
     * @param string $type Notice Type.
     * @param string $message Notice Message.
     */
    public static function ep_add_notice( $type, $message ){
        $notice[$type] = $message;
        
        self::ep_store_notice( $notice );
    }

    /**
     * Store notices
     * 
     * @param array $notice Notice data
     */
    public static function ep_store_notice( $notice_data ) {
        $notice = self::ep_get_notices();
        if( empty( $notice ) ) {
            $notice = $notice_data;
            add_option( 'eventprime_admin_notices', $notice );
        } else{
            $notice[] = $notice_data;
            update_option( 'eventprime_admin_notices', $notice );
        }
    }

    /**
     * Remove Notice
     */
    public static function ep_remove_notices() {
        delete_option( 'eventprime_admin_notices' );
    }

}

new EventM_Admin_Notices();