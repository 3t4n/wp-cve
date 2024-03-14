<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_payment{
	
    public function __construct(){
		
		add_shortcode( 'job_bm_payment', array( $this, 'job_bm_payment' ) );

   		}

    public function job_bm_payment($atts, $content = null ) {

        include( job_bm_plugin_dir . 'templates/payment/job-payment-hook.php');

        ob_start();

        include( job_bm_plugin_dir . 'templates/payment/job-payment.php');

        //wp_enqueue_script( 'job-bm-notice' );
        wp_enqueue_style( 'font-awesome-5' );
        wp_enqueue_style( 'job-bm-dashboard' );
        wp_enqueue_style( 'job-bm-notice' );



        return ob_get_clean();
    }

}
	
	new class_job_bm_shortcodes_payment();