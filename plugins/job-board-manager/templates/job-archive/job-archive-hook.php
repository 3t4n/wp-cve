<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


add_action('job_bm_job_archive_loop_before', 'job_bm_job_archive_before_search', 90, 2);

if(!function_exists('job_bm_job_archive_before_search')){
    function job_bm_job_archive_before_search($wp_query, $atts){

        $display_search = $atts['display_search'];


        if($display_search == 'no') return;


        $job_bm_archive_page_id = get_option('job_bm_archive_page_id');
        $job_bm_archive_page_url = get_permalink($job_bm_archive_page_id);


        $class_job_bm_functions = new class_job_bm_functions();
        $job_type_list = array_filter($class_job_bm_functions->job_type_list());


        if(!empty($_GET['job_type'])){

            $job_type = job_bm_recursive_sanitize_arr($_GET['job_type']);

            if(!is_array($job_type)){

                $job_type = array($job_type);

            }

        }


        if(!empty($_GET['job_bm_job_archive_search_hidden'])){
            do_action('job_bm_job_archive_search_submit_data', $_GET);
        }


        //echo '<pre>'.var_export($job_type_list, true).'</pre>';

        ?>

        <form class="search-input" method="get" action="<?php echo esc_url_raw($job_bm_archive_page_url); ?>">

            <input type="hidden" name="job_bm_job_archive_search_hidden" class="" value="Y">

            <div class="option half">
                <input placeholder="<?php echo __('Keyword', 'job-board-manager'); ?>" name="keywords" type="search" value="<?php if(!empty($_GET['keywords'])) echo esc_attr($_GET['keywords']) ?>" />

            </div>

            <div class="option half">

                <input placeholder="<?php echo __('Location', 'job-board-manager'); ?>" name="locations" type="search" value="<?php if(!empty($_GET['locations'])) echo esc_attr($_GET['locations']) ?>" />
            </div>



            <div class="option">
                <?php

                foreach($job_type_list as $job_type_key=>$job_type_name){

                    if(!empty($job_type_key)):
                        ?>
                        <label>
                            <input type="checkbox" <?php if( !empty($job_type) && in_array($job_type_key, $job_type) ) echo 'checked'; ?>  name="job_type[]" value="<?php echo esc_attr($job_type_key); ?>" /> <?php echo esc_html($job_type_name); ?>
                        </label>
                    <?php
                    endif;

                }


                ?>


            </div>

            <?php

            do_action('job_bm_job_archive_search_form');

            ?>

            <input type="submit" value="<?php echo __('Search', 'job-board-manager'); ?>" />

        </form> <!-- .search-input -->
        <?php

    }
}







add_action('job_bm_job_archive_loop', 'job_bm_job_archive_loop_item_company_logo', 10, 2);

if(!function_exists('job_bm_job_archive_loop_item_company_logo')):
    function job_bm_job_archive_loop_item_company_logo($job_id, $atts){

        $job_bm_default_company_logo = get_option('job_bm_company_logo');
        $job_bm_company_logo = get_post_meta($job_id,'job_bm_company_logo', true);


        if(!empty($job_bm_company_logo)){

            if(is_serialized($job_bm_company_logo)){

                $job_bm_company_logo = unserialize($job_bm_company_logo);
                if(!empty($job_bm_company_logo[0])){
                    $job_bm_company_logo = $job_bm_company_logo[0];
                    $job_bm_company_logo = wp_get_attachment_url($job_bm_company_logo);
                }
                else{
                    $job_bm_company_logo = $job_bm_default_company_logo;

                }
            }

        }
        else{
            $job_bm_company_logo = $job_bm_default_company_logo;

        }


        ?>
        <div class="company_logo">
            <img src="<?php echo apply_filters('job_bm_job_archive_loop_item_company_logo', $job_bm_company_logo); ?>" />
        </div>

        <?php



    }
endif;



add_action('job_bm_job_archive_loop', 'job_bm_job_archive_loop_item_title', 20, 2);

