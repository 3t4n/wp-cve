<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_registration_form{
	
    public function __construct(){
		
		add_shortcode( 'job_bm_registration_form', array( $this, 'job_bm_registration_form_display' ) );

   		}

	public function job_bm_registration_form_display($atts, $content = null ){
		
		$atts = shortcode_atts(
			array(
				//'themes' => 'flat',
				), $atts);





        ob_start();
		
		include( job_bm_plugin_dir . 'templates/registration-form/registration-form.php');

        //wp_localize_script('job-bm-applications', 'job_bm_ajax', array( 'job_bm_ajaxurl' => admin_url( 'admin-ajax.php')));
        //wp_enqueue_script('job-bm-registration-form');

        wp_enqueue_style('job-bm-registration-form');


        return ob_get_clean();
		
		}
	
			
	}
	
new class_job_bm_shortcodes_registration_form();