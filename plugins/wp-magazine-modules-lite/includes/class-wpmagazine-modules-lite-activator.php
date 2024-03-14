<?php
/**
 * Defines the plugin class executes when plugin is activated.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite_Activator' ) ) :

    class Wpmagazine_Modules_Lite_Activator {
        /**
         * Called by plugin activation hook.
         * 
         * @access public static
         */
        public static function activate() {
            // Set the plugin activation time. 
            $wpmagazine_modules_lite_activated_time = get_option( 'wpmagazine_modules_lite_activated_time' );
            if ( !$wpmagazine_modules_lite_activated_time ) {
                update_option( 'wpmagazine_modules_lite_activated_time', time() );
            }

            // set the free plugin activation for premium update notice
            $wpmagazine_modules_lite_upgrade_premium = get_option( 'wpmagazine_modules_lite_upgrade_premium' );
            if ( !$wpmagazine_modules_lite_upgrade_premium ) {
                update_option( 'wpmagazine_modules_lite_upgrade_premium', time() );
            }
        }
    }
    
endif;