if(!function_exists('job_bm_job_archive_loop_item_title')):
    function job_bm_job_archive_loop_item_title($job_id, $atts){


        ?>
        <div class="title">
            <a <?php echo apply_filters('job_bm_job_archive_loop_item_link_attr', '') ; ?> href="<?php echo apply_filters('job_bm_job_archive_loop_item_link', get_permalink($job_id)) ; ?>"><?php echo get_the_title($job_id); ?></a>
        </div>
        <?php

    }
endif;




add_action('job_bm_job_archive_loop', 'job_bm_job_archive_loop_item_company_name', 30, 2);

if(!function_exists('job_bm_job_archive_loop_item_company_name')):
    function job_bm_job_archive_loop_item_company_name($job_id, $atts){


        $job_bm_company_name = get_post_meta($job_id, 'job_bm_company_name', true);

        if(!empty($job_bm_company_name)): ?>
            <div class="company-name"><?php echo apply_filters('job_bm_job_archive_loop_item_company', $job_bm_company_name); ?></div>
        <?php
        endif;
    }
endif;






add_action('job_bm_job_archive_loop', 'job_bm_job_archive_loop_item_meta', 40, 2);

if(!function_exists('job_bm_job_archive_loop_item_meta')):
    function job_bm_job_archive_loop_item_meta($job_id, $atts){

        $job_bm_job_data = new job_bm_job_data($job_id);

        $salary_html = $job_bm_job_data->get_salary_html();
        $job_salary_duration = $job_bm_job_data->get_salary_duration();
        $job_location = $job_bm_job_data->get_location();
        $job_status = $job_bm_job_data->get_job_status();
        $job_type = $job_bm_job_data->get_job_type();
        $job_expire_date = $job_bm_job_data->get_expire_date();
        $job_expire_in = $job_bm_job_data->get_expire_in();


        //echo '<pre>'.var_export($job_type, true).'</pre>';

        $meta_items = array();


        ob_start();

        if(!empty($job_type['type'])):
            ?>
            <span class="meta-item job_type <?php echo $job_type['type']; ?>"><i class="fas
               fa-briefcase"></i>  <?php echo $job_type['type_name']; ?></span>
            <?php
        endif;


        $meta_items['job_type'] = ob_get_clean();

        ob_start();

        if(!empty($job_status['status'])):?>
            <span class=" meta-item job_status <?php echo $job_status['status']; ?>"><i class="fas
            fa-traffic-light"></i> <?php echo $job_status['status_name']; ?></span>
        <?php endif;

        $meta_items['job_status'] = ob_get_clean();

        if(!empty($job_location)):
            ob_start();
            ?>
            <span class="job-location meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo apply_filters('job_bm_job_archive_loop_item_location', $job_location); ?></span>
            <?php
            $meta_items['location'] = ob_get_clean();
        endif;

        ob_start();

        ?>
        <span class="job-post-date meta-item"><i class="far fa-calendar-alt"></i> <?php echo sprintf(__('Posted %s ago','job-board-manager'), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) )?></span>
        <?php

        $meta_items['date'] = ob_get_clean();


        if(!empty($salary_html)){
            ob_start();
            ?>
            <span class="job-salary meta-item"><i class="fas fa-pizza-slice"></i> <?php echo sprintf(__('Salary: %s','job-board-manager'), $salary_html )?></span>
            <?php

            $meta_items['salary'] = ob_get_clean();
        }

        if(!empty($job_expire_in)):
            ob_start();

            ?>
            <span class="job-post-date meta-item"><i class="far fa-calendar-check"></i> <?php echo sprintf(__('Expire in %s','job-board-manager'), $job_expire_in )?></span>
            <?php

            $meta_items['expire'] = ob_get_clean();
        endif;



        $meta_items = apply_filters('job_bm_job_archive_loop_meta', $meta_items);

        ?>

        <div class="clear"></div>
        <div class="job-meta">
            <?php

            if(!empty($meta_items)):
                foreach ($meta_items as $item):

                    echo $item;

                endforeach;
            endif;
            ?>
        </div>

        <?php



    }
