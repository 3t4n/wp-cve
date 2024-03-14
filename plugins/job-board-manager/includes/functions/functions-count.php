<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 






function job_bm_user_job_count($user_id=0){

    $user_id = !empty($user_id) ? $user_id : get_current_user_id();

    $job_ids = array();

    $wp_query = new WP_Query(
        array (
            'post_type' => 'job',
            'orderby' => 'date',
            'order' => 'DESC',
            'author' => $user_id,
            'posts_per_page' => -1,
        )
    );


    if ( $wp_query->have_posts() ) :

        $post_count = $wp_query->found_posts;
    else:
        $post_count = 0;
    endif;


    return $post_count;
}



function job_bm_user_application_count($user_id=0){

    $user_id = !empty($user_id) ? $user_id : get_current_user_id();

    $job_ids = array();

    $wp_query = new WP_Query(
        array (
            'post_type' => 'application',
            'orderby' => 'date',
            'order' => 'DESC',
            'author' => $user_id,
            'posts_per_page' => -1,
        )
    );


    if ( $wp_query->have_posts() ) :

        $post_count = $wp_query->found_posts;
    else:
        $post_count = 0;
    endif;


    return $post_count;
}



function job_bm_user_application_hired_count($user_id=0){

    $user_id = !empty($user_id) ? $user_id : get_current_user_id();

    $job_ids = array();
    $meta_query = array();

    $meta_query[] = array(
        'key' => 'application_hired',
        'value' => 'yes',
        'compare' => '=',
    );


    $wp_query = new WP_Query(
        array (
            'post_type' => 'application',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => $meta_query,
            'author' => $user_id,
            'posts_per_page' => -1,
        )
    );


    if ( $wp_query->have_posts() ) :

        $post_count = $wp_query->found_posts;
    else:
        $post_count = 0;
    endif;


    return $post_count;
}



function job_bm_user_application_received_count($user_id=0){

    $user_id = !empty($user_id) ? $user_id : get_current_user_id();
    $current_user_job_ids = job_ids_by_user($user_id);

    $meta_query = array();
    $post_count = 0;
    //var_dump($current_user_job_ids);

    $meta_query['relation'] = 'OR';
    $i=0;
    if(!empty($current_user_job_ids)){

        foreach ($current_user_job_ids as $job_id){

            $meta_query[] = array(

                'relation'=>'AND',

                array(
                    'key'	 	=> 'job_bm_am_job_id',
                    'value'	  	=> $job_id,
                    'compare' 	=> '=',
                ),
                array(
                    'key' => 'application_trash',
                    'value'	  	=> 'yes',
                    'compare' 	=> 'NOT EXISTS',
                    'type' => 'CHAR',
                )
            );



            $i++;
        }


        $wp_query = new WP_Query(
            array (
                'post_type' => 'application',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => $meta_query,
                //'author' => $user_id,
                'posts_per_page' => -1,
            )
        );

        //var_dump($wp_query->found_posts);

        if ( $wp_query->have_posts() ) :

            $post_count = $wp_query->found_posts;
        else:
            $post_count = 0;
        endif;



    }







    return $post_count;
}


function job_bm_job_application_count($job_id=0){

    $meta_query = array();

    $meta_query[] = array(
        'key' => 'job_bm_am_job_id',
        'value' => $job_id,
        'compare' => '=',
        //'type' => 'CHAR',
    );


    $wp_query = new WP_Query(
        array (
            'post_type' => 'application',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => $meta_query,
            'posts_per_page' => -1,
        )
    );


    if ( $wp_query->have_posts() ) :

        $post_count = $wp_query->found_posts;
    else:
        $post_count = 0;
    endif;


    return $post_count;
}







function job_bm_job_application_hired_count($job_id=0){

    $meta_query = array();

    $meta_query[] = array(
        'key' => 'job_bm_am_job_id',
        'value' => $job_id,
        'compare' => '=',
        //'type' => 'CHAR',
    );


    $meta_query[] = array(
        'key' => 'application_hired',
        'value' => 'yes',
        'compare' => '=',
        //'type' => 'CHAR',
    );

    $wp_query = new WP_Query(
        array (
            'post_type' => 'application',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => $meta_query,
            'posts_per_page' => -1,
        )
    );


    if ( $wp_query->have_posts() ) :

        $post_count = $wp_query->found_posts;
    else:
        $post_count = 0;
    endif;


    return $post_count;
}




