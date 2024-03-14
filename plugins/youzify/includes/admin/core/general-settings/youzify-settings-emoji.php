<?php

/**
 * Emoji Settings.
 */
function youzify_emoji_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Emoji', 'youzify' ),
            'desc'  => __( 'Enable emoji in posts', 'youzify' ),
            'id'    => 'youzify_enable_posts_emoji',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Emoji', 'youzify' ),
            'desc'  => __( 'Enable emoji in comments', 'youzify' ),
            'id'    => 'youzify_enable_comments_emoji',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Messages Emoji', 'youzify' ),
            'desc'  => __( 'Enable emoji in messages', 'youzify' ),
            'id'    => 'youzify_enable_messages_emoji',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}