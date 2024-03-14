<?php
defined('ABSPATH') or die('Exit');
class Truepush_main
{
    public static function tp_config_page()
        {
            if (!Truepush_Initialize::is_authorised()) {
                set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                        <p><strong>Truepush :</strong><em> Only administrators are allowed to save plugin settings.</em></p>
                    </div>', 86400);
                return;
            }
            if($_POST['welcomeNotificationTitle']=="")
            {
                $_POST['welcomeNotificationTitle']="Welcome :)";
            }
            if($_POST['welcomeNotificationMessage']=="")
            {
                $_POST['welcomeNotificationMessage']="Thanks for subscribing";
            }
            if($_POST['welcomeNotificationUrl']=="")
            {
            }
            else {
                $website = sanitize_url($_POST['welcomeNotificationUrl']);
                if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                   set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                    <p><strong>Truepush :</strong><em> Welcome notification is not a valid url. Please enter the valid url</em></p>
                </div>', 86400);
                return;
                }
              } 
            $tpSettings = Truepush_Initialize::getTpSettings();
            $new_app_id = sanitize_key($_POST['platform_id']);
            $tpSettings['platform_id'] = trim($new_app_id);
            $tpSettings['welcomeNotification'] =  sanitize_text_field($_POST['welcomeNotification']);
            $tpSettings['welcomeNotificationUserInteraction'] =  sanitize_text_field($_POST['welcomeNotificationUserInteraction']);
            $booleanSettings = array(
            'welcomeNotification',
            'welcomeNotificationUserInteraction',
            'tp_publishNotification',
            'notification_on_post_from_plugin',
            'iconFromPost',
            'imageFromPost',
            'chrome_auto_dismiss_notifications',
            );
            self::booleanVariables($tpSettings, $booleanSettings);
            $stringSettings = array(
            'truepush_api_key',
            'welcomeNotificationTitle',
            'welcomeNotificationMessage',
            'welcomeNotificationUrl',
            'allowed_custom_post_types',
            'notificationTitle',
            );
            self::stringVariables($tpSettings, $stringSettings);
            Truepush_Initialize::saveTpSettings($tpSettings);
            if($_POST['welcomeNotification']=="on")
            {
                self::save_truepush_settings_to_server($tpSettings, $stringSettings);
            }
            return;
        }
        public static function save_truepush_settings_to_server($tpSettings, $stringSettings)
        {
            try {
                if( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                    return;
                }
                if($config['welcomeNotificationUserInteraction']=="on")
                {
                    $truepush_user_is_interact = true;
                }
                else{
                    $truepush_user_is_interact = false;
                }
                
                    $fields = array(
                        'app_id' =>  trim(sanitize_key($_POST['platform_id'])),
                        'title' => trim(sanitize_text_field($_POST['welcomeNotificationTitle'])),
                        'message'=> trim(sanitize_text_field($_POST['welcomeNotificationMessage'])),
                        'notify_url'=> trim(sanitize_url($_POST['welcomeNotificationUrl'])),
                        'is_user_interact'=>  sanitize_text_field($truepush_user_is_interact),
                    );
                    $truepush_update_url = 'https://api.truepush.com/plugin/v1/welcomeNotificationupdate';
                    if($tpSettings['truepush_api_key']!="")
                    {
                        $truepush_auth_key = trim($tpSettings['truepush_api_key']);
                    }
                    else
                    {
                        $truepush_auth_key = trim(sanitize_key($_POST['truepush_api_key']));
                    }
                    $request = array(
                        'headers' => array(
                                    'content-type' => 'application/json;charset=utf-8',
                                    'authorization' => $truepush_auth_key,
                            ),
                        'body' => wp_json_encode($fields),
                        'timeout' => 3,
                    );
                    $response = self::exec_update_request($truepush_update_url, $request); 
                    $newresponse = $response['body'];
                    $datares = json_decode($newresponse);
                    $responceerrormsg =  $datares->status;
                   if ($responceerrormsg == 'ERROR') {
                        set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                            <p><strong>Truepush :</strong><em> There was a problem updating your welcome notification.</em></p>
                            </div>', 86400);
                        return;
                    }
                     if (is_wp_error($response)) {
                        set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                            <p><strong>Truepush :</strong><em> There was a problem updating your welcome notification.</em></p>
                            </div>', 86400);
                        return;
                    }
                    if (isset($response['body'])) {
                        $truepush_response = json_encode($response['body'], true);
                         $bodydata = json_decode($truepush_response, true);
                        $bodyvalue = json_decode($bodydata);
            }

               if (!empty($response)) {
                $tot_message=$bodyvalue->message;
                    $url_dat=$tot_message->dat->url;
                    if($_POST['welcomeNotificationUrl'] != ($url_dat))
                    {
                        $_POST['welcomeNotificationUrl']=$url_dat;
                        self::stringVariables($tpSettings, $stringSettings);
                        Truepush_Initialize::saveTpSettings($tpSettings);
                    }
                    set_transient('truepush_success_message', '<div class="updated notice notice-success is-dismissible">
                    <p><strong>Truepush :</strong><em>.'.$tot_message->mess.'</em></p>  </div>', 86400);
               }
            } catch (Exception $e) {
                return new WP_Error('err', __( "Truepush: There was a problem updating your welcome notification"));
            }
        }
        public static function booleanVariables(&$tpSettings, $settings)
        {
            foreach ($settings as $setting) {
                if (array_key_exists($setting, $_POST)) {
                    $tpSettings[$setting] = true;
                } else {
                    $tpSettings[$setting] = false;
                }
            }
        }
        public static function stringVariables(&$tpSettings, $settings)
        {
            foreach ($settings as $setting) {
                $value = sanitize_text_field($_POST[$setting]);

                if ($setting === 'truepush_api_key') {
                    if (Truepush_Initialize::maskedApiKey($tpSettings[$setting]) === $value)
                        continue;
                }

                $tpSettings[$setting] = $value;
            }
        }
        public static function tp_config_settings_form()
        {

            if (array_key_exists('platform_id', $_POST)) {
                check_admin_referer(Truepush_Install::$wpConfigNonceAction, Truepush_Install::$wpConfigNonceKey);
                Truepush_main::tp_config_page();
                // Truepush_main::tp_config_page($_POST);
            }
        }
       
        public static function load_truepush_scripts()
        {
        }
        public static function exec_post_request($truepush_post_url, $request) { 
           $response = wp_remote_post($truepush_post_url, $request);
            return $response;
        }
        public static function exec_update_request($truepush_update_url, $request) { 
            $response = wp_remote_post($truepush_update_url, $request);
            return $response;
        }
        public static function send_notification_to_server($new_status, $old_status, $post)
        {
            try {
                if( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                    return;
                }
                $was_posted = !empty($_POST);
                if ($was_posted && !wp_verify_nonce((
                    isset($_POST[Truepush_Install::$wpNoncekey]) ? 
                    sanitize_text_field($_POST[Truepush_Install::$wpNoncekey]) : 
                    ''
                ), Truepush_Install::$wpNonceaction)) {
                    return;
                }
                $tpSettings = Truepush_Initialize::getTpSettings();
                $post_metadata_was_truepush_meta_box_present = (get_post_meta($post->ID, 'truepush_meta_box_present', true) === '1');
                $truepush_meta_box_present = $was_posted && isset($_POST['truepush_meta_box_present'], $_POST['truepush_meta_box_present']) && $_POST['truepush_meta_box_present'] === 'true';
                $truepush_meta_box_send_notification_checked = $was_posted && array_key_exists('send_truepush_notification', $_POST) && $_POST['send_truepush_notification'] === 'true';
                $posted_from_wordpress_editor = $truepush_meta_box_present || $post_metadata_was_truepush_meta_box_present;
                $post_metadata_was_send_notification_checked = (get_post_meta($post->ID, 'truepush_send_notification', true) === '1');
                
                
                if ($posted_from_wordpress_editor) {
                    $do_send_notification = ($was_posted && $truepush_meta_box_send_notification_checked) ||
                                        (!$was_posted && $post_metadata_was_send_notification_checked);
                } else {
                    
                }

                if ( ! is_post_type_viewable( $post->post_type ) ) {
                    $do_send_notification = false;
                }

                if (has_filter('truepush_include_post')) {
                    if (apply_filters('truepush_include_post', $new_status, $old_status, $post)) {
                        $do_send_notification = true;
                    }
                }
                if ($do_send_notification) {
                    update_post_meta($post->ID, 'truepush_meta_box_present', false);
                    update_post_meta($post->ID, 'truepush_send_notification', false);
                    if ($was_posted) {
                        if (array_key_exists('truepush_meta_box_present', $_POST)) {
                            unset($_POST['truepush_meta_box_present']);
                        }
                        if (array_key_exists('send_truepush_notification', $_POST)) {
                            unset($_POST['send_truepush_notification']);
                        }
                    }
                    $notif_content = Truepush_Initialize::string_to_html(get_the_title($post->ID));
                    $site_title = '';
                    $user_specific = '';
                   if(!empty( $_POST['site_titlee'] )) {
                        if($_POST['site_titlee']=="on") {
                        
                        if( !empty( $_POST['notify_title'] ) && !empty( $_POST['notify_content'] ) ) {

                            $tpSettings['notificationTitle'] = sanitize_text_field($_POST['notify_title']);
                            $notif_content = sanitize_text_field($_POST['notify_content']);
                            $user_specific = 'yes';                   
                        }
                        else {
                            if( !empty( $_POST['notify_content'] ) ) {
                                $notif_content = sanitize_text_field($_POST['notify_content']);
                            }
                            if( !empty( $_POST['notify_title'] ) ) {
                                $tpSettings['notificationTitle'] = sanitize_text_field($_POST['notify_title']);
                            }
                        }
                    }
                    }
                    if ($tpSettings['notificationTitle'] !== '') {
                        $site_title = Truepush_Initialize::string_to_html($tpSettings['notificationTitle']);
                    } else {
                        $site_title = Truepush_Initialize::string_to_html(get_bloginfo('name'));
                    }
                    if (function_exists('qtrans_getLanguage')) {
                        try {
                            $qtransLang = qtrans_getLanguage();
                            $site_title = qtrans_use($qtransLang, $site_title, false);
                            $notif_content = qtrans_use($qtransLang, $notif_content, false);
                        } catch (Exception $e) {
                            return new WP_Error('err', __( "Truepush: Unknown error. Try again."));
                        }
                    }
                    $fields = array(
                        'app_id' => $tpSettings['platform_id'],
                        'title' => $site_title,
                        'message'  => $notif_content,
                        'link' => get_permalink($post->ID),
                        'postId' => $post->ID
                    );
                    if (has_post_thumbnail($post->ID)) {
                        $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                        $thumbnail_sized_images_array = wp_get_attachment_image_src($post_thumbnail_id, array(192, 192), true);
                        $large_sized_images_array = wp_get_attachment_image_src($post_thumbnail_id, 'large', true);

                        $config_use_featured_image_as_icon = $tpSettings['iconFromPost'] === true;
                        $config_use_featured_image_as_image = $tpSettings['imageFromPost'] === true;

                        if ($config_use_featured_image_as_icon) {
                            $thumbnail_image = $thumbnail_sized_images_array[0];
                            $fields['chrome_web_icon'] = $thumbnail_image;
                            $fields['firefox_icon'] = $thumbnail_image;
                        }
                        if ($config_use_featured_image_as_image) {
                            $large_image = $large_sized_images_array[0];
                            $fields['chrome_web_image'] = $large_image;
                            $fields['image'] = $large_image;
                        }
                    }
                           if($user_specific==="yes")
                        {
                            $fields['user_specific'] = true;
                        }
                    if (has_filter('truepush_send_notification')) {
                        $fields = apply_filters('truepush_send_notification', $fields, $new_status, $old_status, $post);

                         if (array_key_exists('do_send_notification', $fields) && $fields['do_send_notification'] === false) {
                            return;
                        }
                    }
                    $truepush_post_url = 'https://api.truepush.com/plugin/v1/sendCampaign';
                    $truepush_auth_key = $tpSettings['truepush_api_key'];
                    $request = array(
                        'headers' => array(
                                    'content-type' => 'application/json;charset=utf-8',
                                    'authorization' => $truepush_auth_key,
                            ),
                        'body' => wp_json_encode($fields),
                        'timeout' => 10,
                    );
                    $response = self::exec_post_request($truepush_post_url, $request); 
                    if (is_null($response)) {
                        set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                            <p><strong>Truepush :</strong><em> Unknown error. Try again.</em></p>
                            </div>', 86400);
                        return;
                    }
                    if (isset($response['body'])) {
                                $truepush_response = json_decode($response['body'], true);
                            }
                    if (isset($response['response'])) {
                        $status = $response['response']['status_code'];
                    }
                    update_post_meta($post->ID, 'truepush_response', wp_json_encode($truepush_response));

                    if (defined('TRUEPUSH_DEBUG') || class_exists('WDS_Log_Post')) {
                        fclose($out);
                    }
                    return $response;
                }
            } catch (Exception $e) {
                return new WP_Error('err', __( "Truepush: Unknown error. Try again."));
            } 
		}
	    public static function post_restore_status($old_status, $new_status)
        {
            return $old_status === 'trash' && $new_status === 'publish';
        }
        public static function save_truepush_message_to_server($post)
        {
            try {
                if( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                    return;
                }
                if(!empty( $_POST['site_titlee'] )) {
                    $tpSettings = Truepush_Initialize::getTpSettings();
                    $notif_content_edit = '';
                    $site_title_edit = '';
                    $site_title_edit_main = '';
                    if($_POST['site_titlee']=="on") {
                    if( !empty( $_POST['notify_title'] ) && !empty( $_POST['notify_content'] ) ) {

                        $site_title_edit = sanitize_text_field($_POST['notify_title']);
                        $notif_content_edit = sanitize_text_field($_POST['notify_content']);
                        }
                    else {
                        if( !empty( $_POST['notify_content'] ) ) {
                            $notif_content_edit = sanitize_text_field($_POST['notify_content']);
                        }
                        if( !empty( $_POST['notify_title'] ) ) {
                            $site_title_edit = sanitize_text_field($_POST['notify_title']);
                        }
                    }
                    $site_title_edit_main = Truepush_Initialize::string_to_html($site_title_edit);
                    if (function_exists('qtrans_getLanguage')) {
                        try {
                            $qtransLang = qtrans_getLanguage();
                            $site_title_edit_main = qtrans_use($qtransLang, $site_title_edit_main, false);
                            $notif_content_edit = qtrans_use($qtransLang, $notif_content_edit, false);
                        } catch (Exception $e) {
                            return new WP_Error('err', __( "Truepush: Unknown error. Try again."));
                        }
                    }
                    $fields = array(
                        'app_id' => $tpSettings['platform_id'],
                        'title' => $site_title_edit_main,
                        'message'  => $notif_content_edit,
                        'postId' => $post->ID,
                    );
                    $truepush_update_url = 'https://api.truepush.com/plugin/v1/saveWordpressupdate';
                    if($tpSettings['truepush_api_key']!="")
                    {
                        $truepush_auth_key = trim($tpSettings['truepush_api_key']);
                    }
                    else
                    {
                        $truepush_auth_key = trim($config['truepush_api_key']);
                    }                    
                    $request = array(
                        'headers' => array(
                                    'content-type' => 'application/json;charset=utf-8',
                                    'authorization' => $truepush_auth_key,
                            ),
                        'body' => wp_json_encode($fields),
                        'timeout' => 3,
                    );
                     $response = self::exec_update_request($truepush_update_url, $request);  
                     if (is_null($response)) {
                         set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                             <p><strong>Truepush :</strong><em> There was a problem updating your future notification.</em></p>
                             </div>', 86400);
                         return;
                     }
                     if (is_wp_error($response)) {
                        set_transient('truepush_error_message', '<div class="error notice truepush-error-notice">
                            <p><strong>Truepush :</strong><em> There was a problem updating your future notification.</em></p>
                            </div>', 86400);
                        return;
                    }
                    if (isset($response['body'])) {
                        $truepush_response = json_encode($response['body'], true);
                         $bodydata = json_decode($truepush_response, true);
                        $bodyvalue = json_decode($bodydata);
                        }
                
                    }
                }
            } catch (Exception $e) {
                return new WP_Error('err', __( "Truepush: There was a problem updating your future notification"));
            }
        }
}