endif;





add_action('job_bm_job_archive_loop_after', 'job_bm_job_archive_after_pagination',90, 2);

if(!function_exists('job_bm_job_archive_after_pagination')){
    function job_bm_job_archive_after_pagination($wp_query, $atts){

        $display_pagination = $atts['display_pagination'];

        if($display_pagination == 'no') return;

        if ( get_query_var('paged') ) {$paged = get_query_var('paged');}
        elseif ( get_query_var('page') ) {$paged = get_query_var('page');}
        else {$paged = 1;}

        //global $wp_query;
        //var_dump($wp_query->max_num_pages);

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

    }
}






add_action('job_bm_job_archive_loop_no_post', 'job_bm_job_archive_loop_no_post_text',90,2);

if(!function_exists('job_bm_job_archive_loop_no_post_text')){
    function job_bm_job_archive_loop_no_post_text($wp_query, $atts){

        ?>
        <div class=""><?php echo apply_filters('job_bm_job_archive_loop_no_post_text', __('Sorry, No jobs found.', 'job-board-manager')); ?></div>
        <?php

    }
}





add_action('job_bm_job_archive_loop_after', 'job_bm_job_archive_after_style',90,2);

if(!function_exists('job_bm_job_archive_after_style')){
    function job_bm_job_archive_after_style($wp_query, $atts){

        $job_bm_featured_bg_color = get_option('job_bm_featured_bg_color');
        $job_bm_job_type_bg_color = get_option('job_bm_job_type_bg_color');
        $job_bm_job_type_text_color = get_option('job_bm_job_type_text_color');
        $job_bm_job_status_bg_color = get_option('job_bm_job_status_bg_color');
        $job_bm_job_status_text_color = get_option('job_bm_job_status_text_color');

        $job_bm_pagination_bg_color = get_option('job_bm_pagination_bg_color');
        $job_bm_pagination_active_bg_color = get_option('job_bm_pagination_active_bg_color');
        $job_bm_pagination_text_color = get_option('job_bm_pagination_text_color');


        ?>
        <style type="text/css">
            <?php

            echo '.job-bm-archive .job-list .single.featured{background:'.$job_bm_featured_bg_color.' !important;}';

            if(!empty($job_bm_job_type_bg_color)){
                foreach($job_bm_job_type_bg_color as $job_type_key=>$job_type_color){

                    echo '.job-bm-archive .job-list .job_type.'.$job_type_key.'{background:'.$job_type_color.' !important;}';
                }
            }

            if(!empty($job_bm_job_type_text_color)){
                foreach($job_bm_job_type_text_color as $job_type_key=>$job_type_color){

                    echo '.job-bm-archive .job-list .job_type.'.$job_type_key.'{color:'.$job_type_color.' !important;}';
                }
            }



            if(!empty($job_bm_job_status_bg_color)){
                foreach($job_bm_job_status_bg_color as $job_status_key=>$job_status_color){
                    echo '.job-bm-archive .job-list .job_status.'.$job_status_key.'{background:'.$job_status_color.' !important;}';
                }
            }

            if(!empty($job_bm_job_status_text_color)){
                foreach($job_bm_job_status_text_color as $job_status_key=>$job_status_color){
                    echo '.job-bm-archive .job-list .job_status.'.$job_status_key.'{color:'.$job_status_color.' !important}';
                }
            }





            ?>

            .job-bm-archive .paginate .page-numbers.current{
                background: <?php echo $job_bm_pagination_active_bg_color; ?>;
                color: <?php echo $job_bm_pagination_text_color; ?> ;
            }
            .job-bm-archive .paginate a.page-numbers{
                background: <?php echo $job_bm_pagination_bg_color; ?>;
                color: <?php echo $job_bm_pagination_text_color; ?> ;
            }

        </style>
        <?php

    }
}








