<?php

    namespace Wpo\Core;
    
    use \Wpo\Core\Extensions_Helpers;
    use \Wpo\Services\Options_Service;
    
    // Prevent public access to this script
    defined( 'ABSPATH' ) or die();

    if ( !class_exists( '\Wpo\Core\Plugin_Helpers' ) ) {

        class Plugin_Helpers {

            /**
             * Helper to check if a premium WPO365 plugin edition is active.
             */
            public static function is_premium_edition_active( $slug = null ) {

                if ( empty( $slug ) ) {
                    $extensions = Extensions_Helpers::get_extensions();

                    foreach ( $extensions as $slug => $extension ) {
                        
                        if ( true === $extension[ 'activated' ] ) {
                            return true;
                        }
                    }

                    return false;
                }

                if ( false === function_exists( 'is_plugin_active' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/plugin.php';
                }
                
                return \is_plugin_active( $slug );
            }

            /**
             * WPMU aware wp filter extension to show the action link on the plugins page. Will add 
             * the wpo365 configuration action link depending on the WPMU configuration
             * 
             * @since 7.3
             * 
             * @param Array $links The current action link collection
             * 
             * @return Array The new action link collection
             */
            public static function get_configuration_action_link( $links ) {
                // Don't show the configuration link for subsite admin if subsite options shouldn't be used
                if ( is_multisite() && !is_network_admin() && false === Options_Service::mu_use_subsite_options() )
                    return $links;
                
                $wizard_link = '<a href="admin.php?page=wpo365-wizard">' . __( 'Configuration', 'wpo365-login' ) . '</a>';
                array_push( $links, $wizard_link );
                
                return $links;
            }
        }
    }
