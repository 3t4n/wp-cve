<?php
//Set Voting Session with Key and Value Pair
if(!function_exists('it_epoll_generate_unique_vote_session')){
	function it_epoll_generate_unique_vote_session($session_key,$poll_id=''){
		$poll_restriction = get_post_meta($poll_id,'it_epoll_poll_voting_restriction',true);
		if($poll_restriction){
			$session_value = uniqid();
			if(get_option('it_epoll_settings_cookies_blocking') && $poll_restriction != 'session'){
				setcookie($session_key, $session_value, time() + (86400 * 30), "/"); 
			}else{
				$_SESSION[$session_key]= $session_value;
			}
		}
	}
}


//Initialize Voting Session
if(!function_exists('it_epoll_unset_unique_vote_session')){
	function it_epoll_unset_unique_vote_session($session_key){
		
		if(get_option('it_epoll_settings_cookies_blocking')){
			unset($_COOKIE[$session_key]); 
			setcookie($session_key, null, -1, '/');
		}else{
			unset($_SESSION[$session_key]);
		}
	}
}

//Unset Voting Session
if(!function_exists('it_epoll_init_unique_vote_session')){
	function it_epoll_init_unique_vote_session(){
				
		if(!get_option('it_epoll_settings_cookies_blocking')){
		
			if (session_status() === PHP_SESSION_NONE) {
				@session_start();
			}
		}
	}
}



//Get Voting Session
if(!function_exists('it_epoll_get_unique_vote_session')){
	function it_epoll_get_unique_vote_session($session_key){
				
		if(get_option('it_epoll_settings_cookies_blocking')){
			if(isset($_COOKIE[$session_key])){
				return $_COOKIE[$session_key];
			}else{
				return "";
			}
		}else{
			if(isset($_SESSION[$session_key])){
				return $_SESSION[$session_key];
			}else{
				return "";
			}
		}
	}
}


if(!function_exists('it_epoll_check_for_unique_voting')){

	function it_epoll_check_for_unique_voting($poll_id,$option_id){
		if(get_post_meta($poll_id,'it_epoll_poll_status',true) == 'end'){
			return true;
		}
		do_action('it_epoll_check_for_unique_voting_add_rules', array('poll_id'=>$poll_id,'option_id'=>$option_id));
		return apply_filters( 'it_epoll_check_for_unique_voting_callback', false, array('poll_id'=>$poll_id,'option_id'=>$option_id));
	}
}

//update and confirm data to results table
if(!function_exists('it_epoll_updateIPBasedData')){
    function it_epoll_updateIPBasedData($args){
        do_action('it_epoll_update_voter_available_data',$args);
		return apply_filters('it_epoll_update_voter_available_data_callback',false);
    }
}

//adding data to results table
if(!function_exists('it_epoll_saveIPBasedData')){
    function it_epoll_saveIPBasedData($args){
         do_action('it_epoll_save_voter_available_data',$args);
		return apply_filters('it_epoll_save_voter_available_data_callback',false);
    }
}

//adding check vooting uniquenesss option
if(!function_exists('it_epoll_check_for_unique_vote_default_addon_rule')){
	add_action('it_epoll_check_for_unique_voting_add_rules','it_epoll_check_for_unique_vote_default_addon_rule');
	function it_epoll_check_for_unique_vote_default_addon_rule($args){
		$poll_id = $args['poll_id'];
		$option_id = $args['option_id'];
		
		if(get_post_meta($poll_id,'it_epoll_poll_multichoice',true)){
			
			if(it_epoll_get_unique_vote_session('it_epoll_session_'.$option_id)){
				remove_filter('it_epoll_check_for_unique_voting_callback','__return_true');
				add_filter('it_epoll_check_for_unique_voting_callback','__return_true');
            }else{
				remove_filter('it_epoll_check_for_unique_voting_callback','__return_false');
                add_filter('it_epoll_check_for_unique_voting_callback','__return_false');
			}
		}else{
			if(it_epoll_get_unique_vote_session('it_epoll_session_'.$poll_id)){
				remove_filter('it_epoll_check_for_unique_voting_callback','__return_true');
				add_filter('it_epoll_check_for_unique_voting_callback','__return_true');
			}else{
				if(it_epoll_get_unique_vote_session('it_epoll_session')){
					remove_filter('it_epoll_check_for_unique_voting_callback','__return_true');
					add_filter('it_epoll_check_for_unique_voting_callback','__return_true');
				}else{
					remove_filter('it_epoll_check_for_unique_voting_callback','__return_false');
					add_filter('it_epoll_check_for_unique_voting_callback','__return_false');
				}
			}
		}
	}
	
}


