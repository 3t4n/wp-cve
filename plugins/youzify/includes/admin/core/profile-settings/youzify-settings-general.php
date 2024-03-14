<?php

/**
 * Profile General Settings.
 */

function youzify_profile_general_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_allow_private_profiles',
            'title' => __( 'Allow Private Profiles', 'youzify' ),
            'desc'  => __( 'Allow users to make their profiles private', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Use Profile Effects?', 'youzify' ),
            'id'    => 'youzify_use_effects',
            'desc'  => __( 'Load profile elements with effects', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Account Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'bp-disable-account-deletion',
            'title' => __( 'Allow Delete Accounts', 'youzify' ),
            'desc'  => __( 'Allow registered members to delete their own accounts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Profiles Photos Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Profile Avatar', 'youzify' ),
            'desc'  => __( 'Upload default profiles avatar', 'youzify' ),
            'id'    => 'youzify_default_profiles_avatar',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Profile Cover', 'youzify' ),
            'desc'  => __( 'Upload default profiles cover', 'youzify' ),
            'id'    => 'youzify_default_profiles_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}