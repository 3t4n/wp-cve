<?php

/**
 * Tabs Settings.
 */

function youzify_tabs_settings() {

    do_action( 'bp_setup_nav' );

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tabs General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    // Get Defaut Tab Options.
    $default_tab_options = (array) youzify_get_profile_default_nav_options();
    $default_option = array( '' => __( '-- Select Default Tab --', 'youzify' ) );
    $default_tab_options = $default_option + $default_tab_options;

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_profile_default_tab',
            'title' => __( 'Default Tab', 'youzify' ),
            'desc'  => __( 'Choose profile default tab', 'youzify' ),
            'opts'  => $default_tab_options,
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
    

    // Get Tabs
    $custom_tabs = youzify_get_profile_primary_nav();

    if ( ! empty( $custom_tabs ) ) {
        youzify_custom_buddypress_tabs_settings( $custom_tabs );
    }

    // Get Custom Tabs Settings.
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Pagination Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Numbers Color', 'youzify' ),
            'id'    => 'youzify_pagination_text_color',
            'desc'  => __( 'Pages numbers color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Numbers Background', 'youzify' ),
            'id'    => 'youzify_pagination_bg_color',
            'desc'  => __( 'Pages numbers background', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Active Page Background', 'youzify' ),
            'id'    => 'youzify_pagination_current_bg_color',
            'desc'  => __( 'Active page background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Active Page Number', 'youzify' ),
            'id'    => 'youzify_pagination_current_text_color',
            'desc'  => __( 'Active page number color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Get Custom Buddypress Tabs Settings.
 */
function youzify_custom_buddypress_tabs_settings( $custom_tabs ) {

    global $Youzify_Settings;

    // Default Tab Values.
    $default_tabs = youzify_profile_tabs_default_value();
    $tabs_settings = youzify_option( 'youzify_profile_tabs' );

    foreach ( $custom_tabs as $tab ) {

        // Get Tab Name
        $tab_name = isset( $tab['name'] ) ? $tab['name'] : $tab['slug'];

        // Filter Name.
        $tab_name = _bp_strip_spans_from_title( $tab_name );

        // Get Tab Slug
        $tab_slug = isset( $tab['slug'] ) ? $tab['slug'] : null;

        $Youzify_Settings->get_field(
            array(
                'title' => sprintf( __( '%s Tab', 'youzify' ), $tab_name ),
                'class' => 'ukai-box-3cols kl-accordion-box',
                'type'  => 'openBox',
                'hide'  => true,
            )
        );

        $default_visibility = isset( $default_tabs[ $tab_slug ] ) ? $default_tabs[ $tab_slug ]['visibility'] : 'on';

        $Youzify_Settings->get_field(
            array(
                'type'  => 'checkbox',
                'std'   => youzify_admin_get_tab_option_value( $tab_slug, 'visibility', $default_visibility ),
                'id'    => 'visibility',
                'title' => sprintf( __( 'Display Tab', 'youzify' ), $tab_name ),
                'desc'  => sprintf( __( 'Show %s tab', 'youzify' ), $tab_name ),
            ), false, 'youzify_profile_tabs[' . $tab_slug .']'
        );

        $default_icon = isset( $default_tabs[ $tab_slug ] ) ? $default_tabs[ $tab_slug ]['icon'] : 'fas fa-globe-asia';

        $Youzify_Settings->get_field(
            array(
                'type'  => 'icon',
                'std'   => youzify_admin_get_tab_option_value( $tab_slug, 'icon', $default_icon ),
                'id'    => 'icon',
                'title' => sprintf( __( '%s Icon', 'youzify' ), $tab_name ),
                'desc'  => sprintf( __( '%s tab icon', 'youzify' ), $tab_name ),
            ), false, 'youzify_profile_tabs[' . $tab_slug .']'
        );

        $Youzify_Settings->get_field(
            array(
                'type'  => 'text',
                'std'   => youzify_admin_get_tab_option_value( $tab_slug, 'name', $tab_name ),
                'id'    => 'name',
                'title' => sprintf( __( '%s Title', 'youzify' ), $tab_name ),
                'desc' => sprintf( __( '%s tab title', 'youzify' ), $tab_name ),
            ), false, 'youzify_profile_tabs[' . $tab_slug .']'
        );

        $Youzify_Settings->get_field(
            array(
                'id'    => 'position',
                'type'  => 'number',
                'std'   => youzify_admin_get_tab_option_value( $tab_slug, 'position', $tab['position'] ),
                'title' => sprintf( __( '%s Order', 'youzify' ), $tab_name ),
                'desc'  => sprintf( __( '%s tab order', 'youzify' ), $tab_name ),
            ), false, 'youzify_profile_tabs[' . $tab_slug .']'
        );

        if ( in_array( $tab_slug, youzify_profile_deletable_tabs() ) ) {

            $Youzify_Settings->get_field(
                array(
                    'std'   => youzify_admin_get_tab_option_value( $tab_slug, 'deleted', 'off' ),
                    'type'  => 'checkbox',
                    'id'    => 'deleted',
                    'title' => sprintf( __( 'Delete Tab', 'youzify' ), $tab_name ),
                    'desc'  => sprintf( __( 'Delete %s tab', 'youzify' ), $tab_name ),
                ), false, 'youzify_profile_tabs[' . $tab_slug .']'
            );
        }

       $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

}

/**
 * Get Tab Option
 */
function youzify_admin_get_tab_option_value( $slug, $option, $std = null ) {

    $tabs = youzify_option( 'youzify_profile_tabs' );

    if ( isset( $tabs[ $slug ][ $option ] ) ) {
        return $tabs[ $slug ][ $option ];
    }

    return $std;
}

/**
 * Profile Default Nav Options
 */
function youzify_get_profile_default_nav_options() {

    // Get Youzify Custom Tabs
    $primary_tabs = youzify_get_profile_primary_nav();

    if ( empty( $primary_tabs ) ) {
        return false;
    }

    // Init
    $tab_options = array();

    foreach ( $primary_tabs as $tab ) {

        // Get Tab Slug.
        $tab_slug = $tab['slug'];

        // Get Tab ID.
        $tab_id = youzify_get_custom_tab_id_by_slug( $tab_slug );

        // if ( empty( $tab_id ) ) {
        //     continue;
        // }
        // Get Custom Tab Link.
        if ( youzify_is_custom_tab( $tab_id ) ) {

            // Get Tab Type.
            $tab_type = youzify_get_custom_tab_data( $tab_id, 'type' );

            if ( 'link' == $tab_type ) {
                continue;
            }
        }

        // Set Option.
        $tab_options[ $tab_slug ] = _bp_strip_spans_from_title( $tab['name'] );

    }

    return $tab_options;
}

/**
 * Get Profile Deletable Tabs.
 */
function youzify_profile_deletable_tabs() {

    // Get Default Tabs Slugs.
    $default_tabs = youzify_get_youzify_default_tabs();

    // Get Youzify Custom Tabs Slugs.
    $custom_tabs = (array) youzify_custom_youzify_tabs_slugs();

    // Merge Tabs Slugs.
    $all_tabs = array_merge( $default_tabs, $custom_tabs );

    return $all_tabs;
}