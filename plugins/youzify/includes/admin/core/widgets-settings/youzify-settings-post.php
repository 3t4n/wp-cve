<?php

/**
 * Post Settings.
 */
function youzify_post_widget_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Types', 'youzify' ),
            'id'    => 'youzify_wg_post_types',
            'desc'  => __( 'Add post types', 'youzify' ),
            'type'  => 'taxonomy'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_post_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Visibility Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_post_title',
            'desc'  => __( 'Type widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Meta', 'youzify' ),
            'id'    => 'youzify_display_wg_post_meta',
            'desc'  => __( 'Show post meta', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Meta Icons', 'youzify' ),
            'desc'  => __( 'Show meta icons', 'youzify' ),
            'id'    => 'youzify_display_wg_post_meta_icons',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Date', 'youzify' ),
            'id'    => 'youzify_display_wg_post_date',
            'desc'  => __( 'Show post date', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Comments', 'youzify' ),
            'id'    => 'youzify_display_wg_post_comments',
            'desc'  => __( 'Show post comments', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Excerpt', 'youzify' ),
            'id'    => 'youzify_display_wg_post_excerpt',
            'desc'  => __( 'Show post excerpt', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Tags', 'youzify' ),
            'id'    => 'youzify_display_wg_post_tags',
            'desc'  => __( 'Show post tags', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More', 'youzify' ),
            'id'    => 'youzify_display_wg_post_readmore',
            'desc'  => __( 'Show read more button', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Styling Widget', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Type Background', 'youzify' ),
            'id'    => 'youzify_wg_post_type_bg_color',
            'desc'  => __( 'Post type background', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Type Text', 'youzify' ),
            'id'    => 'youzify_wg_post_type_txt_color',
            'desc'  => __( 'Type text color ', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzify' ),
            'id'    => 'youzify_wg_post_title_color',
            'desc'  => __( 'Post title color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Meta', 'youzify' ),
            'id'    => 'youzify_wg_post_meta_txt_color',
            'desc'  => __( 'Post meta color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Meta Icons', 'youzify' ),
            'id'    => 'youzify_wg_post_meta_icon_color',
            'desc'  => __( 'Meta icons color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Excerpt', 'youzify' ),
            'id'    => 'youzify_wg_post_text_color',
            'desc'  => __( 'Post excerpt color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags', 'youzify' ),
            'id'    => 'youzify_wg_post_tags_color',
            'desc'  => __( 'Post tags color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Background', 'youzify' ),
            'id'    => 'youzify_wg_post_tags_bg_color',
            'desc'  => __( 'Tags background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Hashtag', 'youzify' ),
            'id'    => 'youzify_wg_post_tags_hashtag_color',
            'desc'  => __( 'Post hashtags color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More', 'youzify' ),
            'id'    => 'youzify_wg_post_rm_color',
            'desc'  => __( 'Read more text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More Background', 'youzify' ),
            'id'    => 'youzify_wg_post_rm_bg_color',
            'desc'  => __( 'Read more background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Read More Icon', 'youzify' ),
            'id'    => 'youzify_wg_post_rm_icon_color',
            'desc'  => __( 'Read more icon color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}