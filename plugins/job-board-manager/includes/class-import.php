<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_job_bm_import {
	
	public function __construct(){

		//add_action('add_meta_boxes', array($this, 'meta_boxes_job'));
		//add_action('save_post', array($this, 'meta_boxes_job_save'));

		}
		

	public function insert_job_data($job_data){

        $post_title = isset($job_data['post_title'] )? sanitize_text_field($job_data['post_title']) : '';
        $post_content = isset($job_data['post_content']) ? wp_kses_post($job_data['post_content']) : '';

        $taxonomy = isset($job_data['taxonomy']) ? $job_data['taxonomy'] : '';
        $taxonomy_terms = isset($job_data['taxonomy_terms']) ? $job_data['taxonomy_terms'] : '';

        $job_bm_is_imported = isset($job_data['job_bm_is_imported']) ? $job_data['job_bm_is_imported'] : '';
        $job_bm_import_source = isset($job_data['job_bm_import_source']) ? $job_data['job_bm_import_source'] : '';
        $job_bm_import_source_jobid = isset($job_data['job_bm_import_source_jobid']) ? $job_data['job_bm_import_source_jobid'] : '';
        $job_bm_imported_job_url = isset($job_data['job_bm_imported_job_url']) ? $job_data['job_bm_imported_job_url'] : '';


        $meta_fields = isset($job_data['meta_fields']) ? $job_data['meta_fields'] : array();


        $meta_query[] = array(
            'key' => 'job_bm_is_imported',
            'value' => 'yes',
            'compare' => '=',
        );

        $meta_query[] = array(
            'key' => 'job_bm_import_source',
            'value' => $job_bm_import_source,
            'compare' => '=',
        );
								
        $meta_query[] = array(
            'key' => 'job_bm_import_source_jobid',
            'value' => $job_bm_import_source_jobid,
            'compare' => '=',
        );

        $query_args = array(
            'post_type' => 'job',
            'post_status' => 'publish',
            'meta_query' => $meta_query,
            'posts_per_page' => -1,
        );

        $query_args = apply_filters('job_bm_job_import_query_args', $query_args);

        $wp_query = new WP_Query($query_args);
		
		
			if ($wp_query->have_posts()):
			// No post insert.
			else:

                $job_bm_submitted_job_status = get_option('job_bm_submitted_job_status');

                if (is_user_logged_in()){
                    $userid = get_current_user_id();
                }
                else{
                    $userid = 1;
                }

                $job_post = array(
                  'post_title'    => $post_title,
                  'post_content'  => $post_content,
                  'post_status'   => $job_bm_submitted_job_status,
                  'post_type'   => 'job',
                  'post_author'   => $userid,
                );

                // Insert the post into the database
                $job_ID = wp_insert_post($job_post);

                update_post_meta($job_ID, 'job_bm_is_imported', $job_bm_is_imported);
                update_post_meta($job_ID, 'job_bm_import_source_jobid', $job_bm_import_source_jobid);
                update_post_meta($job_ID, 'job_bm_import_source', $job_bm_import_source);
                update_post_meta($job_ID, 'job_bm_imported_job_url', $job_bm_imported_job_url);



                if(!empty($taxonomy_terms)){
                    wp_set_post_terms( $job_ID, $taxonomy_terms, $taxonomy );
                }


                if(!empty($meta_fields)){
                    foreach($meta_fields as $meta_key => $meta_value){
                        update_post_meta($job_ID, $meta_key , $meta_value);
                    }
                }
            endif;

		}

	}
	
//new class_job_bm_import();