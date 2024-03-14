<?php
/**
 * EventPrime handle front notices 
 */
defined( 'ABSPATH' ) || exit;

class EventM_Front_Notices_Service {
    /**
     * Init Hooks
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'ep_print_notices' ) );
    }

    /**
     * Add eventprime notices
     * 
     * @param string $notice Notice
     */
    public static function ep_add_front_notice( $type, $notice ) {
        $notices = get_option( 'eventprime_front_notices' );
        $notice_data[$type] = $notice;
        if( empty( $notices ) ) {
            add_option( 'eventprime_front_notices', $notice_data );
        } else{
            if( isset( $notices[ $type ] ) ) {
                $notices[ $type ][] = $notice;
            } else{
                $notices[ $type ] = $notice_data;
            }
            update_option( 'eventprime_front_notices', $notices );
        }

        self::ep_print_notices();
    }

    /**
     * Print frontend notices.
     */
    public static function ep_print_notices() {
        $notices = get_option( 'eventprime_front_notices' );
        if( ! empty( $notices ) ) {
            // enqueue toast
            wp_enqueue_style(
                'ep-toast-css',
                EP_BASE_URL . '/includes/assets/css/jquery.toast.min.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-toast-js',
                EP_BASE_URL . '/includes/assets/js/jquery.toast.min.js',
                array('jquery'), EVENTPRIME_VERSION
            );

            foreach( $notices as $type => $notice ) { 
                if( $type == 'error' ) {?>
                    <script>
                        document.addEventListener( "DOMContentLoaded", function(event) { 
                            jQuery.toast({
                                heading: 'Error',
                                text: "<?php echo $notice;?>",
                                position: 'top-right',
                                stack: false,
                                hideAfter: 5000,
                                bgColor: '#dc3545',
                                textColor: 'white'
                            });
                        });
                    </script><?php
                }

                if( $type == 'success' ) {?>
                    <script>
                        document.addEventListener( "DOMContentLoaded", function(event) { 
                            jQuery.toast({
                                heading: 'Success',
                                text: "<?php echo $notice;?>",
                                position: 'top-right',
                                stack: false,
                                hideAfter: 5000,
                                bgColor: '#218838',
                                textColor: 'white'
                            });
                        });
                    </script><?php
                }

                if( $type == 'warning' ) {?>
                    <script>
                        document.addEventListener( "DOMContentLoaded", function(event) { 
                            jQuery.toast({
                                heading: 'Warning',
                                text: "<?php echo $notice;?>",
                                position: 'top-right',
                                stack: false,
                                hideAfter: 5000,
                                bgColor: '#d39e00',
                                textColor: '#212529'
                            });
                        });
                    </script><?php
                }

            }

            self::ep_delete_front_notices();
        }
    }

    /**
     * Delete front notices
     */
    public static function ep_delete_front_notices() {
        delete_option( 'eventprime_front_notices' );
    }

}

EventM_Front_Notices_Service::init();