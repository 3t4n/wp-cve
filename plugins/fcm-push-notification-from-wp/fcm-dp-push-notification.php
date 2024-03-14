<?php
/*
Plugin Name:FCM Push Notification from WP
Description:Notify your app users using Firebase Cloud Messaging (FCM) when content is published or updated
Version:1.9.0
Author:dprogrammer
Author URI:https://www.buymeacoffee.com/dprogrammer
License:GPL2
License URI:https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
FCM Push Notification from WP is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
FCM Push Notification from WP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with MV Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!defined("FCMDPPLGPN_VERSION_CURRENT")) define("FCMDPPLGPN_VERSION_CURRENT", '1');
if (!defined("FCMDPPLGPN_URL")) define("FCMDPPLGPN_URL", plugin_dir_url( __FILE__ ) );
if (!defined("FCMDPPLGPN_PLUGIN_DIR")) define("FCMDPPLGPN_PLUGIN_DIR", plugin_dir_path(__FILE__));
if (!defined("FCMDPPLGPN_PLUGIN_NM")) define("FCMDPPLGPN_PLUGIN_NM", 'FCM Push Notification from WP');
if (!defined("FCMDPPLGPN_TRANSLATION")) define("FCMDPPLGPN_TRANSLATION", 'fcmdpplgpn_translation');

/* FCMDPPLGPN -> FCM DP(dprogrammer) PLG(plugin) PN(Push Notification) */
Class FCMDPPLGPN_Push_Notification
{

    public function __construct()
    {

        // Installation and uninstallation hooks
        register_activation_hook(__FILE__, array($this, 'fcmdpplgpn_activate'));
        register_deactivation_hook(__FILE__, array($this, 'fcmdpplgpn_deactivate'));
        add_action('admin_menu', array($this, 'fcmdpplgpn_setup_admin_menu'));
        add_action('admin_init', array($this, 'fcmdpplgpn_settings'));

        add_action('add_meta_boxes', array($this, 'fcmdpplgpn_featured_meta'), 1);
        add_action('save_post', array($this, 'fcmdpplgpn_meta_save'),1,1);

        //https://codex.wordpress.org/Post_Status_Transitions
        add_action('future_to_publish', array($this, 'fcmdpplgpn_future_to_publish'), 10, 1);

        //https://davidwalsh.name/wordpress-publish-post-hook
        //add_action('transition_post_status', array($this, 'fcmdpplgpn_send_new_post'), 10, 3);

        add_filter('plugin_action_links_fcm-push-notification-from-wp/fcm-dp-push-notification.php', array($this, 'fcmdpplgpn_settings_link') );

    }

    function fcmdpplgpn_featured_meta() {
        //add_meta_box( 'fcmdpplgpn_ckmeta_send_notification', __( 'Push Notification', FCMDPPLGPN_TRANSLATION ), array($this, 'fcmdpplgpn_meta_callback'), 'post', 'side', 'high', null );

        /* set meta box to a post type */
        $args  = array(
            'public' => true,
        );

        $post_types = get_post_types( $args, 'objects' );

        if ( $post_types ) { // If there are any custom public post types.

            foreach ( $post_types  as $post_type ) {
                if ($post_type->name != 'attachment'){
                    if ($this->get_options_posttype($post_type->name)) {
                        add_meta_box( 'fcmdpplgpn_ckmeta_send_notification', esc_attr(__( 'Push Notification', FCMDPPLGPN_TRANSLATION )), array($this, 'fcmdpplgpn_meta_callback'), $post_type->name, 'side', 'high', null );
                    }
                }
            }

        }
    }

    function fcmdpplgpn_meta_callback( $post ) {
        global $pagenow;
        wp_nonce_field( basename( __FILE__ ), 'fcmdpplgpn_nonce' );
        $fcmdpplgpn_stored_meta = get_post_meta( $post->ID );
        $checked = get_option('fcmdpplgpn_disable') != 1;//$fcmdpplgpn_stored_meta['send-fcm-checkbox'][0];

        //$this->write_log('fcmdpplgpn_meta_callback: $checked: ' . $checked);
        //$this->write_log('fcmdpplgpn_meta_callback: $post->post_status: ' . $post->post_status);

        ?>

            <p>
                <span class="fcm-row-title"><?php echo esc_html(__( 'Check if send a push notification: ', FCMDPPLGPN_TRANSLATION ));?></span>
                <div class="fcm-row-content">
                    <label for="send-fcm-checkbox">
                        <?php if (in_array( $pagenow, array( 'post-new.php' ) ) ||  $post->post_status == 'future') { ?>
                            <input type="checkbox" name="send-fcm-checkbox" id="send-fcm-checkbox" value="1" <?php if ( isset ( $fcmdpplgpn_stored_meta['send-fcm-checkbox'] ) ) checked( $checked, '1' ); ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="send-fcm-checkbox" id="send-fcm-checkbox" value="1"/>
                        <?php } ?>
                        <?php echo esc_attr(__( 'Send Push Notification', FCMDPPLGPN_TRANSLATION ));?>
                    </label>

                </div>
            </p>

        <?php
    }

    /**
     * Saves the custom meta input
     */
    function fcmdpplgpn_meta_save( $post_id ) {

        // Checks save status - overcome autosave, etc.
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[ 'fcmdpplgpn_nonce' ] ) && wp_verify_nonce( $_POST[ 'fcmdpplgpn_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

        //$this->write_log('fcmdpplgpn_meta_save');

        // Exits script depending on save status
        if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
            return;
        }

        //$this->write_log('remove_action: wp_insert_post');
        remove_action('wp_insert_post', array($this, 'fcmdpplgpn_on_post_save'),10);

        if( isset( $_POST[ 'send-fcm-checkbox' ] ) ) {
            update_post_meta( $post_id, 'send-fcm-checkbox', '1' );
            //$this->write_log('add_action: send-fcm-checkbox 1');
        } else {
            //$this->write_log('add_action: send-fcm-checkbox 0');
            update_post_meta( $post_id, 'send-fcm-checkbox', '0' );
        }

        //$this->write_log('add_action: wp_insert_post');
        add_action('wp_insert_post', array($this, 'fcmdpplgpn_on_post_save'),10, 3);

    }

    function fcmdpplgpn_future_to_publish($post) {
        //$this->write_log('fcmdpplgpn_future_to_publish: CHAMOU EVENTO');
        $this->fcmdpplgpn_send_notification_on_save($post, true);
    }

    // Listen for publishing of a new post
    /*
    function fcmdpplgpn_send_new_post($new_status, $old_status, $post) {
        //$this->write_log('fcmdpplgpn_send_new_post: CHAMOU EVENTO - ' . $new_status . ' - ' . $old_status);

        //if('publish' === $new_status && 'future' == $old_status && $post->post_type === 'post') {
        if('publish' === $new_status && 'future' == $old_status) {
            $this->write_log('fcmdpplgpn_send_new_post: CHAMOU EVENTO IF ALOU - ' . $new_status . ' - ' . $old_status);
            $this->fcmdpplgpn_send_notification_on_save($post, true);
        }
    }
    */

    // função utilizada no evento ao salvar e quando um post que foi agendado, é publicado
    function fcmdpplgpn_on_post_save($post_id, $post, $update) {
        $this->fcmdpplgpn_send_notification_on_save($post, $update);
    }

    // utilizada na função ao salvar e na função ao mudar de status de agendado para movo post
    private function fcmdpplgpn_send_notification_on_save($post, $update){
        //$this->write_log('fcmdpplgpn_on_post_save: CHAMOU EVENTO');

        if(get_option('fcmdpplgpn_api') && get_option('fcmdpplgpn_topic')) {
            //$this->write_log('fcmdpplgpn_on_post_save: ENTROU NO TESTE');
            //new post/page
            if (isset($post->post_status)) {
                //$this->write_log('fcmdpplgpn_on_post_save: ENTROU NO IF - ' . $post->post_status);

                if ($update && ($post->post_status == 'publish')) {

                    //$this->write_log('fcmdpplgpn_on_post_save: ENTROU NO SEGUNDO IF - ' . $post->post_status);

                    $send_fcmdpplgpn_checkbox = get_post_meta( $post->ID, 'send-fcm-checkbox', true );

                    //$this->write_log('send_fcmdpplgpn_checkbox: ' . $send_fcmdpplgpn_checkbox);

                    if ($send_fcmdpplgpn_checkbox) {

                        //$this->write_log('fcmdpplgpn_on_post_save: ENTROU NO SEGUNDO IF - ' . $post->post_status);

                        if ($this->get_options_posttype($post->post_type)) {
                            //($post, $sendOnlyData, $showLocalNotification, $command)
                            $result = $this->fcmdpplgpn_notification($post, false, false, '');
                            //$this->write_log('post updated: ' . $post_title);
                        } elseif ($this->get_options_posttype($post->post_type)) {
                            $result = $this->fcmdpplgpn_notification($post, false, false, '');
                            //$this->write_log('page updated: ' . $post_title);
                        }

                        update_post_meta( $post->ID, 'send-fcm-checkbox', '0' );

                    }
                }

            }
        }
    }

    public function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

    public function get_options_posttype($post_type) {
        return get_option('fcmdpplgpn_posttype_' . $post_type) == 1;
    }

    public function fcmdpplgpn_setup_admin_menu()
    {
        add_submenu_page('options-general.php', __('Firebase Push Notification', FCMDPPLGPN_TRANSLATION), FCMDPPLGPN_PLUGIN_NM, 'manage_options', 'fcmdpplgpn_push_notification', array($this, 'fcmdpplgpn_admin_page'));

        add_submenu_page(null
            , __('Test Push Notification', FCMDPPLGPN_TRANSLATION)
            , 'Test Notification'
            , 'administrator'
            , 'test_push_notification'
            , array($this, 'fcmdpplgpn_send_test_notification')
        );
    }

    public function fcmdpplgpn_admin_page()
    {
        include(plugin_dir_path(__FILE__) . 'fcm-dp-admin-panel.php');
    }

    public function fcmdpplgpn_activate()
    {

    }

    public function fcmdpplgpn_deactivate()
    {
    }


    function fcmdpplgpn_settings()
    {
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_api');
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_topic');
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_disable');
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_page_disable');
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_channel');
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_default_image');

        /* 30/04/2021 - Included notification sound setting */
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_sound');

        // 1.9.0 31/12/2021 - Included to enter custom field names
        // Suggestion of @ibrahimelshamy
        register_setting('fcmdpplgpn_group', 'fcmdpplgpn_custom_fields');

        /* set checkboxs post types */
        $args  = array(
            'public' => true,
        );

        $post_types = get_post_types( $args, 'objects' );

        if ( $post_types ) { // If there are any custom public post types.

            foreach ( $post_types  as $post_type ) {
                //$this->write_log('add action 4: ' . $post_type->name);
                if ($post_type->name != 'attachment'){
                    register_setting('fcmdpplgpn_group', 'fcmdpplgpn_posttype_' . $post_type->name);
                }
            }

        }

    }

    function fcmdpplgpn_send_test_notification(){

        $test = new FCMDPPLGPNTestSendPushNotification;

        $test->post_type = "test";
        $test->ID = 0;
        $test->post_title = "Teste Push Notification";
        $test->post_content = "Test from Firebase Push Notification Plugin";
        $test->post_excerpt = "Test from Firebase Push Notification Plugin";
        $test->post_url = "https://dprogrammer.net";


        $result = $this->fcmdpplgpn_notification($test, false, false, '');

        echo '<div class="row">';
        echo '<div><h2>API Return</h2>';

        echo '<pre>';
        printf($result);
        echo '</pre>';

        echo '<p><a href="'. admin_url('admin.php').'?page=test_push_notification">Send again</a></p>';
        echo '<p><a href="'. admin_url('admin.php').'?page=fcmdpplgpn_push_notification">FCM Options</a></p>';

        echo '</div>';
    }

    //function fcmdpplgpn_notification($title, $content, $resume, $post_id, $image){
    function fcmdpplgpn_notification($post, $sendOnlyData, $showLocalNotification, $command){

        $from = get_bloginfo('name');
        //$content = 'There are new post notification from '.$from;

        $post_type = esc_attr($post->post_type);
        $post_id = esc_attr($post->ID);
        $post_title = $post->post_title;
        $content = esc_html(wp_strip_all_tags(preg_replace( "/\r|\n/", " ", $post->post_content)));

        // 1.9.0 - para exibir corretamente o caracter '
        // problema relatado por @global01
        $content = wp_specialchars_decode($content, ENT_QUOTES);

        // incluída opção para remover as tags do visual composer - 1.6.0
        // suporte: https://wordpress.org/support/topic/change-plugin-to-push-different-information/
        $shotcodes_tags = array( 'vc_row', 'vc_column', 'vc_column', 'vc_column_text', 'vc_message' );
        $content = preg_replace( '/\[(\/?(' . implode( '|', $shotcodes_tags ) . ').*?(?=\]))\]/', ' ', $content );

        // 1.9.0 - para remover as tags do tema DIVI
        // correção enviada por @inaki81
        $content = preg_replace( '/\[(\/?.*?(?=\]))\]/', ' ', $content );

        // 1.9.0 - para exibir corretamente o caracter '
        // problema relatado por @global01
        $resume = wp_specialchars_decode(esc_attr($post->post_excerpt), ENT_QUOTES);
            
        // https://wordpress.org/support/topic/url-is-empty/
        $post_url = esc_url(get_the_permalink($post->ID)); // contribuição do usuário https://wordpress.org/support/users/alchmi/
        //$this->write_log('$post_url: ' . $post_url);

        // http://codex.wordpress.org/Function_Reference/get_post_thumbnail_id
        $thumb_id = get_post_thumbnail_id( $post_id );

        // http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
        // https://stackoverflow.com/a/56340953 -> set true to get default image
        $thumb_url = wp_get_attachment_image_src( $thumb_id, 'full' );

        $image = $thumb_url ? esc_url($thumb_url[0]) : '';

        if (_mb_strlen($image) == 0) {
            $image = get_option('fcmdpplgpn_default_image');
        }

        //$this->write_log('post Id: ' . $post_id);
        //$this->write_log('imagem: ' . $image);

        /*
        $this->write_log('post Id: ' . $post_id);
        $this->write_log('post Title: ' . $post_title);
        $this->write_log('post Content: ' . $content);
        $this->write_log('resume: ' . $resume);
        $this->write_log('image: ' . $image);
        */

        /* 30/04/2021 - Included notification sound setting */
        $sound =  esc_attr(get_option('fcmdpplgpn_sound'));


        $topic =  esc_attr(get_option('fcmdpplgpn_topic'));
        $apiKey = esc_attr(get_option('fcmdpplgpn_api'));
        $url = 'https://fcm.googleapis.com/fcm/send';

        // 31/12/2021 - 1.9.0
        $customFields =  esc_attr(get_option('fcmdpplgpn_custom_fields'));
        //$this->write_log('customFields: ' . $customFields);

        $arrCustomFieldsValues = [];

        if (_mb_strlen($customFields) > 0) {

            $arrCustomFields = explode("|", $customFields);
            foreach($arrCustomFields as $i=>$customField):
                //$this->write_log('arrCustomFields: ['. $i . '] ' . $customField);
                
                $arrCustomFieldsValues[$customField] = esc_attr(get_post_meta( $post_id, $customField, TRUE ));
            endforeach; 

            //$this->write_log('arrCustomFieldsValues: ' . json_encode($arrCustomFieldsValues));
            
        } else{
            //$this->write_log('arrCustomFields: Vazio');
        }
        // 31/12/2021

        $notification_data = array(
            'click_action'          => 'FLUTTER_NOTIFICATION_CLICK',
            'message'               => _mb_strlen($resume) == 0 ? _mb_substr(wp_strip_all_tags($content), 0, 55) . '...' : $resume,
            'post_type'             => $post_type,
            'post_id'               => $post_id,
            'title'                 => $post_title,
            'image'                 => $image,
            'url'                   => $post_url,
            'show_in_notification'  => $showLocalNotification,
            'command'               => $command,
            'dialog_title'          => $post_title,
            'dialog_text'           => _mb_strlen($resume) == 0 ? _mb_substr(wp_strip_all_tags($content), 0, 100) . '...' : $resume,
            'dialog_image'          => $image,
            'sound'                 => _mb_strlen($sound) == 0 ? 'default' : $sound,
            'customm_fields'        => $arrCustomFieldsValues
        );

        $this->write_log('notification_data: ' . json_encode($notification_data));

        //$this->write_log('sound field: ' . $sound);
        //$this->write_log('sound: ' . (_mb_strlen($sound) == 0 ? 'default' : $sound));

        $notification = array(
            'title'                 => $post_title,
            'body'                  => _mb_strlen($resume) == 0 ? _mb_substr(wp_strip_all_tags($content), 0, 55) . '...' : $resume,
            'content_available'     => true,
            'android_channel_id'    => get_option('fcmdpplgpn_channel'),
            'click_action'          => 'FLUTTER_NOTIFICATION_CLICK',
            'sound'                 => _mb_strlen($sound) == 0 ? 'default' : $sound,
            'image'                 => $image,
        );

        $post = array(
            'to'                    => '/topics/' . $topic,
            'collapse_key'          => 'type_a',
            'notification'          => $notification,
            'priority'              => 'high',
            'data'                  => $notification_data,
            'timeToLive'            => 10,
        );

        $payload = json_encode($post);

        //$this->write_log('payload: ' . json_encode($payload));

        $args = array(
            'timeout'           => 45,
            'redirection'       => 5,
            'httpversion'       => '1.1',
            'method'            => 'POST',
            'body'              => $payload,
            'sslverify'         => false,
            'headers'           => array(
                'Content-Type'      => 'application/json',
                'Authorization'     => 'key=' . $apiKey,
            ),
            'cookies'           => array()
        );

        $response = wp_remote_post($url, $args);

        return json_encode($response);
    }


    function fcmdpplgpn_settings_link( $links ) {
        // Build and escape the URL.
        $url = esc_url( add_query_arg(
            'page',
            'fcmdpplgpn_push_notification',
            get_admin_url() . 'admin.php'
        ) );
        // Create the link.
        $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
        // Adds the link to the end of the array.
        array_push(
            $links,
            $settings_link
        );
        return $links;
    }//end nc_settings_link()


}

/* to test a send notification */
class FCMDPPLGPNTestSendPushNotification
{
    public  $ID;
    public  $post_type;
    public  $post_content;
    public  $post_excerpt;
    public  $post_url;
}

$FCMDPPLGPN_Push_Notification_OBJ = new FCMDPPLGPN_Push_Notification();