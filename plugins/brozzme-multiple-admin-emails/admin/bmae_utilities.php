<?php

/**
 * Created by PhpStorm.
 * User: benoti
 * Date: 07/09/2016
 * Time: 10:29
 */
class bmae_utilities{

    public function __construct(){
        
        $this->settings_options = get_option('bmae_settings');
        $this->plugin_text_domain = BMAE_TEXT_DOMAIN;
        if(is_admin() && $this->settings_options['new_post_email'] == 'true'){

            $this->silent_mode = $this->_silent_mode();

            $this->_init();
        }
    }

    /**
     *
     */
    public function _init(){

        // post, page, both
        $post_type = (empty($this->settings_options['send_post_type']))? 'post' : $this->settings_options['send_post_type'];

        // publish, pending, future, draft on_demand
        $event_email = (empty($this->settings_options['event_email']))? 'publish' : $this->settings_options['event_email'];

        $this->_event_hook($post_type, $event_email);
    }

    /**
     * @param $post_type_event
     * @param $event_email
     */
    public function _event_hook($post_type_event, $event_email){
        global $post;

        if($event_email !== 'on_demand'){

            if($post_type_event != 'both'){
                add_action($event_email.'_'.$post_type_event, array($this, 'bmae_event_hook') );
            }
            else{
                add_action($event_email.'_post', array($this, 'bmae_event_hook') );
                add_action($event_email.'_page', array($this, 'bmae_event_hook') );
            }
        }
        else{

            // silent mode will not load on demand meta box for non-registered blog admin email
            if($this->silent_mode === false){
                add_action( 'add_meta_boxes', array($this, '_register_meta_boxes') );
                add_action( 'save_post', array($this, '_save_meta_box') );
            }

            if(isset($_POST['bmae_on_demand_status']) || get_post_meta($post->ID, 'bmae_on_demand_status', true) == 'true'
                || isset($_POST['bmae_send_again'])){
                if($post_type_event != 'both'){
                    add_action('publish_'.$post_type_event, array($this, 'bmae_event_hook') );
                    add_action('pending_'.$post_type_event, array($this, 'bmae_event_hook') );
                    add_action('draft_'.$post_type_event, array($this, 'bmae_event_hook') );
                    add_action('future_'.$post_type_event, array($this, 'bmae_event_hook') );

                }
                else{
                    $post_type_event = array('post', 'page');

                    foreach ($post_type_event as $cpt_event) {
                        add_action('publish_'.$cpt_event, array($this, 'bmae_event_hook') );
                        add_action('pending_'.$cpt_event, array($this, 'bmae_event_hook') );
                        add_action('draft_'.$cpt_event, array($this, 'bmae_event_hook') );
                        add_action('future_'.$cpt_event, array($this, 'bmae_event_hook') );

                     //   https://developer.wordpress.org/reference/hooks/transition_post_status/
                        //add_action('future_'.$cpt_event, array($this, 'bmae_event_hook') );
                    }
                    add_action( 'save_post', array($this, 'bmae_event_hook') );
                }
            }

            add_action( 'admin_print_footer_scripts', array($this, 'wpse_redirect_to_post_list') );
        }
    }

    /**
     * @param $post_ID
     */
    public function bmae_event_hook($post_ID){

        // récupérer les mails admin et retirer celui du current_user_id()
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return;

        //Perform permission checks! For example:
        if ( !current_user_can('edit_post', $post_ID) )
            return;

        $admin_emails = get_option('admin_email');

        $current_user = wp_get_current_user();
        $current_user_email = $current_user->user_email;

        $stack_email = explode(',', $admin_emails);
        $to = array();
        if(count($stack_email) <= $this->settings_options['limit']){

            foreach ($stack_email as $key=>$value){

                if(trim($value) != $current_user_email){

                    $to[] = trim($value);

                }
            }
            $to = implode(', ', $to);
            $notification_status = get_post_meta($post_ID, 'bmae_sent_before', true);
            if(isset($_POST['bmae_send_again'])){
                $notification_status = 'send_again';
            }

            if($notification_status === 'send_again' || $notification_status == ''){

                    add_filter( 'wp_mail_content_type', array($this, '_set_mail_content_type') );

                    wp_mail($to, '['. get_bloginfo('name') .'] '. __('New content posted on ', $this->plugin_text_domain). ' '. get_bloginfo('name'), $this->message($post_ID) );

                    remove_filter( 'wp_mail_content_type', array($this, '_set_mail_content_type') );

                    update_post_meta($post_ID, 'bmae_sent_before', 'true');

                    $edit_post_link = get_edit_post_link($post_ID);
                    wp_redirect($edit_post_link);
                    exit;
            }
        }
    }

    /**
     * @return string
     */
    public function _set_mail_content_type(){
        return 'text/html';
    }

