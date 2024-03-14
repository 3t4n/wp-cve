<?php
    $actions = $this->settings_obj;
    $name_prefix = 'wpg_';
    if( isset( $_REQUEST['wpg_submit'] ) ){
        $actions->store_data();
    }

    if(isset($_GET['wpg_auto_scroll_tab'])){
        $wpg_auto_scroll_tab = sanitize_text_field( $_GET['wpg_auto_scroll_tab'] );
    }else{
        $wpg_auto_scroll_tab = 'tab1';
    }

    $options = (Auto_Scroll_Data::wpg_get_setting_data('options') === false) ? array() : json_decode(Auto_Scroll_Data::wpg_get_setting_data('options'), true);

    $wpg_auto_scroll_button_position  = (isset($options[$name_prefix .'auto_scroll_button_position']) && $options[$name_prefix .'auto_scroll_button_position'] != '') ? esc_attr($options[$name_prefix .'auto_scroll_button_position']) : 'right';
    $wpg_auto_scroll_button_color     = (isset($options[$name_prefix .'auto_scroll_button_color']) && $options[$name_prefix .'auto_scroll_button_color'] != '') ? esc_attr($options[$name_prefix .'auto_scroll_button_color']) : '#6369d1';
    $wpg_auto_scroll_rescroll_delay   = (isset($options[$name_prefix .'auto_scroll_rescroll_delay']) && $options[$name_prefix .'auto_scroll_rescroll_delay'] !== '') ? intval(esc_attr($options[$name_prefix .'auto_scroll_rescroll_delay'])) : 30;
    $wpg_auto_scroll_autoplay         = (isset($options[$name_prefix .'auto_scroll_autoplay']) && $options[$name_prefix .'auto_scroll_autoplay'] == 'on') ? true : false;
    $wpg_auto_scroll_autoplay_delay   = (isset($options[$name_prefix .'auto_scroll_autoplay_delay']) && $options[$name_prefix .'auto_scroll_autoplay_delay'] != '') ? intval(esc_attr($options[$name_prefix .'auto_scroll_autoplay_delay'])) : 10;
    $options[$name_prefix .'auto_scroll_hover_title'] = isset($options[$name_prefix .'auto_scroll_hover_title']) ? $options[$name_prefix .'auto_scroll_hover_title'] : 'off';
    $wpg_auto_scroll_hover_title         = (isset($options[$name_prefix .'auto_scroll_hover_title']) && $options[$name_prefix .'auto_scroll_hover_title'] == 'on') ? true : false;
    $wpg_auto_scroll_go_to_top_automatically = (isset($options[$name_prefix .'auto_scroll_go_to_top_automatically']) && $options[$name_prefix .'auto_scroll_go_to_top_automatically'] == 'on') ? true : false;
    $wpg_auto_scroll_go_to_top_automatically_delay = (isset($options[$name_prefix .'auto_scroll_go_to_top_automatically_delay']) && $options[$name_prefix .'auto_scroll_go_to_top_automatically_delay'] != '') ? intval(esc_attr($options[$name_prefix .'auto_scroll_go_to_top_automatically_delay'])) : 0;
    $wpg_auto_scroll_default_speed = (isset($options[$name_prefix . 'auto_scroll_default_speed']) && $options[$name_prefix . 'auto_scroll_default_speed'] != '') ? esc_attr($options[$name_prefix .'auto_scroll_default_speed']) : 1;
?>


