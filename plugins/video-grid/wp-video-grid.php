<?php
/*
 * Plugin Name: Video Grid With Lightbox 
 * Plugin URI:https://www.i13websolution.com/product/wordpress-responsive-video-grid-pro/
 * Author URI:http://www.i13websolution.com
 * Description:This is beautiful responsive video grid with responsive lightbox.Add any number of video from admin panel. 
 * Author:I Thirteen Web Solution 
 * Version:1.23
 * Text Domain:video-grid
 */

add_filter ( 'widget_text', 'do_shortcode' );
add_action ( 'admin_menu', 'responsive_video_grid_add_admin_menu' );
//add_action ( 'admin_init', 'responsive_video_grid_add_admin_init' );
register_activation_hook ( __FILE__, 'install_responsive_video_grid' );
register_deactivation_hook(__FILE__,'rvg_video_grid_remove_access_capabilities');

add_action ( 'wp_enqueue_scripts', 'responsive_video_grid_load_styles_and_js' );
add_shortcode ( 'print_responsive_video_grid', 'print_responsive_video_grid_func' );
add_action ( 'admin_notices', 'responsive_video_grid_admin_notices' );

add_action( 'wp_ajax_check_file_exist_grid', 'check_file_exist_grid_callback' );
add_action( 'wp_ajax_get_youtube_info_grid', 'get_youtube_info_grid_callback' );

add_action('plugins_loaded', 'vg_load_lang_for_responsive_video_grid');
add_filter( 'user_has_cap', 'rvg_video_grid_admin_cap_list' , 10, 4 );

function vg_load_lang_for_responsive_video_grid() {
            
    load_plugin_textdomain( 'video-grid', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    add_filter( 'map_meta_cap',  'map_rvg_video_grid_meta_caps', 10, 4 );
 }
 
function rvg_video_grid_admin_cap_list($allcaps, $caps, $args, $user){
        
        
    if ( ! in_array( 'administrator', $user->roles ) ) {

        return $allcaps;
    }
    else{

        if(!isset($allcaps['rvg_video_grid_settings'])){

            $allcaps['rvg_video_grid_settings']=true;
        }

        if(!isset($allcaps['rvg_video_grid_view_videos'])){

            $allcaps['rvg_video_grid_view_videos']=true;
        }
        if(!isset($allcaps['rvg_video_grid_add_video'])){

            $allcaps['rvg_video_grid_add_video']=true;
        }
        if(!isset($allcaps['rvg_video_grid_edit_video'])){

            $allcaps['rvg_video_grid_edit_video']=true;
        }
        if(!isset($allcaps['rvg_video_grid_delete_video'])){

            $allcaps['rvg_video_grid_delete_video']=true;
        }
        if(!isset($allcaps['rvg_video_grid_preview'])){

            $allcaps['rvg_video_grid_preview']=true;
        }
        

    }

    return $allcaps;

}

function map_rvg_video_grid_meta_caps( array $caps, $cap, $user_id, array $args  ) {
        
       
        if ( ! in_array( $cap, array(
                                      'rvg_video_grid_settings',
                                      'rvg_video_grid_view_videos',
                                      'rvg_video_grid_add_video', 
                                      'rvg_video_grid_edit_video',
                                      'rvg_video_grid_delete_video',
                                      'rvg_video_grid_preview',
                                      
                                    ), true ) ) {
            
			return $caps;
         }

       
         
   
        $caps = array();

        switch ( $cap ) {
            
                 case 'rvg_video_grid_settings':
                        $caps[] = 'rvg_video_grid_settings';
                        break;
              
                case 'rvg_video_grid_view_videos':
                        $caps[] = 'rvg_video_grid_view_videos';
                        break;
              
                case 'rvg_video_grid_add_video':
                        $caps[] = 'rvg_video_grid_add_video';
                        break;
              
                case 'rvg_video_grid_edit_video':
                        $caps[] = 'rvg_video_grid_edit_video';
                        break;
              
                case 'rvg_video_grid_delete_video':
                        $caps[] = 'rvg_video_grid_delete_video';
                        break;
                    
                case 'rvg_video_grid_preview':
                        $caps[] = 'rvg_video_grid_preview';
                        break;
              
                default:
                        
                        $caps[] = 'do_not_allow';
                        break;
        }

      
     return apply_filters( 'rvg_video_grid_meta_caps', $caps, $cap, $user_id, $args );
}

function rvg_video_grid_add_access_capabilities() {
     
    // Capabilities for all roles.
    $roles = array( 'administrator' );
    foreach ( $roles as $role ) {
        
            $role = get_role( $role );
            if ( empty( $role ) ) {
                    continue;
            }
         
            
            if(!$role->has_cap( 'rvg_video_grid_settings' ) ){
            
                    $role->add_cap( 'rvg_video_grid_settings' );
            }
            
            if(!$role->has_cap( 'rvg_video_grid_view_videos' ) ){
            
                    $role->add_cap( 'rvg_video_grid_view_videos' );
            }
         
            
            if(!$role->has_cap( 'rvg_video_grid_add_video' ) ){
            
                    $role->add_cap( 'rvg_video_grid_add_video' );
            }
            
            if(!$role->has_cap( 'rvg_video_grid_edit_video' ) ){
            
                    $role->add_cap( 'rvg_video_grid_edit_video' );
            }
            
            if(!$role->has_cap( 'rvg_video_grid_delete_video' ) ){
            
                    $role->add_cap( 'rvg_video_grid_delete_video' );
            }
            
            if(!$role->has_cap( 'rvg_video_grid_preview' ) ){
            
                    $role->add_cap( 'rvg_video_grid_preview' );
            }
            
            
         
    }
    
    $user = wp_get_current_user();
    $user->get_role_caps();
    
}

function rvg_video_grid_remove_access_capabilities(){
    
    global $wp_roles;

    if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
    }

    foreach ( $wp_roles->roles as $role => $details ) {
            $role = $wp_roles->get_role( $role );
            if ( empty( $role ) ) {
                    continue;
            }

            $role->remove_cap( 'rvg_video_grid_settings');
            $role->remove_cap( 'rvg_video_grid_view_videos');
            $role->remove_cap( 'rvg_video_grid_add_video');
            $role->remove_cap( 'rvg_video_grid_edit_video');
            $role->remove_cap( 'rvg_video_grid_delete_video');
            $role->remove_cap( 'rvg_video_grid_preview');
         

    }

    // Refresh current set of capabilities of the user, to be able to directly use the new caps.
    $user = wp_get_current_user();
    $user->get_role_caps();
    
}

function get_youtube_info_grid_callback() {

    if (isset($_POST) and is_array($_POST) and isset($_POST['url'])) {

        $retrieved_nonce = '';

        if (isset($_POST['vNonce']) and $_POST['vNonce'] != '') {

            $retrieved_nonce = sanitize_text_field($_POST['vNonce']);
        }
        if (!wp_verify_nonce($retrieved_nonce, 'vNonce')) {


            wp_die('Security check fail');
        }

        if (isset($_POST) and is_array($_POST) and isset($_POST['url'])) {

            $vid = htmlentities(sanitize_text_field($_POST['vid']),ENT_QUOTES);
            $url = esc_url_raw($_POST['url']);
            $output=  wp_remote_retrieve_body( wp_remote_get( $url ) );
            $output=json_decode($output);


            $videoInfo=  wp_remote_retrieve_body( wp_remote_get( "https://www.youtube.com/watch?v=$vid"));
                
            $pattern = '/\\"shortDescription\\":(.*)\\"isCrawlable\\"/Uis'; 
            $vinfo='';
              if(preg_match_all($pattern, $videoInfo, $matches)) {
                if(is_array($matches) and isset($matches[1])){
                    if(isset($matches[1][0])){
                        $vinfo= stripcslashes($matches[1][0]);
                    }
                }
              }

            $breaks = array("<br />","<br>","<br/>");  
            $vinfo = str_ireplace($breaks, "\r\n", $vinfo); 
            $vinfo=trim($vinfo);
            $vinfo=trim($vinfo,',');
            $vinfo=trim($vinfo,'"');
            $vinfo=rtrim($vinfo,'"');
            $vinfo=ltrim($vinfo,'"');
            $vinfo=trim($vinfo,'"');
            $vinfo=trim($vinfo,',');

            $return=array();
            if(is_object($output)){

             $return['title']=$output->title;
             $return['thumbnail_url']=$output->thumbnail_url;
             $return['description']=$vinfo;

           }

            echo json_encode($return);
            exit;
        }
    }
}

function vg_save_image($url,$saveto){
    
     $raw = wp_remote_retrieve_body( wp_remote_get( $url ) );
    
    if(file_exists($saveto)){
        @unlink($saveto);
    }
    $fp = @fopen($saveto,'x');
    @fwrite($fp, $raw);
    @fclose($fp);
    
}

function check_file_exist_grid_callback() {
	
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
	//echo die;
	
}