    /**
     * @param $post_ID
     * @return string
     */
    public function message($post_ID){
        $post = get_post($post_ID);

        $post_type_object = get_post_type_object( $post->post_type );
        $edit_link =  admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=edit', $post->ID ) );
        $author = get_userdata($post->post_author);
        $date = date_i18n(get_option('date_format'), strtotime($post->post_modified_gmt));
        $post_status = $post->post_status;


        $output = '';
        $output .= '<b>'.__('New content has been post for verification on ', $this->plugin_text_domain).' '. get_bloginfo('name').'</b><br/>';
        $output .= '<br/>';
        $output .= '<br/>';
        $output .= __('Last modify').' : '. $date;
        $output .= '<br/>';
        $output .= __('Status').' : '. $post_status;
        $output .= '<br/>';
        $output .= __('Post type').' : '. $post->post_type;
        $output .= '<br/>';
        $output .= __('Author').' : '. $author->display_name;
        $output .= '<br/>';
        $output .= $post_type_object->labels->view_item. ' : <a href="'. get_permalink($post_ID).'">'. get_the_title($post_ID) .'</a>';
        $output .= '<br/>';
        $output .= __('Link').' : <a href="'. get_permalink($post_ID).'">'. get_permalink($post_ID).'</a>';
        $output .= '<br/>';
        $output .= $post_type_object->labels->edit_item . ': <a href="'.$edit_link.'">'. get_the_title($post_ID).'</a>';
        $output .= '<br/>';
        $output .= '<br/>';

        $output .= __('This message has been sent by notifications system of', $this->plugin_text_domain) . ' ' . get_bloginfo('name');
        $output .= '<br/>';

        return $output;
    }

    // -------- on demand notifications

    /**
     *
     */
    public function _register_meta_boxes(){
        $post_type_screen = $this->settings_options['send_post_type'];
        if($post_type_screen === 'both'){
            $post_type_screen = array('post', 'page');
        }


        add_meta_box(
            'bmae_on_demand_box',
            'Notification',
            array($this, 'on_demand_callback'),
            $post_type_screen,
            'side',
            'high',
            array(
                '__block_editor_compatible_meta_box' => true,
            )
        );
    }

    /**
     *
     */
    public function on_demand_callback(){
        global $post;

        $notification_on_demand_status = get_post_meta($post->ID, 'bmae_on_demand_status', true);
        $notification_sent = get_post_meta($post->ID, 'bmae_sent_before', true);

        if($notification_sent == 'true'){
            ?><b><?php _e('A notification has been already sent. ', $this->plugin_text_domain);?></b>
            <br><br>
            <label><?php _e('Send another notification', $this->plugin_text_domain);?>
            <input type="checkbox" name="bmae_send_again" value="true" >
            </label>
            <br><br>
            <label><?php _e('Reset status', $this->plugin_text_domain);?>
            <input id="bmae_reset_status" type="checkbox" name="bmae_reset_status" value="true" >
            <p><?php _e('Send another notification above if set and reset notification status', $this->plugin_text_domain);?></p>

            <?php
        }
        else{
            ?>
            <label><?php _e('Notify by email', $this->plugin_text_domain);?>
                <input type="checkbox" id="bmae_on_demand_status" name="bmae_on_demand_status" value="true" <?php checked('true', $notification_on_demand_status, true);?>>
            </label>

            <?php
        }
    }

    /**
     *
     */
    public function _save_meta_box(){
        global $post;

        if(isset($_POST['bmae_on_demand_status']) && $_POST['bmae_on_demand_status']=='true'){
            update_post_meta($post->ID, 'bmae_on_demand_status', 'true');
        }
        if ( isset($_POST['bmae_on_demand_status']) ) {
            update_post_meta($post->ID, 'bmae_on_demand_status', $_POST['bmae_on_demand_status']);
        }else{
            delete_post_meta($post->ID, 'bmae_on_demand_status');
        }
        if(isset($_POST['bmae_reset_status'])){
            delete_post_meta($post->ID, 'bmae_sent_before');
            delete_post_meta($post->ID, 'bmae_on_demand_status');
        }
    }

    /**
     * @return bool
     */
    public function _silent_mode(){
        // only registered admin_email can see meta box
        $admin_emails = get_option('admin_email');

        if(!function_exists('wp_get_current_user')) {
            include(ABSPATH . "wp-includes/pluggable.php");
        }

        $current_user_id = get_current_user_id();

        $current_user_data = get_userdata($current_user_id);
        $current_user_email = $current_user_data->user_email;

        $stack_email = explode(',', $admin_emails);

        if($this->settings_options['silent_mode'] == 'true'){
            if(in_array($current_user_email, $stack_email)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
}

new bmae_utilities();