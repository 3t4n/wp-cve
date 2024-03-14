<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('WP_Tracking_Manager_Hooks'))
{
    class WP_Tracking_Manager_Hooks
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions
			add_action('wp_head', array(&$this,'_wp_tracking_manager_head_hooks_func'),-1000);
			add_action('wp_footer', array(&$this,'_wp_tracking_manager_footer_hooks_func'),100);
			add_action('template_redirect', array(&$this,'_wp_tracking_manager_block_thankyou'));
        } // END public function __construct
     /*
	 * @wp_head action hook
	 * @add script into head section
	 * */
       public function wp_tracking_post_types($postion='head')
       {
		  global $post;
		  

		  $post_types = get_post_types(array('public' => true,'_builtin' => false),'names','and'); 
		  array_push($post_types,'post');array_push($post_types,'page');
		  $html = '';

		  foreach($post_types as $val)
			{
			if(isset($post))
			{	
			 if($postion=='head' && $val==$post->post_type){
			 $html .=  get_option('wtm_header_script_'.$val) ? get_option('wtm_header_script_'.$val) :'';}
			 
			 if($postion=='footer' && $val==$post->post_type){
			 $html .=  get_option('wtm_footer_script_'.$val) ? get_option('wtm_footer_script_'.$val) : '';}
		     }  
			}
			
	      echo $html;
		}
     /*
	 * @wp_head action hook
	 * @add script into head section
	 * */
       public function _wp_tracking_manager_head_hooks_func()
       {
		   // added header tracking code
			$wp_tracking_manager_head = get_option('wtm_header_script');
			if($wp_tracking_manager_head!=''){
			echo $wp_tracking_manager_head;}
			
			   global $post;
			   // ID of the thank you page
				if (isset($post) && is_singular('page')) {
				$header = get_post_meta($post->ID,'_wtm_page_header',true);
				echo $header;
                
                // Is Thank you
				$is_thankyou = get_post_meta($post->ID,'_wtm_page_thank_you',true);
				//noindex thank-you page
				if (isset($post) && $is_thankyou) {
				echo '<meta name=”robots” content=”noindex, follow”>';
                }
			   }
			   //add post type specific header tracking code
			   $this->wp_tracking_post_types();
			   
			   
			   
		   }
	/*
	 * @wp_footer action hook
	 * @add script into footer section
	 * */
       public function _wp_tracking_manager_footer_hooks_func()
       {
			$wp_tracking_manager_footer = get_option('wtm_footer_script');
			if($wp_tracking_manager_footer!=''){
			echo $wp_tracking_manager_footer;}
			
			    global $post;
			   // ID of the thank you page
				if (isset($post) && is_singular('page')) {
				$footer = get_post_meta($post->ID,'_wtm_page_footer',true);
				echo $footer;
                }
         	//add post type specific header tracking code
			   $this->wp_tracking_post_types('footer');
		   }
	/*
	 * @template_redirect action hook
	 * @add script into footer section
	 * */
       public function _wp_tracking_manager_block_thankyou()
       {
		   global $post;
			// ID of the thank you page
				if (!isset($post)) {
					return;
				}
				// Is block
				$is_thankyou = get_post_meta($post->ID,'_wtm_page_thank_you',true);

				  if($is_thankyou && !wp_get_referer())
					{
						wp_safe_redirect( get_home_url() );
						exit;
					}
		   }
     }
}
add_action('init','init_class_wp_tracking_plugin_hooks');
function init_class_wp_tracking_plugin_hooks()
{
	new WP_Tracking_Manager_Hooks();
	}
