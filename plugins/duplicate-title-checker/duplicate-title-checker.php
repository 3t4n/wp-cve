<?php
/* 	
Plugin Name: Duplicate Title Checker
Plugin URI: http://wordpress.org/extend/plugins/duplicate-title-checker/
Description: This plugin provides alert message for duplicate post title and unique post title when adding new post.
Author:  Ketan Ajani
Version: 1.2
Author URI: http://www.webconfines.com
*/
	
//////////////////////////this is duplicate title check///////////////////////////////////


	add_filter( 'admin_notices', 'duplicate_notice' );

	function duplicate_notice() {
		echo '<div id="duplicate-message" class="' . esc_attr( $response['status'] ) . '"></div>';
	}


	//jQuery to send AJAX request - only available on the post editing page
	function duplicate_titles_enqueue_scripts( $hook ) {

		if( !in_array( $hook, array( 'post.php', 'post-new.php' ) ) )
			return;



		if (get_option( 'classic-editor-replace' )=='block') {
			
			
			if(function_exists( 'block_version' ))
			{
				wp_enqueue_script( 'duptitles_title_checker', plugins_url( 'js/duptitles-title-checker-block-editor.js', __FILE__ ), array( 'jquery', 'wp-data', 'wp-notices' ), false, true );
			}
			else
			{
			wp_enqueue_script( 'duptitles_title_checker', plugins_url( 'js/duptitles.js', __FILE__ ), array( 'jquery' ), false, true );	
			}
			
		} else {
			wp_enqueue_script( 'duptitles_title_checker', plugins_url( 'js/duptitles.js', __FILE__ ), array( 'jquery' ), false, true );
		}

		//wp_enqueue_script( 'duptitles',wp_enqueue_script( 'duptitles',plugins_url().'/duplicate-title-checker/js/duptitles.js',array( 'jquery' )), array( 'jquery' )  );
	}
	add_action( 'admin_enqueue_scripts', 'duplicate_titles_enqueue_scripts', 2000 );

	add_action('wp_ajax_title_check', 'duplicate_title_check_callback');

	function duplicate_title_check_callback() {

		function title_check() {

			$title = $_REQUEST['post_title'];
			$post_id = $_REQUEST['post_id'];
			$post_type = $_REQUEST['post_type'];

			global $wpdb;

			$sim_titles = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = '".$post_type."' 
						AND post_title = '{$title}' AND ID != {$post_id} ";

			$sim_results = $wpdb->get_results($sim_titles);

			if($sim_results) {
				$titles = '<ul>';
				foreach ( $sim_results as $the_title ) 
				{
					$titles .= '<li>'.$the_title->post_title.'</li>';
				}
				$titles .= '</ul>';

				//return $titles;
				return array(
				'message' => __("Please enter another title it's already exists"),
				'status'  => 'error',
			);
			} else {
				return array(
				'message' => __( 'This title is unique.', 'unique-title-checker' ),
				'status'  => 'updated',
			);;
			}
		}		
		$response =  title_check();
		echo wp_json_encode( $response );
		die();
	}

	function disable_autosave() {
		wp_deregister_script('autosave');
	}
	add_action( 'wp_print_scripts', 'disable_autosave' );


?>
