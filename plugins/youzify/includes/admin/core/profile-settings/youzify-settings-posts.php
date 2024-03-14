<?php

/**
 * Posts Settings.
 */

function youzify_posts_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Per Page', 'youzify' ),
            'id'    => 'youzify_profile_posts_per_page',
            'desc'  => __( 'How many posts per page?', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Visibility Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Meta', 'youzify' ),
            'id'    => 'youzify_display_post_meta',
            'desc'  => __( 'Show post meta', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Meta Icons', 'youzify' ),
            'id'    => 'youzify_display_post_meta_icons',
            'desc'  => __( 'Show post meta icons', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Excerpt', 'youzify' ),
            'id'    => 'youzify_display_post_excerpt',
            'desc'  => __( 'Show post excerpt', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Date', 'youzify' ),
            'id'    => 'youzify_display_post_date',
            'desc'  => __( 'Show post date', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Categories', 'youzify' ),
            'id'    => 'youzify_display_post_cats',
            'desc'  => __( 'Show post categories', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Comments', 'youzify' ),
            'id'    => 'youzify_display_post_comments',
            'desc'  => __( 'Show comments number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More', 'youzify' ),
            'id'    => 'youzify_display_post_readmore',
            'desc'  => __( 'Show read more button', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Title', 'youzify' ),
            'id'    => 'youzify_post_title_color',
            'desc'  => __( 'Post title color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Meta', 'youzify' ),
            'id'    => 'youzify_post_meta_color',
            'desc'  => __( 'Post meta color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Meta Icons', 'youzify' ),
            'id'    => 'youzify_post_meta_icons_color',
            'desc'  => __( 'Meta icons color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Excerpt', 'youzify' ),
            'id'    => 'youzify_post_text_color',
            'desc'  => __( 'Post excerpt color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More Background', 'youzify' ),
            'id'    => 'youzify_post_button_color',
            'desc'  => __( 'Read more button color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More Text', 'youzify' ),
            'id'    => 'youzify_post_button_text_color',
            'desc'  => __( 'Read more text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More Icon', 'youzify' ),
            'id'    => 'youzify_post_button_icon_color',
            'desc'  => __( 'Read more icon color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}