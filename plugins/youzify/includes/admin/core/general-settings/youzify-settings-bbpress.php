<?php

/**
 * Add BBpress Settings Tab
 */
function youzify_bbpress_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'bbPress Integration', 'youzify' ),
            'desc'  => __( 'Enable bbPress integration', 'youzify' ),
            'id'    => 'youzify_enable_bbpress',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}