//Set poll End cron
if(!function_exists('it_epoll_add_cront_event_to_update_poll_end_status')){

	add_filter( 'it_epoll_poll_schedule_cron_event', 'it_epoll_add_cront_event_to_update_poll_end_status' );

	function it_epoll_add_cront_event_to_update_poll_end_status( $post_id ) {
	   // Adds once weekly to the existing schedules.
	   // Define remaining parameters
	$args = array( $post_id );
	$hook = 'it_epoll_poll_update_cron_end_event';
	$timestamp_after_hour = get_post_meta($post_id,'it_epoll_vote_end_date_time',true);
	// Get the timestmap of an already scheduled event for the same post and the same event action ($hook)
	// Returns false if it is not scheduled
	$scheduled_timestamp = wp_next_scheduled( $hook, $args );
	
	if( $scheduled_timestamp == false && $timestamp_after_hour) {
		wp_schedule_single_event( strtotime($timestamp_after_hour), $hook, $args );
	}
  
	}
}

if(!function_exists('it_epoll_process_event_status_end_update')){

	add_action( 'it_epoll_poll_update_cron_end_event', 'it_epoll_process_event_status_end_update' );
	/**
	 * @param numeric $post_id The ID passed through the hook in the $arg variable
	 */
	function it_epoll_process_event_status_end_update( $post_id ) {
	  // Create the logic to share the post
	  update_post_meta($post_id,'it_epoll_poll_status','end');
	}
}

//Set poll Start cron
if(!function_exists('it_epoll_add_cront_event_to_update_poll_status')){

	add_filter( 'it_epoll_poll_schedule_cron_event', 'it_epoll_add_cront_event_to_update_poll_status' );

	function it_epoll_add_cront_event_to_update_poll_status( $post_id ) {
	   // Adds once weekly to the existing schedules.
	   // Define remaining parameters
	$args = array( $post_id );
	$hook = 'it_epoll_poll_update_cron_start_event';
	
	// Get the timestmap of an already scheduled event for the same post and the same event action ($hook)
	// Returns false if it is not scheduled
	$timestamp_after_hour = get_post_meta($post_id,'it_epoll_vote_start_date_time',true);
	// Get the timestmap of an already scheduled event for the same post and the same event action ($hook)
	// Returns false if it is not scheduled
	$scheduled_timestamp = wp_next_scheduled( $hook, $args );
		
		if( $scheduled_timestamp == false && $timestamp_after_hour && $timestamp_after_hour != gmdate('m/d/Y')) {
			update_post_meta($post_id,'it_epoll_poll_status','upcoming');
			wp_schedule_single_event( strtotime($timestamp_after_hour), $hook, $args );
		}
	}
}

if(!function_exists('it_epoll_process_event_status_update')){

	add_action( 'it_epoll_poll_update_cron_start_event', 'it_epoll_process_event_status_update' );
	/**
	 * @param numeric $post_id The ID passed through the hook in the $arg variable
	 */
	function it_epoll_process_event_status_update( $post_id ) {
		update_post_meta($post_id,'it_epoll_poll_status','live');
	  // Create the logic to share the post
	}
}

if(!function_exists('it_epoll_get_branding_sharer_text')){
	function it_epoll_get_branding_sharer_text(){
			return __('&nbsp; &nbsp; &nbsp; Powered By ePoll 3.1 - WordPress Voting Plugin https://wordpress.org/plugins/epoll-wp-voting/','it_epoll');
	}
}

if(!function_exists('it_epoll_get_branding_text')){
	function it_epoll_get_branding_text(){
			return __('Via WP Poll & Voting Contest Maker','it_epoll');
	}
}