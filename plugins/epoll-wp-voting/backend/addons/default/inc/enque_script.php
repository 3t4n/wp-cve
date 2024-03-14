<?php 
if(!function_exists('it_epoll_js_register_addon_default_admin')){
	add_action('it_epoll_module_admin_script_enque','it_epoll_js_register_addon_default_admin');
	function it_epoll_js_register_addon_default_admin() {
		wp_register_script('it_epoll_addon_default_admin', plugins_url('assets/js/it_epoll_addon_default_admin.js', dirname(__FILE__) ), array('jquery','thickbox'));
		wp_enqueue_script('it_epoll_addon_default_admin');
	}
}

//Add ePoll OTP Voting Script
if(!function_exists('it_epoll_enqueue_script_default_addon_frontend_js')){
	add_action( 'it_epoll_module_script_enque', 'it_epoll_enqueue_script_default_addon_frontend_js' );	
	function it_epoll_enqueue_script_default_addon_frontend_js() {
		
			wp_enqueue_script( 'it_epoll_validetta_script', plugins_url('assets/js/jquery.validate.min.js',dirname(__FILE__) ), array('jquery'), true);
			
			wp_enqueue_script( 'it_epoll_common_js', plugins_url('assets/js/it_epoll_common.js', dirname(__FILE__) ), array('jquery'), true);
			
			wp_enqueue_script( 'it_epoll_opinion_voting_js', plugins_url('assets/js/it_epoll_opinion_voting.js', dirname(__FILE__) ), array('jquery'),true);
		
			wp_enqueue_script( 'it_epoll_poll_voting_js', plugins_url('assets/js/it_epoll_poll_voting.js', dirname(__FILE__) ), array('jquery'),true);
			
			
	}
}