<?php

/**
 * Navbar Settings.
 */

function youzify_navbar_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Navbar General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Navbar Icons', 'youzify' ),
            'id'    => 'youzify_display_navbar_icons',
            'desc'  => __( 'Show navbar pages icons', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Navbar Menus Limit', 'youzify' ),
            'id'    => 'youzify_profile_navbar_menus_limit',
            'desc'  => __( 'Number of visible pages on the navbar', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the navbar to be loaded?', 'youzify' ),
            'id'    => 'youzify_navbar_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Navbar Icons Style Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'imgSelect',
            'id'    => 'youzify_navbar_icons_style',
            'opts'  => $Youzify_Settings->get_field_options( 'navbar_icons_style' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Vertical Layout Navbar Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'imgSelect',
            'id'    => 'youzify_vertical_layout_navbar_type',
            'opts'  => $Youzify_Settings->get_field_options( 'vertical_layout_navbar' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Navbar Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background', 'youzify' ),
            'id'    => 'youzify_navbar_bg_color',
            'desc'  => __( 'Navbar background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icons Color', 'youzify' ),
            'id'    => 'youzify_navbar_icons_color',
            'desc'  => __( 'Navbar icons color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tabs Title', 'youzify' ),
            'id'    => 'youzify_navbar_links_color',
            'desc'  => __( 'Pages links color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tabs Hover', 'youzify' ),
            'id'    => 'youzify_navbar_links_hover_color',
            'desc'  => __( 'Pages links hover color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Border Color', 'youzify' ),
            'id'    => 'youzify_navbar_menu_border_color',
            'desc'  => __( 'Links border color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}