<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

add_action('job_bm_my_jobs','job_bm_my_jobs_title');

if(!function_exists('job_bm_my_jobs_title')):
    function job_bm_my_jobs_title(){

        $job_bm_job_submit_page_id                       = get_option( 'job_bm_job_submit_page_id' );
        $job_bm_job_submit_page_url          = get_permalink($job_bm_job_submit_page_id);


        ?>
            <div class="nav-head">
                <?php echo __('My Jobs', 'job-board-manager'); ?>
                <a  <?php echo apply_filters('job_bm_my_jobs_add_job_link_attr', ''); ?> title="<?php echo __('Add Job', 'job-board-manager'); ?>" class="add-job" href="<?php echo $job_bm_job_submit_page_url; ?>"><?php echo sprintf(__('%s Add Job', 'job-board-manager'),'<i class="far fa-plus-square"></i>')?></a>
            </div>
        <?php

    }

endif;


//add_filter('job_bm_my_jobs_add_job_link_attr','job_bm_my_jobs_add_job_link_attr_target');
//
//function job_bm_my_jobs_add_job_link_attr_target($html){
//
//
//    $html .= 'target="_blank"';
//
//    return $html;
//
//}



add_action('job_bm_my_jobs','job_bm_my_jobs_list');

if(!function_exists('job_bm_my_jobs_list')):

    function job_bm_my_jobs_list(){
        ?>
        <div class="job-bm-my-jobs">

            <?php

            $date_format                        = get_option( 'date_format' );
            $job_bm_can_user_edit_published_jobs  = get_option( 'job_bm_can_user_edit_published_jobs','no' );
            $job_bm_can_user_delete_jobs  = get_option( 'job_bm_can_user_delete_jobs','no' );
            $job_bm_job_login_page_id           = get_option('job_bm_job_login_page_id');
            $job_bm_job_login_page_url          = get_permalink($job_bm_job_login_page_id);


            $display_edit = 'yes';
            $display_delete = 'yes';

            if ( is_user_logged_in() ){

                $userid                     = get_current_user_id();
                $job_bm_list_per_page       = get_option('job_bm_list_per_page');
                $job_bm_job_type_bg_color   = get_option('job_bm_job_type_bg_color');
                $job_bm_job_status_bg_color = get_option('job_bm_job_status_bg_color');
                $job_bm_featured_bg_color   = get_option('job_bm_featured_bg_color');
                $job_bm_job_edit_page_id    = get_option('job_bm_job_edit_page_id');
                $job_bm_job_edit_page_url   = get_permalink($job_bm_job_edit_page_id);
                $job_bm_list_per_page       = !empty($job_bm_list_per_page) ? $job_bm_list_per_page : 10;



                if ( get_query_var('paged') ) {
                    $paged = get_query_var('paged');
                } elseif ( get_query_var('page') ) {
                    $paged = get_query_var('page');
                } else {
                    $paged = 1;
                }


                $wp_query = new WP_Query(
                    array (
                        'post_type' => 'job',
                        'post_status' => 'any',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'author' => $userid,
                        'posts_per_page' => $job_bm_list_per_page,
                        'paged' => $paged,
                    )
                );

                ?>
                <div class="job-list">
                    <?php

                    $class_job_bm_functions = new class_job_bm_functions();
                    $class_job_bm_applications = new class_job_bm_applications();

                    $job_type_filters = $class_job_bm_functions->job_type_list();
                    $job_level_filters = $class_job_bm_functions->job_level_list();
                    $job_status_filters = $class_job_bm_functions->job_status_list();


                    if ( $wp_query->have_posts() ) :
                        while ( $wp_query->have_posts() ) : $wp_query->the_post();

                            $job_id         = get_the_id();

                            do_action('job_bm_my_jobs_loop', $job_id);


                        endwhile;

                        ?>
                        <div class="paginate">
                            <?php
                            $big = 999999999; // need an unlikely integer
                            echo paginate_links( array(
                                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                'format' => '?paged=%#%',
                                'current' => max( 1, $paged ),
                                'total' => $wp_query->max_num_pages
                            ) );

                            ?>
                        </div>
                        <?php

                        wp_reset_query();

                    else:

                        echo sprintf(__('%s No job found posted by you.', 'job-board-manager'), '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>');

                    endif;


                    ?>
                </div>
                <?php
            }
            else{
                echo sprintf(__('Please <a href="%s">login</a> to see your jobs.', 'job-board-manager' ), $job_bm_job_login_page_url );

            }

            ?>

        </div>
        <?php

    }

