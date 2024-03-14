<?php
class Auto_Scroll_Settings_Actions {
    private $plugin_name;

    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
    }

    public function store_data(){
        global $wpdb;
        
        $name_prefix = 'wpg_';
        if( isset($_REQUEST["settings_action"]) && wp_verify_nonce( sanitize_text_field( $_REQUEST["settings_action"] ), 'settings_action' ) ){
            $success = 0;

            $wpg_auto_scroll_button_position  = (isset($_REQUEST[$name_prefix .'auto_scrol_positions']) && $_REQUEST[$name_prefix .'auto_scrol_positions'] != '') ? sanitize_text_field($_REQUEST[$name_prefix .'auto_scrol_positions']) : 'right';
            $wpg_auto_scroll_button_color     = (isset($_REQUEST[$name_prefix .'auto_scroll_button_color']) && $_REQUEST[$name_prefix .'auto_scroll_button_color'] != '') ? sanitize_text_field($_REQUEST[$name_prefix .'auto_scroll_button_color']) : '#6369d1';
            $wpg_auto_scroll_rescroll_delay   = (isset($_REQUEST[$name_prefix .'auto_scroll_rescroll_delay']) && $_REQUEST[$name_prefix .'auto_scroll_rescroll_delay'] != '') ? absint(intval($_REQUEST[$name_prefix .'auto_scroll_rescroll_delay'])) : 30;
            $wpg_auto_scroll_autoplay         = (isset($_REQUEST[$name_prefix .'auto_scroll_autoplay']) && $_REQUEST[$name_prefix .'auto_scroll_autoplay'] == 'on') ? 'on' : 'off';
            $wpg_auto_scroll_autoplay_delay   = 10; 
            if( isset($_REQUEST[$name_prefix .'auto_scroll_autoplay_delay']) && $_REQUEST[$name_prefix .'auto_scroll_autoplay_delay'] != '' ){
                $wpg_auto_scroll_autoplay_delay = ( $_REQUEST[$name_prefix .'auto_scroll_autoplay_delay'] < 0 ) ? absint(intval($_REQUEST[$name_prefix .'auto_scroll_autoplay_delay'])) : sanitize_text_field($_REQUEST[$name_prefix .'auto_scroll_autoplay_delay']);                
            }
            $options[$name_prefix .'auto_scroll_hover_title'] = isset($options[$name_prefix .'auto_scroll_hover_title']) ? $options[$name_prefix .'auto_scroll_hover_title'] : 'off';
            $wpg_auto_scroll_hover_title      = (isset($_REQUEST[$name_prefix .'auto_scroll_hover_title']) && $_REQUEST[$name_prefix .'auto_scroll_hover_title'] == 'on') ? 'on' : 'off';
            $wpg_auto_scroll_go_to_top_automatically  = (isset($_REQUEST[$name_prefix .'auto_scroll_go_to_top_automatically']) && $_REQUEST[$name_prefix .'auto_scroll_go_to_top_automatically'] == 'on') ? 'on' : 'off';
            $wpg_auto_scroll_go_to_top_automatically_delay = 0;
            if( isset($_REQUEST[$name_prefix . 'auto_scroll_go_to_top_automatically_delay']) && isset($_REQUEST[$name_prefix . 'auto_scroll_go_to_top_automatically_delay']) != '' ) {
                $wpg_auto_scroll_go_to_top_automatically_delay = ($_REQUEST[$name_prefix . 'auto_scroll_go_to_top_automatically_delay'] < 0 ) ? absint(intval($_REQUEST[$name_prefix .'auto_scroll_go_to_top_automatically_delay'])) : sanitize_text_field($_REQUEST[$name_prefix .'auto_scroll_go_to_top_automatically_delay']);
            }
            $wpg_auto_scroll_default_speed = (isset($_REQUEST[$name_prefix . 'auto_scroll_default_speed']) && $_REQUEST[$name_prefix . 'auto_scroll_default_speed'] != '') ? sanitize_text_field($_REQUEST[$name_prefix .'auto_scroll_default_speed']) : 1;   

            $options = array(
                "wpg_auto_scroll_button_position"  => $wpg_auto_scroll_button_position,
                "wpg_auto_scroll_button_color"     => $wpg_auto_scroll_button_color,
                "wpg_auto_scroll_rescroll_delay"   => $wpg_auto_scroll_rescroll_delay,
                "wpg_auto_scroll_autoplay"         => $wpg_auto_scroll_autoplay,
                "wpg_auto_scroll_autoplay_delay"   => $wpg_auto_scroll_autoplay_delay,
                "wpg_auto_scroll_hover_title"      => $wpg_auto_scroll_hover_title,
                "wpg_auto_scroll_go_to_top_automatically"      => $wpg_auto_scroll_go_to_top_automatically,
                "wpg_auto_scroll_go_to_top_automatically_delay"      => $wpg_auto_scroll_go_to_top_automatically_delay,
                "wpg_auto_scroll_default_speed"    => $wpg_auto_scroll_default_speed
            );

            $fields['options'] = $options;

            foreach ($fields as $key => $value) {
                $result = $this->wpg_update_setting( $key, json_encode( $value ) );
                if( $result ){
                    $success++;
                }
            }

            $message = "saved";
            if($success > 0){
                $tab = "";
                if( isset( $_REQUEST['wpg_auto_scroll_tab'] ) ){
                    $tab = "&wpg_auto_scroll_tab=".sanitize_text_field($_REQUEST['wpg_auto_scroll_tab']);
                }
                $url = admin_url('admin.php') . "?page=auto-scroll-for-reading" . $tab . '&status=' . $message;
                wp_redirect( $url );
            }
        }
        
    }
   
    public function wpg_get_setting($meta_key){
        global $wpdb;
        $settings_table = $wpdb->prefix . "wpgautoscroll_settings";
        $sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = '".$meta_key."'";
        $result = $wpdb->get_var($sql);
        if($result != ""){
            return $result;
        }
        return false;
    }
    
    public function wpg_add_setting($meta_key, $meta_value, $note = "", $options = ""){
        global $wpdb;
        $settings_table = $wpdb->prefix . "wpgautoscroll_settings";
        $result = $wpdb->insert(
            $settings_table,
            array(
                'meta_key'    => $meta_key,
                'meta_value'  => $meta_value,
                'note'        => $note,
                'options'     => $options
            ),
            array( '%s', '%s', '%s', '%s' )
        );
        if($result >= 0){
            return true;
        }
        return false;
    }
    
    public function wpg_update_setting($meta_key, $meta_value, $note = null, $options = null){
        global $wpdb;
        $settings_table = $wpdb->prefix . "wpgautoscroll_settings";
        $value = array(
            'meta_value'  => $meta_value,
        );
        $value_s = array( '%s' );
        if($note != null){
            $value['note'] = $note;
            $value_s[] = '%s';
        }
        if($options != null){
            $value['options'] = $options;
            $value_s[] = '%s';
        }
        $result = $wpdb->update(
            $settings_table,
            $value,
            array( 'meta_key' => $meta_key, ),
            $value_s,
            array( '%s' )
        );
        if($result >= 0){
            return true;
        }
        return false;
    }
    
    public function wpg_delete_setting($meta_key){
        global $wpdb;
        $settings_table = $wpdb->prefix . "wpgautoscroll_settings";
        $wpdb->delete(
            $settings_table,
            array( 'meta_key' => $meta_key ),
            array( '%s' )
        );
    }

    public function auto_scroll_settings_notices($status){

        if ( empty( $status ) )
            return;

        if ( 'saved' == $status )
            $updated_message = esc_html( __( 'Changes saved.', $this->plugin_name ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Quiz attribute .', $this->plugin_name ) );
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Quiz attribute deleted.', $this->plugin_name ) );
        elseif ( 'duration_updated' == $status )
            $updated_message = esc_html( __( 'Duration old data is successfully updated.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }
    
}
