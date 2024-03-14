<?php
//activate addon
if(!function_exists('ajax_it_epoll_addon_action_activate')){

    add_action( 'wp_ajax_it_epoll_addon_action_activate', 'ajax_it_epoll_addon_action_activate' );
    
    function ajax_it_epoll_addon_action_activate() {
        
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_addon_action_activate' and isset($_POST['data']))
        {

            $data = array();
             //Function to check user capability
             it_epoll_admin_ajax_capabilities_check();

            if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
            if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if ( ! wp_verify_nonce( $nonce, 'it_epoll_addon_action_activate_'.$extension_id ) )  exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
           
            
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
           
            $active_addons = array('default');

            if(get_option('it_epoll_active_addon')){
                $active_addons = get_option('it_epoll_active_addon');
            }

            if(!in_array('default',$active_addons)){
                array_push($active_addons,'default');
            }

            if(!in_array($id,$active_addons)){
                array_push($active_addons,$id);
                update_option('it_epoll_active_addon',$active_addons);
                run_activator_script_it_epoll_addon($id);
                exit(wp_json_encode(array('sts'=>200,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Addon Has Been Activated')));
            }else{
                exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Already Activated')));
            }
            
        }
    }
}



//deactivate addon
if(!function_exists('ajax_it_epoll_addon_action_deactivate')){

    add_action( 'wp_ajax_it_epoll_addon_action_deactivate', 'ajax_it_epoll_addon_action_deactivate' );
    
    function ajax_it_epoll_addon_action_deactivate() {
        
       if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_addon_action_deactivate' and isset($_POST['data']))
        {
    
            $data = array();
            
            //Function to check user capability
            it_epoll_admin_ajax_capabilities_check();

            if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
            if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if ( ! wp_verify_nonce( $nonce, 'it_epoll_addon_action_deactivate_'.$extension_id ) )   exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
            
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
           
            $active_addons = array('default');

            if(get_option('it_epoll_active_addon')){
                $active_addons = get_option('it_epoll_active_addon');
            }

            if(($key = array_search($id, $active_addons)) !== false && $active_addons[$key] != 'default'){
                unset($active_addons[$key]);
               
                update_option('it_epoll_active_addon',$active_addons);
                run_deactivator_script_it_epoll_addon($id);
                exit(wp_json_encode(array('sts'=>200,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Addon Has Been Deactivated')));
            }else{
                exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Already Deactivated')));
            }
            
        }
    }
}




//uninstall addon
if(!function_exists('ajax_it_epoll_addon_action_uninstall')){

    add_action( 'wp_ajax_it_epoll_addon_action_uninstall', 'ajax_it_epoll_addon_action_uninstall' );
    
    function ajax_it_epoll_addon_action_uninstall() {
        
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_addon_action_uninstall' and isset($_POST['data']))
        {
    
            $data = array();
            global $wp_filesystem;
                //Function to check user capability
             it_epoll_admin_ajax_capabilities_check();

             if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
             if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
             if ( ! wp_verify_nonce( $nonce, 'it_epoll_addon_action_uninstall_'.$extension_id ) )  exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
            
            
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
             
            if(isset($_POST['data']['path'])) $path = sanitize_text_field($_POST['data']['path']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon path')));
            
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
            
          
            WP_Filesystem();
                if ( ! function_exists( 'wp_handle_upload' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
            $active_addons = array('default');

            if(get_option('it_epoll_active_addon')){
                $active_addons = get_option('it_epoll_active_addon');
            }

            if(!in_array($path,$active_addons) and $path != 'default' and $path != null and $path != "/"){
                $addon_path = IT_EPOLL_DIR_PATH . 'backend/addons/'.$path;
               
                if(file_exists($addon_path)){
                     $wp_filesystem->delete($addon_path,true);//removing files of addon
                    exit(wp_json_encode(array('sts'=>200,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Addon Has Been Removed')));
                }else{
                    exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Already Removed / Not Found')));
                }
            }else{
                exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Can\'t uninstall this')));
            }
            
        }
    }
}


//install update addon
if(!function_exists('ajax_it_epoll_addon_action_install_update')){

    add_action( 'wp_ajax_it_epoll_addon_action_install_update', 'ajax_it_epoll_addon_action_install_update' );
    
    function ajax_it_epoll_addon_action_install_update() {
        
         
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_addon_action_install_update' and isset($_POST['data']))
        {
    
            $data = array();
            //Function to check user capability
            it_epoll_admin_ajax_capabilities_check();

            if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
            if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if ( ! wp_verify_nonce( $nonce, 'it_epoll_addon_action_install_update_'.$extension_id ) ) exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
            
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
             
            if(isset($_POST['data']['url'])) $download_url = sanitize_text_field($_POST['data']['url']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon url')));
            
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
           
           
            $download_url = esc_url($download_url,'it_epoll');
            if(it_epoll_MyDomainCheck($download_url)){
                $response = it_epoll_install_from_store_zip($download_url,'backend/addons/','it_epoll_upload_addon','addon');
                print_r($response);
                exit;
            }else{
                exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'You don\'t have permission to do this!')));
            }
            
        }
    }
}
