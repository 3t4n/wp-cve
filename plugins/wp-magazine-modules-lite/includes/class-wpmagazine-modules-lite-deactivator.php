<?php
/**
 * Defines the plugin class executes when plugin is deactivated.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite_Deactivator' ) ) :

    class Wpmagazine_Modules_Lite_Deactivator {
        /**
         * @access public static
         */
        public static function deactivate() {
            global $current_user;
            $user_id = $current_user->ID;

            $wpmagazine_modules_lite_activated_time = get_option( 'wpmagazine_modules_lite_activated_time' );
            $wpmagazine_modules_lite_ignore_review_notice_partially = get_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_review_notice_partially', true );
            $wpmagazine_modules_lite_ignore_theme_review_notice = get_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_theme_review_notice', true );

            if ( $wpmagazine_modules_lite_activated_time ) {
                delete_option( 'wpmagazine_modules_lite_activated_time' );
            }

            if ( $wpmagazine_modules_lite_ignore_review_notice_partially ) {
                delete_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_review_notice_partially' );
            }

            if ( $wpmagazine_modules_lite_ignore_theme_review_notice ) {
                delete_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_theme_review_notice' );
            }
        }
    }

endif;