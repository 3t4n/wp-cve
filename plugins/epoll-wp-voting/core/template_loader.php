<?php 
/***************
 * Author: Rahul Negi
 * Team: InfoTheme
 * Date: 30-6-2022
 * Desc: Custom Post Type Single Page Template , Loading as per requirement or request
 * Happy Coding.....
 **************/

if(!function_exists('load_it_epoll_theme')){
    
    function load_it_epoll_theme(){
        $active_themes = array('default','default-1');

        if(get_option('it_epoll_active_theme')){
            $active_themes = get_option('it_epoll_active_theme');
        }
        array_map('connect_it_epoll_themes',$active_themes);
    }

}

if(!function_exists('connect_it_epoll_themes')){
    function connect_it_epoll_themes($theme){

        $theme_path = IT_EPOLL_DIR_PATH . 'frontend/templates/';
		
        $theme_file = $theme_path.$theme.'/template.php';
        if(file_exists($theme_file)){
            include_once($theme_file);
        }else{
			$theme_file = $theme_path.'default/template.php';
			include_once($theme_file);
		}
    }
}

if(!function_exists('it_epoll_activated_themes_data')){
	function it_epoll_activated_themes_data(){

	}

}


load_it_epoll_theme(); // Calling Load Theme;

if(!function_exists('get_it_epoll_poll_template')){
	
	add_filter( 'single_template', 'get_it_epoll_poll_template' );
    
	function get_it_epoll_poll_template($single_template) {
		global $post;
        $active_theme = 'default';
		$active_theme = get_post_meta($post->ID,'it_epoll_poll_theme',true);
		
		if ($post->post_type == 'it_epoll_poll') {
			$single_template_file = IT_EPOLL_DIR_PATH . 'frontend/templates/'.$active_theme.'/cpt/it_epoll_poll.php';
				
			if(is_file($single_template_file)){
				$single_template = $single_template_file;
			}else{
				$single_template = IT_EPOLL_DIR_PATH . 'frontend/templates/default/cpt/it_epoll_poll.php';
			}
		}//Template to load poll

		if ($post->post_type == 'it_epoll_opinion') {
			$single_template_file = IT_EPOLL_DIR_PATH . 'frontend/templates/'.$active_theme.'/cpt/it_epoll_opinion.php';
				
			if(is_file($single_template_file)){
				$single_template = $single_template_file;
			}else{
				$single_template = IT_EPOLL_DIR_PATH . 'frontend/templates/default/cpt/it_epoll_opinion.php';
			}
		}//Template to load voting
		
		return $single_template;
	}
}