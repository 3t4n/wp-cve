<?php
//Add ePoll Gutenberg Block Scripts
if(!function_exists('it_epoll_enque_guten_block_js')){

	function  it_epoll_enque_guten_block_js(){
		$guttenBlockPath = plugins_url( 'assets/js/block.js', dirname(__FILE__) );
		wp_enqueue_script(
			'it_epoll_gutenblock',
			$guttenBlockPath, 
			['wp-blocks'],
			$guttenBlockPath
		);
		wp_enqueue_style( 'it_epoll_gutenblock-style', plugins_url( 'assets/css/editor.css', dirname(__FILE__) ),true);
	
		do_action('it_epoll_module_editor_script_enque');
		
	}

	add_action( 'enqueue_block_editor_assets', 'it_epoll_enque_guten_block_js' );
}




//Add ePoll Admin Scripts
if(!function_exists('it_epoll_js_register')){
	
	add_action( 'admin_enqueue_scripts', 'it_epoll_js_register' );
	function it_epoll_js_register() {
		wp_enqueue_script('media-upload');
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_script('thickbox');
		
        wp_register_script('it_epoll_js', plugins_url('assets/js/it_epollv3.js', dirname(__FILE__) ), array('jquery','media-upload','wp-color-picker','thickbox'));
		wp_enqueue_script('it_epoll_js');

		do_action('it_epoll_module_admin_script_enque');
	}
}

//Add ePoll Admin Style
if(!function_exists('it_epoll_css_register')){
	
	add_action( 'admin_enqueue_scripts', 'it_epoll_css_register' );
	function it_epoll_css_register() {
		wp_register_style('it_epoll_css', plugins_url('assets/css/it_epollv3.css', dirname(__FILE__) ));
		
		wp_enqueue_style(array('thickbox','it_epoll_css'));

		do_action('it_epoll_module_admin_css_enque');
	}
}


	
//Add ePoll Frontend Style
if(!function_exists('it_epoll_enqueue_style')){
	
	add_action( 'wp_enqueue_scripts', 'it_epoll_enqueue_style' );
	function it_epoll_enqueue_style() {
		wp_enqueue_style( 'it_epoll_core', plugins_url('assets/css/epoll-core.css', dirname(__FILE__) ), false ); 
	
		wp_enqueue_style( 'it_epoll_style', plugins_url('assets/css/it_epoll_frontendv3.css', dirname(__FILE__) ), false ); 
		
		wp_enqueue_style( 'it_epoll_opinion_style', plugins_url('assets/css/theme/it_epoll_opinion_fontendv3.css', dirname(__FILE__) ), false ); 
		do_action('it_epoll_module_css_enque');
	}
}

//Add ePoll Frontend Script
if(!function_exists('it_epoll_enqueue_script')){
	add_action( 'wp_enqueue_scripts', 'it_epoll_enqueue_script' );	
	function it_epoll_enqueue_script() {
		do_action('it_epoll_module_script_enque');
		wp_localize_script( 'it_epoll_common_js', 'it_epoll_ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		
		
	}
}


if(!function_exists('it_epoll_add_plugin')){
	function it_epoll_add_plugin( $plugin_array ) {
	$plugin_array['it_epoll'] = plugins_url( 'assets/js/it_epoll_tinymce_btn.js', dirname(__FILE__) );
	return $plugin_array;
	}
}



if(!function_exists('it_epoll_register_button')){
	function it_epoll_register_button( $buttons ) {
	array_push( $buttons, "|", "it_epoll" );
	return $buttons;
	}
}


if(!function_exists('it_epoll_tinymce_setup')){
	function it_epoll_tinymce_setup() {

	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
		return;
	}

	if ( get_user_option('rich_editing') == 'true' ) {
		add_filter( 'mce_external_plugins', 'it_epoll_add_plugin' );
		add_filter( 'mce_buttons', 'it_epoll_register_button' );
	}

	}
	add_action('init', 'it_epoll_tinymce_setup');
}
