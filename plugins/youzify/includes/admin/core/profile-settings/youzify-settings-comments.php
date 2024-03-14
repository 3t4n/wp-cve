<?php

/**
 * Comments Settings.
 */
function youzify_comments_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Per Page', 'youzify' ),
            'id'    => 'youzify_profile_comments_nbr',
            'desc'  => __( 'How many comments per page?', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Visibility Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzify' ),
            'id'    => 'youzify_display_comment_title',
            'desc'  => __( 'Show comments title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Username', 'youzify' ),
            'id'    => 'youzify_display_comment_username',
            'desc'  => __( 'Show comments username', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Date', 'youzify' ),
            'id'    => 'youzify_display_comment_date',
            'desc'  => __( 'Show comments date', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button', 'youzify' ),
            'id'    => 'youzify_display_view_comment',
            'desc'  => __( 'Show "View Comment" button', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Fullname', 'youzify' ),
            'id'    => 'youzify_comment_author_color',
            'desc'  => __( 'Comments author color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Username', 'youzify' ),
            'id'    => 'youzify_comment_username_color',
            'desc'  => __( 'Comments username color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Date', 'youzify' ),
            'id'    => 'youzify_comment_date_color',
            'desc'  => __( 'Comments date color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Text', 'youzify' ),
            'id'    => 'youzify_comment_text_color',
            'desc'  => __( 'Comments text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Background', 'youzify' ),
            'id'    => 'youzify_comment_button_bg_color',
            'desc'  => __( '"View Comment" background', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Text', 'youzify' ),
            'id'    => 'youzify_comment_button_text_color',
            'desc'  => __( '"View Comment" text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Icon', 'youzify' ),
            'id'    => 'youzify_comment_button_icon_color',
            'desc'  => __( '"View Comment" icon color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}