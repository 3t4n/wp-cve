<?php

class Youzify_Profile_Networks_Widget {

    function __construct() {

        // Filters.
        add_filter( 'youzify_profile_without_front_end_settings', array( $this, 'display_networks_widget_edit_icon' ) );
        add_filter( 'youzify_profile_widgets_edit_link', array( $this, 'edit_social_networks_settings_widget_link' ), 10, 2 );

    }

    /**
     * Content.
     */
    function widget() {
        // Call Networks Widget.
        youzify_users()->networks( array( 'target' => 'widget' ) );
    }

    /**
     * Edit Social Networks Links.
     */
    function edit_social_networks_settings_widget_link( $url, $widget_name ) {

        if ( $widget_name == 'social_networks' ) {
            return youzify_get_profile_settings_url( 'social-networks' );
        }

        return $url;
    }

    /**
     * Display Networks Edit Icon.
     */
    function display_networks_widget_edit_icon( $widgets ) {

        if ( youzify_is_account_page() ) {
            return $widgets;
        }

        // Get Key.
        $key = array_search( 'social_networks', $widgets );

        // Delete Widget.
        if ( isset( $widgets[ $key ] ) ) {
            unset( $widgets[ $key ] );
        }

        return $widgets;
    }
}