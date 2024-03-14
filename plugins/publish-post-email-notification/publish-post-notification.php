<?php
 /* 
    Plugin Name: Send email notification to author when post is published
    Plugin URI:https://www.i13websolution.com/
    Description: Send email notification to author when post is published
    Author:I Thirteen Web Solution
    Version:1.0.2.3
    Text Domain:publish-post-email-notification
    Author URI:https://www.i13websolution.com/product/wordpress-publish-post-email-notification-pro-plugin/
*/  

  add_action('admin_menu', 'load_submenu');
  //add_action( 'admin_init', 'publish_post_notification_plugin_admin_init' );
  register_activation_hook ( __FILE__, 'ppn_publish_post_notification_add_access_capabilities' );
  register_deactivation_hook(__FILE__,'ppn_publish_post_notification_remove_access_capabilities');
  add_filter( 'user_has_cap', 'ppn_publish_post_notification_admin_cap_list' , 10, 4 );
  add_action('plugins_loaded', 'ppn_publish_post_notification_lang');
  add_action( 'transition_post_status', 'send_email_notification', 10, 3 );
  
  function ppn_publish_post_notification_lang() {
            
            load_plugin_textdomain( 'publish-post-email-notification', false, basename( dirname( __FILE__ ) ) . '/languages/' );
            add_filter( 'map_meta_cap',  'map_ppn_publish_post_notification_meta_caps', 10, 4 );
   }
  
  function publish_post_notification_plugin_admin_init(){
    
        $url = plugin_dir_url(__FILE__);  
        wp_enqueue_script('jquery'); 
        wp_enqueue_script( 'jqueryValidate', $url.'js/jqueryValidate.js' ); 
        wp_enqueue_style( 'admincss', plugins_url('/css/styles.css', __FILE__) );
  
  }
  
  function load_submenu(){
  
        $hook_suffix_email_notify=add_submenu_page( 'options-general.php', 'Publish post notification options', 'Publish post email template', 'manage_options', 'manage_publish_post_notification_options', 'manage_publish_post_notification_options_func' );
        add_action( 'load-' . $hook_suffix_email_notify , 'publish_post_notification_plugin_admin_init' );
  }
  
  function map_ppn_publish_post_notification_meta_caps( array $caps, $cap, $user_id, array $args  ) {
        
       
        if ( ! in_array( $cap, array(
                                        'ppn_publish_post_notification',
                                     
                                      
                                    ), true ) ) {
            
			return $caps;
         }

       
         
   
        $caps = array();

        switch ( $cap ) {
            
                 case 'ppn_publish_post_notification':
                        $caps[] = 'ppn_publish_post_notification';
                        break;
              
           
             
                default:
                        
                        $caps[] = 'do_not_allow';
                        break;
        }

      
     return apply_filters( 'ppn_publish_post_notification_meta_caps', $caps, $cap, $user_id, $args );
}


 function ppn_publish_post_notification_admin_cap_list($allcaps, $caps, $args, $user){
        
        
        if ( ! in_array( 'administrator', $user->roles ) ) {
            
            return $allcaps;
        }
        else{
            
            if(!isset($allcaps['ppn_publish_post_notification'])){
                
                $allcaps['ppn_publish_post_notification']=true;
            }
            
           
         
        }
        
        return $allcaps;
        
  }
    

function  ppn_publish_post_notification_add_access_capabilities() {
     
    // Capabilities for all roles.
    $roles = array( 'administrator' );
    foreach ( $roles as $role ) {
        
            $role = get_role( $role );
            if ( empty( $role ) ) {
                    continue;
            }
         
            
            if(!$role->has_cap( 'ppn_publish_post_notification' ) ){
            
                    $role->add_cap( 'ppn_publish_post_notification' );
            }
            
           
            
         
    }
    
    $user = wp_get_current_user();
    $user->get_role_caps();
    
}

