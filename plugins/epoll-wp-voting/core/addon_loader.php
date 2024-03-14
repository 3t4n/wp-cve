<?php 
/***************
 * Author: Rahul Negi
 * Team: InfoTheme
 * Date: 30-6-2022
 * Desc: Addon Loader , Loading as per requirement or request
 * Happy Coding.....
 **************/
if(!function_exists('load_it_epoll_addon')){
    
    function load_it_epoll_addon(){
        $active_addons = array('default','default-1');

        if(get_option('it_epoll_active_addon')){
            $active_addons = get_option('it_epoll_active_addon');
        }
        array_map('connect_it_epoll_addons',$active_addons);
    }

}

if(!function_exists('connect_it_epoll_addons')){
    function connect_it_epoll_addons($addon){
        $addon_path = IT_EPOLL_DIR_PATH . 'backend/addons/';
        $addon_file = $addon_path.$addon.'/addon.php';
        if(is_file($addon_file)){
            include_once($addon_file);
        }
    }
}

if(!function_exists('run_activator_script_it_epoll_addon')){
    function run_activator_script_it_epoll_addon($addon){
        $addon_path = IT_EPOLL_DIR_PATH . 'backend/addons/';
        $addon_file = $addon_path.$addon.'/activate.php';
        
        if(is_file($addon_file)){
            include_once($addon_file);
            do_action('it_epoll_activate_intial_script'); 
       
        }
    }
}


if(!function_exists('run_deactivator_script_it_epoll_addon')){
    function run_deactivator_script_it_epoll_addon($addon){
        $addon_path = IT_EPOLL_DIR_PATH . 'backend/addons/';
        $addon_file = $addon_path.$addon.'/deactivate.php';
        if(is_file($addon_file)){
            include_once($addon_file);
            do_action('it_epoll_deactivate_intial_script'); 
        }
    }
}

load_it_epoll_addon(); // Calling Load Addon;