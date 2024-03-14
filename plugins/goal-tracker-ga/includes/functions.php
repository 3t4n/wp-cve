<?php

/**
 * Get the Plugin Default Options.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param null
 *
 * @return array Default Options
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_default_options' ) ) {
    function wp_goal_tracker_ga_default_options( $key = '' )
    {
        $default_plugin_options = array();
        $default_general_settings = array(
            'measurementID'            => esc_html__( '', 'wp-goal-tracker-ga' ),
            'trackLinks'               => array(
            'enabled' => false,
            'type'    => "all",
        ),
            'trackEmailLinks'          => false,
            'disableTrackingForAdmins' => false,
            'gaDebug'                  => false,
            'disablePageView'          => false,
            'noSnippet'                => false,
            'multiTrackers'            => false,
        );
        $default_plugin_options["generalSettings"] = $default_general_settings;
        $default_plugin_options["hideGeneralSettingsTutorial"] = false;
        $default_plugin_options["click"] = array();
        $default_plugin_options["visibility"] = array();
        return $default_plugin_options;
    }

}
/**
 * Get the Plugin Saved Options.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param string $key optional option key
 *
 * @return mixed All Options Array Or Options Value
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_get_options' ) ) {
    function wp_goal_tracker_ga_get_options( $key = '' )
    {
        $options = get_option( 'wp_goal_tracker_ga_options' );
        $default_options = wp_goal_tracker_ga_default_options( $key );
        
        if ( !empty($key) ) {
            
            if ( isset( $options[$key] ) ) {
                
                if ( is_array( $options[$key] ) && sizeof( $options[$key] ) == 0 ) {
                    $merged = array_merge( $default_options[$key], $options[$key] );
                    return $merged;
                }
                
                return $options[$key];
            } else {
                if ( isset( $default_options[$key] ) ) {
                    return $default_options[$key];
                }
            }
        
        } else {
            if ( !is_array( $options ) ) {
                $options = array();
            }
            if ( isset( $options['generalSettings'] ) && is_array( $options['generalSettings'] ) ) {
                $options['generalSettings'] = array_merge( $default_options['generalSettings'], $options['generalSettings'] );
            }
            return array_merge( $default_options, $options );
        }
    
    }

}
/**
 * Delete the Plugin Saved Options.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param string $key optional option key
 *
 * @return boolean after delete or update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_delete_options' ) ) {
    function wp_goal_tracker_ga_delete_options( $key = '' )
    {
        
        if ( !empty($key) ) {
            $options = wp_goal_tracker_ga_get_options();
            
            if ( isset( $options[$key] ) ) {
                unset( $options[$key] );
                return update_option( 'wp_goal_tracker_ga_options', $options );
            }
        
        } else {
            return delete_option( 'wp_goal_tracker_ga_options' );
        }
    
    }

}
/**
 * Set/Save the Plugin Options to Database.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param array $settings all options of the plugin to be saved.
 *
 * @return boolean after update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
/* Oblsolete to be removed

if (!function_exists('wp_goal_tracker_ga_set_options')) :
function wp_goal_tracker_ga_set_options($settings)
{
$current_options = wp_goal_tracker_ga_get_options();
$setting_keys = array_keys(wp_goal_tracker_ga_default_options());
$options      = array();
foreach ($settings as $key => $value) {
if (in_array($key, $setting_keys)) {
if ('measurementID' == $key) {
$value = sanitize_text_field($value);
} elseif ('trackEmailLinks' == $key) {
$value =  (bool) $value;
} elseif ('trackLinks' == $key) {
$value = array('enabled' => (bool) $value['enabled'], 'type' => sanitize_text_field($value['type']));
} elseif ('gaDebug' == $key) {
$value =  (bool) $value;
} elseif ('disablePageView' == $key) {
$value = (bool) $value;
} elseif ('permittedRoles' == $key) {
$has_administrator = false;
foreach ($value as $role) {
if ($role['id'] == 'administrator') {
$has_administrator = true;
break;
}
}
if (!$has_administrator) array_push($value, array('id' => 'administrator', 'name' => 'Administrator'));
$value = $value;
} else {
$value = sanitize_key($value);
}
$options[$key] = $value;
}
}
$click_custom_events = wp_goal_tracker_ga_get_config("click", '');
$options['click'] = $click_custom_events;
$visibility_custom_events = wp_goal_tracker_ga_get_config("visibility", '');
$options['visibility'] = $visibility_custom_events;
update_option('wp_goal_tracker_ga_options', $options);
}
endif;
 */
