<?php

/**
 * Courses Settings.
 */

function youzify_courses_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Courses General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Courses Per Page', 'youzify' ),
            'id'    => 'youzify_profile_courses_per_page',
            'desc'  => __( 'How many courses per page?', 'youzify' ),
            'type'  => 'number'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Make Enrolled Courses Tab Public', 'youzify' ),
            'id'    => 'youzify_make_courses_tab_public',
            'desc'  => __( 'Make courses tab visible to everyone. By default each user can see only their own courses tab.', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Courses Visibility Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enrolment Status', 'youzify' ),
            'id'    => 'youzify_display_course_enrolment_status',
            'desc'  => __( 'Show course enrolment status', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Course Author', 'youzify' ),
            'id'    => 'youzify_display_course_author',
            'desc'  => __( 'Show course author', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Course Date', 'youzify' ),
            'id'    => 'youzify_display_course_date',
            'desc'  => __( 'Show course date', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Course Excerpt', 'youzify' ),
            'id'    => 'youzify_display_course_excerpt',
            'desc'  => __( 'Show post excerpt', 'youzify' ),
            'type'  => 'checkbox'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Completion Bar', 'youzify' ),
            'id'    => 'youzify_display_course_completion_bar',
            'desc'  => __( 'Show course completion bar', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Completion Percent', 'youzify' ),
            'id'    => 'youzify_display_course_completion_percent',
            'desc'  => __( 'Show course completion bar', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Completed Lessons', 'youzify' ),
            'id'    => 'youzify_display_course_completed_steps',
            'desc'  => __( 'Show course completed steps', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}