function ppn_publish_post_notification_remove_access_capabilities(){
    
    global $wp_roles;

    if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
    }

    foreach ( $wp_roles->roles as $role => $details ) {
            $role = $wp_roles->get_role( $role );
            if ( empty( $role ) ) {
                    continue;
            }

            $role->remove_cap( 'ppn_publish_post_notification' );
          
       

    }

    // Refresh current set of capabilities of the user, to be able to directly use the new caps.
    $user = wp_get_current_user();
    $user->get_role_caps();
    
}    
 

  
  
  
  function send_email_notification( $new_status, $old_status, $post ){
      
      $post_ID=$post->ID;
      
      $postType=get_post_type($post_ID);
      
      if($postType=="post" ){  
          
            if ( 'publish' !== $new_status )
              return;

           if('publish' != $old_status){


             $meta_values = get_post_meta($post_ID, 'is_notified', true);

             if($meta_values!='yes'){
       
                $pub_post = get_post($post_ID);
                $author_id=$pub_post->post_author;
                $post_title=$pub_post->post_title;
                $postperma=get_permalink( $post_ID );
                $user_info = get_userdata($author_id);

                $usernameauth=$user_info->user_login;
                $user_nicename=$user_info->user_nicename;
                $user_email=$user_info->user_email;
                $first_name=$user_info->user_firstname;
                $last_name=$user_info->user_lastname;

                $blog_title = get_bloginfo('name');
                $siteurl=get_bloginfo('wpurl');  
                $siteurlhtml="<a href='$siteurl' target='_blank' >$siteurl</a>";



                $publish_post_notification_settings=get_option('publish_post_notification_settings');  

                $subject=$publish_post_notification_settings['subject'];
                $from_name=$publish_post_notification_settings['from_name'];
                $from_email=$publish_post_notification_settings['from_email'];
                $emailBody=$publish_post_notification_settings['emailBody'];
                $emailBody=stripslashes($emailBody);
                $emailBody=str_replace('[username]',$usernameauth,$emailBody); 
                $emailBody=str_replace('[user_login]',$usernameauth,$emailBody); 
                $emailBody=str_replace('[user_nicename]',$user_nicename,$emailBody); 
                $emailBody=str_replace('[user_email]',$user_email,$emailBody); 
                $emailBody=str_replace('[first_name]',$first_name,$emailBody); 
                $emailBody=str_replace('[last_name]',$last_name,$emailBody);
                

                $emailBody=str_replace('[published_post_link_plain]',$postperma,$emailBody); 

                $postlinkhtml="<a href='$postperma' target='_blank'>$postperma</a>";

                $emailBody=str_replace('[published_post_link_html]',$postlinkhtml,$emailBody); 

                $emailBody=str_replace('[published_post_title]',$post_title,$emailBody); 
                $emailBody=str_replace('[site_name]',$blog_title,$emailBody); 
                $emailBody=str_replace('[site_url]',$siteurl,$emailBody); 
                $emailBody=str_replace('[site_url_html]',$siteurlhtml,$emailBody); 
                //$emailBody= nl2br($emailBody);
                $emailBody=stripslashes(htmlspecialchars_decode($emailBody));
                
                $charSet=get_bloginfo( "charset" );   
                $mailheaders='';
                //$mailheaders .= "X-Priority: 1\n";
                $mailheaders .= "Content-Type: text/html; charset=\"$charSet\"\n";
                $mailheaders .= "From: $from_name <$from_email>" . "\r\n";
                //$mailheaders .= "Bcc: $emailTo" . "\r\n";
                $emailBody=wpautop($emailBody);
                $emailBody='<!DOCTYPE html><html '.get_language_attributes().'><head> <meta http-equiv="Content-Type" content="text/html; charset='. get_bloginfo( "charset" ).'" /><title>'.get_bloginfo( 'name', 'display' ).'</title></head><body>'.$emailBody.'</body></html>';
                    
                
                $Rreturns=wp_mail($user_email, $subject, $emailBody, $mailheaders);

                if($Rreturns){
                  add_post_meta($post_ID, 'is_notified', 'yes');
                } 
            }
            
         } 
      }   
  }

  function manage_publish_post_notification_options_func(){
  
  if(isset($_POST['savesettings'])){
  
        $subject=sanitize_text_field($_POST['email_subject']);
        $from_name=sanitize_text_field($_POST['email_From_name']);
        $from_email=sanitize_text_field($_POST['email_From']);
         $emailBody=wp_kses_post($_POST['txtArea']);
        if(function_exists('get_magic_quotes_gpc')){
            if(get_magic_quotes_gpc()){
              $emailBody=addslashes($emailBody);  
            }
        }
        
        $emailBody=htmlentities($emailBody);
        

        $publish_post_notification_settings=array('subject'=>$subject,'from_name'=>$from_name,'from_email'=>$from_email,'emailBody'=>$emailBody);
        update_option('publish_post_notification_settings',$publish_post_notification_settings); 
        $publish_post_notification_settings=get_option('publish_post_notification_settings');
  
  ?>
  
  <div class='notice notice-success is-dismissible'><p><?php echo __ ( 'Settings updated successfully','publish-post-email-notification' );?></p></div> 
 
 <?php 
  }
 else{
 
     $publish_post_notification_settings=get_option('publish_post_notification_settings');
     
     
     
 } 
?>  
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
<a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=nvgandhi123@gmail.com&amp;item_name=Publish Post Email Notification&amp;item_number=publish post notification support&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8">
<img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
</a>
</td>
</tr>
</table>
<span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/wordpress-publish-post-email-notification-pro-plugin/"><?php echo __ ( 'Update to Publish Post Email Notification Pro','publish-post-email-notification' );?></a></h3></span>
<br/>
<h3><?php echo __ ( 'Post notification email template settings','publish-post-email-notification' );?></h3>  
  
 <div style="width: 100%;">  
     <div style="float:left;" >
      
        <form name="publishpostfrm" id='publishpostfrm' method="post" action=""> 
        <table class="form-table" style="" >
        <tbody>
          <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right;"><?php echo __ ( 'Subject','publish-post-email-notification' );?> *</th>
             <td>    
                <input type="text" id="email_subject" name="email_subject" value="<?php echo isset($publish_post_notification_settings['subject'])?esc_html(stripslashes($publish_post_notification_settings['subject'])):'';?>"  class="valid" size="70">
                <div style="clear: both;"></div><div></div>
              </td>
           </tr>
           <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right"><?php echo __ ( 'Email From Name','publish-post-email-notification' );?> *</th>
             <td>    
                <input type="text" id="email_From_name" name="email_From_name"  value="<?php echo isset($publish_post_notification_settings['from_name'])?esc_html(stripslashes($publish_post_notification_settings['from_name'])):'';?>" class="valid" size="70">
                 <br/><?php echo __ ( '(ex. admin)','publish-post-email-notification' );?>  
                <div style="clear: both;"></div><div></div>
               
              </td>
           </tr>
           <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right"><?php echo __ ( 'Email From','publish-post-email-notification' );?>  *</th>
             <td>    
                <input type="text" id="email_From" name="email_From" value="<?php echo isset($publish_post_notification_settings['from_email']) ? esc_html(stripslashes($publish_post_notification_settings['from_email'])):'';?>"  class="valid" size="70">
                <br/><?php echo __ ( '(ex. admin@yoursite.com)','publish-post-email-notification' );?> 
                <div style="clear: both;"></div><div></div>
          
              </td>
           </tr>
          
           <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right"><?php echo __ ('Email Body','publish-post-email-notification' );?> *</th>
              <?php
                   
                    $emailBody=isset($publish_post_notification_settings['emailBody'])?stripslashes($publish_post_notification_settings['emailBody']):'';  
                    $emailBody=html_entity_decode($emailBody);
               ?>

             <td>    
               <div class="wrap">
               <?php wp_editor( $emailBody, 'txtArea' );?>    
               <input type="hidden" name="editor_val" id="editor_val" />
                 <div style="clear: both;"></div><div></div> 
                </div>
                <span><?php echo __ ('You can use','publish-post-email-notification' );?> [username] , [user_login] , [user_nicename] , [user_email] , [first_name] , [last_name] ,[published_post_link_html] , [published_post_link_plain] ,
                    [published_post_title] , [site_name] , [site_url],[site_url_html] <?php echo __ ('place holder into email body','publish-post-email-notification' );?></span>   
              </td>
           </tr>
           
              <tr valign="top" id="subject">
             <th scope="row" style="width:30%"></th>
             <td> 
                <?php wp_nonce_field('action_settings_nonce','add_edit_nonce'); ?>
               <input type='submit'  value='Save Settings' name='savesettings' class='button-primary' id='savesettings' >  
              </td>
           </tr> 
           
           
        </table>
        </form>
     </div>
        
 </div>   

    <script type="text/javascript">


     jQuery(document).ready(function() {

        jQuery.validator.addMethod("chkCont", function(value, element) {


                 var editorcontent=tinyMCE.get('txtArea').getContent();
               if (editorcontent.length){
                 return true;
               }
               else{
                  return false;
               }


          },
               "Please enter email content"
       );

       jQuery("#publishpostfrm").validate({
                        errorClass: "error_admin_massemail",
                        rules: {
                                     email_subject: { 
                                            required: true
                                      },
                                      email_From_name: { 
                                            required: true
                                      },  
                                      email_From: { 
                                            required: true ,email:true
                                      }, 
                                     editor_val:{
                                        chkCont: true 
                                     }  
                                
                           }, 
          
                                errorPlacement: function(error, element) {
                                error.appendTo( element.next().next());
                          }
                          
                     });
                          

      });
     
     </script> 


<?php  
  }

?>