endif;



add_action('job_bm_my_jobs_loop','job_bm_my_jobs_loop_wrap_start');

if(!function_exists('job_bm_my_jobs_loop_wrap_start')){
    function job_bm_my_jobs_loop_wrap_start($job_id){


        ?>
        <div class="my-job-card my-job-card-<?php echo $job_id; ?>">



        <?php

    }
}



add_action('job_bm_my_jobs_loop','job_bm_my_jobs_loop_header');

if(!function_exists('job_bm_my_jobs_loop_header')){
    function job_bm_my_jobs_loop_header($job_id){

        $class_job_bm_applications = new class_job_bm_applications();

        $job_bm_job_edit_page_id    = get_option('job_bm_job_edit_page_id');
        $job_bm_job_edit_page_url   = get_permalink($job_bm_job_edit_page_id);

        $featured       = get_post_meta($job_id, 'job_bm_featured',true);
        $featured_class = ($featured == 'yes') ? 'featured' : '';
        $application_count = $class_job_bm_applications->application_count_by_job_id($job_id);
        $application_hired_count = job_bm_job_application_hired_count($job_id);

        ?>
        <div class="card-top">
            <div class="card-action">
                <span class="job-id" title="<?php echo __('Job id.', 'job-board-manager'); ?>">#<?php echo $job_id; ?></span>
                <a class="job-edit" title="<?php echo __('Job edit.', 'job-board-manager'); ?>" href="<?php echo $job_bm_job_edit_page_url; ?>?job_id=<?php echo $job_id; ?>" target="_blank"><i class="fas fa-pencil-alt"></i></a>
                <span class="job-delete delete-job" job-id="<?php echo $job_id; ?>" title="<?php echo __('Job trash.', 'job-board-manager'); ?>"><i class="far fa-trash-alt"></i></span>
                <span class="job-featured <?php echo $featured_class; ?>" title="<?php echo ($featured=='yes') ?  __('Featured job.', 'job-board-manager') : __('Not featured', 'job-board-manager'); ?>"><i class="fas fa-star"></i></span>
                <span class="job-application" title="<?php echo __('Total application.', 'job-board-manager'); ?>"><i class="fas fa-user-clock"></i> <?php echo $application_count; ?></span>
                <span class="job-hired" title="<?php echo __('Total hired.', 'job-board-manager'); ?>"><i class="fas fa-user-tie"></i> <?php echo $application_hired_count; ?></span>

            </div>

        </div>



        <?php

    }
}



add_action('job_bm_my_jobs_loop','job_bm_my_jobs_loop_body');

