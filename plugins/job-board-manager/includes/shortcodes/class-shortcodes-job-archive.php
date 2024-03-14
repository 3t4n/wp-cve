<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_job_archive{
	
    public function __construct(){
		
		add_shortcode( 'job_list', array( $this, 'job_bm_job_archive_display' ) );
        add_shortcode( 'job_bm_archive', array( $this, 'job_bm_job_archive_display' ) );

   		}

	public function job_bm_job_archive_display($atts, $content = null ) {

        $atts = shortcode_atts(
            array(
                'display_search' => 'yes',
                'display_pagination' => 'yes',
                'keywords' => '',
                'company_name' => '',
                'location' => '',
                'per_page' => '',
                'cat_ids' => '',
            ), $atts);

        $display_search = $atts['display_search'];

        include( job_bm_plugin_dir . 'templates/job-archive/job-archive-hook.php');

        ob_start();
		
		include( job_bm_plugin_dir . 'templates/job-archive/job-archive.php');

        wp_enqueue_style('job_bm_job_archive');
        wp_enqueue_style('font-awesome-5');
		return ob_get_clean();
		
		}
	
			
	}
	
new class_job_bm_shortcodes_job_archive();