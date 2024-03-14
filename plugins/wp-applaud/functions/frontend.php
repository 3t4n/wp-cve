<?php

class wpApplaud {
	function __construct() 
    {	
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        add_filter('the_content', array(&$this, 'the_content'));
        add_filter('the_excerpt', array(&$this, 'the_content'));
        add_filter('body_class', array(&$this, 'body_class'));
        add_action('publish_post', array(&$this, 'setup_likes'));
        add_action('wp_ajax_wp-applaud', array(&$this, 'ajax_callback'));
		add_action('wp_ajax_nopriv_wp-applaud', array(&$this, 'ajax_callback'));
        
        add_shortcode('wp_applaud', array(&$this, 'shortcode'));
	}

	function enqueue_scripts()
	{
	    $options = get_option( 'wp_applaud_settings' );
		if( !isset($options['disable_css']) )
			$options['disable_css'] = '0';

		if(!$options['disable_css'])
			wp_enqueue_style( 'wp-applaud', plugins_url( '/assets/styles/wp-applaud.css', dirname(__FILE__) ) );
		
		wp_enqueue_script( 'wp-applaud', plugins_url( '/assets/scripts/wp-applaud.js', dirname(__FILE__) ), array('jquery') );
		wp_enqueue_script( 'jquery' ); 
		
		wp_localize_script( 'wp-applaud', 'wp_applaud', array('ajaxurl' => admin_url('admin-ajax.php'), 'user_likes' => $options['single_user_likes']) );
	}
	
