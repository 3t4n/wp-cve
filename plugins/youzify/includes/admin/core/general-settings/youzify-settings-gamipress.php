<?php

/**
 * Add Mycred Settings Tab
 */
function youzify_gamipress_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'GamiPress Integration', 'youzify' ),
            'desc'  => __( 'Enable GamiPress integration', 'youzify' ),
            'id'    => 'youzify_enable_gamipress',
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
            'id'    => 'youzify_enable_cards_gamipress_badges',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Max Badges', 'youzify' ),
            'desc'  => __( 'Max badges per card', 'youzify' ),
            'id'    => 'youzify_wg_gamipress_max_card_user_badges_items',
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
            'id'    => 'youzify_enable_author_box_gamipress_badges',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Max Badges', 'youzify' ),
            'desc'  => __( 'Max badges per author box', 'youzify' ),
            'id'    => 'youzify_gamipress_author_box_max_user_badges_items',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}