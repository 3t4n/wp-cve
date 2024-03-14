<?php

/**
 * Add Mycred Settings Tab
 */
function youzify_mycred_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'MyCred Integration', 'youzify' ),
            'desc'  => __( 'Enable MyCred integration', 'youzify' ),
            'id'    => 'youzify_enable_mycred',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Members Directory Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Badges', 'youzify' ),
            'desc'  => __( 'Enable cards badges', 'youzify' ),
            'id'    => 'youzify_enable_cards_mycred_badges',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Max Badges', 'youzify' ),
            'desc'  => __( 'Max badges per card', 'youzify' ),
            'id'    => 'youzify_wg_max_card_user_badges_items',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Author Box Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Badges', 'youzify' ),
            'desc'  => __( 'Enable author box badges', 'youzify' ),
            'id'    => 'youzify_enable_author_box_mycred_badges',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Max Badges', 'youzify' ),
            'desc'  => __( 'Max badges per author box', 'youzify' ),
            'id'    => 'youzify_author_box_max_user_badges_items',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}