/**
 * Set/Save an option to Database.
 *
 * @since WP Goal Tracker GA 1.0.2
 *
 * @param array  option to be saved.
 *
 * @return boolean after update option
 *
 * @author     asafo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_update_option' ) ) {
    function wp_goal_tracker_ga_update_option( $option_key, $option )
    {
        $options = wp_goal_tracker_ga_get_options();
        $options[$option_key] = $option;
        //Use opportunity to refresh cache
        $click_custom_events = wp_goal_tracker_ga_get_config( "click", '' );
        $options['click'] = $click_custom_events;
        $visibility_custom_events = wp_goal_tracker_ga_get_config( "visibility", '' );
        $options['visibility'] = $visibility_custom_events;
        $updated = update_option( 'wp_goal_tracker_ga_options', $options );
        if ( !$updated ) {
            return new WP_Error( 'error_updating_options', 'Failed to update options.', [
                'status' => 500,
            ] );
        }
    }

}
/**
 * Set/Save the Plugin General Settings to Database.
 *
 * @since WP Goal Tracker GA 1.0.2
 *
 * @param array $general_settings all general settings to be saved.
 *
 * @return boolean after update option
 *
 * @author     asafo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_general_settings' ) ) {
    function wp_goal_tracker_ga_set_general_settings( $new_general_settings )
    {
        $setting_keys = array_keys( wp_goal_tracker_ga_default_options( "generalSettings" ) );
        $general_settings = array();
        foreach ( $new_general_settings as $key => $value ) {
            #if (in_array($key, $setting_keys)) {
            
            if ( 'measurementID' == $key ) {
                $value = sanitize_text_field( $value );
            } elseif ( 'trackEmailLinks' == $key ) {
                $value = (bool) $value;
            } elseif ( 'disableTrackingForAdmins' == $key ) {
                $value = (bool) $value;
            } elseif ( 'trackUsers' == $key ) {
                $value = (bool) $value;
            } elseif ( 'trackLinks' == $key ) {
                $value = array(
                    'enabled' => (bool) $value['enabled'],
                    'type'    => sanitize_text_field( $value['type'] ),
                );
            } elseif ( 'gaDebug' == $key ) {
                $value = (bool) $value;
            } elseif ( 'disablePageView' == $key ) {
                $value = (bool) $value;
            } elseif ( 'noSnippet' == $key ) {
                $value = (bool) $value;
            } elseif ( 'multiTrackers' == $key ) {
                $value = (bool) $value;
            } elseif ( 'permittedRoles' == $key ) {
                $has_administrator = false;
                foreach ( $value as $role ) {
                    
                    if ( $role['id'] == 'administrator' ) {
                        $has_administrator = true;
                        break;
                    }
                
                }
                if ( !$has_administrator ) {
                    array_push( $value, array(
                        'id'   => 'administrator',
                        'name' => 'Administrator',
                    ) );
                }
                $value = $value;
            } else {
                $value = sanitize_key( $value );
            }
            
            $general_settings[$key] = $value;
            #}
        }
        wp_goal_tracker_ga_update_option( 'generalSettings', $general_settings );
    }

}
/**
 * Hide/Show the Tutorial on the General Settings tab.
 *
 * @since WP Goal Tracker GA 1.0.4
 *
 * @param array $hide - true/false
 *
 * @return boolean after update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_general_settings_tutorial' ) ) {
    function wp_goal_tracker_ga_set_general_settings_tutorial( $hide )
    {
        wp_goal_tracker_ga_update_option( 'hideGeneralSettingsTutorial', $hide );
    }

}
/**
 * Set/Save the Plugin Video Settings to Database.
 *
 * @since WP Goal Tracker GA 1.0.2
 *
 * @param array $general_settings all general settings to be saved.
 *
 * @return boolean after update option
 *
 * @author     asafo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_video_settings' ) ) {
    function wp_goal_tracker_ga_set_video_settings( $new_video_settings )
    {
        $video_settings = array();
        foreach ( $new_video_settings as $key => $value ) {
            # sanitize values - all are boolean
            $value = (bool) $value;
            $video_settings[$key] = $value;
        }
        wp_goal_tracker_ga_update_option( 'videoSettings', $video_settings );
    }

}
/**
 * Set/Save the Plugin Form Tracking Settings to Database.
 *
 * @since WP Goal Tracker GA 1.0.10
 *
 * @param array $general_settings all general settings to be saved.
 *
 * @return boolean after update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_form_tracking_settings' ) ) {
    function wp_goal_tracker_ga_set_form_tracking_settings( $new_form_tracking_settings )
    {
        $form_tracking_settings = array();
        foreach ( $new_form_tracking_settings as $key => $value ) {
            # sanitize values - all are boolean
            $form_tracking_settings[$key] = array();
            foreach ( $value as $nkey => $nvalue ) {
                $nvalue = (bool) $nvalue;
                $form_tracking_settings[$key][$nkey] = $nvalue;
            }
        }
        wp_goal_tracker_ga_update_option( 'formTrackingSettings', $form_tracking_settings );
    }

}
/**
 * Set/Save the Plugin Ecommerce Tracking Settings to Database.
 *
 * @since WP Goal Tracker GA 1.0.17
 *
 * @param array $general_settings all general settings to be saved.
 *
 * @return boolean after update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_ecommerce_tracking_settings' ) ) {
    function wp_goal_tracker_ga_set_ecommerce_tracking_settings( $new_ecommerce_tracking_settings )
    {
        $form_tracking_settings = array();
        foreach ( $new_ecommerce_tracking_settings as $key => $value ) {
            # sanitize values - all are boolean
            $ecommerce_tracking_settings[$key] = array();
            foreach ( $value as $nkey => $nvalue ) {
                $nvalue = (bool) $nvalue;
                $ecommerce_tracking_settings[$key][$nkey] = $nvalue;
            }
        }
        wp_goal_tracker_ga_update_option( 'ecommerceTrackingSettings', $ecommerce_tracking_settings );
    }

}
/**
 * Get Configuration.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param string $key optional option key
 *
 * @return mixed All Options Array Or Options Value
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_get_config' ) ) {
    function wp_goal_tracker_ga_get_config( $type, $ID = '' )
    {
        
        if ( isset( $ID ) && is_int( $ID ) ) {
            // get specific post config
            return get_post_meta( $ID );
        } else {
            // return all posts of given types
            $all_events_data = [];
            $all_events = get_posts( array(
                'post_type'   => $type,
                'post_status' => 'published',
                'fields'      => 'ids',
                'numberposts' => -1,
            ) );
            foreach ( $all_events as &$event ) {
                $event_meta['selector'] = get_post_meta( $event, "selector", true );
                $event_meta['eventName'] = get_post_meta( $event, "eventName", true );
                $event_meta['isRecommended'] = get_post_meta( $event, "isRecommended", true );
                $event_meta["props"] = get_post_meta( $event, "props", true );
                // workaround to unserialize issue
                $event_meta["id"] = $event;
                array_push( $all_events_data, $event_meta );
            }
            return $all_events_data;
        }
    
    }

}
/**
 * Delete Configuration.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param string $key optional option key
 *
 * @return boolean after delete or update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_delete_config' ) ) {
    function wp_goal_tracker_ga_delete_config( $key = '' )
    {
        
        if ( !empty($key) ) {
            $options = wp_goal_tracker_ga_get_options();
            
            if ( isset( $options[$key] ) ) {
                unset( $options[$key] );
                return update_option( 'wp_goal_tracker_ga_options', $options );
            }
        
        } else {
            return delete_option( 'wp_goal_tracker_ga_options' );
        }
    
    }

}
/**
 * Set/Save Configuration.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param array $settings all options of the plugin to be saved.
 *
 * @return boolean after update option
 *
 * @author     yuvalo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_config' ) ) {
    function wp_goal_tracker_ga_set_config( $type, $ID, $config )
    {
        $meta_input = array(
            'type'          => $config["type"],
            'selector'      => $config["selector"],
            'eventName'     => $config["eventName"],
            'isRecommended' => $config["isRecommended"],
            'props'         => $config["props"],
        );
        $postarr = array(
            'ID'         => $ID,
            'post_type'  => $type,
            'meta_input' => $meta_input,
        );
        $ID = wp_insert_post( $postarr, true );
        // Note the 'true' to return WP_Error on failure
        // Check if wp_insert_post returned a WP_Error object
        if ( is_wp_error( $ID ) ) {
            // You can return the WP_Error object directly or create a new one
            return new WP_Error( 'error_inserting_post', 'Failed to create event post: ' . $ID->get_error_message(), [
                'status' => 500,
            ] );
        }
        return $ID;
    }

}
/**
 * Delete Event.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param array $settings all options of the plugin to be saved.
 *
 * @return boolean after update option
 *
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_delete_event' ) ) {
    function wp_goal_tracker_ga_delete_event( $ID )
    {
        if ( isset( $ID ) ) {
            // get specific post config
            return wp_delete_post( $ID );
        }
        return "404";
    }

}
/**
 * Replace events config
 *
 * @since WP Goal Tracker GA 1.0.8
 *
 * @param array $type - events type, $config - all events configs for that type
 *
 * @return boolean after update option
 *
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_replace_events_config' ) ) {
    function wp_goal_tracker_ga_replace_events_config( $post_type, $config )
    {
        // Get all existing configuration posts
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'any',
        );
        $posts = get_posts( $args );
        // Delete all existing configuration posts
        foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
        }
        // Insert new configuration posts
        foreach ( $config as $event_config ) {
            $meta_input = array(
                'type'          => $event_config["type"],
                'selector'      => $event_config["selector"],
                'eventName'     => $event_config["eventName"],
                'isRecommended' => $event_config["isRecommended"],
                'props'         => $event_config["props"],
            );
            $postarr = array(
                'ID'         => $ID,
                'post_type'  => $post_type,
                'meta_input' => $meta_input,
            );
            wp_insert_post( $postarr );
        }
        return true;
    }

}
/**
 * Set the Plugin Entire config.
 *
 * @since WP Goal Tracker GA 1.0.0
 *
 * @param string $config
 *
 * @return mixed All Options Array Or Options Value
 *
 * @author     asafo <support@wpgoaltracker.com>
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_set_entire_config' ) ) {
    function wp_goal_tracker_ga_set_entire_config( $config = '' )
    {
        wp_goal_tracker_ga_replace_events_config( 'click', $config['click'] );
        wp_goal_tracker_ga_replace_events_config( 'visibility', $config['visibility'] );
        $default_options = wp_goal_tracker_ga_default_options();
        $options = array_merge( $default_options, $config );
        wp_goal_tracker_ga_update_option( "generalSettings", $options["generalSettings"] );
        wp_goal_tracker_ga_update_option( "hideGeneralSettingsTutorial", $options["hideGeneralSettingsTutorial"] );
        wp_goal_tracker_ga_update_option( "click", $options["click"] );
        wp_goal_tracker_ga_update_option( "visibility", $options["visibility"] );
        return true;
    }

}
/**
 * Update Custom Event Cache
 *
 * @since    1.0.0
 *
 * @param
 *
 *
 */
if ( !function_exists( 'wp_goal_tracker_ga_update_cache_settings' ) ) {
    function wp_goal_tracker_ga_update_cache_settings()
    {
        $options = wp_goal_tracker_ga_get_options();
        $click_custom_events = wp_goal_tracker_ga_get_config( "click", '' );
        $options['click'] = $click_custom_events;
        $visibility_custom_events = wp_goal_tracker_ga_get_config( "visibility", '' );
        $options['visibility'] = $visibility_custom_events;
        $updated = update_option( 'wp_goal_tracker_ga_options', $options );
        if ( $updated === false ) {
            // Since update_option does not return WP_Error, you need to create it
            return new WP_Error( 'error_updating_options', 'Failed to update options. It might be due to the new value being identical to the existing one or a database error.', [
                'status' => 500,
            ] );
        }
    }

}