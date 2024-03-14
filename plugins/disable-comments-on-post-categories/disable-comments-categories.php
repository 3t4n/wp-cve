<?php 
    /*
    Plugin Name: Disable Comments on Post Categories
    Plugin URI: http://www.spoontalk.com
    Description: A simple plugin which lets you disable comments on specific Post Categories. 
    Author: Ankit
    Version: 0.91
    Author URI: http://www.spoontalk.com
    */

	
	
	
					
		add_action('admin_menu','stdc_disable_categories_comments_menu');


        /*
        * Add the menu
        *
        * Create the Plugin Menu
        *
        */
		function stdc_disable_categories_comments_menu()
		{
        
		
	
	
	       add_submenu_page( 'options-general.php', 'Disable Comments on Post Categories Page', 'Disable Comments Post Categories', 'manage_options', 'disable_categories_comments', 'stdc_simple_comments_settings_page' ); 
			
		}
	
	
	
	
	// Load the Settings Panel
	function stdc_simple_comments_settings_page() {

	require_once('settings-page.php');
	
	
}

// We will hook after the post object has been created. Our function will check if the post belongs to disabled category.. 
// If yes,  then run respective filters and actions.   


	add_action( 'the_post', 'stdc_check_for_closed' );

	
	function stdc_check_for_closed() {

		global $post;


		$my_post_cat = wp_get_post_categories($post->ID);
   
		$disabled_cat = get_option('st_disable_comments_post_cat');
		
		// When running for the first time, $disabled_cat will be an empty string. 
	    //So this check will convert it into array so that we can do array comparison
       
	   if(empty($disabled_cat)){$disabled_cat = array();}

		$my_result = array_intersect($my_post_cat,$disabled_cat);
 
			if (empty($my_result)) 
				{return; }
 
			else    { 
					add_filter( 'comments_open', 'stdc_close_comments_on_category', 10, 2 );
					add_filter( 'comments_template', 'stdc_load_empty_template');
					add_action('wp_head', 'stdc_deregister_reply_js');
		 
					}
								}


		function stdc_deregister_reply_js() 
		{
		wp_deregister_script( 'comment-reply' );

		}

		function stdc_load_empty_template ($comment_template) 
		{
			return dirname( __FILE__ ) . '/comments-template.php';
		}
		
		function stdc_close_comments_on_category ($open, $post_id) 
		{
			$open = false;
		}


	



?>