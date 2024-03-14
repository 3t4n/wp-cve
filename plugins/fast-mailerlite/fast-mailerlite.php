<?php
/*
 * Plugin Name: Fast MailerLite
 * Plugin URI: https://www.fastflow.io/products/fast-mailerlite
 * Description: MailerLite addon for Fast Flow
 * Version: 1.1.3
 * Author: FastFlow
 * Author URI: https://www.fastflow.io
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  if (!class_exists('FastMailerLite')) {

      class FastMailerLite {

          protected $mailerlite_url;
          protected $fastflow_mailerlite_apikey;
          protected $user_id;
          protected $prod_id;

          public function __construct() {
              $this->mailerlite_url = 'https://api.mailerlite.com/api/v2';
              $this->fast_mailerlite_apikey();
              register_activation_hook(__FILE__, array($this, 'fast_mailerlite_activate'));
              register_deactivation_hook(__FILE__, array($this, 'fast_mailerlite_deactivate'));
              add_action('admin_notices', array($this, 'fast_mailerlite_admin_notice__error'));
              add_filter('ff_settings', array($this, 'fast_mailerlite_settings_html'), 82, 1 );
              add_filter('ff_settings_data', array($this, 'fast_process_mailerlite_data'), 82, 1);
              add_filter('FM_AR_select_options_addons', array($this, 'fast_mailerlite_AR_select_options'), 82, 2 );
              add_filter('FM_AR_options_HTML_addons', array($this, 'fast_mailerlite_AR_options_HTML'), 82, 2 );
              add_action('FM_add_to_AR_addons', array($this, 'fast_mailerlite_add_to_AR'), 82, 2 );
              add_action( 'user_register', array($this, 'fast_mailerlite_user_registration'), 99, 1 );
              add_action('profile_update', array($this, 'fast_mailerlite_profile_update'), 99, 2 );
              add_action( 'FM_after_member_registered', array($this, 'fast_mailerlite_fm_user_registration'), 99, 3 );
              add_action( 'FM_after_transaction_recorded', array($this, 'fast_mailerlite_stripe_user_registration'), 99, 3 );
              add_action( 'after_tag_applied_hook', array($this, 'fast_mailerlite_ftag'), 99, 2 );
              add_action( 'admin_footer', array($this, 'fast_mailerlite_admin_script'));
              add_action('wp_ajax_nopriv_fastflow_mailerlite_group_subscriber_sync', array($this, 'fastflow_mailerlite_group_subscriber_sync'));
              add_action('wp_ajax_fastflow_mailerlite_group_subscriber_sync', array($this, 'fastflow_mailerlite_group_subscriber_sync'));
              add_action('FF_add_to_AR_addons', array($this, 'fast_mailerlite_add_FF_user_to_AR'), 82, 2 );
          }

          public function fast_mailerlite_activate() {
            $this->fast_mailerlite_create_webhooks();
          }

          public function fast_mailerlite_deactivate() {
              flush_rewrite_rules();
          }

          public function fast_mailerlite_apikey(){
            $mailerlite_db = $this->fast_mailerlite_settings_db('Mailer Lite');
            $mailerlite_options = empty( $mailerlite_db->settings_data ) ? array() : unserialize( $mailerlite_db->settings_data );
            $this->fastflow_mailerlite_apikey = empty( $mailerlite_options['fastflow_mailerlite_apikey'] ) ? '' : $mailerlite_options['fastflow_mailerlite_apikey'];
          }

          public function fast_mailerlite_admin_notice__error() {
              if( is_plugin_active( 'fast-mailerlite/fast-mailerlite.php' ) ) {
                $class = 'notice notice-error';
                $message = 'Please input API keys for <a href="'.admin_url("admin.php?page=fast-flow-settings").'">Mailer Lite.</a>';
                if(!empty($this->fastflow_mailerlite_apikey)){
                  return true;
                }else{
                  printf( '<div class="%1$s is-dismissible"><p>%2$s</p></div>', esc_attr( $class ), ($message) );
                }
              }else{
                return true;
              }
            }

            public function fast_mailerlite_settings_html($html){
                $settings_html = '<h1><strong>Mailer Lite</strong></h1>';
                $settings_html .= '<div class="item-tab-box">';
                $settings_html .= '<table cellspacing="10" width="100%">
                                  <tr><td width="30%">'.__("API Key").':</td><td width="70%"><input type="text" id="fastflow_mailerlite_apikey" style="width: 390px;" name="fastflow_mailerlite_apikey" value="' . $this->fastflow_mailerlite_apikey . '" /></td></tr>
                                  <tr><td width="30%">'.__("Webhook URL").':</td><td width="70%"><input type="text"  style="width: 390px;" value="' .plugins_url('fast-mailerlite/fast-mailerlite-webhook.php'). '" disabled/></td></tr>';
                $settings_html .= '<tr><td width="30%">'.__("SYNC").':</td><td width="70%"><button type="button" style="width: 390px;" id="fastflow_mailerlite_sync_btn" class="btn">Sync Groups and Subscribers</button></td></tr>';
                $settings_html .=' </table>';
                $settings_html .= '</div>';
                return $html.$settings_html;
            }

            public function fast_process_mailerlite_data($data) {
              global $wpdb;
              $data_arr = array();
              $data_arr['fastflow_mailerlite_apikey'] = empty( $data['fastflow_mailerlite_apikey'] ) ? '' : sanitize_text_field( $data['fastflow_mailerlite_apikey'] );
              $data_ser = serialize($data_arr);
              $count = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->prefix}fastflow_settings
                                                                                  WHERE settings_for='Mailer Lite'" );

                if( $count == 1 ) {
                    $wpdb->update(
                        $wpdb->prefix . 'fastflow_settings',
                        array( 'settings_data' => $data_ser, 'extra_data' => '' ),
                        array( 'settings_for' => 'Mailer Lite' ),
                        array( '%s', '%s' ),
                        array( '%s' )
                      );
                } else {
                    $wpdb->insert(
                        $wpdb->prefix . 'fastflow_settings',
                        array( 'settings_for' => 'Mailer Lite', 'settings_data' => $data_ser, 'extra_data' => '' ),
                        array( '%s', '%s', '%s' ) );
                }
                return $data;
            }

            public function fast_mailerlite_settings_db($for = ''){
              if( !empty( $for ) && $for !== '' ) {
                  global $wpdb;
                  $data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fastflow_settings WHERE settings_for=%s", $for ) );
                  if( count($data) >= 1 ) {
                      return $data[0];
                  } else return false;
              }
              return false;
            }

            public function fast_mailerlite_AR_select_options($value, $selected_opts){
              $selected_attr = ( $selected_opts == 11 ) ? "selected='selected'" : "";
              $fsn_opts = "<option value='11'{$selected_attr}>Mailer Lite</option>";
              $return_val = $fsn_opts . $value;
              return $return_val;
            }

            public function fast_mailerlite_AR_options_HTML( $value, $selected_opts ){
                $mldisp = ( $selected_opts == 11 ) ? "block" : "none";
                $mailerlite_group_id = sanitize_text_field($_POST['mailerlite_group_id']);
                $groups = $this->fast_mailerlite_get_groups();
                $return_html = $value . "<div style='display:$mldisp' id='arbox11' class='arcontentbox'><table cellspacing='10'><tr><td style='width: 140px;'>Choose Group:</td><td>";
                $return_html .= "<select id='mailerlite_group_id' style='width: 200px;' name='mailerlite_group_id'>";
                if(!empty($groups)){
                  foreach($groups as $group){
                    $selected = ($group->id == $mailerlite_group_id)?"selected='selected'":"";
                    $return_html .= "<option value='".$group->id."' ".$selected.">".$group->name."</option>";
                  }
                }
                $return_html .= "</select>";
                $return_html .= "</td></tr></table></div>";

                return $return_html;
            }

            public function fast_mailerlite_add_to_AR($user_id, $prod_id){
              global $wpdb;
              $this->user_id = $user_id;
              $this->prod_id = $prod_id;
              $pdata = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpbn_products WHERE id=%d",$this->prod_id));
              $arservice = $pdata->arservice;
              if( $arservice != 11 ) { return; }
              $aroptions = unserialize($pdata->aroptions);
              $mailerlite_group_id = $aroptions['mailerlite_group_id'];
              $mailerlite_db = $this->fast_mailerlite_settings_db('Mailer Lite');
              $mailerlite_options = empty( $mailerlite_db->settings_data ) ? array() : unserialize( $mailerlite_db->settings_data );
              $this->fastflow_mailerlite_apikey = empty( $mailerlite_options['fastflow_mailerlite_apikey'] ) ? '' : $mailerlite_options['fastflow_mailerlite_apikey'];
              if($this->fastflow_mailerlite_apikey == ''){
                error_log( "Empty Mailer Lite Api key" );
                return;
              }
              $email = get_user_by('id', $this->user_id)->user_email;
              $first_name = get_user_meta($this->user_id, 'first_name', true);
              $last_name = get_user_meta($this->user_id, 'last_name', true);
              $full_name = $first_name . " " . $last_name;
              $mailerlite_name = trim( $full_name );
              if ( empty( $mailerlite_name ) || $mailerlite_name == "" ){ $mailerlite_name = $email;}
              $user_fast_tags = wp_get_object_terms( $this->user_id, 'fast_tag', array('order' => 'ASC'));

              $mailerlite_subscriber = $this->fast_mailerlite_get_subscriber($email);
              $mailerlite_groups = $this->fast_mailerlite_get_groups();
              if($mailerlite_subscriber){
                $mailerlite_subscriber_id = $mailerlite_subscriber->id;
                $mailerlite_subscriber_groups = $this->fast_mailerlite_get_subscriber_groups($mailerlite_subscriber_id);
                if(!in_array($mailerlite_group_id, array_column($mailerlite_subscriber_groups, 'id'))){
                  $this->fast_mailerlite_add_subscriber_to_group($mailerlite_group_id, $email, $mailerlite_name);
                }
              }else{
                $this->fast_mailerlite_add_subscriber_to_group($mailerlite_group_id, $email, $mailerlite_name);
              }
            }

            public function fast_mailerlite_profile_update($user_id, $old_user_data){
              $this->fast_mailerlite_user_subscribe_to_groups($user_id);
            }
            public function fast_mailerlite_user_registration($user_id){
              $this->fast_mailerlite_user_subscribe_to_groups($user_id);
            }
            public function fast_mailerlite_fm_user_registration($fm_id, $prodid, $user_id){
              $this->fast_mailerlite_user_subscribe_to_groups($user_id);
            }
            public function fast_mailerlite_stripe_user_registration($fm_id, $prodid, $user_id){
              $this->fast_mailerlite_user_subscribe_to_groups($user_id);
            }
            public function fast_mailerlite_ftag($tag_id, $user_id){
              $email = get_user_by('id', $user_id)->user_email;
              $first_name = get_user_meta($user_id, 'first_name', true);
              $last_name = get_user_meta($user_id, 'last_name', true);
              $full_name = $first_name . " " . $last_name;
              $mailerlite_name = trim( $full_name );
              if ( empty( $mailerlite_name ) || $mailerlite_name == "" ){ $mailerlite_name = $email;}
              $tag = get_term_by('id', $tag_id, 'fast_tag');
              $user_fast_tags[] = $tag;
              $mailerlite_subscriber = $this->fast_mailerlite_get_subscriber($email);
              $mailerlite_groups = $this->fast_mailerlite_get_groups();
              if($mailerlite_subscriber){
                $mailerlite_subscriber_id = $mailerlite_subscriber->id;
                $mailerlite_subscriber_groups = $this->fast_mailerlite_get_subscriber_groups($mailerlite_subscriber_id);
                foreach($user_fast_tags as $tag){
                  if(!in_array($tag->name, array_column($mailerlite_subscriber_groups, 'name'))){
                    if(!in_array($tag->name, array_column($mailerlite_groups, 'name'))){
                      $group = $this->fast_mailerlite_create_group($tag->name);
                      $this->fast_mailerlite_add_subscriber_to_group($group->id, $email, $mailerlite_name);
                    }else{
                      $group_key = array_search($tag->name, array_column($mailerlite_groups, 'name'));
                      $this->fast_mailerlite_add_subscriber_to_group($mailerlite_groups[$group_key]->id, $email, $mailerlite_name);
                    }
                  }else{
                    $group_key = array_search($tag->name, array_column($mailerlite_subscriber_groups, 'name'));
                    $this->fast_mailerlite_add_subscriber_to_group($mailerlite_subscriber_groups[$group_key]->id, $email, $mailerlite_name);
                  }
                }
              }else{

                if(!empty($user_fast_tags)){
                  foreach($user_fast_tags as $tag){
                    if(!in_array($tag->name, array_column($mailerlite_groups, 'name'))){
                      $group = $this->fast_mailerlite_create_group($tag->name);
                      $this->fast_mailerlite_add_subscriber_to_group($group->id, $email, $mailerlite_name);
                    }else{
                      $group_key = array_search($tag->name, array_column($mailerlite_groups, 'name'));
                      $this->fast_mailerlite_add_subscriber_to_group($mailerlite_groups[$group_key]->id, $email, $mailerlite_name);
                    }
                  }
                }
              }
            }

            public function fast_mailerlite_user_subscribe_to_groups($user_id){
              $email = get_user_by('id', $user_id)->user_email;
              $first_name = get_user_meta($user_id, 'first_name', true);
              $last_name = get_user_meta($user_id, 'last_name', true);
              $full_name = $first_name . " " . $last_name;
              $mailerlite_name = trim( $full_name );
              if ( empty( $mailerlite_name ) || $mailerlite_name == "" ){ $mailerlite_name = $email;}
              $user_fast_tags = wp_get_object_terms( $user_id, 'fast_tag', array('order' => 'ASC'));

              $mailerlite_subscriber = $this->fast_mailerlite_get_subscriber($email);
              $mailerlite_groups = $this->fast_mailerlite_get_groups();
              if($mailerlite_subscriber){
                $mailerlite_subscriber_id = $mailerlite_subscriber->id;
                $mailerlite_subscriber_groups = $this->fast_mailerlite_get_subscriber_groups($mailerlite_subscriber_id);
                foreach($user_fast_tags as $tag){
                  if(!in_array($tag->name, array_column($mailerlite_subscriber_groups, 'name'))){
                    if(!in_array($tag->name, array_column($mailerlite_groups, 'name'))){
                      $group = $this->fast_mailerlite_create_group($tag->name);
                      $this->fast_mailerlite_add_subscriber_to_group($group->id, $email, $mailerlite_name);
                    }else{
                      $group_key = array_search($tag->name, array_column($mailerlite_groups, 'name'));
                      $this->fast_mailerlite_add_subscriber_to_group($mailerlite_groups[$group_key]->id, $email, $mailerlite_name);
                    }
                  }else{
                    $group_key = array_search($tag->name, array_column($mailerlite_subscriber_groups, 'name'));
                    $this->fast_mailerlite_add_subscriber_to_group($mailerlite_subscriber_groups[$group_key]->id, $email, $mailerlite_name);
                  }
                }
                /*$diff_result = array_diff(array_column($mailerlite_subscriber_groups, 'name'), array_column($user_fast_tags, 'name'));
                if(!empty($diff_result)){
                  foreach($diff_result as $key => $diff_tag){
                    $this->fast_mailerlite_remove_subscriber_from_group($mailerlite_subscriber_groups[$key]->id, $mailerlite_subscriber_id);
                  }
                }*/
              }else{

                if(!empty($user_fast_tags)){
                  foreach($user_fast_tags as $tag){
                    if(!in_array($tag->name, array_column($mailerlite_groups, 'name'))){
                      $group = $this->fast_mailerlite_create_group($tag->name);
                      $this->fast_mailerlite_add_subscriber_to_group($group->id, $email, $mailerlite_name);
                    }else{
                      $group_key = array_search($tag->name, array_column($mailerlite_groups, 'name'));
                      $this->fast_mailerlite_add_subscriber_to_group($mailerlite_groups[$group_key]->id, $email, $mailerlite_name);
                    }
                  }
                }
              }
            }

            public function fast_mailerlite_create_group($name){
               $response = wp_remote_post( $this->mailerlite_url.'/groups', array(
                    'method' => 'GET',
                    'method' => 'POST',
                    'headers' => array(
                       'Content-Type' => 'application/json',
                       'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                     ),
                     'body' => json_encode(array(
                       'name' => $name
                     ))
               ));
               if ( !is_wp_error($response) ) {
                 $response_body = wp_remote_retrieve_body( $response );
                 $result = json_decode($response_body);
                 if(wp_remote_retrieve_response_code($response) == '201'){
                   return $result;
                 }else{
                   error_log( "Mailer Lite Error: ".$result->error->message);
                 }
               }else{
                 error_log( "Mailer Lite Error: ".$response->get_error_message());
               }
            }

            public function fast_mailerlite_get_groups(){
               $response = wp_remote_get( $this->mailerlite_url.'/groups', array(
                    'method' => 'GET',
                    'headers' => array(
                       'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                     )
               ));
               if ( !is_wp_error($response) ) {
                 $response_body = wp_remote_retrieve_body( $response );
                 $result = json_decode($response_body);
                 if(wp_remote_retrieve_response_code($response) == '200'){
                   if(!empty($result)){
                    return $result;
                   }else{
                     return [];
                   }
                 }else{
                   return [];
                 }
               }else{
                 error_log( "Mailer Lite Error: ".$response->get_error_message());
               }
            }

            public function fast_mailerlite_get_subscriber($email){
               $response = wp_remote_get( $this->mailerlite_url.'/subscribers/'.$email, array(
                    'method' => 'GET',
                    'headers' => array(
                       'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                     )
               ));
               if ( !is_wp_error($response) ) {
                 $response_body = wp_remote_retrieve_body( $response );
                 $result = json_decode($response_body);
                 if(wp_remote_retrieve_response_code($response) == '200'){
                   return $result;
                 }else if(wp_remote_retrieve_response_code($response) == '404'){
                   return false;
                 }else{
                   error_log( "Mailer Lite Error: ".$result->error->message);
                 }
               }else{
                 error_log( "Mailer Lite Error: ".$response->get_error_message());
               }
            }

            public function fast_mailerlite_check_subscriber_already_in_group($group_id, $subscriber_id){
              $response = wp_remote_get( $this->mailerlite_url.'/groups/'.$group_id.'/subscribers/'.$subscriber_id, array(
                   'method' => 'GET',
                   'headers' => array(
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    )
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  return false;
                }else if(wp_remote_retrieve_response_code($response) == '404'){
                  return true;
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }

            public function fast_mailerlite_add_subscriber_to_group($group_id, $email, $name, $fields = array()){
              $response = wp_remote_post( $this->mailerlite_url.'/groups/'.$group_id.'/subscribers', array(
                   'method' => 'POST',
                   'headers' => array(
                      'Content-Type' => 'application/json',
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    ),
                    'body' => json_encode(array(
                      'email' => $email,
                      'name' => $name,
                      'type' => 'active',
                      'fields' => $fields
                    ))
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  return $result;
                }else if(wp_remote_retrieve_response_code($response) == '404'){
                  error_log( "Mailer Lite Error: ".$result->error->message);
                  return false;
                }else{
                  error_log( "Mailer Lite Error: Bad request");
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }

            public function fast_mailerlite_get_subscriber_groups($subscriber_id){
              $response = wp_remote_get( $this->mailerlite_url.'/subscribers/'.$subscriber_id.'/groups', array(
                   'method' => 'GET',
                   'headers' => array(
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    )
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  return $result;
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }

            public function fast_mailerlite_remove_subscriber_from_group($group_id, $subscriber_id){
              $response = wp_remote_post( $this->mailerlite_url.'/groups/'.$group_id.'/subscribers/'.$subscriber_id, array(
                   'method' => 'DELETE',
                   'headers' => array(
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    )
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '404'){
                  return true;
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }

            public function fast_mailerlite_admin_script () {
              $screen = get_current_screen();
              if('fast-flow_page_fast-flow-settings' == $screen->id){
              ?>
              <script type='text/javascript'>
                jQuery(document).ready( function(){
                   jQuery('#fastflow_mailerlite_sync_btn').on('click', function(){
                     var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                     jQuery(this).addClass("updating-message");
                     jQuery.ajax({
                          type : "POST",
                          url : ajaxurl,
                          data : {action: 'fastflow_mailerlite_group_subscriber_sync'},
                          dataType : "json",
                          context : this,
                          success: function(data){
                            jQuery(this).removeClass("updating-message");
                          },
                          error: function(xhr, status, error) {
                           var err = eval("(" + xhr.responseText + ")");

                         }
                     })
                   })
                });
              </script>
              <?php
              }
            }

            public function fastflow_mailerlite_group_subscriber_sync(){
              if(is_admin() && defined('DOING_AJAX') && DOING_AJAX){
                global $wpdb;
                $users = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users");
                if($users){
                  foreach($users as $user){
                    $user_fast_tags = wp_get_object_terms( $user->ID, 'fast_tag', array('order' => 'ASC'));
                    $first_name = get_user_meta($user->ID, 'first_name', true);
                    $last_name = get_user_meta($user->ID, 'last_name', true);
                    $full_name = $first_name . " " . $last_name;
                    $mailerlite_name = trim( $full_name );
                    if ( empty( $mailerlite_name ) || $mailerlite_name == "" ){ $mailerlite_name = $user->user_email;}
                    $mailerlite_subscriber = $this->fast_mailerlite_get_subscriber($user->user_email);
                    if(!$mailerlite_subscriber){
                      if(!empty($user_fast_tags)){
                        $mailerlite_groups = $this->fast_mailerlite_get_groups();
                        foreach($user_fast_tags as $tag){
                          if(!in_array($tag->name, array_column($mailerlite_groups, 'name'))){
                            $group = $this->fast_mailerlite_create_group($tag->name);
                            $this->fast_mailerlite_add_subscriber_to_group($group->id, $user->user_email, $mailerlite_name);
                          }else{
                            $group_key = array_search($tag->name, array_column($mailerlite_groups, 'name'));
                            $this->fast_mailerlite_add_subscriber_to_group($mailerlite_groups[$group_key]->id, $user->user_email, $mailerlite_name);
                          }
                        }
                      }
                    }else{
                      $mailerlite_subscriber_id = $mailerlite_subscriber->id;
                      $mailerlite_subscriber_groups = $this->fast_mailerlite_get_subscriber_groups($mailerlite_subscriber_id);
                      if(empty($user_fast_tags)){
                        if(!empty($mailerlite_subscriber_groups)){
                          foreach($mailerlite_subscriber_groups as $group){
                            $term = term_exists( $group->name, 'fast_tag' );
                            if($term){
                              wp_set_object_terms( $user->ID, (int)$term['term_id'], 'fast_tag', true );
                              error_log('Fast Mailerlite sync: '.$group->name.' tag added to user id '.$user->ID);
                            }else{
                              $newterm = wp_insert_term($group->name,'fast_tag',array('description' => 'Mailerlite Tag'));
                              wp_set_object_terms( $user->ID, (int)$newterm['term_id'], 'fast_tag', true );
                              error_log('Fast Mailerlite sync: '.$newterm['term_id'].' tag added to user id '.$user->ID);
                            }
                          }
                        }
                      }else{

                      }
                    }
                  }
                }
                die(json_encode(array('success' => true)));
              }
            }

            public function fast_mailerlite_create_webhooks(){
              $response = wp_remote_get( $this->mailerlite_url.'/webhooks', array(
                   'method' => 'GET',
                   'headers' => array(
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    )
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  if(!in_array('subscriber.add_to_group', array_column($result->webhooks, 'event'))){
                    $this->fast_mailerlite_create_subscriber_add_to_group_webhook();
                    error_log( "Mailer Lite: subscriber.add_to_group webhook created");
                  }
                  if(!in_array('subscriber.remove_from_group', array_column($result->webhooks, 'event'))){
                    $this->fast_mailerlite_create_subscriber_remove_from_group_webhook();
                    error_log( "Mailer Lite: subscriber.remove_from_group webhook created");
                  }
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }

            public function fast_mailerlite_create_subscriber_add_to_group_webhook(){
              $response = wp_remote_post( $this->mailerlite_url.'/webhooks/', array(
                   'method' => 'POST',
                   'headers' => array(
                      'Content-Type' => 'application/json',
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    ),
                    'body' => json_encode(array(
                      'url' => plugins_url('fast-mailerlite/fast-mailerlite-webhook.php'),
                      'event' => 'subscriber.add_to_group'
                    ))
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  return $result;
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }
            public function fast_mailerlite_create_subscriber_remove_from_group_webhook(){
              $response = wp_remote_post( $this->mailerlite_url.'/webhooks/', array(
                   'method' => 'POST',
                   'headers' => array(
                      'Content-Type' => 'application/json',
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    ),
                    'body' => json_encode(array(
                      'url' => plugins_url('fast-mailerlite/fast-mailerlite-webhook.php'),
                      'event' => 'subscriber.remove_from_group'
                    ))
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  return $result;
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }

            public function fast_mailerlite_add_FF_user_to_AR($data, $form_id){


              global $wpdb;
              $fast_form = get_post($form_id);
              if($fast_form->post_type == 'fast-forms'){
                $arservice = get_post_meta( $fast_form->ID, 'ff_auto_reponder', true );
                if( $arservice != 11 ) { return; }
                $mailerlite_group_id = get_post_meta( $fast_form->ID, 'mailerlite_group_id', true );
                $mailerlite_db = $this->fast_mailerlite_settings_db('Mailer Lite');
                $mailerlite_options = empty( $mailerlite_db->settings_data ) ? array() : unserialize( $mailerlite_db->settings_data );
                $this->fastflow_mailerlite_apikey = empty( $mailerlite_options['fastflow_mailerlite_apikey'] ) ? '' : $mailerlite_options['fastflow_mailerlite_apikey'];
                if($this->fastflow_mailerlite_apikey == ''){
                  error_log( "Empty Mailer Lite Api key" );
                  return;
                }

                $mailerlite_name = trim( $data['name'] );
                $mailerlite_email = trim( $data['email'] );
                unset($data['name']);
                unset($data['email']);
                if ( empty( $mailerlite_name ) || $mailerlite_name == "" ){ $mailerlite_name = $mailerlite_email;}

                $mailerlite_subscriber = $this->fast_mailerlite_get_subscriber($mailerlite_email);
                $mailerlite_groups = $this->fast_mailerlite_get_groups();
                if($mailerlite_subscriber){
                  $mailerlite_subscriber_id = $mailerlite_subscriber->id;
                  $mailerlite_subscriber_groups = $this->fast_mailerlite_get_subscriber_groups($mailerlite_subscriber_id);

                  if(!in_array($mailerlite_group_id, array_column($mailerlite_subscriber_groups, 'id'))){
                    $this->fast_mailerlite_add_subscriber_to_group($mailerlite_group_id, $mailerlite_email, $mailerlite_name, $data);
                  }else{
                    $this->fast_mailerlite_update_subscriber($mailerlite_subscriber_id, $mailerlite_email, $mailerlite_name, $data);
                  }
                }else{
                  $this->fast_mailerlite_add_subscriber_to_group($mailerlite_group_id, $mailerlite_email, $mailerlite_name, $data);
                }

              }

            }

            public function fast_mailerlite_update_subscriber($subscriber_id, $email, $name, $fields = array()){
              $response = wp_remote_post( $this->mailerlite_url.'/subscribers/'.$subscriber_id, array(
                   'method' => 'PUT',
                   'headers' => array(
                      'Content-Type' => 'application/json',
                      'X-MailerLite-ApiKey' => $this->fastflow_mailerlite_apikey
                    ),
                    'body' => json_encode(array(
                      'type' => 'active',
                      'fields' => $fields
                    ))
              ));
              if ( !is_wp_error($response) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $result = json_decode($response_body);
                if(wp_remote_retrieve_response_code($response) == '200'){
                  return $result;
                }else if(wp_remote_retrieve_response_code($response) == '404'){
                  return false;
                }else{
                  error_log( "Mailer Lite Error: ".$result->error->message);
                }
              }else{
                error_log( "Mailer Lite Error: ".$response->get_error_message());
              }
            }
      }

  }

  new FastMailerLite();
