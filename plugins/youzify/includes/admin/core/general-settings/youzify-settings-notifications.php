<?php

/**
 * Notifications Settings.
 */
function youzify_notifications_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Live Notifications Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_live_notifications',
            'title' => __( 'Live Notifications', 'youzify' ),
            'desc'  => __( 'Enable live notifications', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_live_notifications_interval',
            'title' => __( 'New Notifications Interval', 'youzify' ),
            'desc'  => __( 'Check for new notifications interval by seconds', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}