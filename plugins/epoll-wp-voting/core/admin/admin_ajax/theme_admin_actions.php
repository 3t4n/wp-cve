<?php
//activate theme
if(!function_exists('ajax_it_epoll_theme_action_activate')){

    add_action( 'wp_ajax_it_epoll_theme_action_activate', 'ajax_it_epoll_theme_action_activate' );
    
    function ajax_it_epoll_theme_action_activate() {
        
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_theme_action_activate' and isset($_POST['data']))
        {

            $data = array();
            
              //Function to check user capability
              it_epoll_admin_ajax_capabilities_check();

              if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
              if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
              if ( ! wp_verify_nonce( $nonce, 'it_epoll_theme_action_activate_'.$extension_id ) )  exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
             
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
            
            $active_themes = array('default');

            if(get_option('it_epoll_active_theme')){
                $active_themes = get_option('it_epoll_active_theme');
            }

            if(!in_array('default',$active_themes)){
                array_push($active_themes,'default');
            }

            if(!in_array($id,$active_themes)){
                array_push($active_themes,$id); 
                update_option('it_epoll_active_theme',$active_themes);
                exit(wp_json_encode(array('sts'=>200,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Template Has Been Activated')));
            }else{
                exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Already Activated')));
            }
            
        }
    }
}



//deactivate theme
if(!function_exists('ajax_it_epoll_theme_action_deactivate')){

    add_action( 'wp_ajax_it_epoll_theme_action_deactivate', 'ajax_it_epoll_theme_action_deactivate' );
    
    function ajax_it_epoll_theme_action_deactivate() {
        
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_theme_action_deactivate' and isset($_POST['data']))
        {

            $data = array();
             //Function to check user capability
             it_epoll_admin_ajax_capabilities_check();

             if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
             if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
             if ( ! wp_verify_nonce( $nonce, 'it_epoll_theme_action_deactivate_'.$extension_id ) )  exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
          
            
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
           
            $active_themes = array('default');

            if(get_option('it_epoll_active_theme')){
                $active_themes = get_option('it_epoll_active_theme');
            }

            if(($key = array_search($id, $active_themes)) !== false && $active_themes[$key] != 'default'){
                unset($active_themes[$key]);
                update_option('it_epoll_active_theme',$active_themes);
                exit(wp_json_encode(array('sts'=>200,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Template Has Been Deactivated')));
            }else{
                exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Already Deactivated')));
            }
            
        }
    }
}




//uninstall theme
if(!function_exists('ajax_it_epoll_theme_action_uninstall')){

    add_action( 'wp_ajax_it_epoll_theme_action_uninstall', 'ajax_it_epoll_theme_action_uninstall' );
    
    function ajax_it_epoll_theme_action_uninstall() {
        
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_theme_action_uninstall' and isset($_POST['data']))
        {
            
            //Function to check user capability
            it_epoll_admin_ajax_capabilities_check();

            if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
            if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if ( ! wp_verify_nonce( $nonce, 'it_epoll_theme_action_uninstall_'.$extension_id ) )   exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
         
                global $wp_filesystem;
                $data = array();
 
                if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
                 
                if(isset($_POST['data']['path'])) $path = sanitize_text_field($_POST['data']['path']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon path')));
                
                if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
                
              
                WP_Filesystem();
                if ( ! function_exists( 'wp_handle_upload' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
                
                $active_themes = array('default');
    
                if(get_option('it_epoll_active_theme')){
                    $active_themes = get_option('it_epoll_active_theme');
                }
    
                if(!in_array($path,$active_themes) and $path != 'default' and $path != null and $path != "/"){
                    $theme_path = IT_EPOLL_DIR_PATH . 'frontend/templates/'.$path;
                   
                    if(file_exists($theme_path)){
                        $wp_filesystem->delete($theme_path,true);//removing files of theme
                        exit(wp_json_encode(array('sts'=>200,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Template Has Been Removed')));
                    }else{
                        exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Already Removed / Not Found')));
                    }
                }else{
                    exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Can\'t uninstall this')));
                }
           
    }
}
}


//install update theme
if(!function_exists('ajax_it_epoll_theme_action_install_update')){

    add_action( 'wp_ajax_it_epoll_theme_action_install_update', 'ajax_it_epoll_theme_action_install_update' );
    
    function ajax_it_epoll_theme_action_install_update() {
        
        if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_theme_action_install_update' and isset($_POST['data']))
        {
           
      
            //Function to check user capability
            it_epoll_admin_ajax_capabilities_check();

            if(isset($_POST['wp_nonce']))  $nonce = sanitize_text_field($_POST['wp_nonce']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Nonce')));
            if(isset($_POST['extension_id']))  $extension_id = sanitize_text_field($_POST['extension_id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
            if ( ! wp_verify_nonce( $nonce, 'it_epoll_theme_action_install_update_'.$extension_id ) )  exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'Security Check Failed! :- Please Refresh your page')));
            
            $data = array();
            if(isset($_POST['data']['id'])) $id = sanitize_text_field($_POST['data']['id']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon Id')));
             
            if(isset($_POST['data']['url'])) $download_url = sanitize_text_field($_POST['data']['url']); else exit(wp_json_encode(array('sts'=>404,'data'=>array(),'msg'=>'Invalid Addon url')));
            
            if(isset($_POST['data']['name']))  $name = sanitize_text_field($_POST['data']['name']); else $name = "Unknown";
           
            $download_url = esc_url($download_url,'it_epoll');
           
            
          
           if(it_epoll_MyDomainCheck($download_url)){
                $response = it_epoll_install_from_store_zip($download_url,'frontend/templates/','it_epoll_upload_theme','template');
                print_r($response);
                exit;
                }else{
                    exit(0);
                }
            
        }
    }
}