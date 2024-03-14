<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_job_edit{
	
    public function __construct(){
		
		add_shortcode( 'job_bm_job_edit', array( $this, 'job_bm_job_edit_display' ) );

   		}

	public function job_bm_job_edit_display($atts, $content = null ) {


        $job_bm_can_user_edit_published_jobs = get_option('job_bm_can_user_edit_published_jobs');
        $job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');
        $dashboard_page_url = get_permalink($job_bm_job_login_page_id);
        $dashboard_page_title = get_the_title($job_bm_job_login_page_id);


        $userid = get_current_user_id();


        if(!isset($_GET['job_id'])):
            return apply_filters('job_bm_job_edit_invalid_job_id_text', sprintf(__('Job id is invalid. please go to %s » <a href="%s">My Jobs</a> see your jobs.', 'job-board-manager'), '<strong>'.$dashboard_page_title.'</strong>',$dashboard_page_url.'?tabs=my_jobs'));
        endif;

        $job_id = sanitize_text_field($_GET['job_id']);

        // job poster auhtor id.
        $job_post_data = get_post($job_id, ARRAY_A);
        $author_id = (int)$job_post_data['post_author'];

        //var_dump($userid);
        //var_dump($author_id);

        if(!is_user_logged_in()){
            return apply_filters('job_bm_job_edit_login_required_text', sprintf(__('Please <a href="%s">login</a> to edit job.', 'job-board-manager'), $dashboard_page_url));

        }


        if($job_bm_can_user_edit_published_jobs != 'yes' || $userid != $author_id){

            do_action('job_bm_job_edit_login_required');

            return apply_filters('job_bm_job_edit_unauthorized_text', __('Sorry! you are not authorized to edit this job.', 'job-board-manager'));

        }




        include( job_bm_plugin_dir . 'templates/job-edit/job-edit-hook.php');

		ob_start();
		include( job_bm_plugin_dir . 'templates/job-edit/job-edit.php');

        wp_enqueue_style('job-bm-job-submit');
        wp_enqueue_script('job-bm-job-submit');
        wp_enqueue_script('job-bm-media-upload');
        wp_enqueue_style('job-bm-media-upload');
        // For media uploader in front-end
        wp_enqueue_media();

        ?>
        <style type="text/css">
            .screen-reader-text{
                display: none;
            }
        </style>
        <?php

		return ob_get_clean();
		
		}
	
			
	}
	
new class_job_bm_shortcodes_job_edit();