function responsive_video_grid_admin_notices() {
    
	if (is_plugin_active ( 'wp-video-grid/wp-video-grid.php' )) {
		
		$uploads = wp_upload_dir();
		$baseDir = $uploads ['basedir'];
		$baseDir = str_replace ( "\\", "/", $baseDir );
		$pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
		
		if (file_exists ( $pathToImagesFolder ) and is_dir ( $pathToImagesFolder )) {
			
			if (! is_writable ( $pathToImagesFolder )) {
				echo "<div class='updated'><p>".__( 'Video Grid With Lightbox is active but does not have write permission on','video-grid')."</p><p><b>" . $pathToImagesFolder . "</b>".__( 'directory.Please allow write permission.','video-grid')."</p></div> ";
			}
		} else {
			
			wp_mkdir_p ( $pathToImagesFolder );
			if (! file_exists ( $pathToImagesFolder ) and ! is_dir ( $pathToImagesFolder )) {
				echo "<div class='updated'><p>".__( 'Video Grid With Lightbox is active but plugin does not have permission to create directory','video-grid')."</p><p><b>" . $pathToImagesFolder . "</b> ".__( '.Please create wp-responsive-video-grid directory inside upload directory and allow write permission.','video-grid')."</p></div> ";
			}
		}
	}
        
}
function responsive_video_grid_load_styles_and_js() {
    
	if (! is_admin()) {
		
		
                wp_register_style('wp-video-grid-lighbox-style', plugins_url('/css/wp-video-grid-lighbox-style.css', __FILE__),array(),'1.17');
                wp_register_style('vl-box-grid-css', plugins_url('/css/vl-box-grid-css.css', __FILE__),array(),'1.11');
                wp_register_script('vl-grid-js', plugins_url('/js/vl-grid-js.js', __FILE__),array('jquery'),'1.11');
                wp_register_script('v_grid', plugins_url('/js/v_grid.js', __FILE__),array('jquery'),'1.11');

                
	}
}
function install_responsive_video_grid() {
	global $wpdb;
	$table_name = $wpdb->prefix . "responsive_video_grid";
	
	$sql = "CREATE TABLE " . $table_name . " (
        `id` int(10)  NOT NULL AUTO_INCREMENT,
        `vtype` varchar(50)  NOT NULL,
        `vid` varchar(500) NOT NULL,
        `video_url` varchar(1000)  DEFAULT NULL,
        `embed_url` varchar(300)  NOT NULL,
        `HdnMediaSelection` varchar(500)  NOT NULL,
        `image_name` varchar(500)  NOT NULL,
        `videotitle` varchar(1000)  NOT NULL,
        `videotitleurl` varchar(1000)  DEFAULT NULL,
         `video_description` text  DEFAULT NULL,
        `video_order` int(11) NOT NULL DEFAULT '0',
        `open_link_in` tinyint(1) NOT NULL DEFAULT '1',
        `enable_light_box_video_desc` tinyint(1) NOT NULL DEFAULT '1',
        `createdon` datetime NOT NULL,
        `slider_id` int(10)  NOT NULL DEFAULT '1',
         PRIMARY KEY (`id`)
        );
        ";
        
         $responsive_video_grid_settings=array(
                                                    'display_video_lightbox' => '1',
                                                    'scollerBackground'=>'#FFFFFF',
                                                      'resize_images'=>'1'
                                                    
                                                );
               
         
               $existingopt=get_option('responsive_video_grid_settings');
               if(!is_array($existingopt)){

                    update_option('responsive_video_grid_settings',$responsive_video_grid_settings);

                }
                else{
                    
                    $flag=false;
                    if(!isset($existingopt['resize_images'])){

                       $flag=true; 
                       $existingopt['resize_images']='1'; 

                    }
                  
                    if($flag==true){
                        
                       update_option('responsive_video_grid_settings', $existingopt); 
                       
                      }
                }
           
                
                require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta ( $sql );

                $uploads = wp_upload_dir();
                $baseDir = $uploads ['basedir'];
                $baseDir = str_replace ( "\\", "/", $baseDir );
                $pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
                wp_mkdir_p ( $pathToImagesFolder );
                rvg_video_grid_add_access_capabilities();
        
        
        
}

function responsive_video_grid_add_admin_menu() {
    
	$hook_suffix=add_menu_page ( __ ( 'Video Grid Plus Lightbox','video-grid' ), __ ( 'Video Grid With Lightbox','video-grid' ), 'rvg_video_grid_settings', 'video_grid_with_lightbox', 'video_grid_with_lightbox_admin_options_func' );
	$hook_suffix=add_submenu_page ( 'video_grid_with_lightbox', __ ( 'Gallery Settings','video-grid' ), __ ( 'Gallery Settings','video-grid' ), 'rvg_video_grid_settings', 'video_grid_with_lightbox', 'video_grid_with_lightbox_admin_options_func' );
	$hook_suffix_image=add_submenu_page ( 'video_grid_with_lightbox', __ ( 'Manage Videos','video-grid' ), __ ( 'Manage Videos','video-grid' ), 'rvg_video_grid_view_videos', 'video_grid_with_lightbox_video_management', 'video_grid_with_lightbox_video_management_func' );
	$hook_suffix_prev=add_submenu_page ( 'video_grid_with_lightbox', __ ( 'Preview Gallery','video-grid' ), __ ( 'Preview Gallery','video-grid' ), 'rvg_video_grid_preview', 'video_grid_with_lightbox_video_preview', 'video_grid_with_lightbox_video_preview_func' );
	
	add_action( 'load-' . $hook_suffix , 'responsive_video_grid_add_admin_init' );
	add_action( 'load-' . $hook_suffix_image , 'responsive_video_grid_add_admin_init' );
	add_action( 'load-' . $hook_suffix_prev , 'responsive_video_grid_add_admin_init' );
	
}
function responsive_video_grid_add_admin_init() {
    
	$url = plugin_dir_url ( __FILE__ );
	
	wp_enqueue_style ( 'wp-video-grid-lighbox-style', plugins_url ( '/css/wp-video-grid-lighbox-style.css', __FILE__ ) );
	wp_enqueue_style ( 'vl-box-grid-css', plugins_url ( '/css/vl-box-grid-css.css', __FILE__ ) );
	wp_enqueue_style ( 'admin_css', plugins_url ( '/css/admin_css.css', __FILE__ ) );
	wp_enqueue_script ( 'jquery' );
	wp_enqueue_script ( 'jquery.validate', $url . 'js/jquery.validate.js' );
	wp_enqueue_script ( 'vl-grid-js', plugins_url ( '/js/vl-grid-js.js', __FILE__ ) );
	wp_enqueue_script ( 'v_grid', plugins_url ( '/js/v_grid.js', __FILE__ ) );
	
	responsive_video_grid_admin_scripts_init();
}


 function video_grid_with_lightbox_admin_options_func(){
       
      if ( ! current_user_can( 'rvg_video_grid_settings' ) ) {

              wp_die( __( "Access Denied", "video-grid" ) );

         }   
       
     if(isset($_POST['btnsave'])){
         
         if (!check_admin_referer('action_image_add_edit', 'add_edit_image_nonce')) {

                wp_die(__( 'Security check fail','video-grid'));
            }
           
            
         if(isset($_POST['display_video_lightbox']))
           $display_video_lightbox=true;  
        else
           $display_video_lightbox=false;  

         
         $scollerBackground=trim(htmlentities(sanitize_text_field($_POST['scollerBackground']),ENT_QUOTES));
         $resize_images = htmlentities(sanitize_text_field($_POST['resize_images']),ENT_QUOTES);
                           
         
         $options=array();
         $options['display_video_lightbox']=$display_video_lightbox;  
         $options['scollerBackground']=$scollerBackground;  
         $options['resize_images']=$resize_images;  
        
         
         $settings=update_option('responsive_video_grid_settings',$options); 
         $responsive_video_grid_messages=array();
         $responsive_video_grid_messages['type']='succ';
         $responsive_video_grid_messages['message']=__( 'Settings saved successfully.','video-grid');
         update_option('responsive_video_grid_messages', $responsive_video_grid_messages);

        
         
     }  
      $settings=get_option('responsive_video_grid_settings');

    if(!isset($settings['resize_images'])){

        $settings['resize_images']=1;

    } 

?>      
<div id="poststuff" > 
   <div id="post-body" class="metabox-holder columns-2" >  
      <div id="post-body-content">
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
                          <a target="_blank" title="Donate" href="https://www.i13websolution.com/donate-wordpress_image_thumbnail.php">
                              <img id="<?php echo __( 'help us for free plugin','video-grid');?>" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ ) ;?>" border="0" alt="<?php echo __( 'help us for free plugin','video-grid');?>" title="<?php echo __( 'help us for free plugin','video-grid');?>">
                          </a>
                      </td>
                  </tr>
              </table>
                <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/wordpress-responsive-video-grid-pro.html"><?php echo __( 'UPGRADE TO PRO VERSION','video-grid');?></a></h3></span>
              <?php
                  $messages=get_option('responsive_video_grid_messages'); 
                  $type='';
                  $message='';
                  if(isset($messages['type']) and $messages['type']!=""){

                      $type=$messages['type'];
                      $message=$messages['message'];

                  }  


                  if(trim($type)=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                  else if(trim($type)=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}
       

                  update_option('responsive_video_grid_messages', array());     
              ?>      
              
              <h2><?php echo __( 'Gallery Grid Settings','video-grid');?></h2>
              <div id="poststuff">
                  <div id="post-body" class="metabox-holder columns-2">
                      <div id="post-body-content">
                          <form method="post" action="" id="scrollersettiings" name="scrollersettiings" >

                             
                              <div class="stuffbox" id="namediv" style="width:100%;">
                                  <h3><label ><?php echo __( 'Play Video In Lightbox ?','video-grid');?></label></h3>
                                  <div class="inside">
                                      <table>
                                          <tr>
                                              <td>
                                                  <input type="checkbox" id="display_video_lightbox" size="30" name="display_video_lightbox" value="" <?php if($settings['display_video_lightbox']==true){echo "checked='checked'";} ?> style="width:20px;">&nbsp;Play Video in Lightbox ? 
                                                  <div style="clear:both"></div>
                                                  <div></div>
                                              </td>
                                          </tr>
                                      </table>
                                      <div style="clear:both"></div>

                                  </div>
                              </div>
                              <div class="stuffbox" id="namediv" style="width:100%;">
                                  <h3><label><?php echo __( 'Video Grid Background color','video-grid');?></label></h3>
                                  <div class="inside">
                                      <table>
                                          <tr>
                                              <td>
                                                  <input type="text" id="scollerBackground" size="30" name="scollerBackground" value="<?php echo $settings['scollerBackground']; ?>" style="width:100px;">
                                                  <div style="clear:both"></div>
                                                  <div></div>
                                              </td>
                                          </tr>
                                      </table>

                                      <div style="clear:both"></div>
                                  </div>
                              </div>
                             
                              <div class="stuffbox" id="namediv" style="width: 100%;">
                            <h3>
                                    <label><?php echo __('Resize image physically?','video-grid');?></label>
                            </h3>
                            <div class="inside">
                                    <table>
                                            <tr>
                                                    <td><input style="width: 20px;" type='radio'
                                                            <?php
                                                            if ($settings ['resize_images'] == true) {
                                                                    echo "checked='checked'";
                                                            }
                                                            ?>
                                                            name='resize_images' value='1'><?php echo __('yes','video-grid');?> &nbsp;<input
                                                            style="width: 20px;" type='radio' name='resize_images'
                                                            <?php
                                                            if ($settings ['resize_images'] == false) {
                                                                    echo "checked='checked'";
                                                            }
                                                            ?>
                                                            value='0'><?php echo __('No','video-grid');?> 
                                                            <div style="clear: both"></div>
                                                            <div></div></td>
                                            </tr>
                                    </table>
                                    <div style="clear: both"></div>
                            </div>
                    </div>
                               <?php wp_nonce_field('action_image_add_edit', 'add_edit_image_nonce'); ?>   
                              <input type="submit"  name="btnsave" id="btnsave" value="<?php echo __( 'Save Changes','video-grid');?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="<?php echo __( 'Cancle','video-grid');?>" class="button-primary" onclick="location.href='admin.php?page=video_grid_with_lightbox_video_management'">

                          </form> 
                          <script type="text/javascript">

                              jQuery(document).ready(function() {

                                      jQuery("#scrollersettiings").validate({
                                              rules: {
                                                  scollerBackground:{
                                                      required:true,
                                                      maxlength:7  
                                                  }
                                                 

                                              },
                                              errorClass: "image_error",
                                              errorPlacement: function(error, element) {
                                                  error.appendTo( element.next().next());
                                              } 


                                      })
                                      
                                         jQuery('#scollerBackground').wpColorPicker();
                              });

                          </script> 

                      </div>
                  </div>
              </div>  
          </div>      
      </div>
 <div id="postbox-container-1" class="postbox-container" > 

          <div class="postbox"> 
              <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','video-grid');?></h3> 
              <div class="inside">
                  <center><a href="https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank">
                          <img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__);?>" width="250" height="250">
                      </a></center>

                  <div style="margin:10px 5px">

                  </div>
              </div></div>
              <div class="postbox"> 
                <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon','video-grid');?></h3> 
                    <div class="inside">
                        <center><a href="https://goo.gl/OJBuHT" target="_blank">
                                <img src="<?php echo plugins_url( 'images/g-suite-promo-code-4.png', __FILE__ );?>" width="250" height="250" border="0">
                            </a></center>
                        <div style="margin:10px 5px">
                        </div>
                    </div>
                    
                </div>

      </div>      
     
     <div class="clear"></div>
  </div>  
 </div> 
