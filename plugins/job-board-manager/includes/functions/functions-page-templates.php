<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


add_filter( 'page_template', 'job_bm_page_template_job_xml' );
function job_bm_page_template_job_xml( $page_template ){

    if ( get_page_template_slug() == 'job-bm-job-xml.php' ) {
        $page_template = job_bm_plugin_dir . 'templates/page-templates/job-bm-job-xml.php';
    }

    return $page_template;
}

add_filter( 'theme_page_templates', 'job_bm_add_template_to_select', 10, 4 );
function job_bm_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {

    // Add custom template named template-custom.php to select dropdown
    $post_templates['job-bm-job-xml.php'] = __('Job XML');

    return $post_templates;
}




add_action('job_bm_job_xml', 'job_bm_job_xml_generate');
function job_bm_job_xml_generate($request){

    $action = isset($request['action']) ? sanitize_text_field($request['action']) : '';

        $response = array();
        $error_message = array();
        $meta_query = array();
        $job_bm_search_api_fields = array('job_id', 'title', 'url', 'publish_date', 'job_bm_expire_date', 'job_bm_featured');


        // Extract query arguments
        $keywords = isset($request['keywords']) ? sanitize_text_field($request['keywords']) : '';
        $company_name = isset($request['company_name']) ? sanitize_text_field($request['company_name']) : '';
        $locations = isset($request['locations']) ? sanitize_text_field($request['locations']) : '';

        $per_page = isset($request['per_page']) ? sanitize_text_field($request['per_page']) : 10;
        $page_number = isset($request['page_number']) ? sanitize_text_field($request['page_number']) : 1;

        if( !empty($locations)){
            $meta_query[] = array(
                'key' => 'job_bm_location',
                'value' => $locations,
                'compare' => '=',
            );
        }


        if( !empty($company_name)){
            $meta_query[] = array(
                'key' => 'job_bm_company_name',
                'value' => $company_name,
                'compare' => '=',
            );
        }




        $query_args = array (
            'post_type' => 'job',
            'post_status' => 'publish',
            's' => $keywords,
            'orderby' => 'date',
            'meta_query' => $meta_query,
            //'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $per_page,
            'paged' => $page_number,

        );


        $query_args = apply_filters('job_bm_job_xml_search_query_args', $query_args);
        $wp_query = new WP_Query($query_args);

        $response['site_url'] = get_bloginfo('url');
        $response['found_posts'] = $wp_query->found_posts;


        ?>
        <response version="1.0">
            <keywords><?php echo $keywords; ?></keywords>
            <location><?php echo $locations; ?></location>
            <perPage><?php echo $per_page; ?></perPage>
            <pageNumber><?php echo $page_number; ?></pageNumber>
            <foundPosts><?php echo $wp_query->found_posts; ?></foundPosts>

        <?php


        if ( $wp_query->have_posts() ) :
            ?>
            <results>
                <?php
            while ( $wp_query->have_posts() ) : $wp_query->the_post();

                $job_id = get_the_ID();
                $job_bm_expire_date = get_post_meta($job_id, 'job_bm_expire_date', true);
                $job_bm_job_status = get_post_meta($job_id, 'job_bm_job_status', true);
                $job_bm_total_vacancies = get_post_meta($job_id, 'job_bm_total_vacancies', true);
                $job_bm_job_type = get_post_meta($job_id, 'job_bm_job_type', true);

                ?>
                <result>

                    <?php ob_start(); ?>
                    <jobId><?php echo $job_id; ?></jobId>
                    <jobtitle><?php echo get_the_title(); ?></jobtitle>
                    <joburl><?php echo get_permalink(); ?></joburl>
                    <publishDdate><?php echo get_the_date('Y-m-d'); ?></publishDdate>
                    <expireDate><?php echo $job_bm_expire_date; ?></expireDate>
                    <jobStatus><?php echo $job_bm_job_status; ?></jobStatus>
                    <totalVacancies><?php echo $job_bm_total_vacancies; ?></totalVacancies>
                    <jobType><?php echo $job_bm_job_type; ?></jobType>
                    <?php echo apply_filters('job_bm_job_xml_loop', ob_get_clean(), $job_id); ?>
                </result>
                <?php

                endwhile;
                ?>
            </results>
                <?php

            wp_reset_query();
        else:
            ?>
            <results>
                <noJobs>No job found</noJobs>
            </results>
            <?php

        endif;



        ?>

        </response>
        <?php




}