	function the_content( $content )
	{	
		global $post;

		//exclude Applaud, if set to from setting
		$value = get_post_meta( $post->ID, '_wp_applaud_exclude', true );
		if($value) return $content;

	    // Don't show on custom page templates
	    if(is_page_template()) return $content;

		global $wp_current_filter;
		if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
			return $content;
		}
		
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['add_to_posts']) ) $options['add_to_posts'] = '0';
		if( !isset($options['add_to_pages']) ) $options['add_to_pages'] = '0';
		if( !isset($options['add_to_other']) ) $options['add_to_other'] = '0';
		if( !isset($options['exclude_from']) ) $options['exclude_from'] = '';
		
		$ids = explode(',', $options['exclude_from']);
		if(in_array(get_the_ID(), $ids)) return $content;
		
		if(is_singular('post') && $options['add_to_posts']) $content .= $this->do_likes();
		if(is_page() && !is_front_page() && $options['add_to_pages']) $content .= $this->do_likes();
		if((is_front_page() || is_home() || is_category() || is_tag() || is_author() || is_date() || is_search()) && $options['add_to_other'] ) $content .= $this->do_likes();
		
		return $content;
	}
	
	function setup_likes( $post_id ) 
	{
		if(!is_numeric($post_id)) return;
	
		add_post_meta($post_id, '_wp_applaud', '0', true);
	}
	
	function ajax_callback($post_id) 
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['add_to_posts']) ) $options['add_to_posts'] = '0';
		if( !isset($options['add_to_pages']) ) $options['add_to_pages'] = '0';
		if( !isset($options['add_to_other']) ) $options['add_to_other'] = '0';
		if( !isset($options['zero_title']) ) $options['zero_title'] = '';
		if( !isset($options['one_title']) ) $options['one_title'] = '';
		if( !isset($options['more_title']) ) $options['more_title'] = '';
		if( !isset($options['single_user_likes']) ) $options['single_user_likes'] = 1;

		if( isset($_POST['likes_id']) ) {
		    // Click event. Get and Update Count
			$post_id = str_replace('wp-applaud-', '', $_POST['likes_id']);
			echo $this->like_this($post_id, $options['single_user_likes'], $options['zero_title'], $options['one_title'], $options['more_title'], 'update');
		} else {
		    // AJAXing data in. Get Count
			$post_id = str_replace('wp-applaud-', '', $_POST['post_id']);
			echo $this->like_this($post_id, $options['single_user_likes'], $options['zero_title'], $options['one_title'], $options['more_title'], 'get');
		}
		
		exit;
	}

	function get_title($user_likes = 0, $zero_title = false, $one_title = false, $more_title = false) {

		$not_liked_title = __('Applaud this', 'wpapplaud');
		$liked_title = __('You already applaud this', 'wpapplaud');

		$zero_title = isset($zero_title) ? $zero_title : $not_liked_title;
		$one_title = isset($one_title) ? $one_title : $liked_title;
		$more_title = isset($more_title) ? $more_title : $liked_title;

		if( $user_likes == 0 ) { $postfix = $zero_title; }
		elseif( $user_likes == 1 ) { $postfix = $one_title; }
		else { $postfix = $more_title; }

		return $postfix;
	}
	
	function like_this($post_id, $single_user_likes = 1, $zero_title = false, $one_title = false, $more_title = false, $action = 'get') 
	{

		if(!is_numeric($post_id)) return;
		$zero_title = strip_tags($zero_title);
		$one_title = strip_tags($one_title);
		$more_title = strip_tags($more_title);
		switch($action) {
		
			case 'get':
				$likes = get_post_meta($post_id, '_wp_applaud', true);
				if( !$likes ){
					$likes = 0;
					add_post_meta($post_id, '_wp_applaud', $likes, true);
				}
				
				return '<span class="wp-applaud-count">'. $likes .'</span>';
				break;
				
			case 'update':
				$result = array();

				$likes = get_post_meta($post_id, '_wp_applaud', true);
				
				$user_likes = isset($_COOKIE['wp_applaud_'. $post_id .'_count']) ? $_COOKIE['wp_applaud_'. $post_id .'_count'] : 0;

				if($user_likes >= $single_user_likes) {
					if( isset($_COOKIE['wp_applaud_'. $post_id]) ) {
						$results['likes'] = $likes;
						return $results;
					}
				}
				
				$user_likes++;
				
				$likes++;
				
				$results['likes'] = $likes;
				$results['user_likes'] = $user_likes;

				update_post_meta($post_id, '_wp_applaud', $likes);
				setcookie('wp_applaud_'. $post_id, $post_id, time()*20, '/');

				setcookie('wp_applaud_'. $post_id .'_count', $user_likes, time()*20, '/');
				
				$title = $this->get_title($user_likes, $zero_title, $one_title, $more_title);
				
				$results['title'] = $title;

				$results['html'] = '<span class="wp-applaud-count">'. $likes .'</span>';

				return json_encode($results);

				break;
		
		}
	}
	
	function shortcode( $atts )
	{
		extract( shortcode_atts( array(
		), $atts ) );
		
		return $this->do_likes();
	}
	
	function do_likes()
	{
		global $post;

        $options = get_option( 'wp_applaud_settings' );
		if( !isset($options['zero_title']) ) $options['zero_title'] = '';
		if( !isset($options['one_title']) ) $options['one_title'] = '';
		if( !isset($options['more_title']) ) $options['more_title'] = '';
		if( !isset($options['single_user_likes']) ) $options['single_user_likes'] = 1;
		
		$output = $this->like_this($post->ID, $options['single_user_likes'], $options['zero_title'], $options['one_title'], $options['more_title']);
  
		$class = 'wp-applaud';

		$user_likes = isset($_COOKIE['wp_applaud_'. $post->ID .'_count']) ? $_COOKIE['wp_applaud_'. $post->ID .'_count'] : 0;

		$title = $this->get_title($user_likes, $options['zero_title'], $options['one_title'], $options['more_title']);

		if($user_likes >= $options['single_user_likes']) {
			if( isset($_COOKIE['wp_applaud_'. $post->ID]) ){
				$class = 'wp-applaud active';
			}
		}
		
		return '<a href="#" class="'. $class .'" id="wp-applaud-'. $post->ID .'" title="'. $title .'">'. $output .'</a>';
	}
	
    function body_class($classes) {
        $options = get_option( 'wp_applaud_settings' );
        
        if( !isset($options['ajax_likes']) ) $options['ajax_likes'] = false;
        
        if( $options['ajax_likes'] ) {
        	$classes[] = 'ajax-wp-applaud';
    	}
    	return $classes;
    }
}

?>