<?php
   } 
   
function video_grid_with_lightbox_video_management_func() {
    
	$action = 'gridview';
	global $wpdb;
	
	
	
	if (isset ( $_GET ['action'] ) and $_GET ['action'] != '') {
		
		$action = trim ( sanitize_text_field($_GET ['action'] ));
	}
	?>

        <?php
	if (strtolower ( $action ) == strtolower ( 'gridview' )) {
		
		$wpcurrentdir = dirname ( __FILE__ );
		$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
		
		$uploads = wp_upload_dir();
		$baseurl = $uploads ['baseurl'];
		$baseurl .= '/wp-responsive-video-grid/';
                
                 if ( ! current_user_can( 'rvg_video_grid_view_videos' ) ) {

                        wp_die( __( "Access Denied", "video-grid" ) );

                   }  
		?> 
            <div class="wrap">
		
             <?php
		$messages = get_option ( 'responsive_video_grid_messages' );
		$type = '';
		$message = '';
		if (isset ( $messages ['type'] ) and $messages ['type'] != "") {
			
			$type = $messages ['type'];
			$message = $messages ['message'];
		}
		
		 if(trim($type)=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                else if(trim($type)=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}
       
		
		update_option ( 'responsive_video_grid_messages', array() );
		?>
                <div id="poststuff" > 
         <div id="post-body" class="metabox-holder columns-2" >  
          <div id="post-body-content">
          <div class="wrap">
                <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/wordpress-responsive-video-grid-pro.html"><?php echo __( 'UPGRADE TO PRO VERSION','video-grid');?></a></h3></span>
                <div style="width: 100%;">
			<div style="float: left; width: 100%;">
				<div class="icon32 icon32-posts-post" id="icon-edit">
					<br>
				</div>
				<h1>
					<?php echo __( 'Videos','video-grid');?><a class="button add-new-h2" href="admin.php?page=video_grid_with_lightbox_video_management&action=addedit"><?php echo __( 'Add New','video-grid');?></a>
				</h1>
				<br />

				<form method="POST" action="admin.php?page=video_grid_with_lightbox_video_management&action=deleteselected" id="posts-filter" onkeypress="return event.keyCode != 13;">
					<div class="alignleft actions">
						<select name="action_upper" id="action_upper">
							<option selected="selected" value="-1"><?php echo __( 'Bulk Actions','video-grid');?></option>
							<option value="delete"><?php echo __( 'Delete','video-grid');?></option>
						</select> 
                                                 <input type="submit" value="<?php echo __( 'Apply','video-grid');?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
					</div>
					<br class="clear">
                                        
                                        <?php
                                            
                                            $sliderid="0";
                                             if(isset($_GET['sliderid']) and $_GET['sliderid']!=""){
                                              $sliderid=intval(trim($_GET['sliderid']));   
                                             }

                                             $setacrionpage='admin.php?page=video_grid_with_lightbox_video_management';

                                             if(isset($_GET['order_by']) and $_GET['order_by']!=""){
                                              $setacrionpage.='&order_by='.esc_html(sanitize_text_field($_GET['order_by']));   
                                             }

                                             if(isset($_GET['order_pos']) and $_GET['order_pos']!=""){
                                              $setacrionpage.='&order_pos='.esc_html(sanitize_text_field($_GET['order_pos']));   
                                             }

                                             $seval="";
                                             if(isset($_GET['search_term']) and $_GET['search_term']!=""){
                                              $seval=trim(esc_html(sanitize_text_field($_GET['search_term'])));   
                                             }
                                             $search_term_='';
                                            if(isset($_GET['search_term'])){

                                               $search_term_='&search_term='.esc_html(sanitize_text_field($_GET['search_term']));
                                            }

                                         ?>
					<br class="clear">
                                            <?php
                                                global $wpdb;
                                                $settings=get_option('responsive_video_grid_settings');


                                                $order_by='id';
                                                $order_pos="asc";

                                                if(isset($_GET['order_by'])){

                                                   $order_by=esc_html(sanitize_text_field($_GET['order_by'])); 
                                                }

                                                if(isset($_GET['order_pos'])){

                                                   $order_pos=esc_html(sanitize_text_field($_GET['order_pos'])); 
                                                }
                                                 $search_term='';
                                                if(isset($_GET['search_term'])){

                                                   $search_term= esc_sql(sanitize_text_field($_GET['search_term']));
                                                }

                                                $query = "SELECT * FROM " . $wpdb->prefix . "responsive_video_grid ";
                                                $queryCount = "SELECT count(*) FROM " . $wpdb->prefix . "responsive_video_grid ";
                                                if($search_term!=''){
                                                   $query.=" where id like '%$search_term%' or videotitle like '%$search_term%' "; 
                                                   $queryCount.=" where id like '%$search_term%' or videotitle like '%$search_term%' "; 
                                                }

                                                $order_by=sanitize_text_field(sanitize_sql_orderby($order_by));
                                                $order_pos=sanitize_text_field(sanitize_sql_orderby($order_pos));

                                                $query.=" order by $order_by $order_pos";


                                                 $rowsCount=$wpdb->get_var($queryCount);

                                                ?>
                                                <div style="padding-top:5px;padding-bottom:5px">
                                                    <b><?php echo __( 'Search','best-testimonial-slider');?> : </b>
                                                      <input type="text" value="<?php echo $seval;?>" id="search_term" name="search_term">&nbsp;
                                                      <input type='button'  value='<?php echo __( 'Search','video-grid');?>' name='searchusrsubmit' class='button-primary' id='searchusrsubmit' onclick="SearchredirectTO();" >&nbsp;
                                                      <input type='button'  value='<?php echo __( 'Reset Search','video-grid');?>' name='searchreset' class='button-primary' id='searchreset' onclick="ResetSearch();" >
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
                                               
					    <br/>                       
                                            <div id="no-more-tables">
						<table cellspacing="0" id="gridTbl"
							class="table-bordered table-striped table-condensed cf">
							<thead>
								<tr>
									<th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
									<?php if($order_by=="id" and $order_pos=="asc"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Id','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                        <?php else:?>
                                                                            <?php if($order_by=="id"):?>
                                                                        <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Id','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <th><a href="<?php echo $setacrionpage;?>&order_by=id&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Id','video-grid');?></a></th>
                                                                            <?php endif;?>    
                                                                        <?php endif;?> 
									<?php if($order_by=="vtype" and $order_pos=="asc"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=vtype&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Video Type','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                        <?php else:?>
                                                                            <?php if($order_by=="vtype"):?>
                                                                                <th><a href="<?php echo $setacrionpage;?>&order_by=vtype&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Video Type','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <th><a href="<?php echo $setacrionpage;?>&order_by=vtype&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Video Type','video-grid');?></a></th>
                                                                            <?php endif;?>    
                                                                        <?php endif;?>  
									<?php if($order_by=="videotitle" and $order_pos=="asc"):?>
                                                                             <th><a href="<?php echo $setacrionpage;?>&order_by=videotitle&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Title','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                        <?php else:?>
                                                                            <?php if($order_by=="videotitle"):?>
                                                                                <th><a href="<?php echo $setacrionpage;?>&order_by=videotitle&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <th><a href="<?php echo $setacrionpage;?>&order_by=videotitle&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','video-grid');?></a></th>
                                                                            <?php endif;?>    
                                                                        <?php endif;?> 
									<th><span></span></th>
									<?php if($order_by=="createdon" and $order_pos=="asc"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Published On','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                                                            <?php else:?>
                                                                                <?php if($order_by=="createdon"):?>
                                                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','video-grid');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                                                                <?php else:?>
                                                                                    <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','video-grid');?></a></th>
                                                                                <?php endif;?>    
                                                                            <?php endif;?>  
									<th><span><?php echo __( 'Edit','video-grid');?></span></th>
									<th><span><?php echo __( 'Delete','video-grid');?></span></th>
								</tr>
							</thead>

							<tbody id="the-list">
                                                        <?php
								if ($rowsCount > 0) {
									
									global $wp_rewrite;
									$rows_per_page = 15;
									
									$current = (isset ( $_GET ['paged'] )) ? (intval($_GET ['paged'])) : 1;
									$pagination_args = array (
											'base' => @add_query_arg ( 'paged', '%#%' ),
											'format' => '',
											'total' => ceil ( $rowsCount/ $rows_per_page ),
											'current' => $current,
											'show_all' => false,
											'type' => 'plain' 
									);
									
					        			$offset = ($current - 1) * $rows_per_page;
                                                                        $query.=" limit $offset, $rows_per_page";
                                                                        $rows = $wpdb->get_results ( $query,ARRAY_A);
									$delRecNonce = wp_create_nonce('delete_image');  
									foreach($rows as $row) {
										
										
										$id = $row ['id'];
										$editlink = "admin.php?page=video_grid_with_lightbox_video_management&action=addedit&id=$id";
										$deletelink = "admin.php?page=video_grid_with_lightbox_video_management&action=delete&id=$id&nonce=$delRecNonce";
										
										$outputimgmain = $baseurl . $row ['image_name'].'?rand='.  rand(0, 5000);
										?>
                                                                    <tr valign="top">
									<td class="alignCenter check-column" data-title="<?php echo __( 'Select Record','video-grid');?>">
                                                                            <input type="checkbox" value="<?php echo $row['id'] ?>" name="thumbnails[]">
                                                                        </td>
									<td data-title="<?php echo __( 'Id','video-grid');?>" class="alignCenter"><?php echo $row['id']; ?></td>
									<td data-title="<?php echo __( 'Video Type','video-grid');?>" class="alignCenter">
                                                                            <div>
                                                                                <strong><?php echo $row['vtype']; ?></strong>
										</div>
                                                                        </td>
									<td data-title="<?php echo __( 'Title','video-grid');?>" class="alignCenter">
                                                                                <div>
                                                                                    <strong><?php echo $row['videotitle']; ?></strong>
										</div>
                                                                         </td>
									<td class="alignCenter">
                                                                            <img src="<?php echo $outputimgmain; ?>" style="width: 50px" height="50px" />
                                                                        </td>
									<td data-title="<?php echo __( 'Published On','video-grid');?>" class="alignCenter"><?php echo $row['createdon'] ?></td>
									<td data-title="<?php echo __( 'Edit','video-grid');?>" class="alignCenter">
                                                                            <strong>
                                                                                <a href='<?php echo $editlink; ?>' title="<?php echo __( 'Edit','video-grid');?>">
                                                                                    <?php echo __( 'Edit','video-grid');?>
                                                                                </a>
                                                                            </strong>
                                                                        </td>
									<td data-title="<?php echo __( 'Delete','video-grid');?>" class="alignCenter">
                                                                            <strong>
                                                                                <a href='<?php echo $deletelink; ?>' onclick="return confirmDelete();" title="<?php echo __( 'Delete','video-grid');?>">
                                                                                    <?php echo __( 'Delete','video-grid');?>
                                                                                </a> 
                                                                            </strong>
                                                                        </td>
								</tr>
                                                            <?php
									}
								} else {
									?>
								<tr valign="top" class=""
									id="">
									<td colspan="8" data-title="<?php echo __( 'No Records','video-grid');?>" align="center">
                                                                            <strong>
                                                                                <?php echo __( 'No Videos Found','video-grid');?>
                                                                            </strong>
                                                                        </td>
								</tr>
                                                                <?php
								}
								?>      
                                                     </tbody>
                                                    </table>
                                                    </div>
                                                 <?php
							if ($rowsCount > 0) {
								echo "<div class='pagination' style='padding-top:10px'>";
								echo paginate_links ( $pagination_args );
								echo "</div>";
							}
							?>
                                                 <br />
					<div class="alignleft actions">
						<select name="action" id="action_bottom">
							<option selected="selected" value="-1">
                                                            <?php echo __( 'Bulk Actions','video-grid');?>
                                                        </option>
							<option value="delete">
                                                            <?php echo __( 'Delete','video-grid');?>
                                                        </option>
						</select> 
                                                <?php wp_nonce_field('action_settings_mass_delete', 'mass_delete_nonce'); ?>    
                                                <input type="submit" value="<?php echo __( 'Apply','video-grid');?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
					</div>

				</form>
				<script type="text/JavaScript">
                                    
                                    function  confirmDelete_bulk(){
                                            var topval=document.getElementById("action_bottom").value;
                                            var bottomVal=document.getElementById("action_upper").value;

                                            if(topval=='delete' || bottomVal=='delete'){


                                                var agree=confirm("<?php echo __( 'Are you sure you want to delete selected videos?','video-grid');?>");
                                                if (agree)
                                                    return true ;
                                                else
                                                    return false;
                                            }
                                     }    
                                    function  confirmDelete(){
                                    var agree=confirm("<?php echo __( 'Are you sure you want to delete this video?','video-grid');?>");
                                    if (agree)
                                         return true ;
                                    else
                                        return false;
                                    }
                                </script>

				<br class="clear">
			</div>
			<div style="clear: both;"></div>
                    <?php $url = plugin_dir_url(__FILE__); ?>


                </div>
		<h3><?php echo __( 'To print this video gallery into WordPress Post/Page use below code','video-grid');?></h3>
		<input type="text" value='[print_responsive_video_grid] ' style="width: 400px; height: 30px" onclick="this.focus(); this.select()" />
		<div class="clear"></div>
		<h3><?php echo __( 'To print this video gallery into WordPress theme/template PHP files use below code','video-grid');?></h3>
                <?php
		$shortcode = '[print_responsive_video_grid]';
		?>
                <input type="text" value="&lt;?php echo do_shortcode('<?php echo htmlentities($shortcode, ENT_QUOTES); ?>'); ?&gt;" style="width: 400px; height: 30px" onclick="this.focus(); this.select()" />
		<div class="clear"></div>
          </div>
          </div>
             <div id="postbox-container-1" class="postbox-container" > 

          <div class="postbox"> 
              <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','video-grid');?></h3> 
              <div class="inside">
                  <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank">
                          <img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250">
                      </a></center>

                  <div style="margin:10px 5px">

                  </div>
              </div></div>
            <div class="postbox"> 
                <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon','video-grid');?></h3> 
                    <div class="inside">
                        <center><a href="https://goo.gl/OJBuHT" target="_blank">
                                <img src="<?php echo plugins_url( 'images/g-suite-promo-code-4.png', __FILE__ );?>" width="250" height="250" border="0">
                            </a></center>
                        <div style="margin:10px 5px">
                        </div>
                    </div>
                    
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

                            wp_die(__( 'Security check fail','video-grid'));
                        }
                        
			$uploads = wp_upload_dir();
			$baseDir = $uploads ['basedir'];
			$baseDir = str_replace ( "\\", "/", $baseDir );
			$pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
			
			$vtype = trim ( htmlentities(sanitize_text_field ( $_POST ['vtype'] ),ENT_QUOTES) );
			$videourl = trim ( htmlentities(esc_url_raw($_POST ['videourl'] ),ENT_QUOTES));
                        
                        
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
			
			
			$HdnMediaSelection = trim ( htmlentities(esc_url_raw($_POST ['HdnMediaSelection'] ),ENT_QUOTES));
			$videotitle = trim ( htmlentities(sanitize_text_field($_POST ['videotitle'] ),ENT_QUOTES));
			$videotitleurl = trim ( htmlentities(esc_url_raw($_POST ['videotitleurl'] ),ENT_QUOTES));
			$video_order = 0;
			
			$video_description = '';
			
			$videotitle = str_replace("'","’",$videotitle);
			$videotitle = str_replace('"', '&quot;', $videotitle);
			
			$open_link_in = 0;
			
			$enable_light_box_video_desc = 0;

			$location = "admin.php?page=video_grid_with_lightbox_video_management";
				// edit save
			if (isset ( $_POST ['videoid'] )) {
                            
                                if ( ! current_user_can( 'rvg_video_grid_edit_video' ) ) {

                                        $location = "admin.php?page=video_grid_with_lightbox_video_management";
                                        $responsive_video_grid_messages = array ();
                                        $responsive_video_grid_messages ['type'] = 'err';
                                        $responsive_video_grid_messages ['message'] = __('Access Denied. Please contact your administrator','video-grid');
                                        update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                        echo "<script type='text/javascript'> location.href='$location';</script>";
                                        exit (); 


                                } 
				
				try {
						
						$videoidEdit=intval($_POST ['videoid']);
						if (trim ( $_POST ['HdnMediaSelection'] ) != '') {
							$pInfo = pathinfo ( $HdnMediaSelection );
							$ext = $pInfo ['extension'];
                                                        if($ext==''){
                                                            if (exif_imagetype($HdnMediaSelection) == IMAGETYPE_PNG) {

                                                               $ext='png'; 
                                                            } 
                                                            else if (exif_imagetype($HdnMediaSelection) == IMAGETYPE_JPEG) {

                                                               $ext='jpeg'; 
                                                            } 
                                                            else if (exif_imagetype($HdnMediaSelection) == IMAGETYPE_GIF) {

                                                               $ext='gif'; 
                                                            } 

                                                         }
							$imagename = $vid . '_big.' . $ext;
							$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
							//@copy ( $HdnMediaSelection, $imageUploadTo );
                                                        
                                                        @copy ( $HdnMediaSelection, $imageUploadTo );
                                                        if(!file_exists($imageUploadTo)){
                                                         vg_save_image($HdnMediaSelection,$imageUploadTo);
                                                        }
                                                        
                                                        $settings=get_option('responsive_video_grid_settings');
                                                        $imageheight = 270;
                                                        $imagewidth = 360;
                                                        @unlink($pathToImagesFolder.'/'.$vid . '_big_'.$imageheight.'_'.$imagewidth.'.'.$ext);
						}
							
						$query = "update " . $wpdb->prefix . "responsive_video_grid
						set vtype='$vtype',vid='$vid',video_url='$videourl',embed_url='$embed_url',image_name='$imagename',HdnMediaSelection='$HdnMediaSelection',
						videotitle='$videotitle',videotitleurl='$videotitleurl',video_description='$video_description',video_order=$video_order,
						open_link_in=$open_link_in,enable_light_box_video_desc=$enable_light_box_video_desc where id=$videoidEdit";
							
						
						$wpdb->query ( $query );
							
						$responsive_video_grid_messages = array();
						$responsive_video_grid_messages ['type'] = 'succ';
						$responsive_video_grid_messages ['message'] =  __( 'Video updated successfully.','video-grid');
						update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                                
					} catch ( Exception $e ) {
							
						$responsive_video_grid_messages = array();
                                                $responsive_video_grid_messages ['type'] = 'err';
						$responsive_video_grid_messages ['message'] = __( 'Error while adding video','video-grid');
						update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                        }

				
				
				echo "<script type='text/javascript'> location.href='$location';</script>";
				exit();
			} else {
				
				// add new
				
                               if ( ! current_user_can( 'rvg_video_grid_add_video' ) ) {

                                        $location = "admin.php?page=video_grid_with_lightbox_video_management";
                                        $responsive_video_grid_messages = array ();
                                        $responsive_video_grid_messages ['type'] = 'err';
                                        $responsive_video_grid_messages ['message'] = __('Access Denied. Please contact your administrator','video-grid');
                                        update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                        echo "<script type='text/javascript'> location.href='$location';</script>";
                                        exit (); 


                                } 
                                
				$createdOn = current_time ( 'Y-m-d h:i:s' );
				
				try {
					
					if (trim ( $_POST ['HdnMediaSelection'] ) != '') {
						$pInfo = pathinfo ( $HdnMediaSelection );
						$ext = isset($pInfo ['extension'])?$pInfo ['extension']:'';
                                                if($ext==''){
                                                   if (exif_imagetype($HdnMediaSelection) == IMAGETYPE_PNG) {
                                                   
                                                      $ext='png'; 
                                                   } 
                                                   else if (exif_imagetype($HdnMediaSelection) == IMAGETYPE_JPEG) {
                                                   
                                                      $ext='jpeg'; 
                                                   } 
                                                   else if (exif_imagetype($HdnMediaSelection) == IMAGETYPE_GIF) {
                                                   
                                                      $ext='gif'; 
                                                   } 
                                                    
                                                }
						$imagename = $vid . '_big.' . $ext;
						$imageUploadTo = $pathToImagesFolder . '/' . $imagename;
						//@copy ( $HdnMediaSelection, $imageUploadTo );
                                                
                                                @copy ( $HdnMediaSelection, $imageUploadTo );
                                                if(!file_exists($imageUploadTo)){
                                                 vg_save_image($HdnMediaSelection,$imageUploadTo);
                                                }
                                                
					}
					
					$query = "INSERT INTO " . $wpdb->prefix . "responsive_video_grid 
                                		(vtype, vid,video_url,embed_url,image_name,HdnMediaSelection,videotitle,videotitleurl,video_description,video_order,open_link_in,
                            			enable_light_box_video_desc,createdon) 
                           				 VALUES ('$vtype','$vid','$videourl','$embed_url','$imagename','$HdnMediaSelection','$videotitle','$videotitleurl','$video_description',
                                		$video_order,$open_link_in,$enable_light_box_video_desc,'$createdOn')";
					
					//echo $query;die;
					$wpdb->query ( $query );
					
					$responsive_video_grid_messages = array();
					$responsive_video_grid_messages ['type'] = 'succ';
					$responsive_video_grid_messages ['message'] = __( 'New video added successfully.','video-grid');
					update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
				} 
                                catch ( Exception $e ) {
					
					$responsive_video_grid_messages = array();
					$responsive_video_grid_messages ['type'] = 'err';
					$responsive_video_grid_messages ['message'] = __( 'Error while adding video','video-grid');
					update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
				}
				
				echo "<script type='text/javascript'> location.href='$location';</script>";
				exit();
			}
		} 
                else {
			
			$uploads = wp_upload_dir();
			$baseurl = $uploads ['baseurl'];
			$baseurl .= '/wp-responsive-video-grid/';
                  ?>
                 <div id="poststuff" > 
                  <div id="post-body" class="metabox-holder columns-2" >  
                   <div id="post-body-content">
                    <div class="wrap">
                        <div style="float: left; width: 100%;">
                         <div class="wrap">
                        <?php
                          if (isset ( $_GET ['id'] ) and intval($_GET ['id']) > 0) {
                                    
				
                                if ( ! current_user_can( 'rvg_video_grid_edit_video' ) ) {

                                        $location = "admin.php?page=video_grid_with_lightbox_video_management";
                                        $responsive_video_grid_messages = array ();
                                        $responsive_video_grid_messages ['type'] = 'err';
                                        $responsive_video_grid_messages ['message'] = __('Access Denied. Please contact your administrator','video-grid');
                                        update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                        echo "<script type='text/javascript'> location.href='$location';</script>";
                                        exit (); 


                                 } 
                                
                                    $id = intval($_GET ['id']);
                                    $query = "SELECT * FROM " . $wpdb->prefix . "responsive_video_grid WHERE id=$id";

                                    $myrow = $wpdb->get_row ( $query );
				
                                    if (is_object ( $myrow )) {
					
					$vtype = $myrow->vtype;
					$title = $myrow->videotitle;
					$image_name = $myrow->image_name;
					$video_url = $myrow->video_url;
					$HdnMediaSelection = $myrow->HdnMediaSelection;
					$videotitle = $myrow->videotitle;
					$videotitleurl=$myrow->videotitleurl;
					$video_order = $myrow->video_order;
					$video_description = $myrow->video_description;
					$open_link_in = $myrow->open_link_in ;
					$enable_light_box_video_desc = $myrow->enable_light_box_video_desc;
                                    }
				?>
                                <h2><?php echo __( 'Update Video','video-grid');?></h2>
                        <?php
			} 
                        
                        else {
				
				$vtype='';
				$title = '';
				$videotitle='';
                                $videotitleurl='';
				$HdnMediaSelection='';
				$video_url = '';
				$image_link = '';
				$image_name = '';
				$video_order = '';
				$video_description = '';
				$open_link_in = true;
				$enable_light_box_video_desc = true;
                                
                                if ( ! current_user_can( 'rvg_video_grid_add_video' ) ) {

                                        $location = "admin.php?page=video_grid_with_lightbox_video_management";
                                        $responsive_video_grid_messages = array ();
                                        $responsive_video_grid_messages ['type'] = 'err';
                                        $responsive_video_grid_messages ['message'] = __('Access Denied. Please contact your administrator','video-grid');
                                        update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                        echo "<script type='text/javascript'> location.href='$location';</script>";
                                        exit (); 


                                 } 
                                 
                                 ?>
                         <div style="clear:both">
                            <span><h3 style="color: blue;">
                                    <a target="_blank" href="https://www.i13websolution.com/wordpress-responsive-video-grid-pro.html"><?php echo __( 'UPGRADE TO PRO VERSION','video-grid');?></a>
                                </h3>
                            </span>
                        </div>  
                    <h2><?php echo __( 'Add Video','video-grid');?></h2>
                   <?php } ?>
                     <br />
                    <div id="poststuff">
                         <div id="post-body" class="metabox-holder columns-2">
                             <div id="post-body-content">
                                <form method="post" action="" id="addimage" name="addimage"  enctype="multipart/form-data">
                                                    <div class="stuffbox" id="namediv" style="width: 100%">
                                                            <h3>
                                                                    <label for="link_name"><?php echo __( 'Video Information','video-grid');?> (<span
                                                                            style="font-size: 11px; font-weight: normal"><?php echo __('Choose Video Site','video-grid'); ?></span>)
                                                                    </label>
                                                            </h3>
                                                            <div class="inside">
                                                                    <div>
                                                                            <input type="radio" value="youtube" name="vtype" <?php if($vtype=='youtube'): ?> checked='checked' <?php endif;?> style="width: 15px" id="type_youtube" /><?php echo __( 'Youtube','video-grid');?>&nbsp;&nbsp;
                                                                            <input <?php if($vtype=='dailymotion'): ?> checked='checked' <?php endif;?> type="radio" value="dailymotion" name="vtype" style="width: 15px" id="type_DailyMotion" /><?php echo __( 'DailyMotion','video-grid');?>&nbsp;&nbsp;
                                                                    </div>
                                                                    <div style="clear: both"></div>
                                                                    <div></div>
                                                                    <div style="clear: both"></div>
                                                                    <br />
                                                                    <div>
                                                                        <b><?php echo __( 'Video Url','video-grid');?></b> 
                                                                        <input type="text" id="videourl" class="url" tabindex="1" size="30" name="videourl" value="<?php echo $video_url; ?>">
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
                                                    <div class="stuffbox" id="namediv" style="width: 100%">
                                                            <h3>
                                                                    <label for="link_name"><?php echo __( 'Video Thumbnail Information','video-grid');?></label>
                                                            </h3>
                                                            <div class="inside" id="fileuploaddiv">
                                                             <?php if ($image_name != "") { ?>
                                                                    <div>
                                                                            <b><?php echo __( 'Current Image','video-grid');?> : </b>
                                                                            <br/>
                                                                            <img id="img_disp" name="img_disp" src="<?php echo $baseurl . $image_name; ?>" />
                                                                    </div>
                                                                    <?php }else{ ?>      
                                                                        <img src="<?php echo plugins_url('/images/no-img.jpeg', __FILE__); ?>" id="img_disp" name="img_disp" />

                                                                    <?php } ?>
                                                                    <br /> <a href="javascript:;" class="niks_media" id="videoFromExternalSite"  ><b><?php echo __( 'Click Here to get video information and thumbnail','video-grid');?><span id='fromval'> <?php echo __( 'from','video-grid');?> <?php echo $vtype;?></span>
                                                                    </b></a>&nbsp;<img src="<?php echo plugins_url('/images/ajax-loader.gif', __FILE__); ?>" style="display: none" id="loading_img" name="loading_img" />
                                                                    <div style="clear: both"></div>
                                                                    <div></div>
                                                                    <div class="uploader">
                                                                            <br /> <b style="margin-left: 50px;">OR</b>
                                                                            <div style="clear: both; margin-top: 15px;"></div>
                                                                               <a href="javascript:;" class="niks_media" id="myMediaUploader"><b><?php echo __( 'Click Here to upload custom video thumbnail','video-grid');?></b></a> 
                                                                                   <br /> <br />
                                                                                    <div>
                                                                                    <input id="HdnMediaSelection" name="HdnMediaSelection" type="hidden" value="<?php echo $HdnMediaSelection;?>" />
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

                                                                        alert('<?php echo __( 'Please select video site','video-grid');?>.\n <?php echo __( 'Please enter video url.','video-grid');?>');
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
                                                                                                    'action': 'check_file_exist_grid',
                                                                                                    'url': tumbnailImg,
                                                                                                    'vNonce':'<?php echo $vNonce; ?>'
                                                                                            };

                                                                                            jQuery.post(ajaxurl, data, function(response) {



                                                                                          var youtubeJsonUri='https://www.youtube.com/oembed?url=https://www.youtube.com/watch%3Fv='+vId+'&format=json';
                                                                                           var data_youtube = {
                                                                                                    'action': 'get_youtube_info_grid',
                                                                                                    'url': youtubeJsonUri,
                                                                                                    'vid':vId,
                                                                                                     'vNonce':'<?php echo $vNonce; ?>'
                                                                                            };

                                                                                          jQuery.post(ajaxurl, data_youtube, function(data) {

                                                                                           data = jQuery.parseJSON(data);

                                                                                           if(typeof data =='object'){    
                                                                                                   if(typeof data =='object'){ 

                                                                                                        if(data.title!='' && data.title!=''){
                                                                                                            jQuery("#videotitle").val(data.title); 
                                                                                                        }
                                                                                                        jQuery("#videotitleurl").val(videourlVal);
                                                                                                        if(data.description!='' && data.description!=''){
                                                                                                            jQuery("#video_description").val(data.description); 
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
                                                                                    alert('<?php echo __( 'Could not found such video','video-grid');?>');
                                                                                    jQuery("#loading_img").hide();
                                                                                }
                                                                            }
                                                                            else if(checkedValueRadio == 'dailymotion'){

                                                                                    var vid=getDailyMotionId(videourlVal);	
                                                                                    var apiUrl='https://api.dailymotion.com/video/'+vid+'?fields=description,id,thumbnail_720_url,title';
                                                                                    jQuery.getJSON( apiUrl, function( data ) {
                                                                                             if(typeof data =='object'){    


                                                                                                     jQuery("#HdnMediaSelection").val(data.thumbnail_720_url);	
                                                                                                     jQuery("#videotitle").val(jQuery.trim(data.title));
                                                                                                     jQuery("#videotitleurl").val(videourlVal);
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

                                                                                  alert('<?php echo __( 'Invalid image selection.','video-grid');?>');
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
                                                                            <label for="link_name"><?php echo __( 'Video Title','video-grid');?> (<span
                                                                                    style="font-size: 11px; font-weight: normal"><?php echo __('Used into lightbox','video-grid'); ?></span>)
                                                                            </label>
                                                                    </h3>
                                                                    <div class="inside">
                                                                            <div>
                                                                                    <input type="text" id="videotitle" tabindex="1" size="30" name="videotitle" value="<?php echo $videotitle; ?>">
                                                                            </div>
                                                                            <div style="clear: both"></div>
                                                                            <div></div>
                                                                            <div style="clear: both"></div>
                                                                    </div>
                                                            </div>
                                                            <div class="stuffbox" id="namediv" style="width: 100%">
                                                                    <h3>
                                                                            <label for="link_name"><?php echo __( 'Video Title Url','video-grid');?> (<span
                                                                                    style="font-size: 11px; font-weight: normal"><?php echo __('Click on title redirect to this url.Used in lightbox for video title','video-grid'); ?></span>)
                                                                            </label>
                                                                    </h3>
                                                                    <div class="inside">
                                                                            <div>
                                                                                    <input type="text" id="videotitleurl" class="url" tabindex="1" size="30" name="videotitleurl" value="<?php echo $videotitleurl; ?>">
                                                                            </div>
                                                                            <div style="clear: both"></div>
                                                                            <div></div>
                                                                            <div style="clear: both"></div>

                                                                    </div>
                                                            </div>


                                                              <?php if (isset($_GET['id']) and intval($_GET['id']) > 0) { ?> 
                                                                     <input type="hidden" name="videoid" id="videoid" value="<?php echo intval($_GET['id']); ?>">
                                                                <?php
                                                                    }
                                                                    ?>
                                                                 <?php wp_nonce_field('action_image_add_edit','add_edit_image_nonce'); ?>     
                                                                 <input type="submit" onclick="" name="btnsave" id="btnsave" value="<?php echo __( 'Save Changes','video-grid');?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="<?php echo __( 'Cancel','video-grid');?>" class="button-primary" onclick="location.href = 'admin.php?page=video_grid_with_lightbox_video_management'">

                                                        </form>
                                                        <script type="text/javascript">

                                                                
                                                                jQuery(document).ready(function() {

                                                                 jQuery.validator.setDefaults({ 
                                                                     ignore: [],
                                                                     // any other default options and/or rules
                                                                 });

                                                                 jQuery("#addimage").validate({
                                                                 rules: {
                                                                 videotitle: {
                                                                 required:true,
                                                                         maxlength: 200
                                                                 },
                                                                    vtype: {
                                                                    required:true

                                                                    },
                                                                    videourl: {
                                                                    required:true,
                                                                            url:true,
                                                                            maxlength: 500
                                                                    },
                                                                    HdnMediaSelection:{
                                                                      required:true  
                                                                    },
                                                                    videotitleurl: {

                                                                    url:true,
                                                                     maxlength: 500
                                                                    }

                                                                 },
                                                                  errorClass: "image_error",
                                                                         errorPlacement: function(error, element) {
                                                                         error.appendTo(element.parent().next().next());
                                                                         }, messages: {
                                                                             HdnMediaSelection: "<?php echo __( 'Please select video thumbnail or Upload by wordpress media uploader.','video-grid');?>",

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
                                                                        jQuery("#myMediaUploader").after('<br/><label class="image_error" id="err_daynamic">Please select file.</label>');
                                                                        return false;  
                                                                    } 

                                                                } 
                                                             </script>

							</div>
						</div>
					</div>
                   
				</div>
			</div>
          </div>
          </div>     
                    <div id="postbox-container-1" class="postbox-container" > 

                 <div class="postbox"> 
                     <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','video-grid');?></h3> 
                     <div class="inside">
                         <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank">
                                 <img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250">
                             </a></center>

                         <div style="margin:10px 5px">

                         </div>
                     </div></div>
                  <div class="postbox"> 
                    <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon','video-grid');?></h3> 
                        <div class="inside">
                            <center><a href="https://goo.gl/OJBuHT" target="_blank">
                                    <img src="<?php echo plugins_url( 'images/g-suite-promo-code-4.png', __FILE__ );?>" width="250" height="250" border="0">
                                </a></center>
                            <div style="margin:10px 5px">
                            </div>
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
               
               if ( ! current_user_can( 'rvg_video_grid_delete_video' ) ) {

                        $location = "admin.php?page=video_grid_with_lightbox_video_management";
                        $responsive_video_grid_messages = array ();
                        $responsive_video_grid_messages ['type'] = 'err';
                        $responsive_video_grid_messages ['message'] = __('Access Denied. Please contact your administrator','video-grid');
                        update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                        echo "<script type='text/javascript'> location.href='$location';</script>";
                        exit (); 


                 }

		$uploads = wp_upload_dir();
		$baseDir = $uploads ['basedir'];
		$baseDir = str_replace ( "\\", "/", $baseDir );
		$pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
		
		
		$location = "admin.php?page=video_grid_with_lightbox_video_management";
		$deleteId = intval($_GET ['id']);
		
		try {
			
			$query = "SELECT * FROM " . $wpdb->prefix . "responsive_video_grid WHERE id=$deleteId";
			$myrow = $wpdb->get_row ( $query );
			
			if (is_object ( $myrow )) {
				
				$image_name = $myrow->image_name;
				$wpcurrentdir = dirname ( __FILE__ );
				$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
				$imagetoDel = $pathToImagesFolder . '/' . $image_name;
                                $settings=get_option('responsive_video_grid_settings');
                                $imageheight = 270;
                                $imagewidth = 360;
				
                                $pInfo = pathinfo ( $myrow->HdnMediaSelection );
                                $ext = $pInfo ['extension'];

                                @unlink ( $imagetoDel );
                                @unlink($pathToImagesFolder.'/'.$myrow->vid . '_big_'.$imageheight.'_'.$imagewidth.'.'.$ext);

				
				$query = "delete from  " . $wpdb->prefix . "responsive_video_grid where id=$deleteId";
				$wpdb->query ( $query );
				
				$responsive_video_grid_messages = array();
				$responsive_video_grid_messages ['type'] = 'succ';
				$responsive_video_grid_messages ['message'] = __( 'Video deleted successfully.','video-grid');
				update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                                
			}
                        
		} 
                catch ( Exception $e ) {
			
			$responsive_video_grid_messages = array();
			$responsive_video_grid_messages ['type'] = 'err';
			$responsive_video_grid_messages ['message'] = __( 'Error while deleting video.','video-grid');
			update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
		}
		
		echo "<script type='text/javascript'> location.href='$location';</script>";
		exit();
	} 
        else if (strtolower ( $action ) == strtolower ( 'deleteselected' )) {
		
		
              if(!check_admin_referer('action_settings_mass_delete','mass_delete_nonce')){

                    wp_die(__( 'Security check fail','video-grid')); 
               }

               if ( ! current_user_can( 'rvg_video_grid_delete_video' ) ) {

                        $location = "admin.php?page=video_grid_with_lightbox_video_management";
                        $responsive_video_grid_messages = array ();
                        $responsive_video_grid_messages ['type'] = 'err';
                        $responsive_video_grid_messages ['message'] = __('Access Denied. Please contact your administrator','video-grid');
                        update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
                        echo "<script type='text/javascript'> location.href='$location';</script>";
                        exit (); 


                 }
                 
		$location = "admin.php?page=video_grid_with_lightbox_video_management";
		
		if (isset ( $_POST ) and isset ( $_POST ['deleteselected'] ) and ($_POST ['action'] == 'delete' or $_POST ['action_upper'] == 'delete')) {
			
			$uploads = wp_upload_dir();
			$baseDir = $uploads ['basedir'];
			$baseDir = str_replace ( "\\", "/", $baseDir );
			$pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
			
			if (sizeof ( $_POST ['thumbnails'] ) > 0) {
				
				$deleteto = $_POST ['thumbnails'];
				$implode = implode ( ',', $deleteto );
				
				try {
					
                                       $settings=get_option('responsive_video_grid_settings');
                                        $imageheight = 270;
                                        $imagewidth = 360;

					foreach ( $deleteto as $img ) {
						
                                                $img=intval($img);
						$query = "SELECT * FROM " . $wpdb->prefix . "responsive_video_grid WHERE id=$img";
						$myrow = $wpdb->get_row ( $query );
						
						if (is_object ( $myrow )) {
							
							$image_name = $myrow->image_name;
							$wpcurrentdir = dirname ( __FILE__ );
							$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
                                                        $imagetoDel = $pathToImagesFolder . '/' . $image_name;
				
                                                        $pInfo = pathinfo ( $myrow->HdnMediaSelection );
                                                        $ext = $pInfo ['extension'];

                                                        @unlink ( $imagetoDel );
                                                        @unlink($pathToImagesFolder.'/'.$myrow->vid . '_big_'.$imageheight.'_'.$imagewidth.'.'.$ext);

				
										
							$query = "delete from  " . $wpdb->prefix . "responsive_video_grid where id=$img";
							$wpdb->query ( $query );
							
							$responsive_video_grid_messages = array();
							$responsive_video_grid_messages ['type'] = 'succ';
							$responsive_video_grid_messages ['message'] = __( 'selected videos deleted successfully.','video-grid');
							update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
						}
					}
				} catch ( Exception $e ) {
					
					$responsive_video_grid_messages = array();
					$responsive_video_grid_messages ['type'] = 'err';
					$responsive_video_grid_messages ['message'] = __( 'Error while deleting videos.','video-grid');
					update_option ( 'responsive_video_grid_messages', $responsive_video_grid_messages );
				}
				
				echo "<script type='text/javascript'> location.href='$location';</script>";
				exit();
			} else {
				
				echo "<script type='text/javascript'> location.href='$location';</script>";
				exit();
			}
		} else {
			
			echo "<script type='text/javascript'> location.href='$location';</script>";
			exit();
		}
	}
}
function video_grid_with_lightbox_video_preview_func() {
    
	global $wpdb;
	

        if ( ! current_user_can( 'rvg_video_grid_preview' ) ) {

              wp_die( __( "Access Denied", "video-grid" ) );

         } 
         
	$settings=get_option('responsive_video_grid_settings');
	
	$rand_Numb = uniqid ( 'thumnail_slider' );
	$rand_Num_td = uniqid ( 'divSliderMain' );
	$rand_var_name = uniqid ( 'rand_' );
	
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	// $settings=get_option('thumbnail_slider_settings');
	
	$uploads = wp_upload_dir();
	$baseDir = $uploads ['basedir'];
	$baseDir = str_replace ( "\\", "/", $baseDir );
	$pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
	$baseurl = $uploads ['baseurl'];
	$baseurl .= '/wp-responsive-video-grid/';
	?>      
        <style type='text/css'>
        #<?php echo $rand_Num_td;?>  {background: none repeat scroll 0 0<?php echo $settings ['scollerBackground'];?> ! important;
                border: 0px none !important;
                box-shadow: 0 0 0 0 !important;

        }
        #poststuff #post-body.columns-2{margin-right: 0px}
        </style>
<?php
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	$randOmeAlbName = uniqid ( 'alb_' );
	$randOmeRel = uniqid ( 'rel_' );
        
         if(!isset($settings['resize_images'])){

                $settings['resize_images']=1;

        } 
        
	?>
                <div style="width: 100%;">
		<div style="float: left; width: 100%;">
		    <div class="wrap_grid">
			  <h2><?php echo __( 'Grid Preview','video-grid');?></h2>
						
                            <?php if (is_array($settings)) { ?>
                                <div id="poststuff">
				 <div id="post-body" class="metabox-holder columns-2">
				   <div id="post-body-content">
					<div style="clear: both;"></div>
                                            <?php $url = plugin_dir_url(__FILE__); ?>           

                                            <div class="wrap_grid"  id="<?php echo $rand_Num_td; ?>">
						 <div id="<?php echo $rand_Numb; ?>" class="responsivegrid" style="margin-top: 2px !important;">
                                                     <div class="box_parent">   
                                                    <?php
                                                            global $wpdb;
                                                             $imageheight = 270;
                                                             $imagewidth = 360;
                                                            $query = "SELECT * FROM " . $wpdb->prefix . "responsive_video_grid  order by createdon desc";
                                                            $rows = $wpdb->get_results ( $query, 'ARRAY_A' );

                                                            if (count ( $rows ) > 0) {
                                                                 
                                                                foreach ( $rows as $row ) {

                                                                            $imagename = $row ['image_name'];
                                                                            $video_url = $row ['video_url'];
                                                                            $imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                                            $imageUploadTo = str_replace ( "\\", "/", $imageUploadTo );
                                                                            $pathinfo = pathinfo ( $imageUploadTo );
                                                                            $filenamewithoutextension = $pathinfo ['filename'];
                                                                            $outputimg = "";

                                                                           if($settings['resize_images']==0){
                                                                                      
                                                                                $outputimg = $baseurl . $row ['image_name'];
                                                                            }
                                                                            else{

                                                                                $imagetoCheck = $pathToImagesFolder . '/' . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];

                                                                               /* if (file_exists ( $imagetoCheck )) {
                                                                                        $outputimg = $baseurl . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];

                                                                                } else {*/

                                                                                        if (function_exists ( 'wp_get_image_editor' )) {

                                                                                                $image = wp_get_image_editor ( $pathToImagesFolder . "/" . $row ['image_name'] );

                                                                                                if (! is_wp_error ( $image )) {
                                                                                                        $image->resize ( $imagewidth, $imageheight, true );
                                                                                                        $image->save ( $imagetoCheck );
                                                                                                        $outputimg = $baseurl . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];
                                                                                                } else {
                                                                                                        $outputimg = $baseurl . $row ['image_name'];
                                                                                                }
                                                                                        } else if (function_exists ( 'image_resize' )) {

                                                                                                $return = image_resize ( $pathToImagesFolder . "/" . $row ['image_name'], $imagewidth, $imageheight );
                                                                                                if (! is_wp_error ( $return )) {

                                                                                                        $isrenamed = rename ( $return, $imagetoCheck );
                                                                                                        if ($isrenamed) {
                                                                                                                $outputimg = $baseurl . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];
                                                                                                        } else {
                                                                                                                $outputimg = $baseurl . $row ['image_name'];
                                                                                                        }
                                                                                                } else {
                                                                                                        $outputimg = $baseurl . $row ['image_name'];
                                                                                                }
                                                                                        } else {

                                                                                                $outputimg = $baseurl . $row ['image_name'];
                                                                                        }

                                                                                        // $url = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                                //}
                                                                            }

                                                                            $embed_url=$row['embed_url'];

                                                                      $title="";
			                                              
                                                                     $rowTitle = stripslashes_deep($row['videotitle']);
                                                                     $rowTitle = str_replace('"', "'", $rowTitle);
                                                                     $rowTitle=str_replace("'","’",$rowTitle); 
                                                                     $rowTitle=preg_replace('/\\\\/', '', $rowTitle);

			                                              
			                                              $rowDescrption=$row['video_description'];
			                                              $rowDescrption=str_replace("'","’",$rowDescrption); 
			                                              $rowDescrption=str_replace('"','”',$rowDescrption); 
                                                                      $rowDescrption=strip_tags($rowDescrption); 
			                                             
			                                              if(strlen($rowDescrption)>300){
                                                                          
                                                                        $rowDescrption=substr($rowDescrption,0,300)."..."; 
                                                                      }
			                                              //$openImageInNewTab='_blank';
			                                              $open_link_in=$row['open_link_in'];
			                                             // if($open_link_in==0){
			                                                $openImageInNewTab='_self';  
			                                               //}
			                                             
			                                              if(trim($row['videotitle'])!='' and trim($row['videotitleurl'])!=''){
			                                                  
			                                                   $title="<a class='Imglink' target='$openImageInNewTab' href='{$row['videotitleurl']}'>{$rowTitle}</a>";
			                                                   if($row['video_description']!=''){
			                                                    $title.="<div class='clear_description_'>{$rowDescrption}</div>";
			                                                   }
			                                              }
			                                              else if(trim($row['videotitle'])!='' and trim($row['videotitleurl'])==''){
			                                                  
			                                                 $title="<a class='Imglink' href='#'>{$rowTitle}</a>"; 
			                                                 if($row['video_description']!=''){
			                                                    $title.="<div class='clear_description_'>{$rowDescrption}</div>";
			                                                  }
			                                              }
			                                              else{
			                                                  
			                                                  if($row['video_description']!='')
			                                                     $title="<div class='clear_description_'>{$row['video_description']}</div>"; 
			                                              }
                                                                     
                                                                        $title= htmlentities($title);
                                                                        $rowDescrption= htmlentities($rowDescrption);
                                                                        //$rowTitle= htmlentities($rowTitle);
                                                                     
			                                           ?>
                                                                        
                                                     
                                                                        <div class="box_grid">
                                                                        <div class="boxInner_grid">
                                                                            
                                                                            <?php if($settings['display_video_lightbox']==true):?>
                                                                                <a rel="<?php echo $randOmeRel;?>" data-overlay="1" data-title="<?php echo $title;?>" class="video_lbox" href="<?php echo $embed_url;?>">
                                                                                    <img loading="lazy"  class="thumb_img__ <?php if($settings['resize_images']==0):?>fit_img <?php endif;?> "   src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>"/>
                                                                                    <span class="playbtnCss"></span>
                                                                                    <div class="titleBox"><?php echo $rowTitle;?></div>
                                                                                </a>  
                                                                            <?php else : ?>
                                                                            
                                                                            <a href="<?php echo $video_url;?>">
                                                                                    <img loading="lazy"  class="thumb_img__ <?php if($settings['resize_images']==0):?>fit_img <?php endif;?> "   src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>"/>
                                                                                    <span class="playbtnCss"></span>
                                                                                    <div class="titleBox"><?php echo $rowTitle;?></div>
                                                                                </a> 
                                                                             
                                                                            <?php endif;?>    
                                                                        </div>
                                                                      </div>
                                                                    
                                                        <?php } ?>   
                                                    <?php } ?>   
                                                </div>
                                            </div>  
			                  </div>
					  <script>
                                                    var uniqObj=jQuery("a[rel='<?php echo $randOmeRel;?>']");

                                                   jQuery(document).ready(function(){

                                                      videoPlacements('<?php echo $rand_Numb; ?>',jQuery);
                                                       jQuery(".video_lbox").fancybox_vg({
                                                    'type'    : "iframe",
                                                    'overlayColor':'#000000',
                                                     'padding': 10,
                                                     'autoScale': true,
                                                     'autoDimensions':true,
                                                     'transitionIn': 'none',
                                                     'uniqObj':uniqObj,
                                                     'transitionOut': 'none',
                                                     'titlePosition': 'outside',
                                                     'hideOnContentClick':false,
                                                     'width' : 650,
                                                     'height' : 400,
                                                     'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                                                         var currtElem = jQuery('#<?php echo $rand_Numb; ?> a[href="'+currentOpts.href+'"]');

                                                         var isoverlay = jQuery(currtElem).attr('data-overlay')

                                                        if(isoverlay=="1" && jQuery.trim(title)!=""){
                                                         return '<span id="fancybox_vg-title-over">' + title  + '</span>';
                                                        }
                                                        else{
                                                            return '';
                                                        }

                                                        },

                                                   });

                                                    });



                                                var width__ = jQuery(window).width();
                                                var timer__;
                                                jQuery(window).bind('resize', function(){
                                                    if(jQuery(window).width() != width__){

                                                        width__ = jQuery(window).width();
                                                        timer__ && clearTimeout(timer__);
                                                        timer__ = setTimeout(function(){ 

                                                        videoPlacements('<?php echo $rand_Numb; ?>',jQuery);

                                                        }, 200);

                                                    }  
                                                });

                                             </script>
                                             
					</div>
				   </div>
				</div>  
                            <?php } ?>
                        </div>
				</div>
				<div class="clear"></div>
			</div>
                <?php if (is_array($settings)) { ?>

                    <h3><?php echo __( 'To print this video gallery into WordPress Post/Page use below code','video-grid');?></h3>
                    <input type="text" value='[print_responsive_video_grid] ' style="width: 400px; height: 30px" onclick="this.focus(); this.select()" />
                    <div class="clear"></div>
                    <h3><?php echo __( 'To print this video gallery into WordPress theme/template PHP files use below code','video-grid');?></h3>
                    <?php
			$shortcode = '[print_responsive_video_grid]';
		    ?>
                    <input type="text" value="&lt;?php echo do_shortcode('<?php echo htmlentities($shortcode, ENT_QUOTES); ?>'); ?&gt;" style="width: 400px; height: 30px" onclick="this.focus(); this.select()" />
                <?php } ?>
                <div class="clear"></div>
 <?php
   }
function print_responsive_video_grid_func($atts) {
    
    
        wp_enqueue_style('wp-video-grid-lighbox-style');
        wp_enqueue_style('vl-box-grid-css');
        wp_enqueue_script('jquery');     
        wp_enqueue_script('vl-grid-js');    
        wp_enqueue_script('v_grid');    
      
        ob_start();

	global $wpdb;

        $settings=get_option('responsive_video_grid_settings');
	$rand_Numb = uniqid ( 'thumnail_slider' );
	$rand_Num_td = uniqid ( 'divSliderMain' );
	$rand_var_name = uniqid ( 'rand_' );
	
	
	$wpcurrentdir = dirname ( __FILE__ );
	$wpcurrentdir = str_replace ( "\\", "/", $wpcurrentdir );
	// $settings=get_option('thumbnail_slider_settings');
	
	$uploads = wp_upload_dir();
	$baseDir = $uploads ['basedir'];
	$baseDir = str_replace ( "\\", "/", $baseDir );
	$pathToImagesFolder = $baseDir . '/wp-responsive-video-grid';
	$baseurl = $uploads ['baseurl'];
	$baseurl .= '/wp-responsive-video-grid/';
        $randOmeRel = uniqid ( 'rel_' );
        $randOmVlBox=  uniqid('video_lbox_');
	?><!-- print_responsive_video_grid_func --><style type='text/css'>
       #<?php echo $rand_Num_td;?>  {background: none repeat scroll 0 0<?php echo $settings['scollerBackground'];?> ! important;
                border: 0px none !important;
                box-shadow: 0 0 0 0 !important;

        }
        </style>	
      <?php
      
          if (is_array($settings)) 
              
              { 
              
               
                    if(!isset($settings['resize_images'])){

                            $settings['resize_images']=1;

                    } 
              ?>
                             <div style="clear: both;"></div>
                                 <?php $url = plugin_dir_url(__FILE__); ?>           

                                 <div style="width: auto; postion: relative" id="<?php echo $rand_Num_td; ?>">
                                     
                                      <div id="<?php echo $rand_Numb; ?>" class="wrap_grid" style="margin-top: 2px !important;">
                                          <div class="box_parent">     
                                            <?php
                                                        global $wpdb;
                                                        $imageheight = 270;
                                                        $imagewidth = 360;
                                                        $query = "SELECT * FROM " . $wpdb->prefix . "responsive_video_grid order by createdon desc";
                                                        $rows = $wpdb->get_results ( $query, 'ARRAY_A' );

                                                         if (count ( $rows ) > 0) {

                                                                       foreach ( $rows as $row ) {

                                                                                   $imagename = $row ['image_name'];
                                                                                   $video_url = $row ['video_url'];
                                                                                   $imageUploadTo = $pathToImagesFolder . '/' . $imagename;
                                                                                   $imageUploadTo = str_replace ( "\\", "/", $imageUploadTo );
                                                                                   $pathinfo = pathinfo ( $imageUploadTo );
                                                                                   $filenamewithoutextension = $pathinfo ['filename'];
                                                                                   $outputimg = "";

                                                                                    if($settings['resize_images']==0){
                                                                                      
                                                                                        $outputimg = $baseurl . $row ['image_name'];
                                                                                    }
                                                                                    else{

                                                                                        $imagetoCheck = $pathToImagesFolder . '/' . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];

                                                                                        if (file_exists ( $imagetoCheck )) {
                                                                                                $outputimg = $baseurl . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];

                                                                                        } else {

                                                                                                if (function_exists ( 'wp_get_image_editor' )) {

                                                                                                        $image = wp_get_image_editor ( $pathToImagesFolder . "/" . $row ['image_name'] );

                                                                                                        if (! is_wp_error ( $image )) {
                                                                                                                $image->resize ( $imagewidth, $imageheight, true );
                                                                                                                $image->save ( $imagetoCheck );
                                                                                                                $outputimg = $baseurl . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];
                                                                                                        } else {
                                                                                                                $outputimg = $baseurl . $row ['image_name'];
                                                                                                        }
                                                                                                } else if (function_exists ( 'image_resize' )) {

                                                                                                        $return = image_resize ( $pathToImagesFolder . "/" . $row ['image_name'], $imagewidth, $imageheight );
                                                                                                        if (! is_wp_error ( $return )) {

                                                                                                                $isrenamed = rename ( $return, $imagetoCheck );
                                                                                                                if ($isrenamed) {
                                                                                                                        $outputimg = $baseurl . $filenamewithoutextension . '_' . $imageheight . '_' . $imagewidth . '.' . $pathinfo ['extension'];
                                                                                                                } else {
                                                                                                                        $outputimg = $baseurl . $row ['image_name'];
                                                                                                                }
                                                                                                        } else {
                                                                                                                $outputimg = $baseurl . $row ['image_name'];
                                                                                                        }
                                                                                                } else {

                                                                                                        $outputimg = $baseurl . $row ['image_name'];
                                                                                                }

                                                                                                // $url = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                                        }
                                                                                    }

                                                                                   $embed_url=$row['embed_url'];


                                                                             $title="";
                                                                             $rowTitle = stripslashes_deep($row['videotitle']);
                                                                             $rowTitle = str_replace('"', "'", $rowTitle);
                                                                             $rowTitle=str_replace("'","’",$rowTitle); 
                                                                             $rowTitle=preg_replace('/\\\\/', '', $rowTitle);


                                                                             $rowDescrption=$row['video_description'];
                                                                             $rowDescrption=str_replace("'","’",$rowDescrption); 
                                                                             $rowDescrption=str_replace('"','”',$rowDescrption); 
                                                                             $rowDescrption=strip_tags($rowDescrption); 

                                                                             if(strlen($rowDescrption)>300){

                                                                               $rowDescrption=substr($rowDescrption,0,300)."..."; 
                                                                             }
                                                                             //$openImageInNewTab='_blank';
                                                                             $open_link_in=$row['open_link_in'];
                                                                            // if($open_link_in==0){
                                                                               $openImageInNewTab='_self';  
                                                                              //}

                                                                             if(trim($row['videotitle'])!='' and trim($row['videotitleurl'])!=''){

                                                                                  $title="<a class='Imglink' target='$openImageInNewTab' href='{$row['videotitleurl']}'>{$rowTitle}</a>";
                                                                                  if($row['video_description']!=''){
                                                                                   $title.="<div class='clear_description_'>{$rowDescrption}</div>";
                                                                                  }
                                                                             }
                                                                             else if(trim($row['videotitle'])!='' and trim($row['videotitleurl'])==''){

                                                                                $title="<a class='Imglink' href='#'>{$rowTitle}</a>"; 
                                                                                if($row['video_description']!=''){
                                                                                   $title.="<div class='clear_description_'>{$rowDescrption}</div>";
                                                                                 }
                                                                             }
                                                                             else{

                                                                                 if($row['video_description']!='')
                                                                                    $title="<div class='clear_description_'>{$row['video_description']}</div>"; 
                                                                             }

                                                                             $title= htmlentities($title);
                                                                             $rowDescrption= htmlentities($rowDescrption);
                                                                             //$rowTitle= htmlentities($rowTitle);
                                                                          ?>


                                                                               <div class="box_grid">
                                                                               <div class="boxInner_grid">

                                                                                   <?php if($settings['display_video_lightbox']==true):?>
                                                                                       <a rel="<?php echo $randOmeRel;?>" data-overlay="1" data-title="<?php echo $title;?>" class="video_lbox" href="<?php echo $embed_url;?>">
                                                                                           <img  loading="lazy"  class="thumb_img__ <?php if($settings['resize_images']==0):?>fit_img <?php endif;?> "  src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>"/>
                                                                                           <span class="playbtnCss"></span>
                                                                                           <div class="titleBox"><?php echo $rowTitle;?></div>
                                                                                       </a>  
                                                                                   <?php else : ?>

                                                                                   <a href="<?php echo $video_url;?>">
                                                                                           <img loading="lazy"  class="thumb_img__ <?php if($settings['resize_images']==0):?>fit_img <?php endif;?> "   src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>"/>
                                                                                           <span class="playbtnCss"></span>
                                                                                           <div class="titleBox"><?php echo $rowTitle;?></div>
                                                                                       </a> 

                                                                                   <?php endif;?>    
                                                                               </div>
                                                                             </div>

                                                               <?php } ?>   
                                                           <?php } ?>   
                                            </div>
                                      </div>
                               </div>
                            <script>
                            
                            <?php $intval= uniqid('interval_');?>
               
                            var <?php echo $intval;?> = setInterval(function() {

                            if(document.readyState === 'complete') {

                               clearInterval(<?php echo $intval;?>);
                                         
                                    var uniqObj=jQuery("a[rel='<?php echo $randOmeRel;?>']");


                                    videoPlacements('<?php echo $rand_Numb; ?>',jQuery);
                                    jQuery(".video_lbox").fancybox_vg({
                                    'type'    : "iframe",
                                    'overlayColor':'#000000',
                                     'padding': 10,
                                     'autoScale': true,
                                     'autoDimensions':true,
                                     'transitionIn': 'none',
                                     'uniqObj':uniqObj,
                                     'transitionOut': 'none',
                                     'titlePosition': 'outside',
                                     'hideOnContentClick':false,
                                     'width' : 650,
                                     'height' : 400,
                                     'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                                         var currtElem = jQuery('#<?php echo $rand_Numb; ?> a[href="'+currentOpts.href+'"]');

                                         var isoverlay = jQuery(currtElem).attr('data-overlay')

                                        if(isoverlay=="1" && jQuery.trim(title)!=""){
                                            return '<span id="fancybox_vg-title-over">' + title  + '</span>';
                                         }
                                        else{
                                                 return '';
                                             }

                                           }

                                        });


                                    var width__ = jQuery(window).width();
                                    var timer__;
                                    jQuery(window).bind('resize', function(){
                                        if(jQuery(window).width() != width__){

                                            width__ = jQuery(window).width();
                                            timer__ && clearTimeout(timer__);
                                            timer__ = setTimeout(function(){ 

                                            videoPlacements('<?php echo $rand_Numb; ?>',jQuery);

                                            }, 200);

                                        }  
                                    });
                                }    
                            }, 100);
                                                            
                             </script>
                             <div class="clear_div"></div><!-- end print_responsive_video_grid_func -->
                 
                                                                                           
         <?php } 
	$output = ob_get_clean();
	return $output;
}
function responsive_video_grid_get_wp_version() {
	global $wp_version;
	return $wp_version;
}

// also we will add an option function that will check for plugin admin page or not
function responsive_video_grid_is_plugin_page() {
	$server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	
	foreach ( array (
			'video_grid_with_lightbox_video_management','video_grid_with_lightbox'
	) as $allowURI ) {
		if (stristr ( $server_uri, $allowURI ))
			return true;
	}
	return false;
}

// add media WP scripts
function responsive_video_grid_admin_scripts_init() {
	if (responsive_video_grid_is_plugin_page()) {
		// double check for WordPress version and function exists
		if (function_exists ( 'wp_enqueue_media' ) && version_compare ( responsive_video_grid_get_wp_version(), '3.5', '>=' )) {
			// call for new media manager
			wp_enqueue_media();
		}
		wp_enqueue_style ( 'media' );
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'wp-color-picker' );
	}
}

function prvg_remove_extra_p_tags($content){

        if(strpos($content, 'print_responsive_video_grid_func')!==false){
        
            
            $pattern = "/<!-- print_responsive_video_grid_func -->(.*)<!-- end print_responsive_video_grid_func -->/Uis"; 
            $content = preg_replace_callback($pattern, function($matches) {


               $altered = str_replace("<p>","",$matches[1]);
               $altered = str_replace("</p>","",$altered);
              
                $altered=str_replace("&#038;","&",$altered);
                $altered=str_replace("&#8221;",'"',$altered);
              

              return @str_replace($matches[1], $altered, $matches[0]);
            }, $content);

              
            
        }
        
        $content = str_replace("<p><!-- print_responsive_video_grid_func -->","<!-- print_responsive_video_grid_func -->",$content);
        $content = str_replace("<!-- end print_responsive_video_grid_func --></p>","<!-- end print_responsive_video_grid_func -->",$content);
        
        
        return $content;
  }

  add_filter('widget_text_content', 'prvg_remove_extra_p_tags', 999);
  add_filter('the_content', 'prvg_remove_extra_p_tags', 999);

  
    function i13_video_grid_pro_render_block_defaults($block_content, $block) { 

        $block_content=prvg_remove_extra_p_tags($block_content);
        return $block_content; 

    }


    add_filter( 'render_block', 'i13_video_grid_pro_render_block_defaults', 10, 2 );

