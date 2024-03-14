<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_dashboard{
	
    public function __construct(){
		
		add_shortcode( 'job_bm_dashboard', array( $this, 'job_bm_dashboard' ) );	

   		}

    public function job_bm_dashboard($atts, $content = null ) {

        include( job_bm_plugin_dir . 'templates/job-dashboard/job-dashboard-hook.php');

        ob_start();

        include( job_bm_plugin_dir . 'templates/job-dashboard/job-dashboard.php');

        wp_enqueue_script( 'job-bm-notice' );
        wp_enqueue_style( 'font-awesome-5' );
        wp_enqueue_style( 'job-bm-dashboard' );
        wp_enqueue_style( 'job-bm-notice' );



        return ob_get_clean();
    }

}
	
	new class_job_bm_shortcodes_dashboard();