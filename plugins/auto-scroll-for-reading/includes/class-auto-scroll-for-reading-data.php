<?php
class Auto_Scroll_Data {
    

    public static function wpg_get_setting_data( $meta_key = 'options' ){
        global $wpdb;

        $settings_table = $wpdb->prefix . "wpgautoscroll_settings";
        $sql = "SELECT meta_value FROM " . $settings_table . " WHERE meta_key = '". esc_sql( $meta_key ) ."'";
        $result = $wpdb->get_var($sql);

        if($result != ""){
            return $result;
        }
        return false;
    }

    public static function get_validated_data_from_array($meta_key = 'options'){
        global $wpdb;
        $name_prefix = "wpg_";
        $options = self::wpg_get_setting_data($meta_key);
        $options = json_decode($options, true);
        $settings = array();

        // Auto scroll position
        $wpg_auto_scroll_button_position = (isset($options[$name_prefix.'auto_scroll_button_position']) && $options[$name_prefix.'auto_scroll_button_position'] != '') ? esc_attr($options[$name_prefix.'auto_scroll_button_position']) : 'right';
        $settings[$name_prefix.'auto_scroll_button_position'] = $wpg_auto_scroll_button_position;
        // Auto scroll color
        $wpg_auto_scroll_button_color = (isset($options[$name_prefix.'auto_scroll_button_color']) && $options[$name_prefix.'auto_scroll_button_color'] != '') ? esc_attr($options[$name_prefix.'auto_scroll_button_color']) : '#6369d1';
        $settings[$name_prefix.'auto_scroll_button_color'] = $wpg_auto_scroll_button_color;
        // Auto scroll color
        $wpg_auto_scroll_button_color = (isset($options[$name_prefix.'auto_scroll_button_color']) && $options[$name_prefix.'auto_scroll_button_color'] != '') ? esc_attr($options[$name_prefix.'auto_scroll_button_color']) : '#6369d1';
        $settings[$name_prefix.'auto_scroll_button_color'] = $wpg_auto_scroll_button_color;
        // Auto scroll rescroll delay
        $wpg_auto_scroll_rescroll_delay = (isset($options[$name_prefix.'auto_scroll_rescroll_delay']) && $options[$name_prefix.'auto_scroll_rescroll_delay'] !== '') ? esc_attr($options[$name_prefix.'auto_scroll_rescroll_delay']) : 30;
        $settings[$name_prefix.'auto_scroll_rescroll_delay'] = $wpg_auto_scroll_rescroll_delay;
        // Auto scroll autoplay 
        $wpg_auto_scroll_autoplay = (isset($options[$name_prefix .'auto_scroll_autoplay']) && $options[$name_prefix .'auto_scroll_autoplay'] == 'on') ? true : false;
        $settings[$name_prefix.'auto_scroll_autoplay'] = $wpg_auto_scroll_autoplay;
        // Auto scroll autoplay delay
        $wpg_auto_scroll_autoplay_delay = (isset($options[$name_prefix .'auto_scroll_autoplay_delay']) && $options[$name_prefix .'auto_scroll_autoplay_delay'] != '') ? esc_attr($options[$name_prefix .'auto_scroll_autoplay_delay']) : 10;
        $settings[$name_prefix.'auto_scroll_autoplay_delay'] = $wpg_auto_scroll_autoplay_delay;
        // Auto scroll hover title
        $wpg_auto_scroll_hover_title = (isset($options[$name_prefix .'auto_scroll_hover_title']) && $options[$name_prefix .'auto_scroll_hover_title'] == 'on') ? true : false;
        $settings[$name_prefix.'auto_scroll_hover_title'] = $wpg_auto_scroll_hover_title;
        // Go to top automatically
        $wpg_auto_scroll_go_to_top_automatically = (isset($options[$name_prefix .'auto_scroll_go_to_top_automatically']) && $options[$name_prefix .'auto_scroll_go_to_top_automatically'] == 'on') ? true : false;
        $settings[$name_prefix.'auto_scroll_go_to_top_automatically'] = $wpg_auto_scroll_go_to_top_automatically;
        // Go to top automatically delay
        $wpg_auto_scroll_go_to_top_automatically_delay = (isset($options[$name_prefix .'auto_scroll_go_to_top_automatically_delay']) && $options[$name_prefix .'auto_scroll_go_to_top_automatically_delay'] != '') ? esc_attr($options[$name_prefix .'auto_scroll_go_to_top_automatically_delay']) : 0;
        $settings[$name_prefix.'auto_scroll_go_to_top_automatically_delay'] = $wpg_auto_scroll_go_to_top_automatically_delay;
        //Default speed
        $wpg_auto_scroll_default_speed = (isset($options[$name_prefix .'auto_scroll_default_speed']) && $options[$name_prefix .'auto_scroll_default_speed'] != '') ? esc_attr($options[$name_prefix .'auto_scroll_default_speed']) : 1;
        $settings[$name_prefix.'auto_scroll_default_speed'] = $wpg_auto_scroll_default_speed;

        return $settings;
    }


}
