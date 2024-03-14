<?php

/**
 * Project Settings.
 */
function youzify_project_widget_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_project_title',
            'desc'  => __( 'Type widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Types', 'youzify' ),
            'id'    => 'youzify_wg_project_types',
            'desc'  => __( 'Add project types', 'youzify' ),
            'type'  => 'taxonomy'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_project_load_effect',
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
            'title' => __( 'Project Meta', 'youzify' ),
            'desc'  => __( 'Show project meta', 'youzify' ),
            'id'    => 'youzify_display_prjct_meta',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Meta Icons', 'youzify' ),
            'desc'  => __( 'Show project icons', 'youzify' ),
            'id'    => 'youzify_display_prjct_meta_icons',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Tags', 'youzify' ),
            'id'    => 'youzify_display_prjct_tags',
            'desc'  => __( 'Show project tags', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Type Background', 'youzify' ),
            'desc'  => __( 'Project type background color', 'youzify' ),
            'id'    => 'youzify_wg_project_type_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Type Text', 'youzify' ),
            'desc'  => __( 'Type text color', 'youzify' ),
            'id'    => 'youzify_wg_project_type_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Title', 'youzify' ),
            'desc'  => __( 'Project title color', 'youzify' ),
            'id'    => 'youzify_wg_project_title_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Meta', 'youzify' ),
            'id'    => 'youzify_wg_project_meta_txt_color',
            'desc'  => __( 'Project meta color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Meta Icons', 'youzify' ),
            'id'    => 'youzify_wg_project_meta_icon_color',
            'desc'  => __( 'Project icons color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Description', 'youzify' ),
            'desc'  => __( 'Project description color', 'youzify' ),
            'id'    => 'youzify_wg_project_desc_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Tags', 'youzify' ),
            'id'    => 'youzify_wg_project_tags_color',
            'desc'  => __( 'Project tags color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Background', 'youzify' ),
            'id'    => 'youzify_wg_project_tags_bg_color',
            'desc'  => __( 'Tags background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Hashtag', 'youzify' ),
            'desc'  => __( 'Project hashtags color', 'youzify' ),
            'id'    => 'youzify_wg_project_tags_hashtag_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}