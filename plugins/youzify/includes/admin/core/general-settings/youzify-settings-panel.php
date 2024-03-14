<?php

/**
 * Panel Settings.
 */

function youzify_panel_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Fixed Save Icon', 'youzify' ),
            'desc' => __( 'Enable fixed Save icon button', 'youzify' ),
            'id'    => 'youzify_enable_panel_fixed_save_btn',
            'type'  => 'checkbox',
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Panel Schemes', 'youzify' ),
            'class' => 'uk-panel-scheme',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_panel_scheme',
            'type'  => 'imgSelect',
            'opts'  => array(
                'youzify-orange-scheme', 'youzify-white-scheme', 'youzify-pink-scheme',
                'youzify-red-scheme', 'youzify-darkgray-scheme', 'youzify-yellow-scheme',
                'youzify-blue-scheme', 'youzify-purple-scheme', 'youzify-green-scheme'
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}