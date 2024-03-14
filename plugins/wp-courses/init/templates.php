<?php

    /*
    ** Use Custom Templates
    */

    add_filter( 'template_include', 'wpc_single_lesson_page_template', 99 );
    function wpc_single_lesson_page_template( $template )
    {
        if ( get_post_type() == 'lesson' && is_single() ) {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/single-lesson.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_archive_lesson_page_template', 99 );
    function wpc_archive_lesson_page_template( $template )
    {
        if ( is_post_type_archive( 'lesson' ) && get_post_type() == 'lesson' ) {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/archive-lesson.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_archive_course_page_template', 99 );
    function wpc_archive_course_page_template( $template )
    {
        if ( is_post_type_archive( 'course' ) && get_post_type() == 'course') {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/archive-course.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_single_course_page_template', 99 );
    function wpc_single_course_page_template( $template )
    {
        if ( is_single() && get_post_type() == 'course') {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/single-course.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_archive_teacher_page_template', 99 );
    function wpc_archive_teacher_page_template( $template )
    {
        if ( is_post_type_archive( 'teacher' )) {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/archive-teacher.php';
            //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_course_category_page_template', 9 );
    function wpc_course_category_page_template( $template )
    {
        if ( is_tax() == 'course-category' && get_post_type() == 'course') {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/category-course.php';
            //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }

    add_filter( 'template_include', 'wpc_single_quiz_page_template_shim', 100 );
    function wpc_single_quiz_page_template_shim( $template )
    {
        if ( get_post_type() == 'wpc-quiz' && is_single() ) {
            $new_template = wpc_dirname_r( __FILE__, 2 ) . '/templates/single-quiz.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    
?>