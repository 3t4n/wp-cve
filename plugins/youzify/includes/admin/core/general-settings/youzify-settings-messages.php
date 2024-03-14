<?php

/**
 * Message Settings.
 */
function youzify_messages_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Attachments Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_messages_attachments',
            'title' => __( 'Messages Attachments', 'youzify' ),
            'desc'  => __( 'Enable attachments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_messages_attachments_extensions',
            'title' => __( 'Allowed Extensions', 'youzify' ),
            'desc'  => __( 'Allowed extensions list', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_messages_attachments_max_size',
            'title' => __( 'Max File Size', 'youzify' ),
            'desc'  => __( 'Attachment max size by megabytes', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}