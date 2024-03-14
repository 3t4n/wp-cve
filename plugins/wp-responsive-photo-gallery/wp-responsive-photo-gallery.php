<?php
    /* 
    Plugin Name:Masonry Tiled Gallery & Photo Gallery Slideshow
    Plugin URI:https://www.i13websolution.com
    Author URI:https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/
    Description: This is beautiful masonry tiled gallery and photo gallery slideshow plugin for wordPress blogs and sites.Admin can manages any number of images for photo slideshow and unlimited media into the masonry gallery.
    Author:I Thirteen Web Solution
    Version:1.0.15
    Text Domain:wp-responsive-photo-gallery
    Domain Path: /languages
    */
    //error_reporting(0);

     if ( ! defined( 'ABSPATH' ) ) exit; 
    
    $dir = plugin_dir_path( __FILE__ );
    $dir=str_replace("\\","/",$dir);
    if(!class_exists('resize')){

        require_once($dir.'classes/class.Images.php');
    } 

    add_action('admin_menu', 'add_my_responsive_photo_gallery_admin_menu');
    //add_action( 'admin_init', 'my_responsive_photo_gallery_admin_init' );
    register_activation_hook(__FILE__,'install_my_responsive_photo_gallery');
    register_deactivation_hook(__FILE__,'rsp_responsive_photo_gallery_remove_access_capabilities');
    add_action('wp_enqueue_scripts', 'my_responsive_photo_gallery_load_styles_and_js');
    add_shortcode('print_my_responsive_photo_gallery', 'print_my_responsive_photo_gallery_func' );
    add_filter('widget_text', 'do_shortcode');
    add_action ( 'admin_notices', 'wp_responsive_photo_gallery_admin_notices' );
    add_action('plugins_loaded', 'rsp_responsive_photo_gallery_load_lang');
    
    add_action( 'wp_ajax_rjg_check_file_exist_justified_gallery', 'rjg_check_file_exist_justified_gallery_callback' );
    add_action( 'wp_ajax_rjg_get_youtube_info_justified_gallery', 'rjg_get_youtube_info_justified_gallery_callback' );
    add_action( 'wp_ajax_rjg_get_metacafe_info_justified_gallery', 'rjg_get_metacafe_info_justified_gallery_callback' );

    add_action('wp_ajax_rjg_get_grid_data_justified_gallery', 'rjg_get_grid_data_justified_gallery_callback');
    add_action('wp_ajax_nopriv_rjg_get_grid_data_justified_gallery', 'rjg_get_grid_data_justified_gallery_callback');
    add_shortcode ( 'print_masonry_gallery_plus_lightbox', 'rjg_print_masonry_gallery_plus_lightbox_func' );
    add_filter( 'user_has_cap', 'rsp_responsive_photo_gallery_admin_cap_list' , 10, 4 );
    add_action( 'wp_ajax_mass_upload_wpresponsivephgallery', 'wrthslider_slider_mass_upload_wpresponsivephgallery' );
    add_action( 'wp_ajax_mass_upload_wpresponsivephgalleryms', 'wrthslider_slider_mass_upload_wpresponsivephgalleryms' );

    function rsp_responsive_photo_gallery_load_lang() {

            load_plugin_textdomain( 'wp-responsive-photo-gallery', false, basename( dirname( __FILE__ ) ) . '/languages/' );
            add_filter( 'map_meta_cap',  'map_rsp_responsive_photo_gallery_meta_caps', 10, 4 );
    }

    function rs_photogallery_save_image_remote_lbox($url,$saveto){
    
        $raw = wp_remote_retrieve_body( wp_remote_get( $url ) );

        if(file_exists($saveto)){
            @unlink($saveto);
        }
        $fp = @fopen($saveto,'x');
        @fwrite($fp, $raw);
        @fclose($fp);
    }
    function map_rsp_responsive_photo_gallery_meta_caps( array $caps, $cap, $user_id, array $args  ) {
        
       
        if ( ! in_array( $cap, array(
                                      'rsp_responsive_photo_gallery_slideshow_settings',
                                      'rsp_responsive_photo_gallery_view_images',
                                      'rsp_responsive_photo_gallery_add_image',
                                      'rsp_responsive_photo_gallery_edit_image',
                                      'rsp_responsive_photo_gallery_delete_image',
                                      'rsp_responsive_photo_gallery_preview',
                                      'rsp_masonry_gallery_settings',
                                      'rsp_masonry_gallery_view_media',
                                      'rsp_masonry_gallery_add_media',
                                      'rsp_masonry_gallery_edit_media',
                                      'rsp_masonry_gallery_delete_media',
                                      'rsp_masonry_gallery_preview',
                                      
                                    ), true ) ) {
            
			return $caps;
         }

       
         
   
        $caps = array();

        switch ( $cap ) {
            
                 case 'rsp_responsive_photo_gallery_slideshow_settings':
                        $caps[] = 'rsp_responsive_photo_gallery_slideshow_settings';
                        break;
              
                 case 'rsp_responsive_photo_gallery_view_images':
                        $caps[] = 'rsp_responsive_photo_gallery_view_images';
                        break;
              
                case 'rsp_responsive_photo_gallery_add_image':
                        $caps[] = 'rsp_responsive_photo_gallery_add_image';
                        break;
              
                case 'rsp_responsive_photo_gallery_edit_image':
                        $caps[] = 'rsp_responsive_photo_gallery_edit_image';
                        break;
              
                case 'rsp_responsive_photo_gallery_delete_image':
                        $caps[] = 'rsp_responsive_photo_gallery_delete_image';
                        break;
              
                case 'rsp_responsive_photo_gallery_preview':
                        $caps[] = 'rsp_responsive_photo_gallery_preview';
                        break;
              
                case 'rsp_masonry_gallery_settings':
                        $caps[] = 'rsp_masonry_gallery_settings';
                        break;
                    
                case 'rsp_masonry_gallery_view_media':
                        $caps[] = 'rsp_masonry_gallery_view_media';
                        break;
              
                case 'rsp_masonry_gallery_add_media':
                        $caps[] = 'rsp_masonry_gallery_add_media';
                        break;
              
                case 'rsp_masonry_gallery_edit_media':
                        $caps[] = 'rsp_masonry_gallery_edit_media';
                        break;
              
                case 'rsp_masonry_gallery_delete_media':
                        $caps[] = 'rsp_masonry_gallery_delete_media';
                        break;
                case 'rsp_masonry_gallery_preview':
                        $caps[] = 'rsp_masonry_gallery_preview';
                        break;
              
                default:
                        
                        $caps[] = 'do_not_allow';
                        break;
        }

      
     return apply_filters( 'rsp_responsive_photo_gallery_meta_caps', $caps, $cap, $user_id, $args );
}


 function rsp_responsive_photo_gallery_admin_cap_list($allcaps, $caps, $args, $user){
        
        
        if ( ! in_array( 'administrator', $user->roles ) ) {
            
            return $allcaps;
        }
        else{
            
            if(!isset($allcaps['rsp_responsive_photo_gallery_slideshow_settings'])){
                
                $allcaps['rsp_responsive_photo_gallery_slideshow_settings']=true;
            }
            
            if(!isset($allcaps['rsp_responsive_photo_gallery_view_images'])){
                
                $allcaps['rsp_responsive_photo_gallery_view_images']=true;
            }
            
            if(!isset($allcaps['rsp_responsive_photo_gallery_add_image'])){
                
                $allcaps['rsp_responsive_photo_gallery_add_image']=true;
            }
            if(!isset($allcaps['rsp_responsive_photo_gallery_edit_image'])){
                
                $allcaps['rsp_responsive_photo_gallery_edit_image']=true;
            }
            if(!isset($allcaps['rsp_responsive_photo_gallery_delete_image'])){
                
                $allcaps['rsp_responsive_photo_gallery_delete_image']=true;
            }
            if(!isset($allcaps['rsp_responsive_photo_gallery_preview'])){
                
                $allcaps['rsp_responsive_photo_gallery_preview']=true;
            }
            if(!isset($allcaps['rsp_masonry_gallery_settings'])){
                
                $allcaps['rsp_masonry_gallery_settings']=true;
            }
            if(!isset($allcaps['rsp_masonry_gallery_view_media'])){
                
                $allcaps['rsp_masonry_gallery_view_media']=true;
            }
            if(!isset($allcaps['rsp_masonry_gallery_add_media'])){
                
                $allcaps['rsp_masonry_gallery_add_media']=true;
            }
            if(!isset($allcaps['rsp_masonry_gallery_edit_media'])){
                
                $allcaps['rsp_masonry_gallery_edit_media']=true;
            }
         
            if(!isset($allcaps['rsp_masonry_gallery_delete_media'])){
                
                $allcaps['rsp_masonry_gallery_delete_media']=true;
            }
            if(!isset($allcaps['rsp_masonry_gallery_preview'])){
                
                $allcaps['rsp_masonry_gallery_preview']=true;
            }
         
        }
        
        return $allcaps;
        
    }

    function  rsp_responsive_photo_gallery_add_access_capabilities() {

        // Capabilities for all roles.
        $roles = array( 'administrator' );
        foreach ( $roles as $role ) {

                $role = get_role( $role );
                if ( empty( $role ) ) {
                        continue;
                }


                if(!$role->has_cap( 'rsp_responsive_photo_gallery_slideshow_settings' ) ){

                        $role->add_cap( 'rsp_responsive_photo_gallery_slideshow_settings' );
                }

                if(!$role->has_cap( 'rsp_responsive_photo_gallery_view_images' ) ){

                        $role->add_cap( 'rsp_responsive_photo_gallery_view_images' );
                }


                if(!$role->has_cap( 'rsp_responsive_photo_gallery_add_image' ) ){

                        $role->add_cap( 'rsp_responsive_photo_gallery_add_image' );
                }

                if(!$role->has_cap( 'rsp_responsive_photo_gallery_edit_image' ) ){

                        $role->add_cap( 'rsp_responsive_photo_gallery_edit_image' );
                }

                if(!$role->has_cap( 'rsp_responsive_photo_gallery_delete_image' ) ){

                        $role->add_cap( 'rsp_responsive_photo_gallery_delete_image' );
                }

                if(!$role->has_cap( 'rsp_responsive_photo_gallery_preview' ) ){

                        $role->add_cap( 'rsp_responsive_photo_gallery_preview' );
                }
                if(!$role->has_cap( 'rsp_masonry_gallery_settings' ) ){

                        $role->add_cap( 'rsp_masonry_gallery_settings' );
                }
                if(!$role->has_cap( 'rsp_masonry_gallery_view_media' ) ){

                        $role->add_cap( 'rsp_masonry_gallery_view_media' );
                }
                if(!$role->has_cap( 'rsp_masonry_gallery_add_media' ) ){

                        $role->add_cap( 'rsp_masonry_gallery_add_media' );
                }
                if(!$role->has_cap( 'rsp_masonry_gallery_edit_media' ) ){

                        $role->add_cap( 'rsp_masonry_gallery_edit_media' );
                }
                if(!$role->has_cap( 'rsp_masonry_gallery_delete_media' ) ){

                        $role->add_cap( 'rsp_masonry_gallery_delete_media' );
                }
                if(!$role->has_cap( 'rsp_masonry_gallery_preview' ) ){

                        $role->add_cap( 'rsp_masonry_gallery_preview' );
                }


        }

        $user = wp_get_current_user();
        $user->get_role_caps();

    }

    function rsp_responsive_photo_gallery_remove_access_capabilities(){

        global $wp_roles;

        if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
        }

        foreach ( $wp_roles->roles as $role => $details ) {
                $role = $wp_roles->get_role( $role );
                if ( empty( $role ) ) {
                        continue;
                }

                $role->remove_cap( 'rsp_responsive_photo_gallery_slideshow_settings' );
                $role->remove_cap( 'rsp_responsive_photo_gallery_view_images' );
                $role->remove_cap( 'rsp_responsive_photo_gallery_add_image' );
                $role->remove_cap( 'rsp_responsive_photo_gallery_edit_image' );
                $role->remove_cap( 'rsp_responsive_photo_gallery_delete_image' );
                $role->remove_cap( 'rsp_responsive_photo_gallery_preview' );
                $role->remove_cap( 'rsp_masonry_gallery_settings' );
                $role->remove_cap( 'rsp_masonry_gallery_view_media' );
                $role->remove_cap( 'rsp_masonry_gallery_add_media' );
                $role->remove_cap( 'rsp_masonry_gallery_edit_media' );
                $role->remove_cap( 'rsp_masonry_gallery_delete_media' );
                $role->remove_cap( 'rsp_masonry_gallery_preview' );


        }

        // Refresh current set of capabilities of the user, to be able to directly use the new caps.
        $user = wp_get_current_user();
        $user->get_role_caps();

    }
    
    function my_responsive_photo_gallery_load_styles_and_js(){

        if (! is_admin ()) {
            
       
            
            wp_register_style( 'jquery.galleryview-3.0-dev-responsive', plugins_url('/css/jquery.galleryview-3.0-dev-responsive.css', __FILE__) );
            wp_register_script('jquery.timers-1.2',plugins_url('/js/jquery.timers-1.2.js', __FILE__),array('jquery'),'1.0.8');
            wp_register_script('jquery.easing.1.3',plugins_url('/js/jquery.easing.1.3.js', __FILE__),array('jquery'),'1.0.8');
            wp_register_script('jquery.gview-3.0-dev-responsive',plugins_url('/js/jquery.gview-3.0-dev-responsive.js', __FILE__),array('jquery'),'1.0.11');

            wp_register_style ( 'rjg-lbox', plugins_url ( '/css/rjg-lbox.css', __FILE__ ) ,array(),'1.0.11');
            wp_register_style ( 'rjg-justified-gallery', plugins_url ( '/css/rjg-justified-gallery.css', __FILE__ ) );
            wp_register_script ( 'rjg-justified-gallery', plugins_url ( '/js/rjg-justified-gallery.js', __FILE__ ),array('jquery'),'1.0.8' );
            wp_register_script ( 'rjg-lbox-js', plugins_url ( '/js/rjg-lbox-js.js', __FILE__ ),array('jquery'),'1.0.11' );

          
        }

    }

    function install_my_responsive_photo_gallery(){
        
        global $wpdb;
        $table_name = $wpdb->prefix . "gv_responsive_slider";
        $table_nameg_rjg = $wpdb->prefix . "rjg_gallery";
         $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $table_name . " (
        id int(10) unsigned NOT NULL auto_increment,
        title varchar(1000) NOT NULL,
        image_name varchar(500) NOT NULL,
        createdon datetime NOT NULL,
        custom_link varchar(1000) default NULL,
        post_id int(10) unsigned default NULL,
        PRIMARY KEY  (id)
        ) $charset_collate; "
        . "CREATE TABLE " . $table_nameg_rjg . " (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `media_type` varchar(10) NOT NULL,
        `image_name` varchar(500) NOT NULL,
        `title` varchar(500) NOT NULL,
        `murl` varchar(2000) DEFAULT NULL,
        `open_link_in` tinyint(1) NOT NULL DEFAULT '0',
         `vtype` varchar(50) DEFAULT NULL,
        `vid` varchar(300) DEFAULT NULL,
        `videourl` varchar(1000) DEFAULT NULL,
        `embed_url` varchar(300) DEFAULT NULL,
        `HdnMediaSelection` varchar(300) NOT NULL,
        `createdon` datetime NOT NULL, 
        `slider_id` int(11) NOT NULL DEFAULT '1',
         PRIMARY KEY (`id`)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);


        $my_responsive_photo_gallery_slider_settings=array('transition_speed' => '1000',
            'transition_interval' => '4000',
            'show_panels' =>'1',
            'show_panel_nav' =>'1',
            'enable_overlays' => '0',
            'panel_width'=>'550',
            'panel_height' => '400',
            'panel_animation' => 'fade',
            'panel_scale' => 'crop',
            'overlay_position'=> 'bottom',
            'pan_images' => '1',
            'pan_style'=>'drag',
            'start_frame'=>'1',
            'show_filmstrip'=>'1',
            'show_filmstrip_nav'=>'0',
            'enable_slideshow'=>'1',
            'autoplay'=>'1',
            'filmstrip_position'=>'bottom',
            'frame_width'=>80,
            'frame_height'=>80,
            'frame_opacity'=>0.4,
            'frame_scale'=>'crop',
            'filmstrip_style'=>'scroll',
            'frame_gap'=>1,
            'show_captions'=>0,
            'show_infobar'=>0,
            'infobar_opacity'=>1
        );

        if( !get_option( 'my_responsive_photo_gallery_slider_settings' ) ) {

            update_option('my_responsive_photo_gallery_slider_settings',$my_responsive_photo_gallery_slider_settings);
        } 
        
        $rjg_settings=array(
	                                                   
            'BackgroundColor'=>'#FFFFFF',
            'imageheight'=>160,
            'imageMargin'=>5,
             'page_size'=>50,
             'show_hover_caption'=>1,   
             'show_hover_icon'=>1

        );

        if( !get_option( 'rjg_settings' ) ) {

             update_option('rjg_settings',$rjg_settings);
         } 
        
        
        $uploads = wp_upload_dir ();
        $baseDir = $uploads ['basedir'];
        $baseDir = str_replace ( "\\", "/", $baseDir );
        $pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
        wp_mkdir_p ( $pathToImagesFolder );
        rsp_responsive_photo_gallery_add_access_capabilities();

    } 


    function rjg_do_upgrade_if_not_done(){
        
        
         global $wpdb;
         $table_nameg_rjg = $wpdb->prefix . "rjg_gallery";
         $charset_collate = $wpdb->get_charset_collate();
         
         
        if($wpdb->get_var("SHOW TABLES LIKE '$table_nameg_rjg'") != $table_nameg_rjg) {
 
            $sql = "CREATE TABLE " . $table_nameg_rjg . " (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `media_type` varchar(10) NOT NULL,
            `image_name` varchar(500) NOT NULL,
            `title` varchar(500) NOT NULL,
            `murl` varchar(2000) DEFAULT NULL,
            `open_link_in` tinyint(1) NOT NULL DEFAULT '0',
             `vtype` varchar(50) DEFAULT NULL,
            `vid` varchar(300) DEFAULT NULL,
            `videourl` varchar(1000) DEFAULT NULL,
            `embed_url` varchar(300) DEFAULT NULL,
            `HdnMediaSelection` varchar(300) NOT NULL,
            `createdon` datetime NOT NULL, 
            `slider_id` int(11) NOT NULL DEFAULT '1',
             PRIMARY KEY (`id`)
            ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
         $rjg_settings=array(
	                                                   
            'BackgroundColor'=>'#FFFFFF',
            'imageheight'=>160,
            'imageMargin'=>5,
             'page_size'=>50,
             'show_hover_caption'=>1,   
             'show_hover_icon'=>1

        );

        if( !get_option( 'rjg_settings' ) ) {

             update_option('rjg_settings',$rjg_settings);
         } 
        
        
    }

    function wp_responsive_photo_gallery_admin_notices(){
        
        if (is_plugin_active('wp-responsive-photo-gallery/wp-responsive-photo-gallery.php')) {
            
            $uploads = wp_upload_dir();
            $baseDir=$uploads['basedir'];
            $baseDir=str_replace("\\","/",$baseDir);
            $pathToImagesFolder=$baseDir.'/wp-responsive-photo-gallery';
            
            if(file_exists($pathToImagesFolder) and is_dir($pathToImagesFolder)){
                
                if( !is_writable($pathToImagesFolder)){
                    
                        echo "<div class='updated'><p>".__( 'Photo Gallery Plugin is active but does not have write permission on','wp-responsive-photo-gallery')."</p><p><b>" . $pathToImagesFolder . "</b>".__( ' directory.Please allow write permission.','wp-responsive-photo-gallery')."</p></div> ";
                        
                }       
            }
            else{
               
                  wp_mkdir_p($pathToImagesFolder);  
                  if(!file_exists($pathToImagesFolder) and !is_dir($pathToImagesFolder)){
                      
                   echo "<div class='updated'><p>".__( 'Photo Gallery Plugin is active but plugin does not have permission to create directory','wp-responsive-photo-gallery')."</p><p><b>" . $pathToImagesFolder . "</b> ".__( '.Please create post-slider-carousel directory inside upload directory and allow write permission.','wp-responsive-photo-gallery')."</p></div> ";
                    
                  }
            }
        }
        
    }
    
    function add_my_responsive_photo_gallery_admin_menu(){



        $hook_suffix_r_p=add_menu_page( __( 'Photo Slideshow & Masonry Gallery','wp-responsive-photo-gallery'), __( 'Photo Slideshow & Masonry Gallery','wp-responsive-photo-gallery' ), 'rsp_responsive_photo_gallery_slideshow_settings', 'responsive_photo_gallery_slider', 'responsive_photo_gallery_slider_admin_options' );
        $hook_suffix_r_p=add_submenu_page( 'responsive_photo_gallery_slider', __( 'Slideshow Settings','wp-responsive-photo-gallery'), __( 'Slideshow Settings','wp-responsive-photo-gallery' ),'rsp_responsive_photo_gallery_slideshow_settings', 'responsive_photo_gallery_slider', 'responsive_photo_gallery_slider_admin_options' );
        $hook_suffix_r_p_1=add_submenu_page( 'responsive_photo_gallery_slider', __( 'Slideshow Images','wp-responsive-photo-gallery'), __( 'Slideshow Images','wp-responsive-photo-gallery'),'rsp_responsive_photo_gallery_view_images', 'responsive_photo_gallery_image_management', 'responsive_photo_gallery_image_management' );
        $hook_suffix_r_p_2=add_submenu_page( 'responsive_photo_gallery_slider', __( 'Slideshow Preview','wp-responsive-photo-gallery'), __( 'Slideshow Preview','wp-responsive-photo-gallery'),'rsp_responsive_photo_gallery_preview', 'responsive_photo_galleryrsp_responsive_photo_gallery_preview_slider_preview', 'responsive_photo_gallery_slider_admin_preview' );

        $hook_suffix=add_submenu_page ( 'responsive_photo_gallery_slider', __ ( 'Masonry Gallery Settings','wp-responsive-photo-gallery' ), __ ( 'Masonry Gallery Settings','wp-responsive-photo-gallery' ), 'rsp_masonry_gallery_settings', 'responsive_justified_gallery_with_lightbox', 'rjg_responsive_justified_gallery_with_lightbox_admin_options_func' );
	$hook_suffix_image=add_submenu_page ( 'responsive_photo_gallery_slider', __ ( 'Masonry Gallery Media','wp-responsive-photo-gallery' ), __ ( 'Masonry Gallery Media','wp-responsive-photo-gallery' ), 'rsp_masonry_gallery_view_media', 'responsive_justified_gallery_with_lightbox_media_management', 'rjg_responsive_justified_gallery_with_lightbox_media_management_func' );
	$hook_suffix_prev=add_submenu_page ( 'responsive_photo_gallery_slider', __ ( 'Masonry Gallery Preview','wp-responsive-photo-gallery' ), __ ( 'Masonry Gallery Preview','wp-responsive-photo-gallery' ), 'rsp_masonry_gallery_preview', 'responsive_justified_gallery_with_lightbox_media_preview', 'rfp_responsive_justified_gallery_with_lightbox_media_preview_func' );
	
	add_action( 'load-' . $hook_suffix , 'my_responsive_photo_gallery_admin_init' );
	add_action( 'load-' . $hook_suffix_image , 'my_responsive_photo_gallery_admin_init' );
	add_action( 'load-' . $hook_suffix_prev , 'my_responsive_photo_gallery_admin_init' );
        
        
        add_action( 'load-' . $hook_suffix_r_p , 'my_responsive_photo_gallery_admin_init' );
        add_action( 'load-' . $hook_suffix_r_p_1 , 'my_responsive_photo_gallery_admin_init' );
        add_action( 'load-' . $hook_suffix_r_p_2 , 'my_responsive_photo_gallery_admin_init' );
        
        
     
       

    }

    function my_responsive_photo_gallery_admin_init(){

        
        
        $url = plugin_dir_url(__FILE__);  

        wp_enqueue_style( 'admin-css-responsive', plugins_url('/css/admin-css-responsive.css', __FILE__) );
        wp_enqueue_style( 'jquery.galleryview-3.0-dev-responsive', plugins_url('/css/jquery.galleryview-3.0-dev-responsive.css', __FILE__) );
        wp_enqueue_style ( 'rjg-lbox', plugins_url ( '/css/rjg-lbox.css', __FILE__ ) );
        wp_enqueue_style ( 'rjg-justified-gallery', plugins_url ( '/css/rjg-justified-gallery.css', __FILE__ ) );
        
        wp_enqueue_script('jquery'); 
        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_script('jquery.timers-1.2',plugins_url('/js/jquery.timers-1.2.js', __FILE__));
        wp_enqueue_script('jquery.easing.1.3',plugins_url('/js/jquery.easing.1.3.js', __FILE__));
        wp_enqueue_script('jquery.gview-3.0-dev-responsive',plugins_url('/js/jquery.gview-3.0-dev-responsive.js', __FILE__));
        wp_enqueue_script('jquery.validate',plugins_url('/js/jquery.validate.js', __FILE__));
        wp_enqueue_script ( 'rjg-justified-gallery', plugins_url ( '/js/rjg-justified-gallery.js', __FILE__ ) );
        wp_enqueue_script ( 'rjg-lbox-js', plugins_url ( '/js/rjg-lbox-js.js', __FILE__ ) );
     
          
        rjg_responsive_justified_gallery_plus_lightbox_admin_scripts_init();
        //my_responsive_photo_gallery_admin_scripts_init();
        
        rjg_do_upgrade_if_not_done();


    }

    function responsive_photo_gallery_slider_admin_options(){


         if ( ! current_user_can( 'rsp_responsive_photo_gallery_slideshow_settings' ) ) {

           wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

        } 
      
        if(isset($_POST['btnsave'])){

            if ( !check_admin_referer( 'action_image_add_edit','add_edit_image_nonce')){

                  wp_die('Security check fail'); 
              }

                
            $options=array();
            $options['transition_speed']       =(int)trim(htmlentities(sanitize_text_field($_POST['transition_speed']),ENT_QUOTES));
            $options['transition_interval']    =(int)trim(htmlentities(sanitize_text_field($_POST['transition_interval']),ENT_QUOTES));
            $options['show_panel_nav']         =(int)trim(htmlentities(sanitize_text_field($_POST['show_panel_nav']),ENT_QUOTES));
            $options['panel_width']            =(int)trim(htmlentities(sanitize_text_field($_POST['panel_width']),ENT_QUOTES));
            $options['panel_height']           =(int)trim(htmlentities(sanitize_text_field($_POST['panel_height']),ENT_QUOTES));
            $options['panel_height']           =(int)trim(htmlentities(sanitize_text_field($_POST['panel_height']),ENT_QUOTES));
            $options['panel_scale']            =trim(htmlentities(sanitize_text_field($_POST['panel_scale']),ENT_QUOTES));
            $options['pan_style'   ]           =trim(htmlentities(sanitize_text_field($_POST['pan_style']),ENT_QUOTES));
            $options['pan_images']             =(int)trim(htmlentities(sanitize_text_field($_POST['pan_images']),ENT_QUOTES));
            $options['show_filmstrip']         =(int)trim(htmlentities(sanitize_text_field($_POST['show_filmstrip']),ENT_QUOTES));
            $options['autoplay']               =(int)trim(htmlentities(sanitize_text_field($_POST['autoplay']),ENT_QUOTES));
            $options['frame_width']            =(int)trim(htmlentities(sanitize_text_field($_POST['frame_width']),ENT_QUOTES));
            $options['frame_height']           =(int)trim(htmlentities(sanitize_text_field($_POST['frame_height']),ENT_QUOTES));
            $options['frame_opacity']          =trim(htmlentities(sanitize_text_field($_POST['frame_opacity']),ENT_QUOTES));
            $options['frame_scale']            =trim(htmlentities(sanitize_text_field($_POST['frame_scale']),ENT_QUOTES));
            $options['filmstrip_style']        ='scroll';
            $options['frame_gap']              =(int)trim(htmlentities(sanitize_text_field($_POST['frame_gap']),ENT_QUOTES));
            $options['show_infobar']           =(int)trim(htmlentities(sanitize_text_field($_POST['show_infobar']),ENT_QUOTES));
            $options['infobar_opacity']        = floatval(htmlentities(sanitize_text_field($_POST['infobar_opacity']),ENT_QUOTES));
            $options['start_frame']            =1;
            $options['panel_animation']        ='fade';
            $options['overlay_position']       ='bottom';
            $options['filmstrip_position']     ='bottom';
            $options['enable_overlays']        =0;
            $options['show_captions']          =0;
            $options['show_filmstrip_nav']     =0;
            $options['show_panels']            =1;

            if((int)trim($_POST['autoplay']))
                $options['enable_slideshow']=1;
            else   
                $options['enable_slideshow']=0;

            $settings=update_option('my_responsive_photo_gallery_slider_settings',$options); 
            $my_responsive_photo_gallery_slider_settings_messages=array();
            $my_responsive_photo_gallery_slider_settings_messages['type']='succ';
            $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Settings saved successfully.','wp-responsive-photo-gallery' );
            update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);


            
        }  
        $settings=get_option('my_responsive_photo_gallery_slider_settings');

    ?>      
    <div style="width: 100%;">  
        <div style="float:left;width:100%;">
            <div class="wrap">
                <table><tr>
                        <td>
                          <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                          <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                      </td>
                        <td>
                            <a target="_blank" title="Donate" href="http://i13websolution.com/donate-wordpress_image_thumbnail.php">
                                <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                            </a>
                        </td>
                    </tr>
                </table>
                <div style="clear:both">
                    <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                </div>     
                <?php
                    $messages=get_option('my_responsive_photo_gallery_slider_settings_messages'); 
                    $type='';
                    $message='';
                    if(isset($messages['type']) and $messages['type']!=""){

                        $type=$messages['type'];
                        $message=$messages['message'];

                    }  


                    if($type=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                    else if($type=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}


                    update_option('my_responsive_photo_gallery_slider_settings_messages', array());     
                ?>      


                <h2><?php echo __( 'Gallery Settings','wp-responsive-photo-gallery' );?></h2>
                
                <div id="poststuff">   
                    <div id="post-body" class="metabox-holder columns-2"> 
                        <div id="post-body-content">
                            <form method="post" action="" id="scrollersettiings" name="scrollersettiings" >
                                <div class="stuffbox" id="namediv" style="width:100%">
                                    <h3><label for="link_name"><?php echo __( 'Settings','wp-responsive-photo-gallery' );?></label></h3>
                                    <table cellspacing="0" class="form-list" cellpadding="10">
                                        <tbody>
                                            <tr>
                                                <td class="label">
                                                    <label for="transition_speed"><?php echo __( 'Transition Speed','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="transition_speed" value="<?php echo $settings['transition_speed']; ?>" name="transition_speed"  class="input-text" type="text">           
                                                    <div style="clear:both"></div>
                                                    <div></div> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="transition_interval"><?php echo __( 'Transition Interval','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="transition_interval"  value="<?php echo $settings['transition_interval']; ?>" name="transition_interval"  class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="show_panel_nav"><?php echo __( 'Show Slider Navigation arrows','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <select id="show_panel_nav" name="show_panel_nav" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['show_panel_nav']==1):?> selected="selected" <?php endif;?>  value="1" ><?php echo __( 'Yes','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['show_panel_nav']==0):?> selected="selected" <?php endif;?>  value="0"><?php echo __( 'No','wp-responsive-photo-gallery' );?></option>
                                                    </select>            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="panel_width"><?php echo __( 'Width','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="panel_width" value="<?php echo $settings['panel_width']; ?>" name="panel_width"  class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="panel_height"><?php echo __( 'Height','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="panel_height" value="<?php echo $settings['panel_height']; ?>"  name="panel_height"  class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="panel_scale"><?php echo __( 'Scale','wp-responsive-photo-gallery' );?>  <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <select id="panel_scale" name="panel_scale" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['panel_scale']=='crop'):?> selected="selected" <?php endif;?> value="crop"><?php echo __( 'Crop','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['panel_scale']=='fit'):?> selected="selected" <?php endif;?> value="fit" ><?php echo __( 'Fit','wp-responsive-photo-gallery' );?></option>
                                                    </select>  
                                                    <div style="clear:both"></div>
                                                    <div></div>          
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">  
                                                    <label for="pan_images"><?php echo __( 'Pan Images','wp-responsive-photo-gallery' );?>  <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <select id="pan_images" name="pan_images" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['pan_images']==1):?> selected="selected" <?php endif;?>  value="1"><?php echo __( 'Yes','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['pan_images']==0):?> selected="selected" <?php endif;?>  value="0" ><?php echo __( 'No','wp-responsive-photo-gallery' );?></option>
                                                    </select>            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="pan_style"><?php echo __( 'Pan Style','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <select id="pan_style" name="pan_style" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['pan_style']=='drag'):?> selected="selected" <?php endif;?>  value="drag"><?php echo __( 'Drag','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['pan_style']=='track'):?> selected="selected" <?php endif;?>  value="track" ><?php echo __( 'Track','wp-responsive-photo-gallery' );?></option>
                                                    </select>            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="show_filmstrip"><?php echo __( 'Show Thumbnail Gallery','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <select id="show_filmstrip" name="show_filmstrip" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['show_filmstrip']==1):?> selected="selected" <?php endif;?>  value="1" ><?php echo __( 'Yes','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['show_filmstrip']==0):?> selected="selected" <?php endif;?>  value="0"><?php echo __( 'No','wp-responsive-photo-gallery' );?></option>
                                                    </select> 
                                                    <div style="clear:both"></div>
                                                    <div></div>           
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label"><label for="autoplay"><?php echo __( 'Auto play?','wp-responsive-photo-gallery' );?>   <span class="required">*</span></label></td>
                                                <td class="value">
                                                    <select id="autoplay" name="autoplay" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['autoplay']==1):?> selected="selected" <?php endif;?>  value="1" ><?php echo __( 'Yes','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['autoplay']==0):?> selected="selected" <?php endif;?>  value="0"><?php echo __( 'No','wp-responsive-photo-gallery' );?></option>
                                                    </select>   
                                                    <div style="clear:both"></div>
                                                    <div></div>         
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="frame_width"><?php echo __( 'Thumbnail Width','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="frame_width" value="<?php echo $settings['frame_width']; ?>" name="frame_width" value="80" class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="frame_height"><?php echo __( 'Thumbnail Height','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="frame_height" value="<?php echo $settings['frame_height']; ?>" name="frame_height"  class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="frame_opacity"><?php echo __( 'Thumbnail Opacity','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="frame_opacity" value="<?php echo $settings['frame_opacity']; ?>" name="frame_opacity"  class="input-text" type="text">           
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="frame_scale"><?php echo __( 'Thumbnail Scale','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <select id="frame_scale" name="frame_scale" class="select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['frame_scale']=='crop'):?> selected="selected" <?php endif;?> value="crop" ><?php echo __( 'Crop','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['frame_scale']=='fit'):?> selected="selected" <?php endif;?>  value="fit"><?php echo __( 'Fit','wp-responsive-photo-gallery' );?></option>
                                                    </select>            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="frame_gap"><?php echo __( 'Thumbnail Gap','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="frame_gap" value="<?php echo $settings['frame_gap']; ?>" name="frame_gap"  class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label"><label for="show_infobar"><?php echo __( 'Show Infobar?','wp-responsive-photo-gallery' );?>  <span class="required">*</span></label></td>
                                                <td class="value">
                                                    <select id="show_infobar" name="show_infobar" class=" select">
                                                        <option value=""><?php echo __( 'Select','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['show_infobar']==1):?> selected="selected" <?php endif;?>  value="1"><?php echo __( 'Yes','wp-responsive-photo-gallery' );?></option>
                                                        <option <?php if($settings['show_infobar']==0):?> selected="selected" <?php endif;?>  value="0" ><?php echo __( 'No','wp-responsive-photo-gallery' );?></option>
                                                    </select>            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">
                                                    <label for="infobar_opacity"><?php echo __( 'Infobar Opacity','wp-responsive-photo-gallery' );?> <span class="required">*</span></label>
                                                </td>
                                                <td class="value">
                                                    <input id="infobar_opacity" value="<?php echo $settings['infobar_opacity']; ?>" name="infobar_opacity"  class="input-text" type="text">            
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>  
                                            <tr>
                                                <td class="label">
                                                     <?php wp_nonce_field('action_image_add_edit','add_edit_image_nonce'); ?>
                                                    <input type="submit"  name="btnsave" id="btnsave" value="<?php echo __( 'Save Changes','wp-responsive-photo-gallery' );?>" class="button-primary">      
                                                </td>
                                                <td class="value">

                                                    <input type="button" name="cancle" id="cancle" value="<?php echo __( 'Cancel','wp-responsive-photo-gallery' );?>" class="button-primary" onclick="location.href='admin.php?page=responsive_photo_gallery_slider'">    

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>                                    
                                </div>
                                <input type="hidden" name="start_frame" id="start_frame" value="1"> 
                                <input type="hidden" name="enable_slideshow" id="start_frame" value="1"> 
                                <input type="hidden" name="panel_animation" id="panel_animation" value="fade"> 
                                <input type="hidden" name="overlay_position" id="overlay_position" value="bottom"> 
                                <input type="hidden" name="filmstrip_position" id="filmstrip_position" value="bottom"> 
                                <input type="hidden" name="enable_overlays" id="enable_overlays" value="0"> 
                                <input type="hidden" name="show_captions" id="show_captions" value="0"> 
                                <input type="hidden" name="show_filmstrip_nav" id="show_filmstrip_nav" value="0"> 
                                <input type="hidden" name="show_panels" id="show_panels" value="1"> 
                            </form> 
                            <script type="text/javascript">

                                jQuery(document).ready(function() {

                                        jQuery("#scrollersettiings").validate({
                                                rules: {
                                                    transition_speed: {
                                                        required:true,
                                                        number:true,
                                                        maxlength:10
                                                    },transition_interval: {
                                                        required:true,
                                                        number:true,
                                                        maxlength:10
                                                    },show_panel_nav: {
                                                        required:true, 
                                                    },
                                                    panel_width:{
                                                        required:true,  
                                                        number:true,
                                                        maxlength:10

                                                    },
                                                    panel_height:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:10  
                                                    },
                                                    panel_scale:{
                                                        required:true
                                                    },
                                                    pan_images:{
                                                        required:true

                                                    },
                                                    pan_style:{
                                                        required:true
                                                    },show_filmstrip:{
                                                        required:true

                                                    },autoplay:{
                                                        required:true

                                                    },frame_width:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:10  
                                                    },frame_height:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:10  
                                                    }
                                                    ,frame_opacity:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:10  
                                                    }
                                                    ,frame_scale:{
                                                        required:true

                                                    },frame_gap:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:10  

                                                    },show_infobar:{
                                                        required:true

                                                    },infobar_opacity:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:10  
                                                    }

                                                },
                                                errorClass: "image_error",
                                                errorPlacement: function(error, element) {
                                                    error.appendTo( element.next().next());
                                                } 


                                        })
                                });

                            </script> 

                        </div>
                        <div id="postbox-container-1" class="postbox-container"  > 

                            <div class="postbox"> 
                                <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-responsive-photo-gallery' );?></h3> 
                                <div class="inside">
                                    <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                                    <div style="margin:10px 5px">

                                    </div>
                                </div></div>
                            <div class="postbox"> 
                            <center><h3 class="hndle"><span></span><?php echo __( 'Google For Business','wp-responsive-photo-gallery');?></h3> </center>
                            <div class="inside">
                                <center><a target="_blank" href="https://goo.gl/OJBuHT"><img style="max-width:350px;width:100%" src="<?php echo plugins_url( 'images/gsuite_promo.png', __FILE__ ) ;?>"  border="0"></a></center>
                                <div style="margin:10px 5px">
                                </div>
                            </div></div>
                            
                             

                        </div>      
                       <div class="clear"></div>
                    </div>                                              

                </div>  
            </div>      
        </div>



        <div class="clear"></div></div>  
    <?php
    }        
    function responsive_photo_gallery_image_management(){

        
        $uploads = wp_upload_dir ();
        $baseDir = $uploads ['basedir'];
        $baseDir = str_replace ( "\\", "/", $baseDir );
        $pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
        
        $baseurl=$uploads['baseurl'];
        $baseurl.='/wp-responsive-photo-gallery/';
        
         global $wpdb;


        $action = 'gridview';
        
        if (isset ( $_GET ['action'] ) and $_GET ['action'] != '') {
		
		$action = trim ( sanitize_text_field($_GET ['action'] ));
                
                if(isset($_GET['order_by'])){
        
                    if(sanitize_sql_orderby($_GET['order_by'])){
                        $order_by=esc_html(sanitize_text_field($_GET['order_by'])); 
                    }
                    else{
                        
                        $order_by=' id ';
                    }
                 }

                 if(isset($_GET['order_pos'])){

                    $order_pos=esc_html(sanitize_text_field($_GET['order_pos'])); 
                 }

                 $search_term_='';
                 if(isset($_GET['search_term'])){

                    $search_term_='&search_term='.esc_html(sanitize_text_field($_GET['search_term']));
                 }
	}
        
         $search_term_='';
        if(isset($_GET['search_term'])){

           $search_term_='&search_term='.esc_html(sanitize_text_field($_GET['search_term']));
        }
	
    ?>

    <?php 
        if(strtolower($action)==strtolower('gridview')){ 

           if ( ! current_user_can( 'rsp_responsive_photo_gallery_view_images' ) ) {

                wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

             } 

            $wpcurrentdir=dirname(__FILE__);
            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);



        ?> 
       <div class="wrap">
           <table><tr>
                     <td>
                          <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                          <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                      </td>
                        <td>
                            <a target="_blank" title="Donate" href="http://i13websolution.com/donate-wordpress_image_thumbnail.php">
                                <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                            </a>
                        </td>
                    </tr>
                </table>
                <div style="clear:both">
                    <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                </div>      

            <?php 

                $messages=get_option('my_responsive_photo_gallery_slider_settings_messages'); 
                $type='';
                $message='';
                if(isset($messages['type']) and $messages['type']!=""){

                    $type=$messages['type'];
                    $message=$messages['message'];

                }  


                 if($type=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                 else if($type=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}


                update_option('my_responsive_photo_gallery_slider_settings_messages', array()); 
                $url = plugin_dir_url(__FILE__);  
            ?>
           <div id="modelMainDiv" style="display:none;z-index: 1000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(0, 0, 0); opacity: 0.2; cursor: wait; position: fixed;filter:alpha(opacity=15)" ></div>
            <div id="LoaderDiv" style="display:none;z-index: 1000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(0, 0, 0); opacity: 0.2; cursor: wait; position: fixed;filter:alpha(opacity=15)" ></div>
            <div id="ContainDiv" style="display:none;z-index: 1056; position: fixed; padding: 5px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: rgb(0, 0, 0); border: 1px solid #999999; background-color: rgb(255, 255, 255); cursor: wait;" >
              <img src="<?php echo $url.'images/ajax-loader.gif'?>" />
               <h5 id="wait"><?php echo __('Please wait while uploading images...','wp-responsive-photo-gallery');?></h5>
            </div>
            <div id="poststuff" >
                <div id="post-body" class="metabox-holder columns-2"> 
                     <div id="post-body-content" >

                        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                        <h1>
                            <?php echo __( 'Images','wp-responsive-photo-gallery' );?> <a class="button add-new-h2" href="admin.php?page=responsive_photo_gallery_image_management&action=addedit"><?php echo __( 'Add New','wp-responsive-photo-gallery' );?></a> 
                              &nbsp;&nbsp;
                             <a class="massAdd button add-new-h2" href="javascript:void(0)"><?php echo __('Mass Add','wp-responsive-photo-gallery');?></a>
                    
                        </h1>
                        <br/>    

                        <form method="POST" action="admin.php?page=responsive_photo_gallery_image_management&action=deleteselected"  id="posts-filter" onkeypress="return event.keyCode != 13;">
                            <div class="alignleft actions">
                                <select name="action_upper" id="action_upper">
                                    <option selected="selected" value="-1"><?php echo __( 'Bulk Actions','wp-responsive-photo-gallery' );?></option>
                                    <option value="delete"><?php echo __( 'Delete','wp-responsive-photo-gallery' );?></option>
                                </select>
                                <input type="submit" value="<?php echo __( 'Apply','wp-responsive-photo-gallery' );?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
                            </div>
                            <br class="clear">
                             <?php
                             
                                $setacrionpage='admin.php?page=responsive_photo_gallery_image_management';

                                 if(isset($_GET['order_by']) and $_GET['order_by']!=""){
                                  $setacrionpage.='&order_by='.esc_html(sanitize_text_field($_GET['order_by']));   
                                 }

                                 if(isset($_GET['order_pos']) and $_GET['order_pos']!=""){
                                  $setacrionpage.='&order_pos='.esc_html(sanitize_text_field($_GET['order_pos']));  
                                 }

                                 $seval="";
                                 if(isset($_GET['search_term']) and $_GET['search_term']!=""){
                                  $seval=esc_html(sanitize_text_field($_GET['search_term']));   
                                 }
                               
                                
                                $order_by='id';
                                $order_pos="asc";

                                if(isset($_GET['order_by']) and sanitize_sql_orderby($_GET['order_by'])!==false){

                                   $order_by=esc_html(sanitize_text_field($_GET['order_by'])); 
                                }

                                 if(isset($_GET['order_pos'])){

                                   $order_pos=esc_html(sanitize_text_field($_GET['order_pos'])); 
                                }

                                 $search_term='';
                                if(isset($_GET['search_term'])){

                                   $search_term= esc_html(sanitize_text_field(esc_sql($_GET['search_term'])));
                                }


                            ?>
                            <?php 

                                $settings=get_option('my_responsive_photo_gallery_slider_settings'); 
                                $query="SELECT * FROM ".$wpdb->prefix."gv_responsive_slider ";
                                $queryCount="SELECT count(*) FROM ".$wpdb->prefix."gv_responsive_slider ";
                                 if($search_term!=''){
                                    $query.=" where id like '%$search_term%' or title like '%$search_term%' "; 
                                    $queryCount.=" where id like '%$search_term%' or title like '%$search_term%' "; 
                                 }

                                 $order_by=sanitize_text_field(sanitize_sql_orderby($order_by));
                                 $order_pos=sanitize_text_field(sanitize_sql_orderby($order_pos));

                                 $rowsCount=$wpdb->get_var($queryCount);
                                 $query.=" order by $order_by $order_pos";
                               

                            ?>
                           
                            <div style="padding-top:5px;padding-bottom:5px">
                                <b><?php echo __( 'Search','wp-responsive-photo-gallery');?> : </b>
                                  <input type="text" value="<?php echo $seval;?>" id="search_term" name="search_term">&nbsp;
                                  <input type='button'  value='<?php echo __( 'Search','wp-responsive-photo-gallery');?>' name='searchusrsubmit' class='button-primary' id='searchusrsubmit' onclick="SearchredirectTO();" >&nbsp;
                                  <input type='button'  value='<?php echo __( 'Reset Search','wp-responsive-photo-gallery');?>' name='searchreset' class='button-primary' id='searchreset' onclick="ResetSearch();" >
                            </div>  
                            <script type="text/javascript" >
                               
                                jQuery('#search_term').on("keyup", function(e) {
                                       if (e.which == 13) {
                                           
                                           SearchredirectTO();
                                       }
                                  });   
                             function SearchredirectTO(){
                                 
                               var redirectto='<?php echo $setacrionpage; ?>';
                               var searchval=jQuery('#search_term').val();
                               redirectto=redirectto+'&search_term='+jQuery.trim(encodeURIComponent(searchval));  
                               window.location.href=redirectto;
                             }
                            function ResetSearch(){

                                 var redirectto='<?php echo $setacrionpage; ?>';
                                 window.location.href=redirectto;
                                 exit;
                            }
                            </script>
                            <div id="no-more-tables">
                                <table cellspacing="0" id="gridTbl" class="table-bordered table-striped table-condensed cf" >
                                    <thead>
                                        <tr>
                                            <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
                                            <?php if($order_by=="id" and $order_pos=="asc"):?>
                                                                               
                                                <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Id','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                <?php else:?>
                                                    <?php if($order_by=="id"):?>
                                                <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Id','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                    <?php else:?>
                                                        <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Id','wp-responsive-photo-gallery');?></a></th>
                                                    <?php endif;?>    
                                                <?php endif;?>  
                                            <?php if($order_by=="title" and $order_pos=="asc"):?>

                                                    <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Title','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                               <?php else:?>
                                                   <?php if($order_by=="title"):?>
                                               <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                   <?php else:?>
                                                       <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','wp-responsive-photo-gallery');?></a></th>
                                                   <?php endif;?>    
                                               <?php endif;?>  
                                               <th><span></span></th>
                                          <?php if($order_by=="createdon" and $order_pos=="asc"):?>
                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Published On','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                            <?php else:?>
                                                <?php if($order_by=="createdon"):?>
                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                <?php else:?>
                                                    <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','wp-responsive-photo-gallery');?></a></th>
                                                <?php endif;?>    
                                            <?php endif;?>  
                                            <th><span><?php echo __( 'Edit','wp-responsive-photo-gallery' );?></span></th>
                                            <th><span><?php echo __( 'Delete','wp-responsive-photo-gallery' );?></span></th>
                                        </tr> 
                                    </thead>
                                    <tbody id="the-list">
                                        <?php

                                            if($rowsCount > 0){

                                                global $wp_rewrite;
                                                $rows_per_page = 10;

                                                $current = (isset($_GET['paged'])) ? (intval($_GET['paged'])) : 1;
                                                $pagination_args = array(
                                                    'base' => @add_query_arg('paged','%#%'),
                                                    'format' => '',
                                                    'total' => ceil($rowsCount/$rows_per_page),
                                                    'current' => $current,
                                                    'show_all' => false,
                                                    'type' => 'plain',
                                                );


                                                $offset = ($current - 1) * $rows_per_page;
                                                $query.=" limit $offset, $rows_per_page";
                                                $rows = $wpdb->get_results ( $query ,ARRAY_A);
                                                $delRecNonce=wp_create_nonce('delete_image');
                                                
                                                foreach ($rows as $row ) {

                                                    $id=$row['id'];
                                                    $editlink="admin.php?page=responsive_photo_gallery_image_management&action=addedit&id=$id";
                                                    $deletelink="admin.php?page=responsive_photo_gallery_image_management&action=delete&id=$id&nonce=$delRecNonce";
                                                    $outputimgmain = $baseurl.$row['image_name']; 

                                                ?>
                                                <tr valign="top">
                                                    <td class="alignCenter check-column"   data-title="<?php echo __( 'Select Record','wp-responsive-photo-gallery' );?>" ><input type="checkbox" value="<?php echo $row['id']; ?>" name="thumbnails[]"></td>
                                                    <td class="alignCenter" data-title="<?php echo __( 'ID','wp-responsive-photo-gallery' );?>"><strong><?php echo $row['id']; ?></strong></td>  
                                                    <td class="alignCenter" data-title="<?php echo __( 'Title','wp-responsive-photo-gallery' );?>"><strong><?php echo $row['title']; ?></strong></td>  
                                                    <td class="alignCenter">
                                                        <img src="<?php echo $outputimgmain;?>" style="width:50px" height="50px"/>
                                                    </td> 
                                                    <td class="alignCenter" data-title="<?php echo __( 'Published On','wp-responsive-photo-gallery' );?>"><?php echo $row['createdon'] ?></td>
                                                    <td class="alignCenter"   data-title="<?php echo __( 'Edit','wp-responsive-photo-gallery' );?>"><strong><a href='<?php echo $editlink; ?>' title="<?php echo __( 'Edit','wp-responsive-photo-gallery' );?>"><?php echo __( 'Edit','wp-responsive-photo-gallery' );?></a></strong></td>  
                                                    <td class="alignCenter"   data-title="<?php echo __( 'Delete','wp-responsive-photo-gallery' );?>"><strong><a href='<?php echo $deletelink; ?>' onclick="return confirmDelete();"  title="<?php echo __( 'Delete','wp-responsive-photo-gallery' );?>"><?php echo __( 'Delete','wp-responsive-photo-gallery' );?></a> </strong></td>  
                                                </tr>
                                                <?php 
                                                } 
                                            }
                                            else{
                                            ?>

                                            <tr valign="top" class="" id="">
                                                <td colspan="7" data-title="No Record" align="center"><strong><?php echo __( 'No Images Found','wp-responsive-photo-gallery' );?></strong></td>  
                                            </tr>
                                            <?php 
                                            } 
                                        ?>      
                                    </tbody>
                                </table>
                            </div>
                            <?php
                                if($rowsCount>0){
                                    echo "<div class='pagination' style='padding-top:10px'>";
                                    echo paginate_links($pagination_args);
                                    echo "</div>";
                                }
                            ?>
                            <br/>
                            <div class="alignleft actions">
                                <select name="action" id="action_bottom">
                                    <option selected="selected" value="-1"><?php echo __( 'Bulk Actions','wp-responsive-photo-gallery' );?></option>
                                    <option value="delete"><?php echo __( 'Delete','wp-responsive-photo-gallery' );?></option>
                                </select>
                                 <?php wp_nonce_field('action_settings_mass_delete','mass_delete_nonce'); ?>
                                <input type="submit" value="<?php echo __( 'Apply','wp-responsive-photo-gallery' );?>" class="button-secondary action" id="deleteselected" name="deleteselected">
                            </div>

                        </form>
                        <script type="text/JavaScript">

                             function  confirmDelete_bulk(){
                                var topval=document.getElementById("action_bottom").value;
                                var bottomVal=document.getElementById("action_upper").value;

                                   if(topval=='delete' || bottomVal=='delete'){


                                    var agree=confirm("<?php echo __( 'Are you sure you want to delete selected images','wp-responsive-photo-gallery' );?> ?");
                                    if (agree)
                                        return true ;
                                    else
                                        return false;
                                    }
                            }
                            function  confirmDelete(){
                                var agree=confirm("<?php echo __( 'Are you sure you want to delete this image ?','wp-responsive-photo-gallery' );?>");
                                if (agree)
                                    return true ;
                                else
                                    return false;
                            }
                            
                             var nonce_sec='<?php echo wp_create_nonce( "thumbnail-mass-image" );?>';
                            jQuery(document).ready(function() {
                                   //uploading files variable
                                   var custom_file_frame;
                                   jQuery(".massAdd").click(function(event) {
                                      var slider_id=jQuery(this).attr('id'); 
                                      event.preventDefault();
                                      //If the frame already exists, reopen it
                                      if (typeof(custom_file_frame)!=="undefined") {
                                         custom_file_frame.close();
                                      }

                                      //Create WP media frame.
                                      custom_file_frame = wp.media.frames.customHeader = wp.media({
                                         //Title of media manager frame
                                         title: "<?php echo __("WP Media Uploader",'wp-responsive-photo-gallery');?>",
                                         library: {
                                            type: 'image'
                                         },
                                         button: {
                                            //Button text
                                            text: "<?php echo __("Set Image",'wp-responsive-photo-gallery');?>"
                                         },
                                         //Do not allow multiple files, if you want multiple, set true
                                         multiple: true
                                      });

                                      //callback for selected image

                                      custom_file_frame.on('select', function() {


                                            jQuery("#modelMainDiv").show();
                                            jQuery("#LoaderDiv").show();
                                            jQuery("#ContainDiv").show();
                                            var selection = custom_file_frame.state().get('selection');
                                            selection.map(function(attachment) {

                                                attachment = attachment.toJSON();
                                                var validExtensions=new Array();
                                                validExtensions[0]='jpg';
                                                validExtensions[1]='jpeg';
                                                validExtensions[2]='png';
                                                validExtensions[3]='gif';


                                                var inarr=parseInt(jQuery.inArray( attachment.subtype, validExtensions));

                                                if(inarr>0 && attachment.type.toLowerCase()=='image' ){

                                                      var titleTouse="";
                                                      var imageDescriptionTouse="";

                                                      if(jQuery.trim(attachment.title)!=''){

                                                         titleTouse=jQuery.trim(attachment.title); 
                                                      }  
                                                      else if(jQuery.trim(attachment.caption)!=''){

                                                         titleTouse=jQuery.trim(attachment.caption);  
                                                      }

                                                      if(jQuery.trim(attachment.description)!=''){

                                                         imageDescriptionTouse=jQuery.trim(attachment.description); 
                                                      }  
                                                      else if(jQuery.trim(attachment.caption)!=''){

                                                         imageDescriptionTouse=jQuery.trim(attachment.caption);  
                                                      }

                                                      var data = {
                                                                imagetitle:titleTouse,
                                                                image_description: imageDescriptionTouse,
                                                                attachment_id:attachment.id,
                                                                slider_id:slider_id,
                                                                action: 'mass_upload_wpresponsivephgallery',
                                                                thumbnail_security:nonce_sec
                                                            };

                                                        url='admin.php?page=responsive_photo_gallery_image_management&action=mass_upload_wpresponsivephgallery'
                                                        jQuery.ajax({
                                                              type: 'POST',
                                                              url: ajaxurl,
                                                              data: data,
                                                              success: function(result) {
                                                                  if(result.isOk == false)
                                                                      alert(result.message);
                                                              },
                                                              dataType:'html',
                                                              async:false
                                                            });


                                                }  

                                            });

                                            jQuery("#modelMainDiv").hide();
                                            jQuery("#LoaderDiv").hide();
                                            jQuery("#ContainDiv").hide();

                                        });

                                         custom_file_frame.on('close', function() {
                                             window.location.reload();
                                          });

                                      //Open modal
                                      custom_file_frame.open();
                                   });
                                })
                        </script>

                        <br class="clear">

                        <h3><?php echo __( 'To print this slideshow gallery into WordPress Post/Page use below Short code','wp-responsive-photo-gallery' );?></h3>
                        <input type="text" value="[print_my_responsive_photo_gallery]" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
                        <div class="clear"></div>
                        <h3><?php echo __( 'To print this slideshow gallery into WordPress theme/template PHP files use below php code','wp-responsive-photo-gallery' );?></h3>
                        <input type="text" value="&lt;?php echo do_shortcode('[print_my_responsive_photo_gallery]'); ?&gt;" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />

                        <div class="clear"></div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container"  > 

                        <div class="postbox"> 
                            <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-responsive-photo-gallery');?></h3> 
                            <div class="inside">
                                <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                                <div style="margin:10px 5px">

                                </div>
                            </div></div>
                        <div class="postbox"> 
                            <center><h3 class="hndle"><span></span><?php echo __( 'Google For Business','wp-responsive-photo-gallery');?></h3> </center>
                            <div class="inside">
                                <center><a target="_blank" href="https://goo.gl/OJBuHT"><img style="width:100%" src="<?php echo plugins_url( 'images/gsuite_promo.png', __FILE__ ) ;?>" border="0"></a></center>
                                <div style="margin:10px 5px">
                                </div>
                            </div></div>
                        

                    </div>
                    <div class="clear"></div>
                </div> 
                
                <div style="clear: both;"></div>
                <?php $url = plugin_dir_url(__FILE__);  ?>
            </div>  
        </div>  
      
        <?php 
        }   
        else if(strtolower($action)==strtolower('addedit')){
            $url = plugin_dir_url(__FILE__);

        ?>
        <?php        
            if(isset($_POST['btnsave'])){

                //edit save
                if(isset($_POST['imageid'])){

                    if ( !check_admin_referer( 'action_image_add_edit','add_edit_image_nonce')){
                      
                      wp_die('Security check fail'); 
                    }
                  
                    if ( ! current_user_can( 'rsp_responsive_photo_gallery_edit_image' ) ) {

                        $location='admin.php?page=responsive_photo_gallery_image_management';
                        $my_responsive_photo_gallery_slider_settings_messages=array();
                        $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                        $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                        update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                        echo "<script type='text/javascript'> location.href='$location';</script>";     
                        exit;   

                    }
                
                   
                    
                    //add new
                    $location='admin.php?page=responsive_photo_gallery_image_management';
                    $title=trim(htmlentities(sanitize_text_field($_POST['imagetitle']),ENT_QUOTES));
                    $imageurl=trim(htmlentities(esc_url_raw($_POST['imageurl']),ENT_QUOTES));
                    $imageid=intval(htmlentities(sanitize_text_field($_POST['imageid']),ENT_QUOTES));
                    $imagename="";
                    if(trim($_POST['HdnMediaSelection'])!=''){

                        $postThumbnailID=(int) htmlentities(strip_tags($_POST['HdnMediaSelection']),ENT_QUOTES);
                        $photoMeta = wp_get_attachment_metadata( $postThumbnailID );
                        if(is_array($photoMeta) and isset($photoMeta['file'])) {

                            $fileName=$photoMeta['file'];
                            $phyPath=ABSPATH;
                            $phyPath=str_replace("\\","/",$phyPath);

                            $pathArray=pathinfo($fileName);

                            $imagename=$pathArray['basename'];

                            $upload_dir_n = wp_upload_dir(); 
                            $upload_dir_n=$upload_dir_n['basedir'];
                            $fileUrl=$upload_dir_n.'/'.$fileName;
                            $fileUrl=str_replace("\\","/",$fileUrl);

                            $wpcurrentdir=dirname(__FILE__);
                            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                            $imageUploadTo=$pathToImagesFolder.'/'.$imagename;

                            @copy($fileUrl, $imageUploadTo);
                            if(!file_exists($imageUploadTo)){
                                rs_photogallery_save_image_remote_lbox($fileUrl,$imageUploadTo);
                               }
                            

                        }

                    }  


                    try{
                        if($imagename!=""){
                            $query = "update ".$wpdb->prefix."gv_responsive_slider set title='$title',image_name='$imagename',
                            custom_link='$imageurl' where id=$imageid";
                        }
                        else{
                            $query = "update ".$wpdb->prefix."gv_responsive_slider set title='$title',
                            custom_link='$imageurl' where id=$imageid";
                        } 
                        $wpdb->query($query); 

                        $my_responsive_photo_gallery_slider_settings_messages=array();
                        $my_responsive_photo_gallery_slider_settings_messages['type']='succ';
                        $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Image updated successfully.','wp-responsive-photo-gallery' );
                        update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);


                    }
                    catch(Exception $e){

                        $my_responsive_photo_gallery_slider_settings_messages=array();
                        $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                        $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Error while updating image.','wp-responsive-photo-gallery' );
                        update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                    }  


                    echo "<script type='text/javascript'> location.href='$location';</script>";
                }
                else{

                    //add new

                    if ( ! current_user_can( 'rsp_responsive_photo_gallery_add_image' ) ) {

                        $location='admin.php?page=responsive_photo_gallery_image_management';
                        $my_responsive_photo_gallery_slider_settings_messages=array();
                        $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                        $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                        update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                        echo "<script type='text/javascript'> location.href='$location';</script>";     
                        exit;   

                    }
                    
                    $location='admin.php?page=responsive_photo_gallery_image_management';
                    $title=trim(htmlentities(sanitize_text_field($_POST['imagetitle']),ENT_QUOTES));
                    $imageurl=trim(htmlentities(esc_url_raw($_POST['imageurl']),ENT_QUOTES));
                    $createdOn=date('Y-m-d h:i:s');
                    if(function_exists('date_i18n')){

                        $createdOn=date_i18n('Y-m-d'.' '.get_option('time_format') ,false,false);
                        if(get_option('time_format')=='H:i')
                            $createdOn=date('Y-m-d H:i:s',strtotime($createdOn));
                        else   
                            $createdOn=date('Y-m-d h:i:s',strtotime($createdOn));

                    }

                     
                    $location='admin.php?page=responsive_photo_gallery_image_management';

                        try{

                            if(trim($_POST['HdnMediaSelection'])!=''){

                                $postThumbnailID=(int) htmlentities(strip_tags($_POST['HdnMediaSelection']),ENT_QUOTES);
                                $photoMeta = wp_get_attachment_metadata( $postThumbnailID );

                                if(is_array($photoMeta) and isset($photoMeta['file'])) {

                                    $fileName=$photoMeta['file'];
                                    $phyPath=ABSPATH;
                                    $phyPath=str_replace("\\","/",$phyPath);

                                    $pathArray=pathinfo($fileName);

                                    $imagename=$pathArray['basename'];

                                    $upload_dir_n = wp_upload_dir(); 
                                    $upload_dir_n=$upload_dir_n['basedir'];
                                    $fileUrl=$upload_dir_n.'/'.$fileName;
                                    $fileUrl=str_replace("\\","/",$fileUrl);

                                    $wpcurrentdir=dirname(__FILE__);
                                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                                    $imageUploadTo=$pathToImagesFolder.'/'.$imagename;

                                    @copy($fileUrl, $imageUploadTo);
                                    if(!file_exists($imageUploadTo)){
                                        rs_photogallery_save_image_remote_lbox($fileUrl,$imageUploadTo);
                                       }

                                }

                            } 


                            $query = "INSERT INTO ".$wpdb->prefix."gv_responsive_slider (title, image_name,createdon,custom_link) 
                            VALUES ('$title','$imagename','$createdOn','$imageurl')";

                            $wpdb->query($query); 

                            $my_responsive_photo_gallery_slider_settings_messages=array();
                            $my_responsive_photo_gallery_slider_settings_messages['type']='succ';
                            $my_responsive_photo_gallery_slider_settings_messages['message']='New image added successfully.';
                            update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);


                        }
                        catch(Exception $e){

                            $my_responsive_photo_gallery_slider_settings_messages=array();
                            $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                            $my_responsive_photo_gallery_slider_settings_messages['message']='Error while adding image.';
                            update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                        }  

                        
                    echo "<script type='text/javascript'> location.href='$location';</script>";          

                } 

            }
            else{ 

            ?>
            <div style="width: 100%;">  
            <div style="float:left;width:100%;" >
                <div class="wrap">
                    <?php if(isset($_GET['id']) and intval($_GET['id'])>0)
                        { 


                            if ( ! current_user_can( 'rsp_responsive_photo_gallery_edit_image' ) ) {

                                $location='admin.php?page=responsive_photo_gallery_image_management';
                                $my_responsive_photo_gallery_slider_settings_messages=array();
                                $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                echo "<script type='text/javascript'> location.href='$location';</script>";     
                                exit;   

                            }

                            $id= intval($_GET['id']);
                            $query="SELECT * FROM ".$wpdb->prefix."gv_responsive_slider WHERE id=$id";
                            $myrow  = $wpdb->get_row($query);

                            if(is_object($myrow)){

                                $title=$myrow->title;
                                $image_link=$myrow->custom_link;
                                $image_name=$myrow->image_name;

                            }   

                        ?>

                        <h2><?php echo __( 'Update Image','wp-responsive-photo-gallery' );?> </h2>

                        <?php }else{ 

                            $title='';
                            $image_link='';
                            $image_name='';

                            if ( ! current_user_can( 'rsp_responsive_photo_gallery_add_image' ) ) {

                                $location='admin.php?page=responsive_photo_gallery_image_management';
                                $my_responsive_photo_gallery_slider_settings_messages=array();
                                $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                echo "<script type='text/javascript'> location.href='$location';</script>";     
                                exit;   

                            }
                        ?>
                        <div style="clear:both">
                            <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                        </div>   
                        <h2><?php echo __( 'Add Image','wp-responsive-photo-gallery' );?></h2>
                        <?php } ?>

                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
                                <form method="post" action="" id="addimage" name="addimage" enctype="multipart/form-data" >

                                    <div class="stuffbox" id="namediv" style="width:100%;">
                                        <h3><label for="link_name"><?php echo __( 'Upload Image','wp-responsive-photo-gallery' );?></label></h3>
                                        <div class="inside" id="fileuploaddiv">
                                            <?php if($image_name!=""){ ?>
                                                <div><b><?php echo __( 'Current Image :','wp-responsive-photo-gallery' );?></b><a id="currImg" href="<?php echo $baseurl.$image_name; ?>" target="_new"><?php echo $image_name; ?></a></div>
                                                <?php } ?>      
                                            
                                                <div class="uploader">
                                                  
                                                        <a href="javascript:;" class="niks_media" id="myMediaUploader"><b><?php echo __( 'Click Here to upload','wp-responsive-photo-gallery' );?></b></a>
                                                        <input id="HdnMediaSelection" name="HdnMediaSelection" type="hidden" value="" />
                                                    <br/>
                                                </div>  
                                             
                                                <script>
                                                    
                                                    jQuery(document).ready(function() {
                                                            //uploading files variable
                                                            var custom_file_frame;
                                                            jQuery("#myMediaUploader").click(function(event) {
                                                                    event.preventDefault();
                                                                    //If the frame already exists, reopen it
                                                                    if (typeof(custom_file_frame)!=="undefined") {
                                                                        custom_file_frame.close();
                                                                    }

                                                                    //Create WP media frame.
                                                                    custom_file_frame = wp.media.frames.customHeader = wp.media({
                                                                            //Title of media manager frame
                                                                            title: "<?php echo __( 'WP Media Uploader','wp-responsive-photo-gallery' );?>",
                                                                            library: {
                                                                                type: 'image'
                                                                            },
                                                                            button: {
                                                                                //Button text
                                                                                text: "<?php echo __( 'Set Image','wp-responsive-photo-gallery' );?>"
                                                                            },
                                                                            //Do not allow multiple files, if you want multiple, set true
                                                                            multiple: false
                                                                    });

                                                                    //callback for selected image
                                                                    custom_file_frame.on('select', function() {

                                                                            var attachment = custom_file_frame.state().get('selection').first().toJSON();

                                                                            var validExtensions=new Array();
                                                                            validExtensions[0]='jpg';
                                                                            validExtensions[1]='jpeg';
                                                                            validExtensions[2]='png';
                                                                            validExtensions[3]='gif';


                                                                            var inarr=parseInt(jQuery.inArray( attachment.subtype, validExtensions));

                                                                            if(inarr>0 && attachment.type.toLowerCase()=='image' ){

                                                                                var titleTouse="";
                                                                                var imageDescriptionTouse="";

                                                                                if(jQuery.trim(attachment.title)!=''){

                                                                                    titleTouse=jQuery.trim(attachment.title); 
                                                                                }  
                                                                                else if(jQuery.trim(attachment.caption)!=''){

                                                                                    titleTouse=jQuery.trim(attachment.caption);  
                                                                                }

                                                                                if(jQuery.trim(attachment.description)!=''){

                                                                                    imageDescriptionTouse=jQuery.trim(attachment.description); 
                                                                                }  
                                                                                else if(jQuery.trim(attachment.caption)!=''){

                                                                                    imageDescriptionTouse=jQuery.trim(attachment.caption);  
                                                                                }

                                                                                jQuery("#imagetitle").val(titleTouse);  
                                                                                jQuery("#image_description").val(imageDescriptionTouse);  

                                                                                if(attachment.id!=''){
                                                                                    jQuery("#HdnMediaSelection").val(attachment.id);  
                                                                                }   

                                                                            }  
                                                                            else{

                                                                                alert('<?php echo __( 'Invalid image selection','wp-responsive-photo-gallery' );?>.');
                                                                            }  
                                                                            //do something with attachment variable, for example attachment.filename
                                                                            //Object:
                                                                            //attachment.alt - image alt
                                                                            //attachment.author - author id
                                                                            //attachment.caption
                                                                            //attachment.dateFormatted - date of image uploaded
                                                                            //attachment.description
                                                                            //attachment.editLink - edit link of media
                                                                            //attachment.filename
                                                                            //attachment.height
                                                                            //attachment.icon - don't know WTF?))
                                                                            //attachment.id - id of attachment
                                                                            //attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
                                                                            //attachment.menuOrder
                                                                            //attachment.mime - mime type, for example image/jpeg"
                                                                            //attachment.name - name of attachment file, for example "my-image"
                                                                            //attachment.status - usual is "inherit"
                                                                            //attachment.subtype - "jpeg" if is "jpg"
                                                                            //attachment.title
                                                                            //attachment.type - "image"
                                                                            //attachment.uploadedTo
                                                                            //attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
                                                                            //attachment.width
                                                                    });

                                                                    //Open modal
                                                                    custom_file_frame.open();
                                                            });
                                                    })
                                                </script>
                                                
                                        </div>
                                    </div>
                                    <div class="stuffbox" id="namediv" style="width:100%">
                                        <h3><label for="link_name"><?php echo __( 'Image Title','wp-responsive-photo-gallery' );?></label></h3>
                                        <div class="inside">
                                            <input type="text" id="imagetitle"  size="30" name="imagetitle" value="<?php echo $title;?>">
                                            <div style="clear:both"></div>
                                            <div></div>
                                            <div style="clear:both"></div>
                                            <p><?php echo __( 'Used in image alt for seo','wp-responsive-photo-gallery' );?></p>
                                        </div>
                                    </div>
                                    <div class="stuffbox" id="namediv" style="width:100%">
                                        <h3><label for="link_name"><?php echo __( 'Image Url','wp-responsive-photo-gallery' );?>(<?php echo __( 'On click redirect to this url.','wp-responsive-photo-gallery' );?>)</label></h3>
                                        <div class="inside">
                                            <input type="text" id="imageurl" class=""   size="30" name="imageurl" value="<?php echo $image_link; ?>">
                                            <div style="clear:both"></div>
                                            <div></div>
                                            <div style="clear:both"></div>
                                            <p><?php echo __( 'On image click users will redirect to this url.','wp-responsive-photo-gallery' );?></p>
                                        </div>
                                    </div>
                                    
                                    <?php if(isset($_GET['id']) and intval($_GET['id'])>0){ ?> 
                                        <input type="hidden" name="imageid" id="imageid" value="<?php echo htmlentities(intval($_GET['id']),ENT_QUOTES);?>">
                                        <?php
                                        } 
                                    ?>
                                     <?php wp_nonce_field('action_image_add_edit','add_edit_image_nonce'); ?>       
                                    <input type="submit" onclick="return validateFile();" name="btnsave" id="btnsave" value="<?php echo __( 'Save Changes','wp-responsive-photo-gallery' );?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="Cancel" class="button-primary" onclick="location.href='admin.php?page=responsive_photo_gallery_image_management'">

                                </form> 
                                <script type="text/javascript">

                                    
                                    jQuery(document).ready(function() {

                                            jQuery("#addimage").validate({
                                                    rules: {
                                                        imagetitle: {
                                                            required:true, 
                                                            maxlength: 200
                                                        },imageurl: {
                                                            url2:true,  
                                                            maxlength: 500
                                                        },
                                                        image_name:{
                                                            isimage:true  
                                                        }
                                                    },
                                                    errorClass: "image_error",
                                                    errorPlacement: function(error, element) {
                                                        error.appendTo( element.next().next().next());
                                                    } 


                                            })
                                    });

                                     function validateFile(){

                                        
                                        if(jQuery('#currImg').length>0 || jQuery.trim(jQuery("#HdnMediaSelection").val())!="" ){
                                            return true;
                                        }
                                        else
                                            {
                                            jQuery("#err_daynamic").remove();
                                            jQuery("#myMediaUploader").after('<br/><label class="image_error" id="err_daynamic"><?php echo __( 'Please select file','wp-responsive-photo-gallery' );?>.</label>');
                                            return false;  
                                        } 
                                            
                                    }
                                </script> 

                            </div>
                         <div id="postbox-container-1" class="postbox-container" > 
					
					          <div class="postbox"> 
					              <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-responsive-photo-gallery' );?></h3> 
					              <div class="inside">
					                  <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>
					
					                  <div style="margin:10px 5px">
					
					                  </div>
					              </div></div>
					          
					
					      </div> 
                        </div>
                    </div>  

                </div>      
            </div>


            <?php 
            } 
        }  

        else if(strtolower($action)==strtolower('delete')){

            
             $retrieved_nonce = '';
            
            if(isset($_GET['nonce']) and $_GET['nonce']!=''){
              
                $retrieved_nonce=sanitize_text_field($_GET['nonce']);
                
            }
            if (!wp_verify_nonce($retrieved_nonce, 'delete_image' ) ){
        
                
                wp_die('Security check fail'); 
            }
            
             if ( ! current_user_can( 'rsp_responsive_photo_gallery_delete_image' ) ) {

                $location='admin.php?page=responsive_photo_gallery_image_management';
                $my_responsive_photo_gallery_slider_settings_messages=array();
                $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                echo "<script type='text/javascript'> location.href='$location';</script>";     
                exit;   

            }
            $location='admin.php?page=responsive_photo_gallery_image_management';
            $deleteId=(int) htmlentities(strip_tags($_GET['id']),ENT_QUOTES);

            try{


                $query="SELECT * FROM ".$wpdb->prefix."gv_responsive_slider WHERE id=$deleteId";
                $myrow  = $wpdb->get_row($query);

                if(is_object($myrow)){

                    $image_name=$myrow->image_name;
                    $wpcurrentdir=dirname(__FILE__);
                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                    $imagetoDel=$pathToImagesFolder.'/'.$image_name;
                    @unlink($imagetoDel);

                    $query = "delete from  ".$wpdb->prefix."gv_responsive_slider where id=$deleteId";
                    $wpdb->query($query); 

                    $my_responsive_photo_gallery_slider_settings_messages=array();
                    $my_responsive_photo_gallery_slider_settings_messages['type']='succ';
                    $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Image deleted successfully.','wp-responsive-photo-gallery' );
                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                }    


            }
            catch(Exception $e){

                $my_responsive_photo_gallery_slider_settings_messages=array();
                $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Error while deleting image.','wp-responsive-photo-gallery' );
                update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
            }  

            echo "<script type='text/javascript'> location.href='$location';</script>";

        }  
        else if(strtolower($action)==strtolower('deleteselected')){

            if(!check_admin_referer('action_settings_mass_delete','mass_delete_nonce')){
               
                wp_die('Security check fail'); 
            }
           
            if ( ! current_user_can( 'rsp_responsive_photo_gallery_delete_image' ) ) {

                $location='admin.php?page=responsive_photo_gallery_image_management';
                $my_responsive_photo_gallery_slider_settings_messages=array();
                $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                echo "<script type='text/javascript'> location.href='$location';</script>";     
                exit;   

            }
            
            $location='admin.php?page=responsive_photo_gallery_image_management'; 
            if(isset($_POST) and isset($_POST['deleteselected']) and  ( $_POST['action']=='delete' or $_POST['action_upper']=='delete')){

                if(sizeof($_POST['thumbnails']) >0){

                    $deleteto=$_POST['thumbnails'];
                    $implode=implode(',',$deleteto);   

                    try{

                        foreach($deleteto as $img){ 

                            $img=intval($img);
                            $query="SELECT * FROM ".$wpdb->prefix."gv_responsive_slider WHERE id=$img";
                            $myrow  = $wpdb->get_row($query);

                            if(is_object($myrow)){

                                $image_name=$myrow->image_name;
                                $wpcurrentdir=dirname(__FILE__);
                                $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                                $imagetoDel=$pathToImagesFolder.'/'.$image_name;
                                @unlink($imagetoDel);
                                $query = "delete from  ".$wpdb->prefix."gv_responsive_slider where id=$img";
                                $wpdb->query($query); 

                                $my_responsive_photo_gallery_slider_settings_messages=array();
                                $my_responsive_photo_gallery_slider_settings_messages['type']='succ';
                                $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Selected images deleted successfully.','wp-responsive-photo-gallery' );
                                update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                            }

                        }

                    }
                    catch(Exception $e){

                        $my_responsive_photo_gallery_slider_settings_messages=array();
                        $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                        $my_responsive_photo_gallery_slider_settings_messages['message']='Error while deleting image.';
                        update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                    }  

                    echo "<script type='text/javascript'> location.href='$location';</script>";


                }
                else{

                    echo "<script type='text/javascript'> location.href='$location';</script>";   
                }

            }
            else{

                echo "<script type='text/javascript'> location.href='$location';</script>";      
            }

        }      
    } 
    function responsive_photo_gallery_slider_admin_preview(){
        
        
        $settings=get_option('my_responsive_photo_gallery_slider_settings');
        
         if ( ! current_user_can( 'rsp_responsive_photo_gallery_preview' ) ) {

           wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

        }

    ?>      
    <div style="">  
        <div style="">
            <br/>
            <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
            <div class="wrap">
                <h2><?php echo __( 'Slider Preview','wp-responsive-photo-gallery' );?></h2>
                <br>

                <?php
                    $wpcurrentdir=dirname(__FILE__);
                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                    
                    $uploads = wp_upload_dir ();
                    $baseDir = $uploads ['basedir'];
                    $baseDir = str_replace ( "\\", "/", $baseDir );
                    $pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';

                    $baseurl=$uploads['baseurl'];
                    $baseurl.='/wp-responsive-photo-gallery/';


                ?>
                <?php $slider_id_html=time().rand(0,5000);?>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <div style="clear: both;"></div>
                            <?php $url = plugin_dir_url(__FILE__);  ?>
                            <div id="divSliderMain_admin" style="max-width:<?php echo $settings['panel_width'];?>px;">
                                <ul id="<?php echo $slider_id_html;?>">
                                    <?php
                                        global $wpdb;
                                        $imageheight=$settings['panel_height'];
                                        $imagewidth=$settings['panel_width'];
                                        $query="SELECT * FROM ".$wpdb->prefix."gv_responsive_slider order by createdon desc";
                                        $rows=$wpdb->get_results($query,'ARRAY_A');

                                        if(count($rows) > 0){
                                            foreach($rows as $row){

                                                $imagename=$row['image_name'];
                                                $imageUploadTo=$pathToImagesFolder.'/'.$imagename;
                                                $imageUploadTo=str_replace("\\","/",$imageUploadTo);
                                                $pathinfo=pathinfo($imageUploadTo);
                                                $filenamewithoutextension=$pathinfo['filename'];
                                                $outputimg="";

                                                if($settings['panel_scale']=='fit'){

                                                    $outputimg = $baseurl.$imagename;

                                                }else{

                                                    list($width, $height) = getimagesize($pathToImagesFolder."/".$row['image_name']);
                                                    if($width<$imagewidth){
                                                        $imagewidth=$width;
                                                    }

                                                    if($height<$imageheight){

                                                        $imageheight=$height;
                                                    }

                                                    $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                    $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                             

                                                    if(file_exists($imagetoCheck)){
                                                        $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                    }
                                                    else if(file_exists($imagetoCheckSmall)){
                                                            $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                        }
                                                    else{

                                                        if(file_exists($pathToImagesFolder."/".$row['image_name'])){

                                                            $resizeObj = new resize($pathToImagesFolder."/".$row['image_name']); 
                                                            $resizeObj -> resizeImage($imagewidth, $imageheight, "exact"); 
                                                            $resizeObj -> saveImage($pathToImagesFolder."/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'], 100); 
                                                            //$outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                            
                                                             if(file_exists($imagetoCheck)){
                                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                }
                                                                else if(file_exists($imagetoCheckSmall)){
                                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                                }

                                                        }else{

                                                            $outputimg = $baseurl.$imagename;
                                                        }   

                                                    }

                                                }
                                            ?>         
                                            <li><img data-target="1" data-href="<?php echo $row['custom_link'];?>" org-src-="<?php echo $outputimg;?>"  /></li> 

                                            <?php }?>   
                                        <?php }?>   
                                </ul>
                            </div>
                            <script type="text/javascript">
                                
                                jQuery(document).ready(function() {

                                        <?php $galRandNo=rand(0,13313); ?> 
                                        var galleryItems<?php echo $galRandNo;?>;
                                        jQuery(function(){
                                                galleryItems<?php echo $galRandNo;?> = jQuery("#<?php echo $slider_id_html;?>");

                                                var galleryItemDivs = jQuery('#divSliderMain_admin');

                                                galleryItems<?php echo $galRandNo;?>.each(function (index, item){
                                                        item.parent_data = jQuery(item).parent("#divSliderMain_admin");
                                                });


                                                galleryItemDivs.each(function(index, item){   
                                                        jQuery("ul",this).galleryView({

                                                                transition_speed:<?php echo $settings['transition_speed'];?>,         //INT - duration of panel/frame transition (in milliseconds)
                                                                transition_interval:<?php echo $settings['transition_interval'];?>,         //INT - delay between panel/frame transitions (in milliseconds)
                                                                easing:'<?php echo isset($settings['easing'])?$settings['easing']:'';?>',                 //STRING - easing method to use for animations (jQuery provides 'swing' or 'linear', more available with jQuery UI or Easing plugin)
                                                                show_panels:<?php echo ($settings['show_panels']==1)?'true':'false' ;?>,                 //BOOLEAN - flag to show or hide panel portion of gallery
                                                                show_panel_nav:<?php echo ($settings['show_panel_nav']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide panel navigation buttons
                                                                enable_overlays:<?php echo ($settings['enable_overlays']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide panel overlays
                                                                panel_width:<?php echo $settings['panel_width'];?>,                 //INT - width of gallery panel (in pixels)
                                                                panel_height:<?php echo $settings['panel_height'];?>,                 //INT - height of gallery panel (in pixels)
                                                                panel_animation:'<?php echo $settings['panel_animation'];?>',         //STRING - animation method for panel transitions (crossfade,fade,slide,none)
                                                                panel_scale: '<?php echo $settings['panel_scale'];?>',             //STRING - cropping option for panel images (crop = scale image and fit to aspect ratio determined by panel_width and panel_height, fit = scale image and preserve original aspect ratio)
                                                                overlay_position:'<?php echo $settings['overlay_position'];?>',     //STRING - position of panel overlay (bottom, top)
                                                                pan_images:<?php echo ($settings['pan_images']==1)?'true':'false' ;?>,                //BOOLEAN - flag to allow user to grab/drag oversized images within gallery
                                                                pan_style:'<?php echo $settings['pan_style'];?>',                //STRING - panning method (drag = user clicks and drags image to pan, track = image automatically pans based on mouse position
                                                                start_frame:'<?php echo $settings['start_frame'];?>',                 //INT - index of panel/frame to show first when gallery loads
                                                                show_filmstrip:<?php echo ($settings['show_filmstrip']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide filmstrip portion of gallery
                                                                show_filmstrip_nav:<?php echo ($settings['show_filmstrip_nav']==1)?'true':'false' ;?>,         //BOOLEAN - flag indicating whether to display navigation buttons
                                                                enable_slideshow:<?php echo ($settings['enable_slideshow']==1)?'true':'false' ;?>,            //BOOLEAN - flag indicating whether to display slideshow play/pause button
                                                                autoplay:<?php echo ($settings['autoplay']==1)?'true':'false' ;?>,                //BOOLEAN - flag to start slideshow on gallery load
                                                                show_captions:<?php echo ($settings['show_captions']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide frame captions    
                                                                filmstrip_style: '<?php echo $settings['filmstrip_style'];?>',         //STRING - type of filmstrip to use (scroll = display one line of frames, scroll filmstrip if necessary, showall = display multiple rows of frames if necessary)
                                                                filmstrip_position:'<?php echo $settings['filmstrip_position'];?>',     //STRING - position of filmstrip within gallery (bottom, top, left, right)
                                                                frame_width:<?php echo $settings['frame_width'];?>,                 //INT - width of filmstrip frames (in pixels)
                                                                frame_height:<?php echo $settings['frame_width'];?>,                 //INT - width of filmstrip frames (in pixels)
                                                                frame_opacity:<?php echo $settings['frame_opacity'];?>,             //FLOAT - transparency of non-active frames (1.0 = opaque, 0.0 = transparent)
                                                                frame_scale: '<?php echo $settings['frame_scale'];?>',             //STRING - cropping option for filmstrip images (same as above)
                                                                frame_gap:<?php echo $settings['frame_gap'];?>,                     //INT - spacing between frames within filmstrip (in pixels)
                                                                show_infobar:<?php echo ($settings['show_infobar']==1)?'true':'false' ;?>,                //BOOLEAN - flag to show or hide infobar
                                                                infobar_opacity:<?php echo $settings['infobar_opacity'];?>,               //FLOAT - transparency for info bar
                                                                clickable: 'all'

                                                        });     

                                                }); 

                                        });


                                        //
                                        // Resize the image gallery
                                        //
                                        var oldsize_w<?php echo $galRandNo;?>=<?php echo $settings['panel_width'];?>;
                                        var oldsize_h<?php echo $galRandNo;?>=<?php echo $settings['panel_height'];?>;

                                        function resizegallery<?php echo $galRandNo;?>(){

                                            if(galleryItems<?php echo $galRandNo;?>==undefined){return;}
                                            galleryItems<?php echo $galRandNo;?>.each(function (index, item){
                                                    var $parent = item.parent_data;

                                                    // width based on parent?
                                                    var width = ($parent.innerWidth()-10);//2 times 5 pixels margin
                                                    var height = ($parent.innerHeight()-10);//2 times 5 pixels margin
                                                    if(oldsize_w<?php echo $galRandNo;?>==width){          
                                                        return;
                                                    }
                                                    oldsize_w<?php echo $galRandNo;?>=width;
                                                    var resizeToHeight=width/3*2;
                                                    if(resizeToHeight><?php echo $settings['panel_height'];?>){
                                                        resizeToHeight=<?php echo $settings['panel_height'];?>;  
                                                    }
                                                    thumbfactor = width/(<?php echo $settings['panel_width'];?>-10);

                                                    jQuery(item).resizeGalleryView(
                                                        width,resizeToHeight, <?php echo $settings['frame_width'];?>*thumbfactor, <?php echo $settings['frame_height'];?>*thumbfactor);

                                            });
                                        }

                                        var inited<?php echo $galRandNo;?>=false;

                                        function onresize<?php echo $galRandNo;?>(){  

                                            resizegallery<?php echo $galRandNo;?>();
                                            inited<?php echo $galRandNo;?>=true;
                                        }


                                        jQuery(window).resize(onresize<?php echo $galRandNo;?>);
                                        jQuery( document ).ready(function() {
                                                onresize<?php echo $galRandNo;?>();
                                        }); 

                                });


                            </script>      
                        </div>
                    </div>      
                </div>  
            </div>      
        </div>
        <div class="clear"></div>
    </div>
    <h3><?php echo __( 'To print this slideshow gallery into WordPress Post/Page use below Short code','wp-responsive-photo-gallery' );?></h3>
    <input type="text" value="[print_my_responsive_photo_gallery]" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
    <div class="clear"></div>
    <h3><?php echo __( 'To print this slideshow gallery into WordPress theme/template PHP files use below php code','wp-responsive-photo-gallery' );?></h3>
    <input type="text" value="&lt;?php echo do_shortcode('[print_my_responsive_photo_gallery]'); ?&gt;" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
    <div class="clear"></div>
    <?php       
    }

    function print_my_responsive_photo_gallery_func(){

        $settings=get_option('my_responsive_photo_gallery_slider_settings');
        $rand_Numb=uniqid('gallery_slider');
        $wpcurrentdir=dirname(__FILE__);
        $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
        $url = plugin_dir_url(__FILE__);
        
        $uploads = wp_upload_dir ();
        $baseDir = $uploads ['basedir'];
        $baseDir = str_replace ( "\\", "/", $baseDir );
        $pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';

        $baseurl=$uploads['baseurl'];
        $baseurl.='/wp-responsive-photo-gallery/';
        
        wp_enqueue_style('jquery.galleryview-3.0-dev-responsive');
        wp_enqueue_script('jquery'); 
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery.timers-1.2');
        wp_enqueue_script('jquery.easing.1.3');
        wp_enqueue_script('jquery.gview-3.0-dev-responsive');
        
        ob_start();
    ?><!-- print_my_responsive_photo_gallery_func --><div id="divSliderMain_admin_<?php echo $rand_Numb;?>" style="max-width:<?php echo $settings['panel_width'];?>px;">
        <ul id="<?php echo $rand_Numb;?>" style="visibility: hidden">
            <?php
                global $wpdb;
                $imageheight=$settings['panel_height'];
                $imagewidth=$settings['panel_width'];
                $query="SELECT * FROM ".$wpdb->prefix."gv_responsive_slider order by createdon desc";
                $rows=$wpdb->get_results($query,'ARRAY_A');

                if(count($rows) > 0){
                    foreach($rows as $row){

                        $imagename=$row['image_name'];
                        $imageUploadTo=$pathToImagesFolder.'/'.$imagename;
                        $imageUploadTo=str_replace("\\","/",$imageUploadTo);
                        $pathinfo=pathinfo($imageUploadTo);
                        $filenamewithoutextension=$pathinfo['filename'];
                        $outputimg="";

                        if($settings['panel_scale']=='fit'){

                            $outputimg = $baseurl.$imagename;

                        }else{
                            list($width, $height) = getimagesize($pathToImagesFolder."/".$row['image_name']);
                            if($width<$imagewidth){
                                $imagewidth=$width;
                            }

                            if($height<$imageheight){

                                $imageheight=$height;
                            }

                            $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                            $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                            
                            if(file_exists($imagetoCheck)){
                                $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                            }
                            else if(file_exists($imagetoCheckSmall)){
                                $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                            }
                            else{

                                if(file_exists($pathToImagesFolder."/".$row['image_name'])){

                                    $resizeObj = new resize($pathToImagesFolder."/".$row['image_name']); 
                                    $resizeObj -> resizeImage($imagewidth, $imageheight, "exact"); 
                                    $resizeObj -> saveImage($pathToImagesFolder."/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'], 100); 
                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                }else{

                                    $outputimg = $baseurl.$imagename;
                                }    

                            }

                        }
                    ?>         
                    <li><img data-target="1" data-href="<?php echo $row['custom_link'];?>" org-src-="<?php echo $outputimg;?>"  /></li> 

                    <?php }?>   
                <?php }?>   
        </ul>
    </div>                  
    <script type="text/javascript">


       <?php $intval= uniqid('interval_');?>
               
        var <?php echo $intval;?> = setInterval(function() {

        if(document.readyState === 'complete') {

           clearInterval(<?php echo $intval;?>);
            
                
        
                jQuery("#<?php echo $rand_Numb;?>").css('visibility','visible');
                <?php $galRandNo=rand(0,13313); ?> 
                var galleryItems<?php echo $galRandNo;?>;
                jQuery(function(){
                        galleryItems<?php echo $galRandNo;?> = jQuery("#<?php echo $rand_Numb;?>");

                        var galleryItemDivs = jQuery('#divSliderMain_admin_<?php echo $rand_Numb;?>');

                        galleryItems<?php echo $galRandNo;?>.each(function (index, item){
                                item.parent_data = jQuery(item).parent("#divSliderMain_admin_<?php echo $rand_Numb;?>");
                        });

                        galleryItemDivs.each(function(index, item){

                                jQuery("ul",this).galleryView({

                                        transition_speed:<?php echo $settings['transition_speed'];?>,         //INT - duration of panel/frame transition (in milliseconds)
                                        transition_interval:<?php echo $settings['transition_interval'];?>,         //INT - delay between panel/frame transitions (in milliseconds)
                                        easing:'<?php echo isset($settings['easing'])?$settings['easing']:'';?>',                 //STRING - easing method to use for animations (jQuery provides 'swing' or 'linear', more available with jQuery UI or Easing plugin)
                                        show_panels:<?php echo ($settings['show_panels']==1)?'true':'false' ;?>,                 //BOOLEAN - flag to show or hide panel portion of gallery
                                        show_panel_nav:<?php echo ($settings['show_panel_nav']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide panel navigation buttons
                                        enable_overlays:<?php echo ($settings['enable_overlays']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide panel overlays
                                        panel_width:<?php echo $settings['panel_width'];?>,                 //INT - width of gallery panel (in pixels)
                                        panel_height:<?php echo $settings['panel_height'];?>,                 //INT - height of gallery panel (in pixels)
                                        panel_animation:'<?php echo $settings['panel_animation'];?>',         //STRING - animation method for panel transitions (crossfade,fade,slide,none)
                                        panel_scale: '<?php echo $settings['panel_scale'];?>',             //STRING - cropping option for panel images (crop = scale image and fit to aspect ratio determined by panel_width and panel_height, fit = scale image and preserve original aspect ratio)
                                        overlay_position:'<?php echo $settings['overlay_position'];?>',     //STRING - position of panel overlay (bottom, top)
                                        pan_images:<?php echo ($settings['pan_images']==1)?'true':'false' ;?>,                //BOOLEAN - flag to allow user to grab/drag oversized images within gallery
                                        pan_style:'<?php echo $settings['pan_style'];?>',                //STRING - panning method (drag = user clicks and drags image to pan, track = image automatically pans based on mouse position
                                        start_frame:'<?php echo $settings['start_frame'];?>',                 //INT - index of panel/frame to show first when gallery loads
                                        show_filmstrip:<?php echo ($settings['show_filmstrip']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide filmstrip portion of gallery
                                        show_filmstrip_nav:<?php echo ($settings['show_filmstrip_nav']==1)?'true':'false' ;?>,         //BOOLEAN - flag indicating whether to display navigation buttons
                                        enable_slideshow:<?php echo ($settings['enable_slideshow']==1)?'true':'false' ;?>,            //BOOLEAN - flag indicating whether to display slideshow play/pause button
                                        autoplay:<?php echo ($settings['autoplay']==1)?'true':'false' ;?>,                //BOOLEAN - flag to start slideshow on gallery load
                                        show_captions:<?php echo ($settings['show_captions']==1)?'true':'false' ;?>,             //BOOLEAN - flag to show or hide frame captions    
                                        filmstrip_style: '<?php echo $settings['filmstrip_style'];?>',         //STRING - type of filmstrip to use (scroll = display one line of frames, scroll filmstrip if necessary, showall = display multiple rows of frames if necessary)
                                        filmstrip_position:'<?php echo $settings['filmstrip_position'];?>',     //STRING - position of filmstrip within gallery (bottom, top, left, right)
                                        frame_width:<?php echo $settings['frame_width'];?>,                 //INT - width of filmstrip frames (in pixels)
                                        frame_height:<?php echo $settings['frame_width'];?>,                 //INT - width of filmstrip frames (in pixels)
                                        frame_opacity:<?php echo $settings['frame_opacity'];?>,             //FLOAT - transparency of non-active frames (1.0 = opaque, 0.0 = transparent)
                                        frame_scale: '<?php echo $settings['frame_scale'];?>',             //STRING - cropping option for filmstrip images (same as above)
                                        frame_gap:<?php echo $settings['frame_gap'];?>,                     //INT - spacing between frames within filmstrip (in pixels)
                                        show_infobar:<?php echo ($settings['show_infobar']==1)?'true':'false' ;?>,                //BOOLEAN - flag to show or hide infobar
                                        infobar_opacity:<?php echo $settings['infobar_opacity'];?>,               //FLOAT - transparency for info bar
                                        clickable: 'all'

                                });

                        }); 


                        var oldsize_w<?php echo $galRandNo;?>=<?php echo $settings['panel_width'];?>;
                        var oldsize_h<?php echo $galRandNo;?>=<?php echo $settings['panel_height'];?>;

                        function resizegallery<?php echo $galRandNo;?>(){

                            if(galleryItems<?php echo $galRandNo;?>==undefined){return;}
                            galleryItems<?php echo $galRandNo;?>.each(function (index, item){
                                    var $parent = item.parent_data;

                                    // width based on parent?
                                    var width = ($parent.innerWidth()-10);//2 times 5 pixels margin
                                    var height = ($parent.innerHeight()-10);//2 times 5 pixels margin
                                    if(oldsize_w<?php echo $galRandNo;?>==width){
                                        return;
                                    }
                                    oldsize_w<?php echo $galRandNo;?>=width;
                                    var resizeToHeight=width/3*2;
                                    if(resizeToHeight><?php echo $settings['panel_height'];?>){
                                        resizeToHeight=<?php echo $settings['panel_height'];?>;  
                                    }
                                    thumbfactor = width/(<?php echo $settings['panel_width'];?>-10);

                                    jQuery(item).resizeGalleryView(
                                        width, 
                                        resizeToHeight, <?php echo $settings['frame_width'];?>*thumbfactor, <?php echo $settings['frame_height'];?>*thumbfactor);

                            });
                        }

                        var inited<?php echo $galRandNo;?>=false;

                        function onresize<?php echo $galRandNo;?>(){  
                            resizegallery<?php echo $galRandNo;?>();
                            inited<?php echo $galRandNo;?>=true;
                        }


                        jQuery(window).resize(onresize<?php echo $galRandNo;?>);
                        jQuery( document ).ready(function() {
                                onresize<?php echo $galRandNo;?>();
                        }); 

                });   


         }    
        }, 100);


    </script><!-- end print_my_responsive_photo_gallery_func --><?php
        $output = ob_get_clean();
        return $output;
    }
    
      function my_responsive_photo_gallery_get_wp_version() {

        global $wp_version;
        return $wp_version;
    }


    function my_responsive_photo_gallery_is_plugin_page() {
        $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        foreach (array('responsive_photo_gallery_image_management') as $allowURI) {
            if(stristr($server_uri, $allowURI)) return true;
        }
        return false;
    }


    function rjg_save_image_remote($url,$saveto){
    
    $raw = wp_remote_retrieve_body( wp_remote_get( $url ) );
    
    if(file_exists($saveto)){
        @unlink($saveto);
    }
    $fp = @fopen($saveto,'x');
    @fwrite($fp, $raw);
    @fclose($fp);
}


function rjg_i13_get_http_response_code_gallery($url) {
    $headers = @get_headers($url);
    return @substr($headers[0], 9, 3);
}


function rjg_get_grid_data_justified_gallery_callback() {
    
        $retrieved_nonce='';
        if (isset($_POST['vNonce']) and $_POST['vNonce'] != '') {

           $retrieved_nonce = $_POST['vNonce'];
        }
        if (!wp_verify_nonce($retrieved_nonce, 'vNonce')) {


           wp_die('Security check fail');
        }

        ob_start();
        global $wpdb;
        
        $pageUrl = urldecode($_POST['page_url']);
        $urlComponents = parse_url($pageUrl);
        $queryStr = $urlComponents['query'];
        if ($queryStr != '') {
            parse_str($queryStr, $qStrArr);
        }

        $pagenum = 0;
        if (isset($qStrArr['pagenum']) and (int) $qStrArr['pagenum'] > 0) {

            $pagenum = intval($qStrArr['pagenum']);
        }
         
	$settings=get_option('rjg_settings');
	
        
        $limit = $settings['page_size'];
        $offset = ( $pagenum - 1 ) * $limit;
        
	$rand_Numb = uniqid ( 'thumnail_slider' );
	$rand_Num_td = uniqid ( 'divSliderMain' );
	$rand_var_name = uniqid ( 'rand_' );
        $target = uniqid ( 'target'.rand(1,2000000) );
	
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	// $settings=get_option('thumbnail_slider_settings');
	
	$uploads = wp_upload_dir ();
	$baseDir = $uploads ['basedir'];
	$baseDir = str_replace ( "\\", "/", $baseDir );
	$pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
	$baseurl = $uploads ['baseurl'];
	$baseurl .= '/wp-responsive-photo-gallery/';
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	$randOmeAlbName = uniqid ( 'alb_' );
	$randOmeRel = uniqid ( 'rel_' );
        $randOmVlBox = uniqid('video_lbox_');
        $vNonce = wp_create_nonce('vNonce');
        $url = plugin_dir_url(__FILE__);
        $loaderImg = $url . 'images/bx_loader.gif';
        $imageMargin=$settings['imageMargin'];
        
        $LoadingBackColor=$settings ['BackgroundColor'];
        if(strtolower($LoadingBackColor)=='none'){
            $LoadingBackColor='#ffffff';
        }
        if (is_array($settings)) { 
                                
        ?>
                        <style>#<?php echo $rand_var_name;?>{background-color:<?php echo $LoadingBackColor;?>;padding-top:<?php echo $imageMargin;?>px;padding-left:<?php echo $imageMargin;?>px;padding-right:<?php echo $imageMargin;?>px;}</style>
                        <div style="clear: both;"></div>
                        <?php $url = plugin_dir_url(__FILE__); ?>           
                         <div style="clear: both;"></div>
                            <?php $url = plugin_dir_url(__FILE__); ?>           


                                <div class="gallery_wrap0" id="<?php echo $rand_Num_td;?>" >

                                        <div class="gallery_ gallery_0" id="<?php echo $rand_var_name;?>" >

                                        <div id="<?php echo $rand_var_name; ?>_overlay_grid" class="overlay_grid" style="background: <?php echo $LoadingBackColor; ?> url('<?php echo $loaderImg; ?>') no-repeat scroll 50% 50%;" ></div>

                                                        <?php

                                                          $imageheight = $settings ['imageheight'];
                                                          $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery  order by createdon desc LIMIT $offset, $limit";
                                                          $firstChild='firstimg';
                                                          $rows = $wpdb->get_results ( $query, 'ARRAY_A' );


                                                          if (count ( $rows ) > 0) {

                                                              foreach ( $rows as $row ) {

                                                                      $imagename = $row ['image_name'];
                                                                      $video_url = $row ['videourl'];
                                                                      $video_url_org = $row ['murl'];
                                                                      $Url_vid = @parse_url($video_url_org);



                                                                      $relend = '';
                                                                       $flag=false;
                                                                          if (isset($Url_vid['query']) and $Url_vid['query'] != '') {


                                                                              parse_str($Url_vid['query'], $get_array);
                                                                              if(is_array($get_array) and sizeof($get_array)>0){

                                                                                 foreach($get_array as $k=>$v){

                                                                                     if($flag==false){

                                                                                         $flag=true;
                                                                                         $relend.="?$k=$v";
                                                                                     }
                                                                                     else{

                                                                                         $relend.="&$k=$v";

                                                                                     }


                                                                                 } 


                                                                              }



                                                                          }

                                                                      $vtype= $row ['vtype'];
                                                                      $imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                                      $imageUploadTo = str_replace ( "\\", "/", $imageUploadTo );
                                                                      $pathinfo = pathinfo ( $imageUploadTo );
                                                                      $filenamewithoutextension = $pathinfo ['filename'];

                                                                      $outputimgmain = $baseurl . $row ['image_name'];
                                                                      $outputimg=$outputimgmain;
                                                                      $media_type=$row['media_type'];  
                                                                      $hoverClass='';
                                                                      if($media_type=="link")
                                                                           $hoverClass="playbtnCss_link";
                                                                      else if($media_type=="video")
                                                                           $hoverClass="playbtnCss_video";
                                                                       else if($media_type=="image")
                                                                            $hoverClass="playbtnCss_zoom";

                                                                          $title = "";
                                                                          $rowTitle = $row['title'];
                                                                          $rowTitle = str_replace("'", "’", $rowTitle);
                                                                          $rowTitle = str_replace('"', '”', $rowTitle);



                                                                          $open_link_in = $row['open_link_in'];
                                                                          $open_title_link_in = 1;

                                                                          if(!$open_title_link_in)
                                                                              $openImageInNewTab = '_self';
                                                                          else
                                                                              $openImageInNewTab = '_blank';

                                                                          $embed_url=$row['embed_url'].$relend;
                                                                         if($media_type=="video"){

                                                                              if (trim($row['title']) != '' and trim($row['videourl']) != '') {

                                                                                  $title = "<a class='Imglink' target='$openImageInNewTab' href='{$row['videourl']}'>{$rowTitle}</a>";

                                                                              } else if (trim($row['title']) != '' and trim($row['videourl']) == '') {

                                                                                  $title = "<a class='Imglink' >{$rowTitle}</a>";

                                                                              } else {

                                                                                  if ($row['mdescription'] != '')
                                                                                      $title = "<div class='clear_description_'>{$row['mdescription']}</div>";

                                                                              }
                                                                         }
                                                                         else if($media_type=="image"){

                                                                             if (trim($row['title']) != '' and trim($row['murl']) != '') {

                                                                                  $title = "<a class='Imglink' target='$openImageInNewTab' href='{$row['murl']}'>{$rowTitle}</a>";

                                                                              } else if (trim($row['title']) != '' and trim($row['murl']) == '') {

                                                                                  $title = "<a class='Imglink' >{$rowTitle}</a>";

                                                                              } else {

                                                                                  if ($row['mdescription'] != '')
                                                                                      $title = "<div class='clear_description_'>{$row['mdescription']}</div>";

                                                                              }

                                                                         }


                                                              ?>

                                                                  <?php if($media_type=='image' or $media_type=='video') :?>

                                                                          <?php if ($open_link_in == 1): ?>
                                                                             <a data-rel="<?php echo $randOmeRel; ?>"  data-overlay="1" data-type="<?php echo $media_type;?>"  data-title="<?php echo $title; ?>" class="thumbnail_ <?php echo $randOmVlBox; ?> <?php if($media_type=='video'):?>iframe <?php endif;?> "  href="<?php if($media_type=='video'):?><?php echo $embed_url; ?> <?php else:?><?php echo $outputimgmain; ?><?php endif;?>"  >
                                                                                  <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure> 
                                                                              </a>
                                                                           <?php else: ?>

                                                                               <a   data-type="<?php echo $media_type;?>" data-overlay="1" data-title="<?php echo $title; ?>" class="thumbnail_ "  href="<?php if($media_type=='video'):?><?php echo $embed_url; ?> <?php else:?><?php echo $outputimgmain; ?><?php endif;?>" >
                                                                                  <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure>
                                                                              </a>
                                                                           <?php endif;?>

                                                                   <?php else:?>
                                                                       <a   data-type="<?php echo $media_type;?>" target='<?php echo $openImageInNewTab;?>' class="thumbnail_ "  href="<?php echo $row['murl']; ?>" >
                                                                         <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure>
                                                                      </a> 

                                                                   <?php endif;?>



                                                                  <?php } ?>   

                                                       <?php } ?>   
                                                          <br style="clear: both;">
                                                      </div>
                                                    <?php
                                                    $total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}rjg_gallery ");
                                                    $num_of_pages = ceil($total / $limit);
                                                    $page_links = paginate_links(array(
                                                        'base' => add_query_arg('pagenum', '%#%'),
                                                        'format' => '',
                                                        'prev_text' => __('&laquo;', 'aag'),
                                                        'next_text' => __('&raquo;', 'aag'),
                                                        'total' => $num_of_pages,
                                                        'current' => $pagenum,
                                                        'prev_next' => true,
                                                        'type' => 'list',
                                                            ));

                                                    if ($page_links) {
                                                          echo '<div class="navigation_grid_rjg" style="margin-bottom:10px;display:table">' . $page_links . '</div>';

                                                    }
                                                    ?>    

                                            <div style="clear:both"></div>
                                </div>

                                <script>
                                    
                                    <?php $uniqId = uniqid(); ?>
                                     var uniqObj<?php echo $uniqId ?> = jQuery("a[data-rel='<?php echo $randOmeRel; ?>']");

                                    jQuery(document).ready(function() {



                                            jQuery("#<?php echo $rand_var_name;?>").latae({
                                                loader : '<?php echo plugins_url( 'images/loader.gif', __FILE__ ) ;?>',
                                                max_height:<?php echo $settings ['imageheight'];?>,
                                                margin:<?php echo $settings ['imageMargin'];?>,
                                                target:'<?php echo $target;?>',
                                                init : function() { },
                                                loadPicture : function(event, img) {  },
                                                resize : function(event, gallery) {  },
                                                displayTitle: <?php echo ($settings['show_hover_caption']==1) ?  'true':'false' ?>,
                                                displayIcons: <?php echo ($settings['show_hover_icon']==1) ?  'true':'false' ?>
                                            });

                                        jQuery(".<?php echo $randOmVlBox; ?>").fancybox_rjg({

                                        'overlayColor':'#000000',
                                        'padding': 3,
                                        'margin': 20,
                                        'autoScale': true,
                                        'autoDimensions':true,
                                        'uniqObj':uniqObj<?php echo $uniqId; ?>,
                                        'uniqRel':'<?php echo $randOmeRel; ?>',
                                        'transitionIn':'fade',
                                        'transitionOut':'fade',
                                        'titlePosition': 'outside',
                                         'cyclic':true,
                                        'hideOnContentClick':false,
                                        'width' : 650,
                                        'height' : 400,
                                         'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                                                var currtElem = jQuery('#<?php echo $rand_var_name; ?> a[href="' + currentOpts.href + '"]');
                                                        var isoverlay = jQuery(currtElem).attr('data-overlay')

                                                 if (isoverlay == "1" && jQuery.trim(title) != ""){
                                                        return '<span id="fancybox_rjg-title-over">' + title + '</span>';
                                                }
                                                else{
                                                     return '';
                                                }

                                                }
                                        });

                                        jQuery(".page-numbers").show();


                                });  
                                jQuery("body").delegate("#<?php echo $rand_Num_td; ?> .navigation_grid_rjg ul.page-numbers li a.page-numbers", "click", function(e) {

                                        jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("width", jQuery("#<?php echo $rand_var_name; ?>").width());
                                        jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("height", jQuery("#<?php echo $rand_var_name; ?>").height());

                                        e.preventDefault();
                                        var data = {
                                                'action': 'rjg_get_grid_data_justified_gallery',
                                                'page_url': encodeURI(jQuery(this).attr('href')),
                                                'grid_id':0,
                                                'total_rec':'<?php echo $total; ?>',
                                                'vNonce':'<?php echo $vNonce;?>'
                                        };
                                        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {


                                        jQuery('html, body').animate({
                                            scrollTop: jQuery("#<?php echo $rand_Num_td; ?>").offset().top
                                        }, 800);
                                        jQuery("#<?php echo $rand_Num_td; ?>").replaceWith(response);
                                        jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("width", "0px");
                                        jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("height", "0px");


                                      });



                                });



                              </script>


    <?php } ?>

        <div class="clear"></div>
	           
 <?php
    $output = ob_get_clean();
    echo $output; 
    exit;
    
}
function rjg_get_youtube_info_justified_gallery_callback(){
  
          if(isset($_POST) and is_array($_POST) and  isset($_POST['url'])){
        

                $retrieved_nonce = '';

               if (isset($_POST['vNonce']) and $_POST['vNonce'] != '') {

                   $retrieved_nonce = sanitize_text_field($_POST['vNonce']);
               }
               if (!wp_verify_nonce($retrieved_nonce, 'vNonce')) {


                   wp_die('Security check fail');
               }

        
                $vid=sanitize_text_field($_POST['vid']);
                $url=$_POST['url']; 
            	
                $output=  wp_remote_retrieve_body( wp_remote_get( $url ) ); 

                $output=json_decode($output);
                

 
                $return=array();
                if(is_object($output)){
                   
                 $return['title']=sanitize_text_field($output->title);
                 $return['thumbnail_url']=sanitize_text_field($output->thumbnail_url);
                 
                 
               }
                
          echo json_encode($return);
          exit;
        
    }
    
}
function rjg_check_file_exist_justified_gallery_callback() {
	
	if(isset($_POST) and is_array($_POST) and  isset($_POST['url'])){

                
               $retrieved_nonce = '';

                if (isset($_POST['vNonce']) and $_POST['vNonce'] != '') {

                    $retrieved_nonce = sanitize_text_field($_POST['vNonce']);
                }
                    if (!wp_verify_nonce($retrieved_nonce, 'vNonce')) {


                    wp_die('Security check fail');
                }
                
                $response = wp_remote_get(sanitize_text_field($_POST['url']));
                $httpCode = wp_remote_retrieve_response_code( $response );
		
		echo trim((string)$httpCode);die;
		
	}
	
}
function rjg_responsive_justified_gallery_with_lightbox_admin_options_func() {
    
       if ( ! current_user_can( 'rsp_masonry_gallery_settings' ) ) {

           wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

        } 
        
        $url='admin.php?page=responsive_justified_gallery_with_lightbox';
     
		
            $url = plugin_dir_url ( __FILE__ );

            if (isset ( $_POST ['btnsave'] )) {

                     if (!check_admin_referer('action_image_add_edit', 'add_edit_image_nonce')) {

                        wp_die('Security check fail');
                    }

                    $imageheight = intval(sanitize_text_field( $_POST['imageheight'] ));
                    $imageMargin = intval(sanitize_text_field( $_POST['imageMargin'] )); 
                    $page_size = intval(sanitize_text_field( $_POST['page_size'] )); 
                    $BackgroundColor = sanitize_text_field( $_POST['BackgroundColor']); 
                    $show_hover_caption = intval(sanitize_text_field( $_POST['show_hover_caption'] )); 
                    $show_hover_icon = intval(sanitize_text_field( $_POST['show_hover_icon'] )); 
                   
                     $rjg_settings=array(

                            'BackgroundColor'=>$BackgroundColor,
                            'imageheight'=>$imageheight,
                            'imageMargin'=>$imageMargin,
                             'page_size'=>$page_size,
                             'show_hover_caption'=>$show_hover_caption,   
                             'show_hover_icon'=>$show_hover_icon

                        );

                   
                     update_option('rjg_settings',$rjg_settings);
                    
                    $my_responsive_photo_gallery_slider_settings_messages=array();
                    $my_responsive_photo_gallery_slider_settings_messages['type']='succ';
                    $my_responsive_photo_gallery_slider_settings_messages['message']=__( 'Settings saved successfully.','wp-responsive-photo-gallery' );
                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);

                     
                    $location = 'admin.php?page=responsive_justified_gallery_with_lightbox';
                    echo "<script type='text/javascript'> location.href='$location';</script>";
                    exit ();
            }
	
            $settings=get_option('rjg_settings');
            
            
           ?>
            <div style="width: 100%;">
		<div style="float: left; width: 100%;">
                	<div class="wrap">
                           <table>
                               <tr>
                                   
                                    <td>
                                       <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                                       <div id="fb-root"></div>
                                         <script>(function(d, s, id) {
                                           var js, fjs = d.getElementsByTagName(s)[0];
                                           if (d.getElementById(id)) return;
                                           js = d.createElement(s); js.id = id;
                                           js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                                           fjs.parentNode.insertBefore(js, fjs);
                                         }(document, 'script', 'facebook-jssdk'));</script>
                                        </td>
                                         <td>
                                         <a target="_blank" title="Donate" href="http://i13websolution.com/donate-wordpress_image_thumbnail.php">
                                                     <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                                                 </a>
                                             </td>
                            </tr>
                        </table>
                        <div style="clear:both">
                            <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                        </div>     
                        <?php
                            $messages=get_option('my_responsive_photo_gallery_slider_settings_messages'); 
                            $type='';
                            $message='';
                            if(isset($messages['type']) and $messages['type']!=""){

                                $type=$messages['type'];
                                $message=$messages['message'];

                            }  


                            if($type=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                            else if($type=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}


                            update_option('my_responsive_photo_gallery_slider_settings_messages', array());     
                        ?>     
                        <h2><?php echo __('Gallery Settings','wp-responsive-photo-gallery');?></h2>
                    
                        <br>
                        <div id="poststuff">
                            <div id="post-body"  class="metabox-holder columns-2">
                                <div id="post-body-content"  >
                                                <form method="post" action="" id="scrollersettiings"
                                                        name="scrollersettiings">
                                                        
                                                        <div class="stuffbox" id="namediv" style="width: 100%;">
                                                                <h3>
                                                                        <label><?php echo __('Gallery Background color','wp-responsive-photo-gallery');?></label>
                                                                </h3>
                                                                <div class="inside">
                                                                        <table>
                                                                                <tr>
                                                                                        <td><input type="text" id="BackgroundColor" size="30"
                                                                                                name="BackgroundColor"
                                                                                                value="<?php echo $settings['BackgroundColor']; ?>"
                                                                                                style="width: 100px;">
                                                                                                <div style="clear: both"></div>
                                                                                                <div></div></td>
                                                                                </tr>
                                                                        </table>

                                                                        <div style="clear: both"></div>
                                                                </div>
                                                        </div>
                                                        <div class="stuffbox" id="namediv" style="width: 100%;">
                                                                <h3>
                                                                        <label><?php echo __('Max Image Height','wp-responsive-photo-gallery');?></label>
                                                                </h3>
                                                                <div class="inside">
                                                                        <table>
                                                                                <tr>
                                                                                        <td><input type="text" id="imageheight" size="30"
                                                                                                name="imageheight"
                                                                                                value="<?php echo $settings['imageheight']; ?>"
                                                                                                style="width: 100px;">
                                                                                                <div style="clear: both"></div>
                                                                                                <div></div></td>
                                                                                </tr>
                                                                        </table>

                                                                        <div style="clear: both"></div>
                                                                </div>
                                                        </div>

                                                        <div class="stuffbox" id="namediv" style="width: 100%;">
                                                                <h3>
                                                                        <label><?php echo __('Image Margin','wp-responsive-photo-gallery');?></label>
                                                                </h3>
                                                                <div class="inside">
                                                                        <table>
                                                                                <tr>
                                                                                        <td><input type="text" id="imageMargin" size="30"
                                                                                                name="imageMargin"
                                                                                                value="<?php echo $settings['imageMargin']; ?>"
                                                                                                style="width: 100px;">
                                                                                                <div style="clear: both; padding-top: 5px"><?php echo __('Gap between two images','wp-responsive-photo-gallery');?></div>
                                                                                                <div></div></td>
                                                                                </tr>
                                                                        </table>

                                                                        <div style="clear: both"></div>
                                                                </div>
                                                        </div>
                                                        <div class="stuffbox" id="namediv" style="width: 100%;">
                                                                <h3>
                                                                        <label><?php echo __('Page Size','wp-responsive-photo-gallery');?> </label>
                                                                </h3>
                                                                <div class="inside">
                                                                        <table>
                                                                                <tr>
                                                                                        <td>
                                                                                            <input type="text" id="page_size" size="30"
                                                                                                name="page_size"
                                                                                                value="<?php echo $settings['page_size']; ?>"
                                                                                                style="width: 100px;">

                                                                                                <div style="clear: both"></div>
                                                                                                <div></div></td>
                                                                                </tr>
                                                                        </table>
                                                                        <div style="clear: both"></div>
                                                                </div>
                                                        </div>

                                                        <div class="stuffbox" id="namediv" style="width: 100%;">
                                                                <h3>
                                                                        <label><?php echo __('Show Caption On Hover ?','wp-responsive-photo-gallery');?></label>
                                                                </h3>
                                                                <div class="inside">
                                                                        <table>
                                                                                <tr>
                                                                                        <td><input style="width: 20px;" type='radio'
                                                                                                <?php
                                                                                                if ($settings ['show_hover_caption'] == true) {
                                                                                                        echo "checked='checked'";
                                                                                                }
                                                                                                ?>
                                                                                                name='show_hover_caption' value='1'><?php echo __('yes','wp-responsive-photo-gallery');?> &nbsp;<input
                                                                                                style="width: 20px;" type='radio' name='show_hover_caption'
                                                                                                <?php
                                                                                                if ($settings ['show_hover_caption'] == false) {
                                                                                                        echo "checked='checked'";
                                                                                                }
                                                                                                ?>
                                                                                                value='0'><?php echo __('No','wp-responsive-photo-gallery');?>
                                                                                                <div style="clear: both"></div>
                                                                                                <div></div></td>
                                                                                </tr>
                                                                        </table>
                                                                        <div style="clear: both"></div>
                                                                </div>
                                                        </div>
                                                        <div class="stuffbox" id="namediv" style="width: 100%;">
                                                                <h3>
                                                                        <label><?php echo __('Show Icon On Hover ?','wp-responsive-photo-gallery');?></label>
                                                                </h3>
                                                                <div class="inside">
                                                                        <table>
                                                                                <tr>
                                                                                        <td><input style="width: 20px;" type='radio'
                                                                                                <?php
                                                                                                if ($settings ['show_hover_icon'] == true) {
                                                                                                        echo "checked='checked'";
                                                                                                }
                                                                                                ?>
                                                                                                name='show_hover_icon' value='1'><?php echo __('yes','wp-responsive-photo-gallery');?> &nbsp;<input
                                                                                                style="width: 20px;" type='radio' name='show_hover_icon'
                                                                                                <?php
                                                                                                if ($settings ['show_hover_icon'] == false) {
                                                                                                        echo "checked='checked'";
                                                                                                }
                                                                                                ?>
                                                                                                value='0'><?php echo __('No','wp-responsive-photo-gallery');?>
                                                                                                <div style="clear: both"></div>
                                                                                                <div></div></td>
                                                                                </tr>
                                                                        </table>
                                                                        <div style="clear: both"></div>
                                                                </div>
                                                        </div>

                                        
                                                                <?php wp_nonce_field('action_image_add_edit', 'add_edit_image_nonce'); ?> 
                                                                <input type="submit"
                                                                name="btnsave" id="btnsave"
                                                                value="<?php echo __('Save Changes','wp-responsive-photo-gallery');?>"
                                                                class="button-primary">&nbsp;&nbsp;<input type="button"
                                                                name="cancle" id="cancle" value="<?php echo __('Cancel','wp-responsive-photo-gallery');?>" class="button-primary"
                                                                onclick="location.href = 'admin.php?page=responsive_justified_gallery_with_lightbox'">

                                                </form>
                                                <script type="text/javascript">

                                                    
                                                    jQuery(document).ready(function() {

                                            jQuery("#scrollersettiings").validate({
                                            rules: {
                                                    name: {
                                                    required:true,
                                                            maxlength:200,
                                                            minlength:3,
                                                    },
                                                    BackgroundColor:{
                                                    required:true,
                                                            maxlength:7
                                                    },
                                                    imageheight:{
                                                            required:true,
                                                            digits:true,
                                                            maxlength:15
                                                    },

                                                    imageMargin:{
                                                    required:true,
                                                            number:true,
                                                            maxlength:15
                                                    },

                                                    page_size:{
                                                    required:true,
                                                            number:true,
                                                            maxlength:15
                                                    }


                                            },
                                                    errorClass: "image_error",
                                                    errorPlacement: function(error, element) {
                                                    error.appendTo(element.next().next());
                                                    }


                                            })

                                              jQuery('#BackgroundColor').wpColorPicker();
                                        });
                                          </script>

                                        </div>
                                        <div id="postbox-container-1" class="postbox-container" > 

                                        <div class="postbox"> 
                                            <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-responsive-photo-gallery' );?></h3> 
                                            <div class="inside">
                                                <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                                                <div style="margin:10px 5px">

                                                </div>
                                            </div></div>
                                        <div class="postbox"> 
                                        <center><h3 class="hndle"><span></span><?php echo __( 'Google For Business','wp-responsive-photo-gallery');?></h3> </center>
                                        <div class="inside">
                                            <center><a target="_blank" href="https://goo.gl/OJBuHT"><img style="max-width:350px;width:100%" src="<?php echo plugins_url( 'images/gsuite_promo.png', __FILE__ ) ;?>" border="0"></a></center>
                                            <div style="margin:10px 5px">
                                            </div>
                                        </div></div>

                                         

                                    </div> 
			          </div>
			      </div>
			</div>
		</div>
		<div class="clear"></div>
	</div>  
       
<?php        
}
function rjg_responsive_justified_gallery_with_lightbox_media_management_func() {
    
        global $wpdb;
	$action = 'gridview';
	
	
	if (isset ( $_GET ['action'] ) and $_GET ['action'] != '') {
		
		$action = trim ( sanitize_text_field($_GET ['action'] ));
                
                if(isset($_GET['order_by'])){
        
                    if(sanitize_sql_orderby($_GET['order_by'])){
                        $order_by=esc_html(sanitize_text_field($_GET['order_by'])); 
                    }
                    else{
                        
                        $order_by=' id ';
                    }
                 }

                 if(isset($_GET['order_pos'])){

                    $order_pos=esc_html(sanitize_text_field($_GET['order_pos'])); 
                 }

                 $search_term_='';
                 if(isset($_GET['search_term'])){

                    $search_term_='&search_term='.esc_html(sanitize_text_field($_GET['search_term']));
                 }
	}
        
         $search_term_='';
        if(isset($_GET['search_term'])){

           $search_term_='&search_term='.esc_html(sanitize_text_field($_GET['search_term']));
        }
	?>

        <?php
	if (strtolower ( $action ) == strtolower ( 'gridview' )) {
		
            
                if ( ! current_user_can( 'rsp_masonry_gallery_view_media' ) ) {

                   wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

                } 
        
		$wpcurrentdir = dirname ( __FILE__ );
		$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
		
		$uploads = wp_upload_dir ();
		$baseurl = $uploads ['baseurl'];
		$baseurl .= '/wp-responsive-photo-gallery/';
                
		?> 
            <div class="wrap">
		
                  <table><tr>
                          <td>
                          <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                          <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                      </td>
                        <td>
                            <a target="_blank" title="Donate" href="http://i13websolution.com/donate-wordpress_image_thumbnail.php">
                                <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                            </a>
                        </td>
                    </tr>
                </table>
                <div style="clear:both">
                    <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                </div>  
                <?php
		$messages = get_option ( 'my_responsive_photo_gallery_slider_settings_messages' );
		$type = '';
		$message = '';
		if (isset ( $messages ['type'] ) and $messages ['type'] != "") {
			
			$type = $messages ['type'];
			$message = $messages ['message'];
		}
		
                
                 if($type=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                    else if($type=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}

		$url = plugin_dir_url(__FILE__);  
		update_option ( 'my_responsive_photo_gallery_slider_settings_messages', array () );
		?>

                <div style="width: 100%;">
                    <div id="modelMainDiv" style="display:none;z-index: 1000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(0, 0, 0); opacity: 0.2; cursor: wait; position: fixed;filter:alpha(opacity=15)" ></div>
                    <div id="LoaderDiv" style="display:none;z-index: 1000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(0, 0, 0); opacity: 0.2; cursor: wait; position: fixed;filter:alpha(opacity=15)" ></div>
                    <div id="ContainDiv" style="display:none;z-index: 1056; position: fixed; padding: 5px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: rgb(0, 0, 0); border: 1px solid #999999; background-color: rgb(255, 255, 255); cursor: wait;" >
                      <img src="<?php echo $url.'images/ajax-loader.gif'?>" />
                       <h5 id="wait"><?php echo __('Please wait while uploading images...','wp-responsive-photo-gallery');?></h5>
                    </div>
                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
				<div class="icon32 icon32-posts-post" id="icon-edit">
					<br>
				</div>
				<h1>	
				<?php echo __('Media','wp-responsive-photo-gallery');?><a class="button add-new-h2" href="admin.php?page=responsive_justified_gallery_with_lightbox_media_management&action=addedit"><?php echo __('Add New','wp-responsive-photo-gallery');?></a>
                                 &nbsp;&nbsp;
                                     <a class="massAdd button add-new-h2" href="javascript:void(0)"><?php echo __('Mass Image Add','wp-responsive-photo-gallery');?></a>
                    
				</h1>
				<br />

				<form method="POST"
					action="admin.php?page=responsive_justified_gallery_with_lightbox_media_management&action=deleteselected"
					id="posts-filter" onkeypress="return event.keyCode != 13;">
					<div class="alignleft actions">
						<select name="action_upper" id="action_upper">
							<option selected="selected" value="-1"><?php echo __('Bulk Actions','wp-responsive-photo-gallery');?></option>
							<option value="delete"><?php echo __('delete','wp-responsive-photo-gallery');?></option>
						</select> <input type="submit" value="<?php echo __('Apply','wp-responsive-photo-gallery');?>"
							class="button-secondary action" id="deleteselected"
							name="deleteselected" onclick="return confirmDelete_bulk();">
					</div>
                                      <?php
                                            
                                         

                                             $setacrionpage='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';

                                             if(isset($_GET['order_by']) and $_GET['order_by']!=""){
                                              $setacrionpage.='&order_by='.esc_html(sanitize_text_field($_GET['order_by']));   
                                             }

                                             if(isset($_GET['order_pos']) and $_GET['order_pos']!=""){
                                              $setacrionpage.='&order_pos='.esc_html(sanitize_text_field($_GET['order_pos']));  
                                             }

                                             $seval="";
                                             if(isset($_GET['search_term']) and $_GET['search_term']!=""){
                                              $seval=esc_html(sanitize_text_field($_GET['search_term']));   
                                             }

                                         ?>
					<br class="clear">
                                                    <?php
							global $wpdb;
                                                      
                                                        
                                                        $order_by='id';
                                                        $order_pos="asc";

                                                        if(isset($_GET['order_by']) and sanitize_sql_orderby($_GET['order_by'])!==false){

                                                           $order_by=esc_html(sanitize_text_field($_GET['order_by'])); 
                                                        }

                                                         if(isset($_GET['order_pos'])){

                                                           $order_pos=esc_html(sanitize_text_field($_GET['order_pos'])); 
                                                        }
                                                        
                                                         $search_term='';
                                                        if(isset($_GET['search_term'])){

                                                           $search_term= esc_html(sanitize_text_field(esc_sql($_GET['search_term'])));
                                                        }

                                                        $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery ";
                                                        $queryCount = "SELECT count(*) FROM " . $wpdb->prefix . "rjg_gallery ";
                                                        if($search_term!=''){
                                                           $query.=" where id like '%$search_term%' or title like '%$search_term%' "; 
                                                           $queryCount.=" where id like '%$search_term%' or title like '%$search_term%' "; 
                                                        }

                                                        $order_by=sanitize_text_field(sanitize_sql_orderby($order_by));
                                                        $order_pos=sanitize_text_field(sanitize_sql_orderby($order_pos)); 

                                                        $query.=" order by $order_by $order_pos";
                                                        
                                                        $rowsCount=$wpdb->get_var($queryCount);
                                                        
                                                                       
							?>
                                            
                                            <div style="padding-top:5px;padding-bottom:5px">
                                                <b><?php echo __( 'Search','wp-responsive-photo-gallery');?> : </b>
                                                  <input type="text" value="<?php echo $seval;?>" id="search_term" name="search_term">&nbsp;
                                                  <input type='button'  value='<?php echo __( 'Search','wp-responsive-photo-gallery');?>' name='searchusrsubmit' class='button-primary' id='searchusrsubmit' onclick="SearchredirectTO();" >&nbsp;
                                                  <input type='button'  value='<?php echo __( 'Reset Search','wp-responsive-photo-gallery');?>' name='searchreset' class='button-primary' id='searchreset' onclick="ResetSearch();" >
                                            </div>  
                                            <script type="text/javascript" >
                                               
                                                jQuery('#search_term').on("keyup", function(e) {
                                                       if (e.which == 13) {
                                                  
                                                           SearchredirectTO();
                                                       }
                                                  });   
                                             function SearchredirectTO(){
                                               var redirectto='<?php echo $setacrionpage; ?>';
                                               var searchval=jQuery('#search_term').val();
                                               redirectto=redirectto+'&search_term='+jQuery.trim(encodeURIComponent(searchval));  
                                               window.location.href=redirectto;
                                             }
                                            function ResetSearch(){

                                                 var redirectto='<?php echo $setacrionpage; ?>';
                                                 window.location.href=redirectto;
                                                 exit;
                                            }
                                            </script>            
                                             <div id="no-more-tables">
						<table cellspacing="0" id="gridTbl" class="table-bordered table-striped table-condensed cf wp-list-table widefat">
							<thead>
								<tr>
									<th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
									 <?php if($order_by=="id" and $order_pos=="asc"):?>
                                                                               
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Id','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <?php if($order_by=="id"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Id','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                                <?php else:?>
                                                                                    <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Id','wp-responsive-photo-gallery');?></a></th>
                                                                                <?php endif;?>    
                                                                            <?php endif;?>  
                                                                         <?php if($order_by=="media_type" and $order_pos=="asc"):?>
                                                                               
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=media_type&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Media Type','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <?php if($order_by=="media_type"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=media_type&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Media Type','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                                <?php else:?>
                                                                                    <th><a href="<?php echo $setacrionpage;?>&order_by=media_type&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Media Type','wp-responsive-photo-gallery');?></a></th>
                                                                                <?php endif;?>    
                                                                            <?php endif;?>  
								            
									
                                                                        <?php if($order_by=="title" and $order_pos=="asc"):?>

                                                                             <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Title','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                        <?php else:?>
                                                                            <?php if($order_by=="title"):?>
                                                                        <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','wp-responsive-photo-gallery');?></a></th>
                                                                            <?php endif;?>    
                                                                        <?php endif;?>  
									<th><span></span></th>
									
								            
                                                                           
									  <?php if($order_by=="createdon" and $order_pos=="asc"):?>
                                                                               
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Published On','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <?php if($order_by=="createdon"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','wp-responsive-photo-gallery');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                                <?php else:?>
                                                                                    <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','wp-responsive-photo-gallery');?></a></th>
                                                                                <?php endif;?>    
                                                                            <?php endif;?>  
								                         
									
									<th><span><?php echo __('Edit','wp-responsive-photo-gallery');?></span></th>
									<th><span><?php echo __('Delete','wp-responsive-photo-gallery');?></span></th>
								</tr>
							</thead>

							<tbody id="the-list">
                                                            <?php
								if ($rowsCount > 0) {
									
									global $wp_rewrite;
									$rows_per_page = 15;
									
									$current = (isset($_GET ['paged'])) ? ((int) intval(sanitize_text_field($_GET ['paged']))) : 1;
									$pagination_args = array (
											'base' => @add_query_arg ( 'paged', '%#%' ),
											'format' => '',
											'total' => ceil ( $rowsCount / $rows_per_page ),
											'current' => $current,
											'show_all' => false,
											'type' => 'plain' 
									);
									
									 $offset = ($current - 1) * $rows_per_page;
                                                                        $query.=" limit $offset, $rows_per_page";
                                                                        $rows = $wpdb->get_results ( $query ,ARRAY_A);
									$delRecNonce = wp_create_nonce('delete_image');
									foreach($rows as $row) {
										
										$id = $row ['id'];
										$editlink = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management&action=addedit&id=$id";
										$deletelink = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management&action=delete&id=$id&nonce=$delRecNonce";
										
										$outputimgmain = $baseurl . $row ['image_name'].'?rand='.  rand(0, 5000);
										?>
                                                                        <tr valign="top">
                                                                            <td class="alignCenter check-column" data-title="Select Record"><input
                                                                                    type="checkbox" value="<?php echo $row['id'] ?>"
                                                                                    name="thumbnails[]"></td>
                                                                            <td data-title="<?php echo __('Id','wp-responsive-photo-gallery');?>" class="alignCenter"><?php echo $row['id']; ?></td>
                                                                            <td data-title="Video Type" class="alignCenter"><div>
                                                                                            <strong><?php echo $row['media_type']; ?> <?php if($row['media_type']=='video'):?> - <?php echo $row['vtype'];?><?php endif;?> </strong>
                                                                                    </div></td>
                                                                               <td data-title="<?php echo __('Title','wp-responsive-photo-gallery');?>" class="alignCenter">
                                                                               <div>
                                                                                            <strong><?php echo $row['title']; ?></strong>
                                                                                    </div></td>
                                                                            <td class="alignCenter"><img src="<?php echo $outputimgmain; ?>" style="width: 100px" height="100px" /></td>
                                                                            <td data-title="<?php echo __('Published On','wp-responsive-photo-gallery');?>" class="alignCenter"><?php echo $row['createdon'] ?></td>
                                                                            <td data-title="<?php echo __('Edit','wp-responsive-photo-gallery');?>" class="alignCenter"><strong><a href='<?php echo $editlink; ?>' title="<?php echo __('Edit','wp-responsive-photo-gallery');?>"><?php echo __('Edit','wp-responsive-photo-gallery');?></a></strong></td>
                                                                            <td data-title="<?php echo __('Delete','wp-responsive-photo-gallery');?>" class="alignCenter"><strong><a href='<?php echo $deletelink; ?>' onclick="return confirmDelete();" title="<?php echo __('Delete','wp-responsive-photo-gallery');?>"><?php echo __('Delete','wp-responsive-photo-gallery');?></a> </strong></td>
                                                                    </tr>
                                                                    <?php
                                                                            }
                                                                    } else {
                                                                            ?>
                                                                    <tr valign="top" class=""
                                                                            id="">
                                                                            <td colspan="9" data-title="<?php echo __('No Record','wp-responsive-photo-gallery');?>" align="center"><strong><?php echo __('No Media Found','wp-responsive-photo-gallery');?></strong></td>
                                                                    </tr>
                                                                 <?php
								}
								?>      
                                                        </tbody>
						</table>
					</div>
                                         <?php
                                            if ($rowsCount> 0) {
                                                    echo "<div class='pagination' style='padding-top:10px'>";
                                                    echo paginate_links ( $pagination_args );
                                                    echo "</div>";
                                            }
                                            ?>
                                         <br />
					<div class="alignleft actions">
						<select name="action" id="action_bottom">
							<option selected="selected" value="-1"><?php echo __('Bulk Actions','wp-responsive-photo-gallery');?></option>
							<option value="delete"><?php echo __('Delete','wp-responsive-photo-gallery');?></option>
						</select> 
                                               <?php wp_nonce_field('action_settings_mass_delete', 'mass_delete_nonce'); ?>
                                                <input type="submit" value="<?php echo __('Apply','wp-responsive-photo-gallery');?>"
							class="button-secondary action" id="deleteselected"
							name="deleteselected" onclick="return confirmDelete_bulk();">
					</div>

				</form>
				<script type="text/JavaScript">

                            function  confirmDelete_bulk(){
                                            var topval=document.getElementById("action_bottom").value;
                                            var bottomVal=document.getElementById("action_upper").value;

                                            if(topval=='delete' || bottomVal=='delete'){


                                                var agree=confirm("<?php echo __('Are you sure you want to delete selected media ?','wp-responsive-photo-gallery');?>");
                                                if (agree)
                                                    return true ;
                                                else
                                                    return false;
                                            }
                                     }
                                     
                            function  confirmDelete(){
                             var agree=confirm("<?php echo __('Are you sure you want to delete this media ?','wp-responsive-photo-gallery');?>");
                             if (agree)
                                 return true ;
                            else
                                return false;
                            }
                            
                            var nonce_sec='<?php echo wp_create_nonce( "thumbnail-mass-image" );?>';
                            jQuery(document).ready(function() {
                                   //uploading files variable
                                   var custom_file_frame;
                                   jQuery(".massAdd").click(function(event) {
                                      var slider_id=jQuery(this).attr('id'); 
                                      event.preventDefault();
                                      //If the frame already exists, reopen it
                                      if (typeof(custom_file_frame)!=="undefined") {
                                         custom_file_frame.close();
                                      }

                                      //Create WP media frame.
                                      custom_file_frame = wp.media.frames.customHeader = wp.media({
                                         //Title of media manager frame
                                         title: "<?php echo __("WP Media Uploader",'wp-responsive-photo-gallery');?>",
                                         library: {
                                            type: 'image'
                                         },
                                         button: {
                                            //Button text
                                            text: "<?php echo __("Set Image",'wp-responsive-photo-gallery');?>"
                                         },
                                         //Do not allow multiple files, if you want multiple, set true
                                         multiple: true
                                      });

                                      //callback for selected image

                                      custom_file_frame.on('select', function() {


                                            jQuery("#modelMainDiv").show();
                                            jQuery("#LoaderDiv").show();
                                            jQuery("#ContainDiv").show();
                                            var selection = custom_file_frame.state().get('selection');
                                            selection.map(function(attachment) {

                                                attachment = attachment.toJSON();
                                                var validExtensions=new Array();
                                                validExtensions[0]='jpg';
                                                validExtensions[1]='jpeg';
                                                validExtensions[2]='png';
                                                validExtensions[3]='gif';


                                                var inarr=parseInt(jQuery.inArray( attachment.subtype, validExtensions));

                                                if(inarr>0 && attachment.type.toLowerCase()=='image' ){

                                                      var titleTouse="";
                                                      var imageDescriptionTouse="";

                                                      if(jQuery.trim(attachment.title)!=''){

                                                         titleTouse=jQuery.trim(attachment.title); 
                                                      }  
                                                      else if(jQuery.trim(attachment.caption)!=''){

                                                         titleTouse=jQuery.trim(attachment.caption);  
                                                      }

                                                      if(jQuery.trim(attachment.description)!=''){

                                                         imageDescriptionTouse=jQuery.trim(attachment.description); 
                                                      }  
                                                      else if(jQuery.trim(attachment.caption)!=''){

                                                         imageDescriptionTouse=jQuery.trim(attachment.caption);  
                                                      }

                                                      var data = {
                                                                imagetitle:titleTouse,
                                                                image_description: imageDescriptionTouse,
                                                                attachment_id:attachment.id,
                                                                slider_id:slider_id,
                                                                action: 'mass_upload_wpresponsivephgalleryms',
                                                                thumbnail_security:nonce_sec
                                                            };

                                                        url='admin.php?page=responsive_justified_gallery_with_lightbox_media_management&action=mass_upload_wpresponsivephgalleryms'
                                                        jQuery.ajax({
                                                              type: 'POST',
                                                              url: ajaxurl,
                                                              data: data,
                                                              success: function(result) {
                                                                  if(result.isOk == false)
                                                                      alert(result.message);
                                                              },
                                                              dataType:'html',
                                                              async:false
                                                            });


                                                }  

                                            });

                                            jQuery("#modelMainDiv").hide();
                                            jQuery("#LoaderDiv").hide();
                                            jQuery("#ContainDiv").hide();

                                        });

                                         custom_file_frame.on('close', function() {
                                             window.location.reload();
                                          });

                                      //Open modal
                                      custom_file_frame.open();
                                   });
                                })
                        </script>

                        <br class="clear">
			<div style="clear: both;"></div>
                            <?php $url = plugin_dir_url(__FILE__); ?>



                        <h3><?php echo __('To print this masonry gallery into WordPress Post/Page use below code','wp-responsive-photo-gallery');?></h3>
                        <input type="text"
                                value='[print_masonry_gallery_plus_lightbox] '
                                style="width: 400px; height: 30px"
                                onclick="this.focus(); this.select()" />
                        <div class="clear"></div>
                        <h3><?php echo __('To print this masonry gallery into WordPress theme/template PHP files use below code','wp-responsive-photo-gallery');?></h3>
                        <?php
                        $shortcode = '[print_masonry_gallery_plus_lightbox]';
                        ?>
                        <input type="text"
                                value="&lt;?php echo do_shortcode('<?php echo htmlentities($shortcode, ENT_QUOTES); ?>'); ?&gt;"
                                style="width: 400px; height: 30px"
                                onclick="this.focus(); this.select()" />
                        <div class="clear"></div>
                    </div>  
                   	
                    <div id="postbox-container-1" class="postbox-container"  > 

                        <div class="postbox"> 
                            <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-responsive-photo-gallery');?></h3> 
                            <div class="inside">
                                <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                                <div style="margin:10px 5px">

                                </div>
                            </div></div>
                        <div class="postbox"> 
                            <center><h3 class="hndle"><span></span><?php echo __( 'Google For Business','wp-responsive-photo-gallery');?></h3> </center>
                            <div class="inside">
                                <center><a target="_blank" href="https://goo.gl/OJBuHT"><img style="width:100%" src="<?php echo plugins_url( 'images/gsuite_promo.png', __FILE__ ) ;?>" border="0"></a></center>
                                <div style="margin:10px 5px">
                                </div>
                            </div></div>
                        

                    </div>    
               </div> 
                        
           </div>       
        </div>            
                <?php
	} else if (strtolower ( $action ) == strtolower ( 'addedit' )) {
		$url = plugin_dir_url ( __FILE__ );
                $vNonce = wp_create_nonce('vNonce');
		?><?php
		if (isset ( $_POST ['btnsave'] )) {
			
                       if (!check_admin_referer('action_image_add_edit', 'add_edit_image_nonce')) {

                            wp_die('Security check fail');
                        }
			$uploads = wp_upload_dir ();
			$baseDir = $uploads ['basedir'];
			$baseDir = str_replace ( "\\", "/", $baseDir );
			$pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
			
                        if(isset($_POST['media_type']) and $_POST['media_type']=='video'){
                        
                        $media_type=trim(sanitize_text_field($_POST['media_type']));    
			$vtype = trim ( sanitize_text_field( $_POST ['vtype'] ) );
			$videourl = trim (esc_url_raw($_POST ['videourl'] ));
			// echo $videourl;die;
                        
                    
                        
			$vid = uniqid ( 'vid_' );
			$embed_url='';
			if ($vtype == 'youtube') {
				// parse
				
				$parseUrl = @parse_url ( $videourl );
				if (is_array ( $parseUrl )) {
					
					$queryStr = $parseUrl ['query'];
					parse_str ( $queryStr, $array );
					if (is_array ( $array ) and isset ( $array ['v'] )) {
						
						$vid = $array ['v'];
					}
				}
				
			    $embed_url="//www.youtube.com/embed/$vid";	
			}
			
			else if($vtype=='dailymotion'){
				
                                $url_arr = parse_url($videourl);
                                if(isset($url_arr['query'])){
                                    
                                    $query = $url_arr['query'];
                                    $videourl = str_replace(array($query,'?'), '', $videourl);
                                }
				$pos = strpos($videourl, '/video/');
                                $vid=0;
                                if ($pos !== false){
                                    
                                    $vid=substr($videourl, $pos+strlen('/video/'));
                                    
                                }
                              
				
				$embed_url="//www.dailymotion.com/embed/video/$vid";
				
			}
                        
			
			
			$HdnMediaSelection = trim ( esc_url_raw($_POST ['HdnMediaSelection'] ));
			$videotitle = trim ( sanitize_text_field($_POST ['title'] )) ;
			
			$videotitle = str_replace("'","’",$videotitle);
			$videotitle = str_replace('"', '&quot;', $videotitle);
			
			
                        if($vtype=='html5'){
                            
                            $open_link_in = 1;
                            
                        }else{
                            
                            if (isset ( $_POST ['open_link_in'] ))
				$open_link_in = 1;
                            else
				$open_link_in = 0;
                        
                        }
			
                        
			

			$location = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management";
				// edit save
                        
			if (isset ( $_POST ['videoid'] )) {
                            
                                
                              if ( ! current_user_can( 'rsp_masonry_gallery_edit_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
				
				try {
						
						$videoidEdit=intval($_POST ['videoid']);   
						if (trim ( $_POST ['HdnMediaSelection'] ) != '') {
                                                    
                                                        $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery WHERE id=$videoidEdit";
                                                        $myrow = $wpdb->get_row($query);
                                                        
                                                      
                                                          if (is_object($myrow)) {

                                                            $image_name = $myrow->image_name;
                                                            $imagetoDel = $pathToImagesFolder . '/' . $image_name;
                                                            $pInfo = pathinfo($myrow->HdnMediaSelection);
                                                            $pInfo2 = pathinfo($imagetoDel);
                                                            $ext = $pInfo2 ['extension'];

                                                            @unlink($imagetoDel);
                                                           
                                                        }
							$pInfo = pathinfo ( $HdnMediaSelection );
							$ext = $pInfo ['extension'];
							$imagename = uniqid("vid_") .".". $ext;
							$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                        
                                                   	@copy ( $HdnMediaSelection, $imageUploadTo );
                                                        if(!file_exists($imageUploadTo)){
                                                         rs_photogallery_save_image_remote_lbox($HdnMediaSelection,$imageUploadTo);
                                                        }
                                                        
                                                     
						}
							
						$query = "update " . $wpdb->prefix . "rjg_gallery
						set media_type='$media_type', vtype='$vtype',vid='$vid',murl='$videourl',embed_url='$embed_url',image_name='$imagename',HdnMediaSelection='$HdnMediaSelection',
						title='$videotitle',open_link_in=$open_link_in where id=$videoidEdit";
							
						//echo $query;die;
						$wpdb->query ( $query );
							
						$my_responsive_photo_gallery_slider_settings_messages = array ();
						$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
						$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Video updated successfully.','wp-responsive-photo-gallery');
						update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                                
					} catch ( Exception $e ) {
							
						$my_responsive_photo_gallery_slider_settings_messages = array ();
                                                $my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
                                                $my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while adding video','wp-responsive-photo-gallery');
						update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                        }

				
				
                                
                                
			} else {
				
                                if ( ! current_user_can( 'rsp_masonry_gallery_add_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
                                
				$createdOn = date ( 'Y-m-d h:i:s' );
                                if (function_exists ( 'date_i18n' )) {

                                        $createdOn = date_i18n ( 'Y-m-d' . ' ' . get_option ( 'time_format' ), false, false );
                                        if (get_option ( 'time_format' ) == 'H:i')
                                                $createdOn = date ( 'Y-m-d H:i:s', strtotime ( $createdOn ) );
                                        else
                                                $createdOn = date ( 'Y-m-d h:i:s', strtotime ( $createdOn ) );
                                }
				
				try {
					
					if (trim ( $_POST ['HdnMediaSelection'] ) != '') {
						$pInfo = pathinfo ( $HdnMediaSelection );
						$ext = isset($pInfo ['extension'])?$pInfo ['extension']:'jpeg';
						$imagename = uniqid("vid_") . ".".$ext;
						$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
						@copy ( $HdnMediaSelection, $imageUploadTo );
                                                 if(!file_exists($imageUploadTo)){
                                                   rs_photogallery_save_image_remote_lbox($HdnMediaSelection,$imageUploadTo);
                                                 }
					}
					
					$query = "INSERT INTO " . $wpdb->prefix . "rjg_gallery 
                                		(media_type,image_name,title,murl,open_link_in,vtype,vid,videourl,embed_url,HdnMediaSelection,createdon) 
                                                VALUES ('$media_type','$imagename','$videotitle','$videourl',
                                                        $open_link_in,'$vtype','$vid','$videourl','$embed_url','$HdnMediaSelection', '$createdOn')";

					
					$wpdb->query ( $query );
					
                                        
                                      

					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('New video added successfully.','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
				} catch ( Exception $e ) {
					
					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while adding video','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
				}
				
				
			}
                        
                   }
                   else if(isset($_POST['media_type']) and $_POST['media_type']=='image'){
                            
                       global $wpdb;
                        $vid = uniqid ( 'vid_' );
                        $media_type=trim(sanitize_text_field($_POST['media_type']));   
                        $HdnMediaSelection = trim ( esc_url_raw($_POST ['HdnMediaSelection_image'] ));
			$mtitle = trim ( sanitize_text_field($_POST ['title'] )) ;
		
			$mtitle = str_replace("'","’",$mtitle);
			$mtitle = str_replace('"', '&quot;', $mtitle);
                        $murl = trim ( sanitize_text_field($_POST ['murl'] ));
			
			
			
			
			if (isset ( $_POST ['open_link_in'] ))
				$open_link_in = 1;
			else
				$open_link_in = 0;
			
		
			$location = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management";
				// edit save
			if (isset ( $_POST ['imageid'] )) {
                            
                                if ( ! current_user_can( 'rsp_masonry_gallery_edit_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
				
				try {
						
						$videoidEdit=intval(sanitize_text_field($_POST ['imageid']));
						if (trim ( $_POST ['HdnMediaSelection_image'] ) != '') {
                                                        
                                                        $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery WHERE id=$videoidEdit";
                                                        $myrow = $wpdb->get_row($query);
                                                        
                                                        
                                                          if (is_object($myrow)) {

                                                            $image_name = $myrow->image_name;
                                                            $imagetoDel = $pathToImagesFolder . '/' . $image_name;
                                                            $pInfo = pathinfo($myrow->HdnMediaSelection);
                                                            $pInfo2 = pathinfo($imagetoDel);
                                                            $ext = $pInfo2 ['extension'];

                                                            @unlink($imagetoDel);
                                                           
                                                        }
                                                        
                                                    
							$pInfo = pathinfo ( $HdnMediaSelection );
							$ext = $pInfo ['extension'];
							$imagename = uniqid("vid_").".". $ext;
							$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                        
                                                        @copy ( $HdnMediaSelection, $imageUploadTo );
                                                        if(!file_exists($imageUploadTo)){
                                                         rs_photogallery_save_image_remote_lbox($HdnMediaSelection,$imageUploadTo);
                                                        }
                                                        
                                                        
						}
							
						$query = "update " . $wpdb->prefix . "rjg_gallery
						set media_type='$media_type', murl='$murl',image_name='$imagename',HdnMediaSelection='$HdnMediaSelection',
						title='$mtitle',open_link_in=$open_link_in where id=$videoidEdit";
							
						//echo $query;die;
						$wpdb->query ( $query );
							
						$my_responsive_photo_gallery_slider_settings_messages = array ();
						$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
						$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Image updated successfully.','wp-responsive-photo-gallery');
						update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                                
					} catch ( Exception $e ) {
							
                                            $my_responsive_photo_gallery_slider_settings_messages = array ();
                                            $my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
                                            $my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while adding image','wp-responsive-photo-gallery');
                                            update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                        }

				
				
			} else {
                            
                 
                               if ( ! current_user_can( 'rsp_masonry_gallery_add_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
				
				$createdOn = date ( 'Y-m-d h:i:s' );
                                if (function_exists ( 'date_i18n' )) {

                                        $createdOn = date_i18n ( 'Y-m-d' . ' ' . get_option ( 'time_format' ), false, false );
                                        if (get_option ( 'time_format' ) == 'H:i')
                                                $createdOn = date ( 'Y-m-d H:i:s', strtotime ( $createdOn ) );
                                        else
                                                $createdOn = date ( 'Y-m-d h:i:s', strtotime ( $createdOn ) );
                                }
				
				try {
					
					if (trim ( $_POST ['HdnMediaSelection_image'] ) != '') {
                                            
						$pInfo = pathinfo ( $HdnMediaSelection );
						$ext = $pInfo ['extension'];
						$imagename = uniqid("vid_").".". $ext;
						$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
						
                                                @copy ( $HdnMediaSelection, $imageUploadTo );
                                                if(!file_exists($imageUploadTo)){
                                                    
                                                    rs_photogallery_save_image_remote_lbox($HdnMediaSelection,$imageUploadTo);
                                                    
                                                }
					}
					
					$query = "INSERT INTO " . $wpdb->prefix . "rjg_gallery 
                                		(media_type,image_name,title,murl,open_link_in,
                                                HdnMediaSelection,createdon) 
                                                VALUES ('$media_type','$imagename','$mtitle','$murl',
                                                        $open_link_in,'$HdnMediaSelection', '$createdOn')";

					
					$wpdb->query ( $query );
					
					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('New image added successfully.','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                        
				} 
                                catch ( Exception $e ) {
					
					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while adding image','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
				}
				
				
			}
                       
                          
                       
                   }
                   else if(isset($_POST['media_type']) and $_POST['media_type']=='link'){
                            
                        $vid = uniqid ( 'vid_' );
                        $media_type=trim(sanitize_text_field($_POST['media_type']));   
                        $HdnMediaSelection = trim (sanitize_text_field( $_POST ['HdnMediaSelection_link'] ));
			$mtitle = trim (sanitize_text_field( $_POST ['title'] ));
			$murl = trim ( esc_url_raw($_POST ['murl'] ));
                        $mtitle = str_replace("'","’",$mtitle);
			$mtitle = str_replace('"', '&quot;', $mtitle);
                        $open_link_in=1;
			
			
			if (isset ( $_POST ['open_title_link_in'] ))
				$open_title_link_in = 1;
			else
				$open_title_link_in = 0;
			
			

			$location = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management";
				// edit save
			if (isset ( $_POST ['linkid'] )) {
                            
                            
                              if ( ! current_user_can( 'rsp_masonry_gallery_edit_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
                                
				
				try {
						
						$videoidEdit=intval($_POST ['linkid']);
						if (trim ( $_POST ['HdnMediaSelection_link'] ) != '') {
                                                    
                                                    
                                                        $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery WHERE id=$videoidEdit";
                                                        $myrow = $wpdb->get_row($query);
                                                        
                                                    
                                                        if (is_object($myrow)) {

                                                          $image_name = $myrow->image_name;
                                                          $imagetoDel = $pathToImagesFolder . '/' . $image_name;
                                                          $pInfo = pathinfo($myrow->HdnMediaSelection);
                                                          $pInfo2 = pathinfo($imagetoDel);
                                                          $ext = $pInfo2 ['extension'];
                                                          @unlink($imagetoDel);
                                                          
                                                       }
                                                        
                                                    
							$pInfo = pathinfo ( $HdnMediaSelection );
							$ext = $pInfo ['extension'];
							$imagename = uniqid("vid_") . ".". $ext;
							$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                        
                                                        
                                                        @copy ( $HdnMediaSelection, $imageUploadTo );
                                                        if(!file_exists($imageUploadTo)){
                                                         rs_photogallery_save_image_remote_lbox($HdnMediaSelection,$imageUploadTo);
                                                        }
                                                        
                                                        
						}
							
						$query = "update " . $wpdb->prefix . "rjg_gallery
						set media_type='$media_type', murl='$murl',image_name='$imagename',HdnMediaSelection='$HdnMediaSelection',
						title='$mtitle',open_link_in=$open_link_in where id=$videoidEdit";
							
						//echo $query;die;
						$wpdb->query ( $query );
							
						$my_responsive_photo_gallery_slider_settings_messages = array ();
						$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
						$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Link updated successfully.','wp-responsive-photo-gallery');
						update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                                
					} 
                                        catch ( Exception $e ) {
							
						$my_responsive_photo_gallery_slider_settings_messages = array ();
                                                $my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
                                                $my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while adding link','wp-responsive-photo-gallery');
						update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
					}

				
				
				
			} else {
				
                            
                                if ( ! current_user_can( 'rsp_masonry_gallery_add_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
                                
				$createdOn = date ( 'Y-m-d h:i:s' );
                                if (function_exists ( 'date_i18n' )) {

                                        $createdOn = date_i18n ( 'Y-m-d' . ' ' . get_option ( 'time_format' ), false, false );
                                        if (get_option ( 'time_format' ) == 'H:i')
                                                $createdOn = date ( 'Y-m-d H:i:s', strtotime ( $createdOn ) );
                                        else
                                                $createdOn = date ( 'Y-m-d h:i:s', strtotime ( $createdOn ) );
                                }
				
				try {
					
					if (trim ( $_POST ['HdnMediaSelection_link'] ) != '') {
                                            
						$pInfo = pathinfo ( $HdnMediaSelection );
						$ext = $pInfo ['extension'];
						$imagename = uniqid("vid_")."." . $ext;
						$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
						@copy ( $HdnMediaSelection, $imageUploadTo );
                                                if(!file_exists($imageUploadTo)){
                                                  rs_photogallery_save_image_remote_lbox($HdnMediaSelection,$imageUploadTo);
                                                }
                                                
					}
					
					$query = "INSERT INTO " . $wpdb->prefix . "rjg_gallery 
                                		(media_type,image_name,title,murl,open_link_in,HdnMediaSelection,createdon) 
                                                VALUES ('$media_type','$imagename','$mtitle','$murl',
                                                        $open_link_in,'$HdnMediaSelection', '$createdOn')";

				
					$wpdb->query ( $query );
					
					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('New link added successfully.','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
                                        
				} 
                                catch ( Exception $e ) {
					
					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while adding link','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
				}
				
				
			}
                       
                       
                   }
                   
                    
                    echo "<script type='text/javascript'> location.href='$location';</script>";
                    exit ();
                   
                   
		} else {
			
			$uploads = wp_upload_dir ();
			$baseurl = $uploads ['baseurl'];
			$baseurl .= '/wp-responsive-photo-gallery/';
			?>
              <div style="float: left; width: 100%;">
             
               <table><tr>
                        <td>
                          <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                          <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                      </td>
                        <td>
                            <a target="_blank" title="Donate" href="http://i13websolution.com/donate-wordpress_image_thumbnail.php">
                                <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                            </a>
                        </td>
                    </tr>
                </table>
                <div style="clear:both">
                    <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                </div>  
	       <div class="wrap">
	    	<?php
		    	if (isset ( $_GET ['id'] ) and intval($_GET ['id']) > 0) {
                            
                            
                              if ( ! current_user_can( 'rsp_masonry_gallery_edit_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
				
				$id = intval($_GET ['id']);
				$query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery WHERE id=$id ";
				
				$myrow = $wpdb->get_row ( $query );
				
				if (is_object ( $myrow )) {
					
					$media_type =  esc_html($myrow->media_type );
					$vtype =  esc_html($myrow->vtype);
					$title =  esc_html($myrow->title) ;
					$murl =   esc_url($myrow->murl);
					$image_name = esc_html($myrow->image_name);
					$video_url = esc_url($myrow->murl);
					$HdnMediaSelection = esc_url($myrow->HdnMediaSelection);
					$videotitle = esc_html($myrow->title);
					$videotitleurl=esc_url($myrow->videourl);
					$open_link_in = intval($myrow->open_link_in);
				}
				?>
	         <h2><?php echo __( 'Update Image','wp-responsive-photo-gallery' );?></h2><?php
			} 
                        else {
				
                                $media_type =  "";
                                $vtype =  "";
                                $title =  "";
                                $murl =   "";
                                $image_name = "";
                                $video_url = "";
                                $HdnMediaSelection = "";
                                $videotitle = "";
                                $videotitleurl="";
                                $open_link_in = "";

				if ( ! current_user_can( 'rsp_masonry_gallery_add_media' ) ) {

                                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                                    $my_responsive_photo_gallery_slider_settings_messages=array();
                                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                                    exit;   

                                }
				
				?>
                 <h2><?php echo __( 'Add Media','wp-responsive-photo-gallery' );?></h2>
                   <?php } ?>
                   <br />
					<div id="poststuff">
						<div id="post-body" class="metabox-holder columns-2">
							<div id="post-body-content">
                                                            
                                                            <div class="stuffbox" id="mediatype" style="width: 100%">
                                                                            <h3>
                                                                                    <label for="link_name"><?php echo __('Media Type','wp-responsive-photo-gallery');?> (<span
                                                                                            style="font-size: 11px; font-weight: normal"><?php echo __('Choose Media Type','wp-responsive-photo-gallery');?></span>)
                                                                                    </label>
                                                                            </h3>
                                                                            <div class="inside">
                                                                                    <div>
                                                                                            <input type="radio" value="image" name="media_type_p" <?php if($media_type=='image'): ?> checked='checked' <?php endif;?> style="width: 15px" id="type_image" /><?php echo __('Image','wp-responsive-photo-gallery');?>&nbsp;&nbsp;
                                                                                            <input type="radio" value="video" name="media_type_p" <?php if($media_type=='video'): ?> checked='checked' <?php endif;?> style="width: 15px" id="type_video" /><?php echo __('Video','wp-responsive-photo-gallery');?>&nbsp;&nbsp; 
                                                                                            <input <?php if($media_type=='link'): ?> checked='checked' <?php endif;?> type="radio" value="link" name="media_type_p" style="width: 15px" id="type_link" /><?php echo __('Link','wp-responsive-photo-gallery');?>&nbsp;&nbsp;

                                                                                    </div>
                                                                                    <div style="clear: both"></div>
                                                                                    <div></div>
                                                                                    <div style="clear: both"></div>
                                                                                    <br />

                                                                            </div>

                                                                        <script>
                                                                           
                                                                           
                                                                           jQuery(document).ready(function() {
                                                                                 jQuery("input[name = 'media_type_p']").click(function(){
                                                                                    var radioValue = jQuery("input[name='media_type_p']:checked").val();
                                                                                    if(radioValue=='video'){

                                                                                        jQuery("#addvideo").show(500);
                                                                                        jQuery("#addimage_").hide(500);
                                                                                        jQuery("#addlink_").hide(500);
                                                                                      
                                                                                    }
                                                                                    else if(radioValue=='image'){

                                                                                       jQuery("#addvideo").hide(500);
                                                                                       jQuery("#addimage_").show(500);
                                                                                       jQuery("#addlink_").hide(500);
                                                                                    }
                                                                                    else if(radioValue=='link'){

                                                                                      jQuery("#addlink_").show(500);
                                                                                      jQuery("#addvideo").hide(500);
                                                                                      jQuery("#addimage_").hide(500);

                                                                                    }

                                                                                });
                                                                                
                                                                                 <?php if(isset($_GET['id']) and (int) $_GET['id']>0):?>
                                                                                
                                                                                    <?php if($media_type=='video') :?>
                                                                                        jQuery("#type_video").trigger('click');
                                                                                    <?php elseif($media_type=='image'):?>
                                                                                        jQuery("#type_image").trigger('click');
                                                                                    <?php elseif($media_type=='link'):?>
                                                                                        jQuery("#type_link").trigger('click');
                                                                                    <?php endif;?>    

                                                                                 <?php endif;?>  
                                                                                
                                                                             });   
                                                                             
                                                                              
                                                                         </script>       
                                                                    </div>
                                                                    <form method="post" action="" id="addvideo" name="addvideo" enctype="multipart/form-data" style="display:none">
                                                                    
                                                                        <input type="hidden" name="media_type" id="media_type" value="video" />
									<div class="stuffbox" id="videoinfo_div_1" style="width: 100%;">
										<h3>
											<label for="link_name"><?php echo __('Video Information','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('Choose Video Site','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="radio" value="youtube" name="vtype"
													<?php if($vtype=='youtube'): ?> checked='checked' <?php endif;?> style="width: 15px" id="type_youtube" /><?php echo __('Youtube','wp-responsive-photo-gallery');?>&nbsp;&nbsp;
												
												<input <?php if($vtype=='dailymotion'): ?> checked='checked' <?php endif;?> type="radio" value="dailymotion" name="vtype"
													style="width: 15px" id="type_DailyMotion" /><?php echo __('DailyMotion','wp-responsive-photo-gallery');?>&nbsp;&nbsp;
                                                                                        </div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>
											<br />
											<div id='vurl_' >
                                                                                            <b ><?php echo __('Video Url','wp-responsive-photo-gallery');?></b> <input style="width:98%" type="text" id="videourl" class="url" tabindex="1"  name="videourl" value="<?php echo $video_url; ?>">
											</div>
                                                                                        
                                                                                        
                                                                                        
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both">
                                                                                            <div id="youtube_note" style="font-size:12px;display:none">
                                                                                                <?php echo __('Please do not use youtube.be, instead of use youtube.com','video-grid');?>
                                                                                            </div>
                                                                                        </div>
										</div>
									</div>
									<div class="stuffbox" id="videoinfo_div_2" style="width: 100%;">
										<h3>
											<label for="link_name"><?php echo __('Video Thumbnail Information','wp-responsive-photo-gallery');?></label>
										</h3>
										<div class="inside" id="fileuploaddiv">
                                                                                <?php if ($image_name != "") { ?>
                                                                                        <div>
												<b><?php echo __('Current Image','wp-responsive-photo-gallery');?>: </b>
												<br/>
												<img id="img_disp" name="img_disp"
													src="<?php echo $baseurl . $image_name; ?>" />
											</div>
                                                                                <?php }else{ ?>      
                                                                                            <img
												src="<?php echo plugins_url('/images/no-img.jpeg', __FILE__); ?>"
												id="img_disp" name="img_disp" />
                                                           
                                                                                     <?php } ?>
                                                                                         <br /> <a
												href="javascript:;" class="niks_media"
												id="videoFromExternalSite"  ><b><?php echo __('Click Here to get video information and thumbnail','wp-responsive-photo-gallery');?><span id='fromval'> From <?php echo $vtype;?></span>
											</b></a>&nbsp;<img
												src="<?php echo plugins_url('/images/ajax-loader.gif', __FILE__); ?>"
												style="display: none" id="loading_img" name="loading_img" />
											<div style="clear: both"></div>
											<div></div>
											<div class="uploader">
												<br /> <b style="margin-left: 50px;" id='or__'>OR</b>
												<div style="clear: both; margin-top: 15px;"></div>
                                                                                                
                                                                                                        <a
													href="javascript:;" class="niks_media" id="myMediaUploader"><b><?php echo __('Click Here to upload custom video thumbnail','wp-responsive-photo-gallery');?></b></a>
                                              
                                                                                                    <br /> <br />
												<div>
													<input id="HdnMediaSelection" name="HdnMediaSelection"
														type="hidden" value="<?php echo $HdnMediaSelection;?>" />
												</div>
												<div style="clear: both"></div>
												<div></div>
												<div style="clear: both"></div>

												<br />
											</div>
                                                                                      
                                                                                <script>
                                                                                        
                                                                                   
                                                                                    function GetParameterValues(param,str) {
                                                                                      var return_p='';  
                                                                                      var url = str.slice(str.indexOf('?') + 1).split('&');
                                                                                      for (var i = 0; i < url.length; i++) {
                                                                                            var urlparam = url[i].split('=');
                                                                                            if (urlparam[0] == param) {
                                                                                             return_p= urlparam[1];
                                                                                            }
                                                                                        }
                                                                                        return return_p;
                                                                                    }

                                                                                   

                                                                                    function UrlExists(url, cb){
                                                                                        jQuery.ajax({
                                                                                            url:      url,
                                                                                            dataType: 'text',
                                                                                            type:     'GET',
                                                                                            complete:  function(xhr){
                                                                                                if(typeof cb === 'function')
                                                                                                   cb.apply(this, [xhr.status]);
                                                                                            }
                                                                                        });
                                                                                    }

                                                                                    function getDailyMotionId(url) {
                                                                                          if (url.indexOf("?") > 0) {
                                                                                            url = url.substring(0, url.indexOf("?"));
                                                                                        }
                                                                                        var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
                                                                                        if (m !== null) {
                                                                                            if(m[4] !== undefined) {
                                                                                                return m[4];
                                                                                            }
                                                                                            return m[2];
                                                                                        }
                                                                                        return null;
                                                                                    }


                                                                                    jQuery(document).ready(function() {


                                                                                  
                                                                                    jQuery("input:radio[name=vtype]").click(function() {


                                                                                            var value = jQuery(this).val();
                                                                                            jQuery("#fromval").html(" from " + value);
                                                                                             if(value=="youtube"){

                                                                                                    jQuery("#youtube_note").show();
                                                                                                }
                                                                                                else{

                                                                                                    jQuery("#youtube_note").hide();
                                                                                                }
                                                                                    });
                                                                                    jQuery("#videoFromExternalSite").click(function() {


                                                                                    var videoService = jQuery('input[name="vtype"]:checked').length;
                                                                                            var videourlVal = jQuery.trim(jQuery("#videourl").val());
                                                                                            var flag = true;
                                                                                            if (videourlVal == '' && videoService == 0){

                                                                                    alert('Please select video site.\nPlease enter video url.');
                                                                                            jQuery("input:radio[name=vtype]").focus();
                                                                                            flag = false;

                                                                                    }
                                                                                    else if (videoService == 0){

                                                                                    alert('Please select video site.');
                                                                                            jQuery("input:radio[name=vtype]").focus();
                                                                                            flag = false;
                                                                                    }
                                                                                    else if (videourlVal == ''){

                                                                                    alert('Please enter video url.');
                                                                                            jQuery("#videourl").focus();
                                                                                            flag = false;
                                                                                    }

                                                                                    if (flag){

                                                                                        setTimeout(function() {
                                                                                                 jQuery("#loading_img").show();   
                                                                                                }, 100);

                                                                                            var selectedRadio = jQuery('input[name=vtype]');
                                                                                            var checkedValueRadio = selectedRadio.filter(':checked').val();
                                                                                            if (checkedValueRadio == 'youtube') {
                                                                                            var vId = GetParameterValues('v', videourlVal);
                                                                                            if(vId!=''){


                                                                                             var tumbnailImg='https://img.youtube.com/vi/'+vId+'/maxresdefault.jpg';

                                                                                             var data = {
                                                                                                                'action': 'rjg_check_file_exist_justified_gallery',
                                                                                                                'url': tumbnailImg,
                                                                                                                'vNonce':'<?php echo $vNonce; ?>'
                                                                                                        };

                                                                                                        jQuery.post(ajaxurl, data, function(response) {



                                                                                                      var youtubeJsonUri='https://www.youtube.com/oembed?url=https://www.youtube.com/watch%3Fv='+vId+'&format=json';
                                                                                                       var data_youtube = {
                                                                                                                'action': 'rjg_get_youtube_info_justified_gallery',
                                                                                                                'url': youtubeJsonUri,
                                                                                                                'vid':vId,
                                                                                                                 'vNonce':'<?php echo $vNonce; ?>'
                                                                                                        };

                                                                                                      jQuery.post(ajaxurl, data_youtube, function(data) {

                                                                                                       data = jQuery.parseJSON(data);

                                                                                                       if(typeof data =='object'){    
                                                                                                               if(typeof data =='object'){ 

                                                                                                                    if(data.title!='' && data.title!=''){
                                                                                                                        jQuery("#title").val(data.title); 
                                                                                                                    }
                                                                                                                    jQuery("#murl").val(videourlVal);
                                                                                                                    if(data.description!='' && data.description!=''){
                                                                                                                        jQuery("#mdescription").val(data.description); 
                                                                                                                    }
                                                                                                                    if(response=='404' && data.thumbnail_url!=''){
                                                                                                                         tumbnailImg=data.thumbnail_url;
                                                                                                                    }
                                                                                                                    else{
                                                                                                                         tumbnailImg='https://img.youtube.com/vi/'+vId+'/0.jpg';
                                                                                                                     }

                                                                                                                    jQuery("#img_disp").attr('src', tumbnailImg);
                                                                                                                    jQuery("#HdnMediaSelection").val(tumbnailImg);
                                                                                                                    jQuery("#loading_img").hide();

                                                                                                               }

                                                                                                            }
                                                                                                           jQuery("#loading_img").hide();
                                                                                                       })  


                                                                                                        });

                                                                                            }
                                                                                            else{
                                                                                                alert('Could not found such video');
                                                                                                jQuery("#loading_img").hide();
                                                                                            }
                                                                                        }
                                                                                        else if (checkedValueRadio == 'vimeo') {

                                                                                                var n = videourlVal.lastIndexOf('/');
                                                                                                var vId = videourlVal.substring(n + 1);


                                                                                                var VimeoJsonUri='https://vimeo.com/api/v2/video/'+vId+'.json';
                                                                                            jQuery.getJSON( VimeoJsonUri, function( data ) {

                                                                                               if(typeof data =='object'){    
                                                                                                     if(typeof data[0] =='object'){ 

                                                                                                          if(data[0].title!='' && data[0].title!=''){
                                                                                                              jQuery("#title").val(data[0].title); 
                                                                                                          }

                                                                                                          jQuery("#murl").val(videourlVal);
                                                                                                          if(data[0].description!='' && data[0].description!=''){
                                                                                                              jQuery("#mdescription").val(data[0].description); 
                                                                                                          }
                                                                                                          jQuery("#img_disp").attr('src', data[0].thumbnail_large);
                                                                                                          jQuery("#HdnMediaSelection").val(data[0].thumbnail_large);
                                                                                                          jQuery("#loading_img").hide();

                                                                                                     }

                                                                                                  }

                                                                                               jQuery("#loading_img").hide();
                                                                                             })  



                                                                                        }    
                                                                                        else if(checkedValueRadio == 'metacafe'){

                                                                                                 jQuery("#loading_img").show();
                                                                                                 var data = {
                                                                                                                'action': 'rjg_get_metacafe_info_justified_gallery',
                                                                                                                'url': videourlVal,
                                                                                                                'vNonce':'<?php echo $vNonce; ?>'
                                                                                                        };

                                                                                                        jQuery.post(ajaxurl, data,function(response) {

                                                                                                                obj = jQuery.parseJSON(response);	
                                                                                                            jQuery("#HdnMediaSelection").val(obj.HdnMediaSelection);
                                                                                                            jQuery("#title").val(jQuery.trim(obj.videotitle));
                                                                                                            jQuery("#murl").val(obj.videotitleurl);
                                                                                                            jQuery("#mdescription").val(jQuery.trim(obj.video_description));
                                                                                                            jQuery("#img_disp").attr('src', obj.HdnMediaSelection);
                                                                                                            jQuery("#loading_img").hide();
                                                                                                });	  


                                                                                        } 
                                                                                        else if(checkedValueRadio == 'dailymotion'){

                                                                                                var vid=getDailyMotionId(videourlVal);	
                                                                                                var apiUrl='https://api.dailymotion.com/video/'+vid+'?fields=description,id,thumbnail_720_url,title';
                                                                                                jQuery.getJSON( apiUrl, function( data ) {
                                                                                                         if(typeof data =='object'){    


                                                                                                                 jQuery("#HdnMediaSelection").val(data.thumbnail_720_url);	
                                                                                                                 jQuery("#title").val(jQuery.trim(data.title));
                                                                                                                 jQuery("#murl").val(videourlVal);
                                                                                                                 jQuery("#mdescription").val(data.description);
                                                                                                                 jQuery("#img_disp").attr('src', data.thumbnail_720_url);
                                                                                                                 jQuery("#loading_img").hide();
                                                                                                         }	 
                                                                                                         jQuery("#loading_img").hide(); 
                                                                                                })	


                                                                                                 jQuery("#loading_img").hide();
                                                                                        }          

                                                                                        jQuery("#loading_img").hide();
                                                                                    }

                                                                                     setTimeout(function() {
                                                                                                 jQuery("#loading_img").hide();   
                                                                                         }, 2000);

                                                                                    });
                                                                                            //uploading files variable
                                                                                       var custom_file_frame;
                                                                                  jQuery("#myMediaUploader").click(function(event) {
                                                                                    event.preventDefault();
                                                                                            //If the frame already exists, reopen it
                                                                                            if (typeof (custom_file_frame) !== "undefined") {
                                                                                    custom_file_frame.close();
                                                                                    }

                                                                                    //Create WP media frame.
                                                                                    custom_file_frame = wp.media.frames.customHeader = wp.media({
                                                                                    //Title of media manager frame
                                                                                    title: "WP Media Uploader",
                                                                                            library: {
                                                                                            type: 'image'
                                                                                            },
                                                                                            button: {
                                                                                            //Button text
                                                                                            text: "Set Image"
                                                                                            },
                                                                                            //Do not allow multiple files, if you want multiple, set true
                                                                                            multiple: false
                                                                                    });
                                                                                            //callback for selected image
                                                                                            custom_file_frame.on('select', function() {

                                                                                        var attachment = custom_file_frame.state().get('selection').first().toJSON();
                                                                                        var validExtensions = new Array();
                                                                                        validExtensions[0] = 'jpg';
                                                                                        validExtensions[1] = 'jpeg';
                                                                                        validExtensions[2] = 'png';
                                                                                        validExtensions[3] = 'gif';
                                                                                       
                                                                                        var inarr = parseInt(jQuery.inArray(attachment.subtype, validExtensions));
                                                                                          if (inarr > 0 && attachment.type.toLowerCase() == 'image'){

                                                                                            var titleTouse = "";
                                                                                            var imageDescriptionTouse = "";
                                                                                             if (jQuery.trim(attachment.title) != ''){

                                                                                                 titleTouse = jQuery.trim(attachment.title);
                                                                                            }
                                                                                            else if (jQuery.trim(attachment.caption) != ''){

                                                                                                titleTouse = jQuery.trim(attachment.caption);
                                                                                            }

                                                                                            if (jQuery.trim(attachment.description) != ''){

                                                                                               imageDescriptionTouse = jQuery.trim(attachment.description);
                                                                                            }
                                                                                            else if (jQuery.trim(attachment.caption) != ''){

                                                                                            imageDescriptionTouse = jQuery.trim(attachment.caption);
                                                                                            }

                                                                                           // jQuery("#videotitle").val(titleTouse);
                                                                                          //  jQuery("#video_description").val(imageDescriptionTouse);

                                                                                            if (attachment.id != ''){

                                                                                                      jQuery("#HdnMediaSelection").val(attachment.url);
                                                                                                      jQuery("#img_disp").attr('src', attachment.url);

                                                                                                }

                                                                                            }
                                                                                            else{

                                                                                              alert("<?php echo __('Invalid image selection.','wp-responsive-photo-gallery');?>");
                                                                                            }
                                                                                            //do something with attachment variable, for example attachment.filename
                                                                                            //Object:
                                                                                            //attachment.alt - image alt
                                                                                            //attachment.author - author id
                                                                                            //attachment.caption
                                                                                            //attachment.dateFormatted - date of image uploaded
                                                                                            //attachment.description
                                                                                            //attachment.editLink - edit link of media
                                                                                            //attachment.filename
                                                                                            //attachment.height
                                                                                            //attachment.icon - don't know WTF?))
                                                                                            //attachment.id - id of attachment
                                                                                            //attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
                                                                                            //attachment.menuOrder
                                                                                            //attachment.mime - mime type, for example image/jpeg"
                                                                                            //attachment.name - name of attachment file, for example "my-image"
                                                                                            //attachment.status - usual is "inherit"
                                                                                            //attachment.subtype - "jpeg" if is "jpg"
                                                                                            //attachment.title
                                                                                            //attachment.type - "image"
                                                                                            //attachment.uploadedTo
                                                                                            //attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
                                                                                            //attachment.width
                                                                                            });
                                                                                            //Open modal
                                                                                            custom_file_frame.open();
                                                                                      });
                                                                                    })
                                                                                 </script>
										</div>
									</div>
                                                                        <div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label for="link_name"><?php echo __('Video Title','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('Used into lightbox','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="text" id="title" tabindex="1" size="30" name="title" value="<?php echo $videotitle; ?>">
											</div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>
										</div>
									</div>
									<div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label for="link_name"><?php echo __('Video Title Url','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('click on title redirect to this url.Used in lightbox for video title','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="text" id="murl" 
													tabindex="1" size="30" name="murl"
													value="<?php echo $videotitleurl; ?>">
											</div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>

										</div>
									</div>
									
									<div class="stuffbox playvideoinlightbox" id="namediv" style="width: 100%">
										<h3>
											<label><?php echo __('Play Video In Lightbox?','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('show video in lightbox or redirect?','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<table>
												<tr>
													<td>
														<div>
															<input type="checkbox" id="open_link_in" size="30"
																name="open_link_in" value=""
																<?php
																	if ($open_link_in == true) {
																		echo "checked='checked'";
																	}
																	?>
																style="width: 20px;">&nbsp;<?php echo __('Show Video In Lightbox?','wp-responsive-photo-gallery');?>
														</div>
														<div style="clear: both"></div>
														<div></div>
													</td>
												</tr>
											</table>
											<div style="clear: both"></div>
										</div>
									</div>
									
									

									 <?php if (isset($_GET['id']) and (int) $_GET['id'] > 0) { ?> 
										 <input type="hidden" name="videoid" id="videoid" value="<?php echo (int) $_GET['id']; ?>">
                                                                         <?php
										}
										?>
                                                                     
                                                                            <?php wp_nonce_field('action_image_add_edit', 'add_edit_image_nonce'); ?>      
                                                                            <input type="submit"
										onclick="" name="btnsave" id="btnsave" value="<?php echo __('Save Changes','wp-responsive-photo-gallery');?>"
										class="button-primary">&nbsp;&nbsp;<input type="button"
										name="cancle" id="cancle" value="<?php echo __('Cancel','wp-responsive-photo-gallery');?>"
										class="button-primary"
										onclick="location.href = 'admin.php?page=responsive_justified_gallery_with_lightbox_media_management'">

								</form>
                                                                   <form method="post" action="" id="addimage_" name="addimage_" enctype="multipart/form-data" style="display:none">
                                                                    
                                                                        <input type="hidden" name="media_type" id="media_type" value="image" />
									 <div class="stuffbox" id="image_info" style="width: 100%;">
										<h3>
											<label for="link_name"><?php echo __('Image Information','wp-responsive-photo-gallery');?></label>
										</h3>
										<div class="inside" id="fileuploaddiv">
                                                                                <?php if ($image_name != "") { ?>
                                                                                        <div>
												<b><?php echo __('Current Image','wp-responsive-photo-gallery');?> : </b>
												<br/>
												<img id="img_disp_img" name="img_disp_img"
													src="<?php echo $baseurl . $image_name; ?>" />
											</div>
                                                                                <?php }else{ ?>      
                                                                                            <img
												src="<?php echo plugins_url('/images/no-image-selected.png', __FILE__); ?>"
												id="img_disp_img" name="img_disp_img" />
                                                           
                                                                                     <?php } ?>
                                                                                         <img
												src="<?php echo plugins_url('/images/ajax-loader.gif', __FILE__); ?>"
												style="display: none" id="loading_img" name="loading_img" />
											<div style="clear: both"></div>
											<div></div>
											<div class="uploader">
												
												<div style="clear: both; margin-top: 15px;"></div>
                                                                                                        <a
													href="javascript:;" class="niks_media" id="myMediaUploader_image"><b><?php echo __('Click Here to upload Image','wp-responsive-photo-gallery');?></b></a>
                                                                                                    <br /> <br />
												<div>
                                                                                                 <input id="HdnMediaSelection_image" name="HdnMediaSelection_image" type="hidden" value="<?php echo $HdnMediaSelection;?>" />
												</div>
												<div style="clear: both"></div>
												<div></div>
												<div style="clear: both"></div>

												<br />
											</div>
                                                                                </div>
                                                                            
                                                                            <script>
                                                                                 //uploading files variable
                                                                                  var custom_file_frame;
                                                                                  jQuery("#myMediaUploader_image").click(function(event) {
                                                                                    event.preventDefault();
                                                                                            //If the frame already exists, reopen it
                                                                                   if (typeof (custom_file_frame) !== "undefined") {
                                                                                    custom_file_frame.close();
                                                                                    }

                                                                                    //Create WP media frame.
                                                                                    custom_file_frame = wp.media.frames.customHeader = wp.media({
                                                                                    //Title of media manager frame
                                                                                    title: "WP Media Uploader",
                                                                                            library: {
                                                                                            type: 'image'
                                                                                            },
                                                                                            button: {
                                                                                            //Button text
                                                                                            text: "Set Image"
                                                                                            },
                                                                                            //Do not allow multiple files, if you want multiple, set true
                                                                                            multiple: false
                                                                                    });
                                                                                            //callback for selected image
                                                                                            custom_file_frame.on('select', function() {

                                                                                        var attachment = custom_file_frame.state().get('selection').first().toJSON();
                                                                                        var validExtensions = new Array();
                                                                                        validExtensions[0] = 'jpg';
                                                                                        validExtensions[1] = 'jpeg';
                                                                                        validExtensions[2] = 'png';
                                                                                        validExtensions[3] = 'gif';
                                                                                       
                                                                                        var inarr = parseInt(jQuery.inArray(attachment.subtype, validExtensions));
                                                                                          if (inarr > 0 && attachment.type.toLowerCase() == 'image'){

                                                                                            var titleTouse = "";
                                                                                            var imageDescriptionTouse = "";
                                                                                             if (jQuery.trim(attachment.title) != ''){

                                                                                                 titleTouse = jQuery.trim(attachment.title);
                                                                                            }
                                                                                            else if (jQuery.trim(attachment.caption) != ''){

                                                                                                titleTouse = jQuery.trim(attachment.caption);
                                                                                            }

                                                                                            if (jQuery.trim(attachment.description) != ''){

                                                                                               imageDescriptionTouse = jQuery.trim(attachment.description);
                                                                                            }
                                                                                            else if (jQuery.trim(attachment.caption) != ''){

                                                                                            imageDescriptionTouse = jQuery.trim(attachment.caption);
                                                                                            }

                                                                                            jQuery("#addimage_ #title").val(titleTouse);
                                                                                            jQuery("#addimage_ #mdescription").val(imageDescriptionTouse);

                                                                                            if (attachment.id != ''){

                                                                                                      jQuery("#HdnMediaSelection_image").val(attachment.url);
                                                                                                      jQuery("#img_disp_img").attr('src', attachment.url);

                                                                                                }

                                                                                            }
                                                                                            else{

                                                                                              alert("<?php echo __('Invalid image selection.','wp-responsive-photo-gallery');?>");
                                                                                            }
                                                                                            //do something with attachment variable, for example attachment.filename
                                                                                            //Object:
                                                                                            //attachment.alt - image alt
                                                                                            //attachment.author - author id
                                                                                            //attachment.caption
                                                                                            //attachment.dateFormatted - date of image uploaded
                                                                                            //attachment.description
                                                                                            //attachment.editLink - edit link of media
                                                                                            //attachment.filename
                                                                                            //attachment.height
                                                                                            //attachment.icon - don't know WTF?))
                                                                                            //attachment.id - id of attachment
                                                                                            //attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
                                                                                            //attachment.menuOrder
                                                                                            //attachment.mime - mime type, for example image/jpeg"
                                                                                            //attachment.name - name of attachment file, for example "my-image"
                                                                                            //attachment.status - usual is "inherit"
                                                                                            //attachment.subtype - "jpeg" if is "jpg"
                                                                                            //attachment.title
                                                                                            //attachment.type - "image"
                                                                                            //attachment.uploadedTo
                                                                                            //attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
                                                                                            //attachment.width
                                                                                            });
                                                                                            //Open modal
                                                                                            custom_file_frame.open();
                                                                                      });
                                                                                    
                                                                                 </script>
                                                                        </div>
                                                                
                                                                        <div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label for="link_name"><?php echo __('Image Title','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('Used into lightbox','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="text" id="title" tabindex="1" size="30" name="title" value="<?php echo $videotitle; ?>">
											</div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>
										</div>
									</div>
									<div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label for="link_name"><?php echo __('Image Title Url','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('click on title redirect to this url.Used in lightbox for video title','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="text" id="murl" 
													tabindex="1" size="30" name="murl"
													value="<?php echo $murl; ?>">
											</div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>

										</div>
									</div>
                                                                        
                                                                        
									<div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label><?php echo __('Show Image In Lightbox?','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('show image in lightbox or redirect ?','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<table>
												<tr>
													<td>
														<div>
															<input type="checkbox" id="open_link_in" size="30"
																name="open_link_in" value=""
																<?php
																	if ($open_link_in == true) {
																		echo "checked='checked'";
																	}
																	?>
																style="width: 20px;">&nbsp;Show image In Lightbox?
														</div>
														<div style="clear: both"></div>
														<div></div>
													</td>
												</tr>
											</table>
											<div style="clear: both"></div>
										</div>
									</div>
									 <?php if (isset($_GET['id']) and intval($_GET['id']) > 0) { ?> 
										 <input type="hidden" name="imageid" id="imageid" value="<?php echo intval($_GET['id']); ?>">
                                                                         <?php
										}
										?>
                                                                            <?php wp_nonce_field('action_image_add_edit', 'add_edit_image_nonce'); ?>      
                                                                            <input type="submit"
										onclick="" name="btnsave" id="btnsave" value="<?php echo __('Save Changes','wp-responsive-photo-gallery');?>"
										class="button-primary">&nbsp;&nbsp;<input type="button"
										name="cancle" id="cancle" value="<?php echo __('Cancel','wp-responsive-photo-gallery');?>"
										class="button-primary"
										onclick="location.href = 'admin.php?page=responsive_justified_gallery_with_lightbox_media_management'">

								</form>
                                                                   <form method="post" action="" id="addlink_" name="addlink_" enctype="multipart/form-data" style="display:none">
                                                                    
                                                                        <input type="hidden" name="media_type" id="media_type" value="link" />
									 <div class="stuffbox" id="image_info" style="width: 100%;">
										<h3>
											<label for="link_name"><?php echo __('Image Information','wp-responsive-photo-gallery');?></label>
										</h3>
										<div class="inside" id="fileuploaddiv">
                                                                                <?php if ($image_name != "") { ?>
                                                                                        <div>
												<b><?php echo __('Current Image','wp-responsive-photo-gallery');?>: </b>
												<br/>
												<img id="img_disp_link" name="img_disp_link"
													src="<?php echo $baseurl . $image_name; ?>" />
											</div>
                                                                                <?php }else{ ?>      
                                                                                            <img
												src="<?php echo plugins_url('/images/no-image-selected.png', __FILE__); ?>"
												id="img_disp_link" name="img_disp_link" />
                                                           
                                                                                     <?php } ?>
                                                                                         <img
												src="<?php echo plugins_url('/images/ajax-loader.gif', __FILE__); ?>"
												style="display: none" id="loading_img" name="loading_img" />
											<div style="clear: both"></div>
											<div></div>
											<div class="uploader">
												
												<div style="clear: both; margin-top: 15px;"></div>
                                                                                                        <a href="javascript:;" class="niks_media" id="myMediaUploader_link"><b><?php echo __('Click Here to upload Image','wp-responsive-photo-gallery');?></b></a>
                                                                                                    <br /> <br />
												<div>
                                                                                                 <input id="HdnMediaSelection_link" name="HdnMediaSelection_link" type="hidden" value="<?php echo $HdnMediaSelection;?>" />
												</div>
												<div style="clear: both"></div>
												<div></div>
												<div style="clear: both"></div>

												<br />
											</div>
                                                                                </div>
                                                                            
                                                                            <script>
                                                                                 //uploading files variable
                                                                                  var custom_file_frame;
                                                                                  jQuery("#myMediaUploader_link").click(function(event) {
                                                                                    event.preventDefault();
                                                                                            //If the frame already exists, reopen it
                                                                                   if (typeof (custom_file_frame) !== "undefined") {
                                                                                    custom_file_frame.close();
                                                                                    }

                                                                                    //Create WP media frame.
                                                                                    custom_file_frame = wp.media.frames.customHeader = wp.media({
                                                                                    //Title of media manager frame
                                                                                    title: "WP Media Uploader",
                                                                                            library: {
                                                                                            type: 'image'
                                                                                            },
                                                                                            button: {
                                                                                            //Button text
                                                                                            text: "Set Image"
                                                                                            },
                                                                                            //Do not allow multiple files, if you want multiple, set true
                                                                                            multiple: false
                                                                                    });
                                                                                            //callback for selected image
                                                                                            custom_file_frame.on('select', function() {

                                                                                        var attachment = custom_file_frame.state().get('selection').first().toJSON();
                                                                                        var validExtensions = new Array();
                                                                                        validExtensions[0] = 'jpg';
                                                                                        validExtensions[1] = 'jpeg';
                                                                                        validExtensions[2] = 'png';
                                                                                        validExtensions[3] = 'gif';
                                                                                       
                                                                                        var inarr = parseInt(jQuery.inArray(attachment.subtype, validExtensions));
                                                                                          if (inarr > 0 && attachment.type.toLowerCase() == 'image'){

                                                                                            var titleTouse = "";
                                                                                            var imageDescriptionTouse = "";
                                                                                             if (jQuery.trim(attachment.title) != ''){

                                                                                                 titleTouse = jQuery.trim(attachment.title);
                                                                                            }
                                                                                            else if (jQuery.trim(attachment.caption) != ''){

                                                                                                titleTouse = jQuery.trim(attachment.caption);
                                                                                            }

                                                                                            if (jQuery.trim(attachment.description) != ''){

                                                                                               imageDescriptionTouse = jQuery.trim(attachment.description);
                                                                                            }
                                                                                            else if (jQuery.trim(attachment.caption) != ''){

                                                                                            imageDescriptionTouse = jQuery.trim(attachment.caption);
                                                                                            }

                                                                                            jQuery("#addlink_ #title").val(titleTouse);
                                                                                         
                                                                                            if (attachment.id != ''){

                                                                                                      jQuery("#HdnMediaSelection_link").val(attachment.url);
                                                                                                      jQuery("#img_disp_link").attr('src', attachment.url);

                                                                                                }

                                                                                            }
                                                                                            else{

                                                                                              alert("<?php echo __('Invalid image selection.','wp-responsive-photo-gallery');?>");
                                                                                            }
                                                                                            //do something with attachment variable, for example attachment.filename
                                                                                            //Object:
                                                                                            //attachment.alt - image alt
                                                                                            //attachment.author - author id
                                                                                            //attachment.caption
                                                                                            //attachment.dateFormatted - date of image uploaded
                                                                                            //attachment.description
                                                                                            //attachment.editLink - edit link of media
                                                                                            //attachment.filename
                                                                                            //attachment.height
                                                                                            //attachment.icon - don't know WTF?))
                                                                                            //attachment.id - id of attachment
                                                                                            //attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
                                                                                            //attachment.menuOrder
                                                                                            //attachment.mime - mime type, for example image/jpeg"
                                                                                            //attachment.name - name of attachment file, for example "my-image"
                                                                                            //attachment.status - usual is "inherit"
                                                                                            //attachment.subtype - "jpeg" if is "jpg"
                                                                                            //attachment.title
                                                                                            //attachment.type - "image"
                                                                                            //attachment.uploadedTo
                                                                                            //attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
                                                                                            //attachment.width
                                                                                            });
                                                                                            //Open modal
                                                                                            custom_file_frame.open();
                                                                                      });
                                                                                    
                                                                                 </script>
                                                                        </div>
                                                                
                                                                        <div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label for="link_name"><?php echo __('Link Title','wp-responsive-photo-gallery');?> (<span style="font-size: 11px; font-weight: normal"><?php echo __('Used into Caption','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="text" id="title" tabindex="1" size="30" name="title" value="<?php echo $videotitle; ?>">
											</div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>
										</div>
									</div>
									<div class="stuffbox" id="namediv" style="width: 100%">
										<h3>
											<label for="link_name"><?php echo __('Link Url','wp-responsive-photo-gallery');?> (<span
												style="font-size: 11px; font-weight: normal"><?php echo __('click on image will redirect to this url.','wp-responsive-photo-gallery');?></span>)
											</label>
										</h3>
										<div class="inside">
											<div>
												<input type="text" id="murl" 
													tabindex="1" size="30" name="murl"
													value="<?php echo $murl; ?>">
											</div>
											<div style="clear: both"></div>
											<div></div>
											<div style="clear: both"></div>

										</div>
									</div>
									
                                                                        <?php if (isset($_GET['id']) and (int) $_GET['id'] > 0) { ?> 
										 <input type="hidden" name="linkid" id="linkid" value="<?php echo (int) $_GET['id']; ?>">
                                                                         <?php
										}
										?>
								
                                                                            <?php wp_nonce_field('action_image_add_edit', 'add_edit_image_nonce'); ?>      
                                                                            <input type="submit"
										onclick="" name="btnsave" id="btnsave" value="<?php echo __('Save Changes','wp-responsive-photo-gallery');?>"
										class="button-primary">&nbsp;&nbsp;<input type="button"
										name="cancle" id="cancle" value="<?php echo __('Cancel','wp-responsive-photo-gallery');?>"
										class="button-primary"
										onclick="location.href = 'admin.php?page=responsive_justified_gallery_with_lightbox_media_management'">

								</form>
								<script type="text/javascript">

                                                                    
                                                                    jQuery(document).ready(function() {

                                                                     jQuery.validator.setDefaults({ 
                                                                         ignore: [],
                                                                         // any other default options and/or rules
                                                                     });

                                                                            jQuery("#addvideo").validate({
                                                                            rules: {
                                                                            videotitle: {
                                                                            required:true,
                                                                                    maxlength: 200
                                                                            },
                                                                             vtype: {
                                                                             required:true

                                                                             },
                                                                             videourl: {
                                                                                required: function(element) {
                                                                                   return jQuery("#type_html5").is(':checked')==0;
                                                                                    },
                                                                                   maxlength: 500
                                                                             },
                                                                             HdnMediaSelection:{
                                                                               required:true  
                                                                             }
                                                                       
                                                                            
                                                                            },
                                                                             errorClass: "image_error",
                                                                             errorPlacement: function(error, element) {
                                                                             error.appendTo(element.parent().next().next());
                                                                             }, messages: {
                                                                                 HdnMediaSelection: "Please select video thumbnail.",

                                                                             }

                                                                         })
                                                                         
                                                                         
                                                                           jQuery("#addimage_").validate({
                                                                            rules: {
                                                                             HdnMediaSelection_image:{
                                                                               required:true  
                                                                             },
                                                                             murl: {

                                                                            /* url:true,*/
                                                                              maxlength: 500
                                                                             }
                                                                            
                                                                            
                                                                            },
                                                                             errorClass: "image_error",
                                                                             errorPlacement: function(error, element) {
                                                                             error.appendTo(element.parent().next().next());
                                                                             }, messages: {
                                                                                 HdnMediaSelection: "Please select image thumbnail or Upload by wordpress media uploader.",

                                                                             }

                                                                         })
                                                                           jQuery("#addlink_").validate({
                                                                            rules: {
                                                                             HdnMediaSelection_link:{
                                                                               required:true  
                                                                             },
                                                                             murl: {
                                                                                required:true,      
                                                                               /* url:true,*/
                                                                                maxlength: 500
                                                                             }
                                                                            
                                                                            },
                                                                             errorClass: "image_error",
                                                                             errorPlacement: function(error, element) {
                                                                             error.appendTo(element.parent().next().next());
                                                                             }, messages: {
                                                                                 HdnMediaSelection: "Please select link thumbnail or Upload by wordpress media uploader.",

                                                                             }

                                                                         
                                                                         
                                                                       })
                                                                           
                                                                         
                                                                     });
                                                                     
                                                                   
                                                                 </script>

							</div>
                                                    <div id="postbox-container-1" class="postbox-container"  > 

                                                        <div class="postbox"> 
                                                            <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-responsive-photo-gallery');?></h3> 
                                                            <div class="inside">
                                                                <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                                                                <div style="margin:10px 5px">

                                                                </div>
                                                            </div></div>
                                                        <div class="postbox"> 
                                                            <center><h3 class="hndle"><span></span><?php echo __( 'Google For Business','wp-responsive-photo-gallery');?></h3> </center>
                                                            <div class="inside">
                                                                <center><a target="_blank" href="https://goo.gl/OJBuHT"><img style="width:100%" src="<?php echo plugins_url( 'images/gsuite_promo.png', __FILE__ ) ;?>" border="0"></a></center>
                                                                <div style="margin:10px 5px">
                                                                </div>
                                                            </div></div>
                                                        

                                                    </div>
						</div>
					</div>
				</div>
			</div>
<?php
		}
	} else if (strtolower ( $action ) == strtolower ( 'delete' )) {
		
             $retrieved_nonce = '';

              if(isset($_GET['nonce']) and $_GET['nonce']!=''){

                  $retrieved_nonce=sanitize_text_field($_GET['nonce']);

              }
              if (!wp_verify_nonce($retrieved_nonce, 'delete_image' ) ){


                  wp_die('Security check fail'); 
              }

              if ( ! current_user_can( 'rsp_masonry_gallery_delete_media' ) ) {

                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                    $my_responsive_photo_gallery_slider_settings_messages=array();
                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                    exit;   

                }
                
		$uploads = wp_upload_dir ();
		$baseDir = $uploads ['basedir'];
		$baseDir = str_replace ( "\\", "/", $baseDir );
		$pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
		
		
		
		$location = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management";
		$deleteId = (int) htmlentities(intval($_GET ['id']),ENT_QUOTES);
		
		try {
			
			$query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery WHERE id=$deleteId";
			$myrow = $wpdb->get_row ( $query );
			
			if (is_object ( $myrow )) {
				
                             
				$image_name = $myrow->image_name;
				$wpcurrentdir = dirname ( __FILE__ );
				$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
				$imagetoDel = $pathToImagesFolder . '/' . $image_name;
                                $pInfo = pathinfo ( $myrow->HdnMediaSelection );
                                $pInfo2 = pathinfo ( $imagetoDel );
                                $ext = $pInfo2 ['extension'];
						
				@unlink ( $imagetoDel );
                            	
				$query = "delete from  " . $wpdb->prefix . "rjg_gallery where id=$deleteId";
				$wpdb->query ( $query );
				
				$my_responsive_photo_gallery_slider_settings_messages = array ();
				$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
				$my_responsive_photo_gallery_slider_settings_messages ['message'] =  __('Video deleted successfully.','wp-responsive-photo-gallery');
				update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
			}
		} catch ( Exception $e ) {
			
			$my_responsive_photo_gallery_slider_settings_messages = array ();
			$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
			$my_responsive_photo_gallery_slider_settings_messages ['message'] =  __('Error while deleting video.','wp-responsive-photo-gallery');
			update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
		}
		
		echo "<script type='text/javascript'> location.href='$location';</script>";
		exit ();
	} else if (strtolower ( $action ) == strtolower ( 'deleteselected' )) {
		
                if(!check_admin_referer('action_settings_mass_delete','mass_delete_nonce')){

                        wp_die('Security check fail'); 
                  }

		if ( ! current_user_can( 'rsp_masonry_gallery_delete_media' ) ) {

                    $location='admin.php?page=responsive_justified_gallery_with_lightbox_media_management';
                    $my_responsive_photo_gallery_slider_settings_messages=array();
                    $my_responsive_photo_gallery_slider_settings_messages['type']='err';
                    $my_responsive_photo_gallery_slider_settings_messages['message']=__('Access Denied. Please contact your administrator.','wp-responsive-photo-gallery');
                    update_option('my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages);
                    echo "<script type='text/javascript'> location.href='$location';</script>";     
                    exit;   

                }
		
		$location = "admin.php?page=responsive_justified_gallery_with_lightbox_media_management";
		
		if (isset ( $_POST ) and isset ( $_POST ['deleteselected'] ) and ($_POST ['action'] == 'delete' or $_POST ['action_upper'] == 'delete')) {
			
			$uploads = wp_upload_dir ();
			$baseDir = $uploads ['basedir'];
			$baseDir = str_replace ( "\\", "/", $baseDir );
			$pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
			
			if (sizeof ( $_POST ['thumbnails'] ) > 0) {
				
				$deleteto = $_POST ['thumbnails'];
				$implode = implode ( ',', $deleteto );
				
				try {
					
					foreach ( $deleteto as $img ) {
						
                                                $img=intval($img);
						$query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery WHERE id=$img";
						$myrow = $wpdb->get_row ( $query );
                                                
                                           
						
						if (is_object ( $myrow )) {
							
							$image_name = $myrow->image_name ;
							$wpcurrentdir = dirname ( __FILE__ );
							$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
							$imagetoDel = $pathToImagesFolder . '/' . $image_name;
							
                                                        $pInfo = pathinfo ( $myrow->HdnMediaSelection );
                                                        $pInfo2 = pathinfo ( $imagetoDel );
                                                        $ext = $pInfo2 ['extension'];
							
                                                        @unlink ( $imagetoDel );
                                                       
							
							$query = "delete from  " . $wpdb->prefix . "rjg_gallery where id=$img";
							$wpdb->query ( $query );
							
							$my_responsive_photo_gallery_slider_settings_messages = array ();
							$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'succ';
							$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('selected media deleted successfully.','wp-responsive-photo-gallery');
							update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
						}
					}
				} catch ( Exception $e ) {
					
					$my_responsive_photo_gallery_slider_settings_messages = array ();
					$my_responsive_photo_gallery_slider_settings_messages ['type'] = 'err';
					$my_responsive_photo_gallery_slider_settings_messages ['message'] = __('Error while deleting videos.','wp-responsive-photo-gallery');
					update_option ( 'my_responsive_photo_gallery_slider_settings_messages', $my_responsive_photo_gallery_slider_settings_messages );
				}
				
				echo "<script type='text/javascript'> location.href='$location';</script>";
				exit ();
			} else {
				
				echo "<script type='text/javascript'> location.href='$location';</script>";
				exit ();
			}
		} else {
			
			echo "<script type='text/javascript'> location.href='$location';</script>";
			exit ();
		}
	}
}

function rjg_justified_gallery_get_wp_version() {
	global $wp_version;
	return $wp_version;
}

// also we will add an option function that will check for plugin admin page or not
function rjg_responsive_justified_gallery_plus_lightbox_is_plugin_page() {
    
	$server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	
	foreach ( array ('responsive_photo_gallery_image_management', 'responsive_justified_gallery_with_lightbox_media_management','responsive_justified_gallery_with_lightbox'
	) as $allowURI ) {
		if (stristr ( $server_uri, $allowURI ))
			return true;
	}
	return false;
}
    function my_responsive_photo_gallery_admin_scripts_init() {
        if(my_responsive_photo_gallery_is_plugin_page()) {
            //double check for WordPress version and function exists
            if(function_exists('wp_enqueue_media') && version_compare(my_responsive_photo_gallery_get_wp_version(), '3.5', '>=')) {
                //call for new media manager
                wp_enqueue_media();
            }
            wp_enqueue_style('media');
        }
    }
    
    // add media WP scripts
    function rjg_responsive_justified_gallery_plus_lightbox_admin_scripts_init() {

            if (rjg_responsive_justified_gallery_plus_lightbox_is_plugin_page()) {
                    // double check for WordPress version and function exists
                    if (function_exists ( 'wp_enqueue_media' ) && version_compare ( rjg_justified_gallery_get_wp_version (), '3.5', '>=' )) {
                            // call for new media manager
                            wp_enqueue_media ();
                    }
                    wp_enqueue_style ( 'media' );
                     wp_enqueue_style( 'wp-color-picker' );
                    wp_enqueue_script( 'wp-color-picker' );

            }
    }
    function wrpg_remove_extra_p_tags($content){

        if(strpos($content, 'print_my_responsive_photo_gallery_func')!==false){
        
            
            $pattern = "/<!-- print_my_responsive_photo_gallery_func -->(.*)<!-- end print_my_responsive_photo_gallery_func -->/Uis"; 
            $content = preg_replace_callback($pattern, function($matches) {


               $altered = str_replace("<p>","",$matches[1]);
               $altered = str_replace("</p>","",$altered);
              
                $altered=str_replace("&#038;","&",$altered);
                $altered=str_replace("&#8221;",'"',$altered);
              

              return @str_replace($matches[1], $altered, $matches[0]);
            }, $content);

              
            
        }
        
        $content = str_replace("<p><!-- print_my_responsive_photo_gallery_func -->","<!-- print_my_responsive_photo_gallery_func -->",$content);
        $content = str_replace("<!-- end print_my_responsive_photo_gallery_func --></p>","<!-- end print_my_responsive_photo_gallery_func -->",$content);
        
        
        return $content;
  }

  add_filter('widget_text_content', 'wrpg_remove_extra_p_tags', 999);
  add_filter('the_content', 'wrpg_remove_extra_p_tags', 999);
  
  
  function rjg_print_masonry_gallery_plus_lightbox_func($atts) {
    

	global $wpdb;
	$settings=get_option('rjg_settings');
        $pagenum = isset($_GET['pagenum']) ? (int) absint($_GET['pagenum']) : 1;
        $limit = $settings['page_size'];
        $offset = ( $pagenum - 1 ) * $limit;
        
	$rand_Numb = uniqid ( 'thumnail_slider' );
	$rand_Num_td = uniqid ( 'divSliderMain' );
	$rand_var_name = uniqid ( 'rand_' );
	$target = uniqid ( 'target'.rand(1,2000000) );
	
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	// $settings=get_option('thumbnail_slider_settings');
	
	$uploads = wp_upload_dir ();
	$baseDir = $uploads ['basedir'];
	$baseDir = str_replace ( "\\", "/", $baseDir );
	$pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
	$baseurl = $uploads ['baseurl'];
	$baseurl .= '/wp-responsive-photo-gallery/';
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	$randOmeAlbName = uniqid ( 'alb_' );
	$randOmeRel = uniqid ( 'rel_' );
        $randOmVlBox = uniqid('video_lbox_');
        $vNonce = wp_create_nonce('vNonce');
        $url = plugin_dir_url(__FILE__);
        $loaderImg = $url . 'images/bx_loader.gif';
        
        $LoadingBackColor=$settings ['BackgroundColor'];
        if(strtolower($LoadingBackColor)=='none'){
            $LoadingBackColor='#ffffff';
        }
        $imageMargin=$settings['imageMargin'];
        
        wp_enqueue_style('rjg-lbox');
        wp_enqueue_style('rjg-justified-gallery');
        wp_enqueue_script('jquery'); 
        wp_enqueue_script('rjg-justified-gallery');
        wp_enqueue_script('rjg-lbox-js');
        
        ob_start();
        ?>
         <!-- rjg_print_masonry_gallery_plus_lightbox_func --><?php if (is_array($settings)) { ?>
                                 
                    <style>#<?php echo $rand_var_name;?>{background-color:<?php echo $LoadingBackColor;?>;padding-top:<?php echo $imageMargin;?>px;padding-left:<?php echo $imageMargin;?>px;padding-right:<?php echo $imageMargin;?>px;}</style>
                    <div style="clear: both;"></div>
                    <?php $url = plugin_dir_url(__FILE__); ?>           
                     <div style="clear: both;"></div>
                        <?php $url = plugin_dir_url(__FILE__); ?>           


                     <div class="gallery_wrap0" id="<?php echo $rand_Num_td;?>" style="visibility: hidden" >

                                    <div class="gallery_ gallery_0" id="<?php echo $rand_var_name;?>" >

                                    <div id="<?php echo $rand_var_name; ?>_overlay_grid" class="overlay_grid" style="background: <?php echo $LoadingBackColor; ?> url('<?php echo $loaderImg; ?>') no-repeat scroll 50% 50%;" ></div>

                                                    <?php

                                                      $imageheight = $settings ['imageheight'];
                                                      $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery  order by createdon desc LIMIT $offset, $limit";
                                                      $firstChild='firstimg';
                                                      $rows = $wpdb->get_results ( $query, 'ARRAY_A' );


                                                      if (count ( $rows ) > 0) {

                                                          foreach ( $rows as $row ) {

                                                                  $imagename = $row ['image_name'];
                                                                  $video_url = $row ['videourl'];
                                                                  $video_url_org = $row ['murl'];
                                                                  $Url_vid = @parse_url($video_url_org);



                                                                  $relend = '';
                                                                   $flag=false;
                                                                      if (isset($Url_vid['query']) and $Url_vid['query'] != '') {


                                                                          parse_str($Url_vid['query'], $get_array);
                                                                          if(is_array($get_array) and sizeof($get_array)>0){

                                                                             foreach($get_array as $k=>$v){

                                                                                 if($flag==false){

                                                                                     $flag=true;
                                                                                     $relend.="?$k=$v";
                                                                                 }
                                                                                 else{

                                                                                     $relend.="&$k=$v";

                                                                                 }


                                                                             } 


                                                                          }



                                                                      }

                                                                  $vtype= $row ['vtype'];
                                                                  $imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                                  $imageUploadTo = str_replace ( "\\", "/", $imageUploadTo );
                                                                  $pathinfo = pathinfo ( $imageUploadTo );
                                                                  $filenamewithoutextension = $pathinfo ['filename'];

                                                                  $outputimgmain = $baseurl . $row ['image_name'];
                                                                  $outputimg=$outputimgmain;
                                                                  $media_type=$row['media_type'];  
                                                                  $hoverClass='';
                                                                  if($media_type=="link")
                                                                       $hoverClass="playbtnCss_link";
                                                                  else if($media_type=="video")
                                                                       $hoverClass="playbtnCss_video";
                                                                   else if($media_type=="image")
                                                                        $hoverClass="playbtnCss_zoom";

                                                                      $title = "";
                                                                      $rowTitle = $row['title'];
                                                                      $rowTitle = str_replace("'", "’", $rowTitle);
                                                                      $rowTitle = str_replace('"', '”', $rowTitle);



                                                                      $open_link_in = $row['open_link_in'];
                                                                      $open_title_link_in = 1;

                                                                      if(!$open_title_link_in)
                                                                          $openImageInNewTab = '_self';
                                                                      else
                                                                          $openImageInNewTab = '_blank';

                                                                      $embed_url=$row['embed_url'].$relend;
                                                                     if($media_type=="video"){

                                                                          if (trim($row['title']) != '' and trim($row['videourl']) != '') {

                                                                              $title = "<a class='Imglink' target='$openImageInNewTab' href='{$row['videourl']}'>{$rowTitle}</a>";

                                                                          } else if (trim($row['title']) != '' and trim($row['videourl']) == '') {

                                                                              $title = "<a class='Imglink' >{$rowTitle}</a>";

                                                                          } else {

                                                                              if ($row['mdescription'] != '')
                                                                                  $title = "<div class='clear_description_'>{$row['mdescription']}</div>";

                                                                          }
                                                                     }
                                                                     else if($media_type=="image"){

                                                                         if (trim($row['title']) != '' and trim($row['murl']) != '') {

                                                                              $title = "<a class='Imglink' target='$openImageInNewTab' href='{$row['murl']}'>{$rowTitle}</a>";

                                                                          } else if (trim($row['title']) != '' and trim($row['murl']) == '') {

                                                                              $title = "<a class='Imglink' >{$rowTitle}</a>";

                                                                          } else {

                                                                              if ($row['mdescription'] != '')
                                                                                  $title = "<div class='clear_description_'>{$row['mdescription']}</div>";

                                                                          }

                                                                     }

                                                                  $title= htmlentities($title);   
                                                          ?>

                                                              <?php if($media_type=='image' or $media_type=='video') :?>

                                                                      <?php if ($open_link_in == 1): ?>
                                                                         <a data-rel="<?php echo $randOmeRel; ?>"  data-overlay="1" data-type="<?php echo $media_type;?>"  data-title="<?php echo $title; ?>" class="thumbnail_ <?php echo $randOmVlBox; ?> <?php if($media_type=='video'):?>iframe <?php endif;?> "  href="<?php if($media_type=='video'):?><?php echo $embed_url; ?> <?php else:?><?php echo $outputimgmain; ?><?php endif;?>"  >
                                                                              <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure> 
                                                                          </a>
                                                                       <?php else: ?>

                                                                           <a   data-type="<?php echo $media_type;?>" data-overlay="1" data-title="<?php echo $title; ?>" class="thumbnail_ "  href="<?php if($media_type=='video'):?><?php echo $embed_url; ?> <?php else:?><?php echo $outputimgmain; ?><?php endif;?>" >
                                                                              <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure>
                                                                          </a>
                                                                       <?php endif;?>

                                                               <?php else:?>
                                                                   <a   data-type="<?php echo $media_type;?>" target='<?php echo $openImageInNewTab;?>' class="thumbnail_ "  href="<?php echo $row['murl']; ?>" >
                                                                     <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure>
                                                                  </a> 

                                                               <?php endif;?>



                                                              <?php } ?>   

                                                   <?php } ?>   
                                                      <br style="clear: both;">
                                                  </div>
                                                <?php
                                                $total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}rjg_gallery ");
                                                $num_of_pages = ceil($total / $limit);
                                                $page_links = paginate_links(array(
                                                    'base' => add_query_arg('pagenum', '%#%'),
                                                    'format' => '',
                                                    'prev_text' => __('&laquo;', 'aag'),
                                                    'next_text' => __('&raquo;', 'aag'),
                                                    'total' => $num_of_pages,
                                                    'current' => $pagenum,
                                                    'prev_next' => true,
                                                    'type' => 'list',
                                                        ));

                                                if ($page_links) {
                                                      echo '<div class="navigation_grid_rjg" style="margin-bottom:10px;display:table">' . $page_links . '</div>';

                                                }
                                                ?>    

                                        <div style="clear:both"></div>
                            </div>

                      <script>
            
                       <?php $intval= uniqid('interval_');?>
               
                        var <?php echo $intval;?> = setInterval(function() {

                        if(document.readyState === 'complete') {

                           clearInterval(<?php echo $intval;?>);
                                    
                                
                                jQuery(".gallery_wrap0").css('visibility','visible');
                                
                                <?php $uniqId = uniqid(); ?>
                                 var uniqObj<?php echo $uniqId ?> = jQuery("a[data-rel='<?php echo $randOmeRel; ?>']");

                               
                                        jQuery("#<?php echo $rand_var_name;?>").latae({
                                            loader : '<?php echo plugins_url( 'images/loader.gif', __FILE__ ) ;?>',
                                            max_height:<?php echo $settings ['imageheight'];?>,
                                            margin:<?php echo $settings ['imageMargin'];?>,
                                            target:'<?php echo $target;?>',
                                            init : function() { },
                                            loadPicture : function(event, img) {  },
                                            resize : function(event, gallery) {  },
                                            displayTitle: <?php echo ($settings['show_hover_caption']==1) ?  'true':'false' ?>,
                                            displayIcons: <?php echo ($settings['show_hover_icon']==1) ?  'true':'false' ?>
                                        });

                                    jQuery(".<?php echo $randOmVlBox; ?>").fancybox_rjg({

                                    'overlayColor':'#000000',
                                    'padding': 3,
                                    'margin': 20,
                                    'autoScale': true,
                                    'autoDimensions':true,
                                    'uniqObj':uniqObj<?php echo $uniqId; ?>,
                                    'uniqRel':'<?php echo $randOmeRel; ?>',
                                    'transitionIn':'fade',
                                    'transitionOut':'fade',
                                    'titlePosition': 'outside',
                                     'cyclic':true,
                                    'hideOnContentClick':false,
                                    'width' : 650,
                                    'height' : 400,
                                     'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                                            var currtElem = jQuery('#<?php echo $rand_var_name; ?> a[href="' + currentOpts.href + '"]');
                                                    var isoverlay = jQuery(currtElem).attr('data-overlay')

                                             if (isoverlay == "1" && jQuery.trim(title) != ""){
                                                    return '<span id="fancybox_rjg-title-over">' + title + '</span>';
                                            }
                                            else{
                                                 return '';
                                            }

                                            }
                                    });

                                    jQuery(".page-numbers").show();


                            jQuery("body").delegate("#<?php echo $rand_Num_td; ?> .navigation_grid_rjg ul.page-numbers li a.page-numbers", "click", function(e) {

                                    jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("width", jQuery("#<?php echo $rand_var_name; ?>").width());
                                    jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("height", jQuery("#<?php echo $rand_var_name; ?>").height());

                                    e.preventDefault();
                                    var data = {
                                            'action': 'rjg_get_grid_data_justified_gallery',
                                            'page_url': encodeURI(jQuery(this).attr('href')),
                                            'grid_id':0,
                                            'total_rec':'<?php echo $total; ?>',
                                            'vNonce':'<?php echo $vNonce;?>'
                                    };
                                    jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {


                                    jQuery('html, body').animate({
                                        scrollTop: jQuery("#<?php echo $rand_Num_td; ?>").offset().top
                                    }, 800);
                                    jQuery("#<?php echo $rand_Num_td; ?>").replaceWith(response);
                                    jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("width", "0px");
                                    jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("height", "0px");


                                  });



                            });

                        }    
                }, 100); 
                         

            </script>
                                                                  
                                                                  
        <?php } ?>

        <div class="clear"></div>
	<!-- end rjg_print_masonry_gallery_plus_lightbox_func -->          
 <?php
    	$output = ob_get_clean ();
	return $output;
}
function rfp_responsive_justified_gallery_with_lightbox_media_preview_func() {
    
	global $wpdb;
        
        
        if ( ! current_user_can( 'rsp_masonry_gallery_preview' ) ) {

           wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

        }
        
        $pagenum = isset($_GET['pagenum']) ? (int) absint($_GET['pagenum']) : 1;
         
	$settings=get_option('rjg_settings');
	
        
        $limit = $settings['page_size'];
        $offset = ( $pagenum - 1 ) * $limit;
        
	$rand_Num_td = uniqid ( 'divSliderMain' );
	$rand_var_name = uniqid ( 'rand_' );
        $target = uniqid ( 'target'.rand(1,2000000) );
	
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	// $settings=get_option('thumbnail_slider_settings');
	
	$uploads = wp_upload_dir ();
	$baseDir = $uploads ['basedir'];
	$baseDir = str_replace ( "\\", "/", $baseDir );
	$pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery';
	$baseurl = $uploads ['baseurl'];
	$baseurl .= '/wp-responsive-photo-gallery/';
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	$randOmeAlbName = uniqid ( 'alb_' );
	$randOmeRel = uniqid ( 'rel_' );
        $randOmVlBox = uniqid('video_lbox_');
        $vNonce = wp_create_nonce('vNonce');
        $url = plugin_dir_url(__FILE__);
        $loaderImg = $url . 'images/bx_loader.gif';
        $imageMargin=$settings['imageMargin'];
        
        $LoadingBackColor=$settings ['BackgroundColor'];
        if(strtolower($LoadingBackColor)=='none'){
            $LoadingBackColor='#ffffff';
        }
       ?>
                <style>#<?php echo $rand_var_name;?>{background-color:<?php echo $LoadingBackColor;?>;padding-top:<?php echo $imageMargin;?>px;padding-left:<?php echo $imageMargin;?>px;padding-right:<?php echo $imageMargin;?>px;}</style>
                        <div style="width: 100%;">
                            <br/>
                           <div style="float: left; width: 100%;">
                               <table><tr>
                                <td>
                                    <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                                    <div id="fb-root"></div>
                                      <script>(function(d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (d.getElementById(id)) return;
                                        js = d.createElement(s); js.id = id;
                                        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                                        fjs.parentNode.insertBefore(js, fjs);
                                      }(document, 'script', 'facebook-jssdk'));</script>
                                </td>
                                <td>
                                    <a target="_blank" title="Donate" href="http://i13websolution.com/donate-wordpress_image_thumbnail.php">
                                        <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <div style="clear:both">
                            <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/photo-gallery-slideshow-masonry-tiled-gallery/"><?php echo __( 'UPGRADE TO PRO VERSION','wp-responsive-photo-gallery' );?></a></h3></span>
                        </div>  
		        <h2><?php echo __('Masonry Gallery Preview','wp-responsive-photo-gallery');?></h2>
                        
                            <?php if (is_array($settings)) { 
                                
                                ?>
                                 
                                                <div style="clear: both;"></div>
                                                <?php $url = plugin_dir_url(__FILE__); ?>           
                                                 <div style="clear: both;"></div>
                                                    <?php $url = plugin_dir_url(__FILE__); ?>           
                              
                              
                                                        <div class="gallery_wrap0" id="<?php echo $rand_Num_td;?>" >
                                                            
                                                                <div class="gallery_ gallery_0" id="<?php echo $rand_var_name;?>" >
                                        
                                                                <div id="<?php echo $rand_var_name; ?>_overlay_grid" class="overlay_grid" style="background: <?php echo $LoadingBackColor; ?> url('<?php echo $loaderImg; ?>') no-repeat scroll 50% 50%;" ></div>
                                                
                                                                                <?php

                                                                                  $imageheight = $settings ['imageheight'];
                                                                                  $query = "SELECT * FROM " . $wpdb->prefix . "rjg_gallery  order by createdon desc LIMIT $offset, $limit";
                                                                                  $firstChild='firstimg';
                                                                                  $rows = $wpdb->get_results ( $query, 'ARRAY_A' );


                                                                                  if (count ( $rows ) > 0) {

                                                                                      foreach ( $rows as $row ) {

                                                                                              $imagename = $row ['image_name'];
                                                                                              $video_url = $row ['videourl'];
                                                                                              $video_url_org = $row ['murl'];
                                                                                              $Url_vid = @parse_url($video_url_org);


                                                                                             
                                                                                              $relend = '';
                                                                                               $flag=false;
                                                                                                  if (isset($Url_vid['query']) and $Url_vid['query'] != '') {


                                                                                                      parse_str($Url_vid['query'], $get_array);
                                                                                                      if(is_array($get_array) and sizeof($get_array)>0){

                                                                                                         foreach($get_array as $k=>$v){

                                                                                                             if($flag==false){

                                                                                                                 $flag=true;
                                                                                                                 $relend.="?$k=$v";
                                                                                                             }
                                                                                                             else{

                                                                                                                 $relend.="&$k=$v";

                                                                                                             }


                                                                                                         } 


                                                                                                      }



                                                                                                  }

                                                                                              $vtype= $row ['vtype'];
                                                                                              $imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                                                              $imageUploadTo = str_replace ( "\\", "/", $imageUploadTo );
                                                                                              $pathinfo = pathinfo ( $imageUploadTo );
                                                                                              $filenamewithoutextension = $pathinfo ['filename'];

                                                                                              $outputimgmain = $baseurl . $row ['image_name'];
                                                                                              $outputimg=$outputimgmain;
                                                                                              $media_type=$row['media_type'];  
                                                                                              $hoverClass='';
                                                                                              if($media_type=="link")
                                                                                                   $hoverClass="playbtnCss_link";
                                                                                              else if($media_type=="video")
                                                                                                   $hoverClass="playbtnCss_video";
                                                                                               else if($media_type=="image")
                                                                                                    $hoverClass="playbtnCss_zoom";

                                                                                                  $title = "";
                                                                                                  $rowTitle = $row['title'];
                                                                                                  $rowTitle = str_replace("'", "’", $rowTitle);
                                                                                                  $rowTitle = str_replace('"', '”', $rowTitle);

                                                                                                

                                                                                                  $open_link_in = $row['open_link_in'];
                                                                                                  $open_title_link_in = 1;

                                                                                                  if(!$open_title_link_in)
                                                                                                      $openImageInNewTab = '_self';
                                                                                                  else
                                                                                                      $openImageInNewTab = '_blank';

                                                                                                  $embed_url=$row['embed_url'].$relend;
                                                                                                 if($media_type=="video"){

                                                                                                      if (trim($row['title']) != '' and trim($row['videourl']) != '') {

                                                                                                          $title = "<a class='Imglink' target='$openImageInNewTab' href='{$row['videourl']}'>{$rowTitle}</a>";
                                                                                                      
                                                                                                      } else if (trim($row['title']) != '' and trim($row['videourl']) == '') {

                                                                                                          $title = "<a class='Imglink' >{$rowTitle}</a>";
                                                                                                         
                                                                                                      } else {

                                                                                                          if ($row['mdescription'] != '')
                                                                                                              $title = "<div class='clear_description_'>{$row['mdescription']}</div>";

                                                                                                      }
                                                                                                 }
                                                                                                 else if($media_type=="image"){

                                                                                                     if (trim($row['title']) != '' and trim($row['murl']) != '') {

                                                                                                          $title = "<a class='Imglink' target='$openImageInNewTab' href='{$row['murl']}'>{$rowTitle}</a>";
                                                                                                         
                                                                                                      } else if (trim($row['title']) != '' and trim($row['murl']) == '') {

                                                                                                          $title = "<a class='Imglink' >{$rowTitle}</a>";
                                                                                                          
                                                                                                      } else {

                                                                                                          if ($row['mdescription'] != '')
                                                                                                              $title = "<div class='clear_description_'>{$row['mdescription']}</div>";

                                                                                                      }

                                                                                                 }


                                                                                      ?>

                                                                                          <?php if($media_type=='image' or $media_type=='video') :?>

                                                                                                  <?php if ($open_link_in == 1): ?>
                                                                                                     <a data-rel="<?php echo $randOmeRel; ?>"  data-overlay="1" data-type="<?php echo $media_type;?>"  data-title="<?php echo $title; ?>" class="thumbnail_ <?php echo $randOmVlBox; ?> <?php if($media_type=='video'):?>iframe <?php endif;?> "  href="<?php if($media_type=='video'):?><?php echo $embed_url; ?> <?php else:?><?php echo $outputimgmain; ?><?php endif;?>"  >
                                                                                                          <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure> 
                                                                                                      </a>
                                                                                                   <?php else: ?>

                                                                                                       <a   data-type="<?php echo $media_type;?>" data-overlay="1" data-title="<?php echo $title; ?>" class="thumbnail_ "  href="<?php if($media_type=='video'):?><?php echo $embed_url; ?> <?php else:?><?php echo $outputimgmain; ?><?php endif;?>" >
                                                                                                          <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure>
                                                                                                      </a>
                                                                                                   <?php endif;?>

                                                                                           <?php else:?>
                                                                                               <a   data-type="<?php echo $media_type;?>" target='<?php echo $openImageInNewTab;?>' class="thumbnail_ "  href="<?php echo $row['murl']; ?>" >
                                                                                                 <figure class="<?php echo $target;?> figure__" data-title="<?php echo $rowTitle;?>" data-url="<?php echo $outputimgmain;?>"></figure>
                                                                                              </a> 

                                                                                           <?php endif;?>



                                                                                          <?php } ?>   

                                                                               <?php } ?>   
                                                                                  <br style="clear: both;">
                                                                              </div>
                                                                            <?php
                                                                            $total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}rjg_gallery ");
                                                                            $num_of_pages = ceil($total / $limit);
                                                                            $page_links = paginate_links(array(
                                                                                'base' => add_query_arg('pagenum', '%#%'),
                                                                                'format' => '',
                                                                                'prev_text' => __('&laquo;', 'aag'),
                                                                                'next_text' => __('&raquo;', 'aag'),
                                                                                'total' => $num_of_pages,
                                                                                'current' => $pagenum,
                                                                                'prev_next' => true,
                                                                                'type' => 'list',
                                                                                    ));

                                                                            if ($page_links) {
                                                                                  echo '<div class="navigation_grid_rjg" style="margin-bottom:10px;display:table">' . $page_links . '</div>';

                                                                            }
                                                                            ?>    
                                                 
                                                                    <div style="clear:both"></div>
                                                        </div>

                                                        <script>
                                                            
                                                            <?php $uniqId = uniqid(); ?>
                                                             var uniqObj<?php echo $uniqId ?> = jQuery("a[data-rel='<?php echo $randOmeRel; ?>']");

                                                            jQuery(document).ready(function() {



                                                                    jQuery("#<?php echo $rand_var_name;?>").latae({
                                                                        loader : '<?php echo plugins_url( 'images/loader.gif', __FILE__ ) ;?>',
                                                                        max_height:<?php echo $settings ['imageheight'];?>,
                                                                        margin:<?php echo $settings ['imageMargin'];?>,
                                                                        target:'<?php echo $target;?>',
                                                                        init : function() { },
                                                                        loadPicture : function(event, img) {  },
                                                                        resize : function(event, gallery) {  },
                                                                        displayTitle: <?php echo ($settings['show_hover_caption']==1) ?  'true':'false' ?>,
                                                                        displayIcons: <?php echo ($settings['show_hover_icon']==1) ?  'true':'false' ?>
                                                                    });

                                                                jQuery(".<?php echo $randOmVlBox; ?>").fancybox_rjg({

                                                                'overlayColor':'#000000',
                                                                'padding': 3,
                                                                'margin': 20,
                                                                'autoScale': true,
                                                                'autoDimensions':true,
                                                                'uniqObj':uniqObj<?php echo $uniqId; ?>,
                                                                'uniqRel':'<?php echo $randOmeRel; ?>',
                                                                'transitionIn':'fade',
                                                                'transitionOut':'fade',
                                                                'titlePosition': 'outside',
                                                                 'cyclic':true,
                                                                'hideOnContentClick':false,
                                                                'width' : 650,
                                                                'height' : 400,
                                                                 'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                                                                        var currtElem = jQuery('#<?php echo $rand_var_name; ?> a[href="' + currentOpts.href + '"]');
                                                                                var isoverlay = jQuery(currtElem).attr('data-overlay')

                                                                         if (isoverlay == "1" && jQuery.trim(title) != ""){
                                                                                return '<span id="fancybox_rjg-title-over">' + title + '</span>';
                                                                        }
                                                                        else{
                                                                             return '';
                                                                        }

                                                                        }
                                                                });

                                                                jQuery(".page-numbers").show();


                                                        });  
                                                        jQuery("body").delegate("#<?php echo $rand_Num_td; ?> .navigation_grid_rjg ul.page-numbers li a.page-numbers", "click", function(e) {

                                                                jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("width", jQuery("#<?php echo $rand_var_name; ?>").width());
                                                                jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("height", jQuery("#<?php echo $rand_var_name; ?>").height());

                                                                e.preventDefault();
                                                                var data = {
                                                                        'action': 'rjg_get_grid_data_justified_gallery',
                                                                        'page_url': encodeURI(jQuery(this).attr('href')),
                                                                        'grid_id':0,
                                                                        'total_rec':'<?php echo $total; ?>',
                                                                        'vNonce':'<?php echo $vNonce;?>'
                                                                };
                                                                jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {


                                                                jQuery('html, body').animate({
                                                                    scrollTop: jQuery("#<?php echo $rand_Num_td; ?>").offset().top
                                                                }, 800);
                                                                jQuery("#<?php echo $rand_Num_td; ?>").replaceWith(response);
                                                                jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("width", "0px");
                                                                jQuery("#<?php echo $rand_var_name; ?>_overlay_grid").css("height", "0px");


                                                              });



                                                        });



                                                      </script>
                                                                  
                                                                  
                            <?php } ?>
                        </div>
			</div>
	        <div class="clear"></div>
			
                <?php if (is_array($settings)) { ?>

                    <h3><?php echo __('To print this masonry gallery into WordPress Post/Page use below code','wp-responsive-photo-gallery');?></h3>
                            <input type="text" value='[print_masonry_gallery_plus_lightbox ] '
                                    style="width: 400px; height: 30px"
                                    onclick="this.focus(); this.select()" />
                            <div class="clear"></div>
                            <h3><?php echo __('To print this masonry gallery into WordPress theme/template PHP files use below code','wp-responsive-photo-gallery');?></h3>
                    <?php
			$shortcode = '[print_masonry_gallery_plus_lightbox ]';
		    ?>
                    <input type="text" value="&lt;?php echo do_shortcode('<?php echo htmlentities($shortcode, ENT_QUOTES); ?>'); ?&gt;" style="width: 400px; height: 30px" onclick="this.focus(); this.select()" />
                <?php } ?>
                <div class="clear"></div>
 <?php
   }
  function rjg_remove_extra_p_tags($content){

        if(strpos($content, 'rjg_print_masonry_gallery_plus_lightbox_func')!==false){
        
            
            $pattern = "/<!-- rjg_print_masonry_gallery_plus_lightbox_func -->(.*)<!-- end rjg_print_masonry_gallery_plus_lightbox_func -->/Uis"; 
            $content = preg_replace_callback($pattern, function($matches) {


               $altered = str_replace("<p>","",$matches[1]);
               $altered = str_replace("</p>","",$altered);
              
                $altered=str_replace("&#038;","&",$altered);
                $altered=str_replace("&#8221;",'"',$altered);
              

              return @str_replace($matches[1], $altered, $matches[0]);
            }, $content);

              
            
        }
        
        $content = str_replace("<p><!-- rjg_print_masonry_gallery_plus_lightbox_func -->","<!-- rjg_print_masonry_gallery_plus_lightbox_func -->",$content);
        $content = str_replace("<!-- end rjg_print_masonry_gallery_plus_lightbox_func --></p>","<!-- end rjg_print_masonry_gallery_plus_lightbox_func -->",$content);
        
        
        return $content;
  }

  
  function wrthslider_slider_mass_upload_wpresponsivephgallery(){
        
       global $wpdb; 
      
        $uploads = wp_upload_dir ();
        $baseDir = $uploads ['basedir'];
        $baseDir = str_replace ( "\\", "/", $baseDir );
        $pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery/';

      if(isset($_POST) and sizeof($_POST)>0){
      
         if(!check_ajax_referer( 'thumbnail-mass-image','thumbnail_security' )){
          
          wp_die('Security check fail'); 
          
          }  
         if ( ! current_user_can( 'rsp_responsive_photo_gallery_add_image' ) ) {

           wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

         }
         $createdOn=date('Y-m-d h:i:s');
         if(function_exists('date_i18n')){
            
             $createdOn=date_i18n('Y-m-d'.' '.get_option('time_format') ,false,false);
            if(get_option('time_format')=='H:i')
                $createdOn=date('Y-m-d H:i:s',strtotime($createdOn));
             else   
               $createdOn=date('Y-m-d h:i:s',strtotime($createdOn));
         } 
         $attachment_id=(int)$_POST['attachment_id'];
         $photoMeta = wp_get_attachment_metadata( $attachment_id );
        
         $open_link_in=0;
         $enable_light_box_img_desc=0;  
         $imageurl='';
         $title=trim(htmlentities(strip_tags($_POST['imagetitle']),ENT_QUOTES));
         $enable_light_box_img_desc=0;     
        
         if(is_array($photoMeta) and isset($photoMeta['file'])) {
             
                 $fileName=$photoMeta['file'];
                 $phyPath=ABSPATH;
                 $phyPath=str_replace("\\","/",$phyPath);
               
                 $pathArray=pathinfo($fileName);
               
                 $imagename=$pathArray['basename'];
                 $imagename_=$pathArray['filename'];
                 $file_ext=$pathArray['extension'];
                 $imagename=$imagename_.uniqid().".".$file_ext;
                 $upload_dir_n = wp_upload_dir(); 
                 $upload_dir_n=$upload_dir_n['basedir'];
                 $fileUrl=$upload_dir_n.'/'.$fileName;
                 $fileUrl=str_replace("\\","/",$fileUrl);
                 $wpcurrentdir=dirname(__FILE__);
                 $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                 $imageUploadTo=$pathToImagesFolder."/".$imagename;
                 @copy($fileUrl, $imageUploadTo);
                 
                  if(!file_exists($imageUploadTo)){
                    rs_photogallery_save_image_remote_lbox($fileUrl,$imageUploadTo);
                   }
                           
          }
      
          
          $query = "INSERT INTO ".$wpdb->prefix."gv_responsive_slider (title, image_name,createdon) 
                            VALUES ('$title','$imagename','$createdOn')";

           $wpdb->query($query); 
                            
         
          
         
      }  

 }
 
  function wrthslider_slider_mass_upload_wpresponsivephgalleryms(){
        
       global $wpdb; 
      
        $uploads = wp_upload_dir ();
        $baseDir = $uploads ['basedir'];
        $baseDir = str_replace ( "\\", "/", $baseDir );
        $pathToImagesFolder = $baseDir . '/wp-responsive-photo-gallery/';

      if(isset($_POST) and sizeof($_POST)>0){
      
         if(!check_ajax_referer( 'thumbnail-mass-image','thumbnail_security' )){
          
          wp_die('Security check fail'); 
          
          }  
         if ( ! current_user_can( 'rsp_masonry_gallery_add_media' ) ) {

           wp_die( __( "Access Denied", "wp-responsive-photo-gallery" ) );

         }
         $createdOn=date('Y-m-d h:i:s');
         if(function_exists('date_i18n')){
            
             $createdOn=date_i18n('Y-m-d'.' '.get_option('time_format') ,false,false);
            if(get_option('time_format')=='H:i')
                $createdOn=date('Y-m-d H:i:s',strtotime($createdOn));
             else   
               $createdOn=date('Y-m-d h:i:s',strtotime($createdOn));
         } 
         $attachment_id=(int)$_POST['attachment_id'];
         $photoMeta = wp_get_attachment_metadata( $attachment_id );
        
         $open_link_in=0;
         $enable_light_box_img_desc=0;  
         $imageurl='';
         $title=trim(htmlentities(strip_tags($_POST['imagetitle']),ENT_QUOTES));
         $enable_light_box_img_desc=0;     
        
         if(is_array($photoMeta) and isset($photoMeta['file'])) {
             
                 $fileName=$photoMeta['file'];
                 $phyPath=ABSPATH;
                 $phyPath=str_replace("\\","/",$phyPath);
               
                 $pathArray=pathinfo($fileName);
               
                 $imagename=$pathArray['basename'];
                 $imagename_=$pathArray['filename'];
                 $file_ext=$pathArray['extension'];
                 $imagename=$imagename_.uniqid().".".$file_ext;
                 $upload_dir_n = wp_upload_dir(); 
                 $upload_dir_n=$upload_dir_n['basedir'];
                 $fileUrl=$upload_dir_n.'/'.$fileName;
                 $fileUrl=str_replace("\\","/",$fileUrl);
                 $wpcurrentdir=dirname(__FILE__);
                 $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                 $imageUploadTo=$pathToImagesFolder."/".$imagename;
                 @copy($fileUrl, $imageUploadTo);
                 
                  if(!file_exists($imageUploadTo)){
                    rs_photogallery_save_image_remote_lbox($fileUrl,$imageUploadTo);
                   }
                           
          }
      
          
          $query = "INSERT INTO " . $wpdb->prefix . "rjg_gallery 
                                		(media_type,image_name,title,murl,open_link_in,
                                                HdnMediaSelection,createdon) 
                                                VALUES ('image','$imagename','$title','',1,'$fileUrl', '$createdOn')";
          
           $wpdb->query($query); 
                            
         
          
         
      }  

 }
 
  add_filter('widget_text_content', 'rjg_remove_extra_p_tags', 999);
  add_filter('the_content', 'rjg_remove_extra_p_tags', 999);
  

  
function i13_rpg_render_block_defaults($block_content, $block) { 

    $block_content=wrpg_remove_extra_p_tags($block_content);
    $block_content=rjg_remove_extra_p_tags($block_content);
    return $block_content; 

}


add_filter( 'render_block', 'i13_rpg_render_block_defaults', 10, 2 );