if(!function_exists('job_bm_my_jobs_loop_body')){
    function job_bm_my_jobs_loop_body($job_id){

        $class_job_bm_functions = new class_job_bm_functions();
        $class_job_bm_applications = new class_job_bm_applications();

        $job_type_filters = $class_job_bm_functions->job_type_list();
        $job_level_filters = $class_job_bm_functions->job_level_list();
        $job_status_filters = $class_job_bm_functions->job_status_list();
        $application_count = $class_job_bm_applications->application_count_by_job_id($job_id);


        $job_bm_job_login_page_id    = get_option('job_bm_job_login_page_id');
        $job_bm_job_login_page_url   = get_permalink($job_bm_job_login_page_id);

        $job_title = get_the_title($job_id);
        $post_date      = get_the_date('Y-m-d');
        $date_format                        = get_option( 'date_format' );

        $expiry_date    = get_post_meta($job_id, 'job_bm_expire_date',true);
        $publish_status = get_post_status($job_id);
        $job_status     = get_post_meta($job_id, 'job_bm_job_status',true);
        $featured       = get_post_meta($job_id, 'job_bm_featured',true);
        $job_type       = get_post_meta($job_id, 'job_bm_job_type',true);
        $job_label = get_post_meta($job_id, 'job_bm_job_level',true);

        $get_post_stati = get_post_statuses();

        //var_dump($get_post_stati);


        ?>
        <div class="card-body">

            <a title="<?php echo __('Job link.', 'job-board-manager'); ?>" class="job-link meta" href="<?php echo get_permalink($job_id); ?>"><i class="fas fa-external-link-square-alt"></i> <?php echo $job_title; ?></a>

            <span class="post-date meta"><b><?php echo __('Published:', 'job-board-manager'); ?></b> <?php echo date_i18n($date_format,strtotime($post_date)); ?></span>
            <span class="publish-status meta"><b><?php echo __('Publish Status:', 'job-board-manager'); ?></b> <?php echo $get_post_stati[$publish_status]; ?></span>
            <span class="featured meta"><b><?php echo __('Featured:', 'job-board-manager'); ?></b> <?php if($featured == 'yes'){echo __('Yes', 'job-board-manager'); }  else{ echo __('No', 'job-board-manager'); } ?></span>
            <?php


            if(!empty($job_status_filters[$job_status])):
                ?>
                <span class="job-status meta"><b><?php echo __('Job Status:', 'job-board-manager'); ?></b> <?php echo $job_status_filters[$job_status]; ?></span>
            <?php
            endif;




            if(!empty($job_type_filters[$job_type])):
                ?>
                <span class="type meta"><b><?php echo __('Job Type:', 'job-board-manager'); ?></b> <?php echo $job_type_filters[$job_type]; ?></span>
            <?php
            endif;


            if(!empty($job_level_filters[$job_label])):
                ?>
                <span class="level meta"><b><?php echo __('Job Level:', 'job-board-manager'); ?></b> <?php echo $job_level_filters[$job_label]; ?></span>
            <?php
            endif;

            ?>
            <span class="applications meta"><b><?php echo __('Applications:', 'job-board-manager'); ?></b> <a href="<?php echo $job_bm_job_login_page_url; ?>?tabs=applications"><?php echo $application_count; ?></a> </span>

        </div>



        <?php

    }
}



















add_action('job_bm_my_jobs_loop','job_bm_my_jobs_loop_wrap_end');

if(!function_exists('job_bm_my_jobs_loop_wrap_end')){
    function job_bm_my_jobs_loop_wrap_end(){


        ?>

        </div>

        <?php

    }
}













add_action('job_bm_my_jobs','job_bm_my_jobs_style');

if(!function_exists('job_bm_my_jobs_style')){
    function job_bm_my_jobs_style(){

        $job_bm_pagination_bg_color         = get_option('job_bm_pagination_bg_color');
        $job_bm_pagination_active_bg_color  = get_option('job_bm_pagination_active_bg_color');
        $job_bm_pagination_text_color       = get_option('job_bm_pagination_text_color');

        ?>
        <style type="text/css">
            .job-bm-my-jobs .paginate .page-numbers.current{
                background: <?php echo $job_bm_pagination_active_bg_color; ?>;
                color: <?php echo $job_bm_pagination_text_color; ?> ;
            }
            .job-bm-my-jobs .paginate a.page-numbers{
                background: <?php echo $job_bm_pagination_bg_color; ?>;
                color: <?php echo $job_bm_pagination_text_color; ?> ;
            }
        </style>
        <?php

    }
}










