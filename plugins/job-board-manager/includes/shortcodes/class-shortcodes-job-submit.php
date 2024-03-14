<?php



if (!defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_job_submit
{

    public function __construct()
    {

        add_shortcode('job_submit_form', array($this, 'job_bm_post_job_display'));
        add_shortcode('job_bm_job_submit', array($this, 'job_bm_post_job_display'));
    }

    public function job_bm_post_job_display($atts, $content = null)
    {


        $job_bm_account_required_post_job = get_option('job_bm_account_required_post_job');
        $job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');
        $dashboard_page_url = get_permalink($job_bm_job_login_page_id);


        if ($job_bm_account_required_post_job == 'yes' && !is_user_logged_in()) {

            do_action('job_bm_job_submit_login_required');

            return apply_filters('job_bm_job_submit_login_required_text', sprintf(__('Please <a href="%s">login</a> to submit job.', 'job-board-manager'), $dashboard_page_url));
        }



        include(job_bm_plugin_dir . 'templates/job-submit/job-submit-hook.php');

        ob_start();
        include(job_bm_plugin_dir . 'templates/job-submit/job-submit.php');





        // CSS & JS for sob submission form
        wp_enqueue_style('job-bm-job-submit');
        wp_enqueue_script('job-bm-job-submit');
        wp_localize_script('job-bm-job-submit', 'job_bm_ajax', array('job_bm_ajaxurl' => admin_url('admin-ajax.php')));


        wp_enqueue_script('job-bm-media-upload');
        wp_enqueue_style('job-bm-media-upload');


        // For media uploader in front-end
        wp_enqueue_media();
        //wp_enqueue_style('media-views');

?>
        <style type="text/css">
            .screen-reader-text {
                display: none;
            }
        </style>
<?php

        return ob_get_clean();
    }
}

new class_job_bm_shortcodes_job_submit();
