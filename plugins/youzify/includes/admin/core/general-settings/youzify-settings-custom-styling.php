<?php

/**
 * Custom Styling Settings.
 */

function youzify_custom_styling_settings() {

	global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Global Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Global CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_global_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Global Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work on all your website pages', 'youzify' ),
            'id'    => 'youzify_global_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Profile Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Profile CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_profile_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Profile Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work only in the user profile page.', 'youzify' ),
            'id'    => 'youzify_profile_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Account Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Account CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_account_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Account Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work only in the user account settings pages.', 'youzify' ),
            'id'    => 'youzify_account_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Groups CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_groups_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work only in the groups pages.', 'youzify' ),
            'id'    => 'youzify_groups_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Members Directory Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Members Directory CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_members_directory_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Members Directory Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work only in the members directory page.', 'youzify' ),
            'id'    => 'youzify_members_directory_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Directory Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Groups Directory CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_groups_directory_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Directory Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work only in the groups directory page.', 'youzify' ),
            'id'    => 'youzify_groups_directory_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Styling Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Activity CSS', 'youzify' ),
            'desc'  => __( 'Enable styling code below', 'youzify' ),
            'id'    => 'youzify_enable_activity_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Custom CSS', 'youzify' ),
            'desc'  => __( 'This code will work only in the Global Activity page.', 'youzify' ),
            'id'    => 'youzify_activity_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}