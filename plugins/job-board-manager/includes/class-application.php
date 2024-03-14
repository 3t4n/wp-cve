<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_applications{

    public $job_id;
	
	public function __construct($job_id = 0){


		}
		
		
		
	public function has_applied($job_id = 0, $email = ''){


        $meta_query = array();

        $meta_query[] = array(
            'key' => 'job_bm_am_job_id',
            'value' => $job_id,
            'compare' => '=',
        );


        $meta_query[] = array(
            'key' => 'job_bm_am_user_email',
            'value' => $email,
            'compare' => '=',
        );


        $wp_query = new WP_Query(
            array (
                'post_type' => 'application',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => $meta_query,
                'posts_per_page' => -1,

            ) );

        if ( $wp_query->have_posts() ) :
            $has_post = true;

            wp_reset_query();
        else:
            $has_post = false;
        endif;



	    return $has_post;

		
		}


    public function application_count_by_job_id($job_id = 0){


        $meta_query = array();

        if(!empty($job_id)){
            $meta_query[] = array(
                'key' => 'job_bm_am_job_id',
                'value' => $job_id,
                'compare' => '=',
            );
        }


        $wp_query = new WP_Query(
            array (
                'post_type' => 'application',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => $meta_query,
                'posts_per_page' => -1,

            ) );

        if ( $wp_query->have_posts() ) :
            $found_posts = $wp_query->found_posts;

            wp_reset_query();
        else:
            $found_posts = 0;
        endif;



        return $found_posts;

    }






}
