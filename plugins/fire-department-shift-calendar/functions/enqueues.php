<?php

	// Load Admin Scripts
	function fd_shift_calendar_admin_scripts(){
		$current_screen = get_current_screen();
		if(strpos($current_screen->base, FD_SHIFT_CAL_SLUG) !== false){
			wp_enqueue_script('bootstrap-datepicker', FD_SHIFT_CAL_PLUGIN_URL . 'js/bootstrap-datepicker/js/bootstrap-datepicker.min.js', false, '1.6.4', true);
			wp_enqueue_script('lity', FD_SHIFT_CAL_PLUGIN_URL . 'js/lity/lity.min.js', array('jquery'), '2.3.1', true);
			wp_enqueue_script('fd-cal-admin-script', FD_SHIFT_CAL_PLUGIN_URL . 'js/admin.min.js', array('jquery', 'lity', 'bootstrap-datepicker', 'wp-color-picker'), '1.0', true);
		}
	}
	add_action( 'admin_enqueue_scripts', 'fd_shift_calendar_admin_scripts' );


	// Load Admin Styles
	function fd_shift_calendar_admin_styles(){
		$current_screen = get_current_screen();
		if(strpos($current_screen->base, FD_SHIFT_CAL_SLUG) !== false){
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style('bootstrap-datepicker-styles', FD_SHIFT_CAL_PLUGIN_URL . 'js/bootstrap-datepicker/css/bootstrap-datepicker.standalone.min.css', false, '1.6.4', 'all');
			wp_enqueue_style('lity-styles', FD_SHIFT_CAL_PLUGIN_URL . 'js/lity/lity.min.css', false, '2.3.1', 'all');
			wp_enqueue_style('fd-cal-admin-styles', FD_SHIFT_CAL_PLUGIN_URL . 'css/admin.min.css', array(), '1.0', 'all');
			wp_enqueue_style('fd-cal-styles', FD_SHIFT_CAL_PLUGIN_URL . 'css/frontend.min.css', array(), '1.0', 'all');
		}
	}
	add_action('admin_head', 'fd_shift_calendar_admin_styles');

	// Load Front-End Scripts
	function fd_shift_calendar_scripts(){

		$ajaxData = array(
			'ajaxUrl' 	=> admin_url('admin-ajax.php'),
			'siteUrl' 	=> get_site_url(),
			'nonce' 	=> wp_create_nonce('fd-shift-calendar-nonce')
		);

		wp_enqueue_script('fd-cal-scripts', FD_SHIFT_CAL_PLUGIN_URL . 'js/frontend.min.js', array(), '1.0');
		wp_localize_script('fd-cal-scripts', 'ajaxData', $ajaxData);
	}
	add_action('wp_enqueue_scripts', 'fd_shift_calendar_scripts');

	// Load Front-End Styles
	function fd_shift_calendar_styles(){
		wp_enqueue_style('fd-cal-styles', FD_SHIFT_CAL_PLUGIN_URL . 'css/frontend.min.css', array(), '1.0', 'all');
		wp_enqueue_style('fd-cal-icons', FD_SHIFT_CAL_PLUGIN_URL . 'icons/css/icons.min.css', array(), '1.0', 'all');
	}
	add_action('wp_enqueue_scripts', 'fd_shift_calendar_styles');
	
?>