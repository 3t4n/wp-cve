<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

add_action('job_bm_job_categories_loop', 'job_bm_job_categories_loop_thumb', 1, 10);

function job_bm_job_categories_loop_thumb($args){

    $term_id = isset($args['term_id']) ? $args['term_id'] : '';


    $term_thumb 	 		= get_term_meta($term_id,  'job_bm_thumb', true );
    $term_thumb_url	= wp_get_attachment_url( $term_thumb );

    if(empty($term_thumb_url)) return;
    ?>
    <div class="job-category-thumb"><img src="<?php echo $term_thumb_url; ?>"> </div>

    <?php

}


add_action('job_bm_job_categories_loop', 'job_bm_job_categories_loop_title',1,20);

function job_bm_job_categories_loop_title($args){

    $term = isset($args['term']) ? $args['term'] : '';
    $term_name = $term->name;


    ?>
    <div class="job-category-title"><?php echo $term_name; ?></div>

    <?php

}


add_action('job_bm_job_categories_loop', 'job_bm_job_categories_loop_count',1,30);

function job_bm_job_categories_loop_count($args){

    $term = isset($args['term']) ? $args['term'] : '';
    $term_count = $term->count;


    ?>
    <div class="job-category-count">(<?php echo $term_count; ?>)</div>

    <?php

}










