<?php

    if( isset( $_POST['ays_submit'] ) || isset( $_POST['ays_submit_top'] ) ) {
        $this->front_settings_obj->store_data();
    }

    $options = (empty($this->front_settings_obj->get_all_data())) ? array() : $this->front_settings_obj->get_all_data();
    
    $settings_table_options = ($this->front_settings_obj->get_settings_db_data() === false) ? array() : $this->front_settings_obj->get_settings_db_data();

    $chatbox_onoff = ( isset( $settings_table_options['chatbox_onoff'] ) && $settings_table_options['chatbox_onoff'] != '' ) ? $settings_table_options['chatbox_onoff'] : 'on';
	$chatbox_onoff = isset( $chatbox_onoff ) && $chatbox_onoff == 'on' ? 'checked' : '';

    $data = $this->db_obj->get_data();

    $api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';

    $check_openai_connection = ChatGPT_assistant_Data::makeRequest($api_key, 'GET', 'models');
    $check_openai_connection_code = false;

    if(is_array($check_openai_connection)){
        $check_openai_connection_code = isset($check_openai_connection['openai_response_code']) && $check_openai_connection['openai_response_code'] == 200 ? true : false; 
    }

    // General settings options start

        $general_options = (empty($this->general_settings_obj->get_all_data())) ? array() : $this->general_settings_obj->get_all_data();
            
        $textarea_height = isset( $general_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height'] ) && intval($general_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height']) > 0 ? esc_attr($general_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height']) : 70;

    // General settings options end

    $chatgpt_assistant_chat_icon_size_front = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size_front'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size_front'] != '' ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size_front']) : (isset($settings_table_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size']) && $settings_table_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size'] != '' ? esc_attr($settings_table_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size']) : 70);

    $chatgpt_assistant_enable_icon_text = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_icon_text'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_icon_text'] == 'on' ) ? true : false;

    $chatgpt_assistant_icon_text = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text'] != '' ) ? stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text'])) : '';
    $chatgpt_assistant_icon_bg = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_bg'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_bg'] != '' ) ? stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_bg'])) : '#3b3b3b';
    $chatgpt_assistant_icon_text_color = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_color'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_color'] != '' ) ? stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_color'])) : '#f8f8f8';
    $chatgpt_assistant_icon_text_font_size = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_font_size'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_font_size'] != '' ) ? absint(stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_font_size']))) : 14;
    $chatgpt_assistant_icon_text_border_color = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_border_color'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_border_color'] != '' ) ? stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_border_color'])) : $chatgpt_assistant_icon_bg;
    $chatgpt_assistant_icon_text_border_width = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_border_width'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_border_width'] != '' ) ? absint(stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_border_width']))) : 0;
    $chatgpt_assistant_icon_text_open_delay = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_open_delay'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_open_delay'] != '' ) ? absint(stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_open_delay']))) : 0;
    $chatgpt_assistant_icon_text_show_once = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_show_once'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_text_show_once'] == 'on' ) ? true : false;
  
    $request_limitation_intervals = array(
        "hour" => __("Hour",'chatgpt-assistant'),
        "day" => __("Day",'chatgpt-assistant'),
        "week" => __("Week",'chatgpt-assistant'),
        "month" => __("Month",'chatgpt-assistant')
    );

    $chatgpt_assistant_enable_request_limitations = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_request_limitations'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_request_limitations'] == 'on' ) ? true : false;

    $chatgpt_assistant_request_limitations_limit = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'request_limitations_limit'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'request_limitations_limit'] != '' ) ? absint($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'request_limitations_limit']) : 0;

    $chatgpt_assistant_request_limitations_interval = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'request_limitations_interval'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'request_limitations_interval'] != '' ) ? sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'request_limitations_interval']) : 'day';

    if (!isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_logged_in'])) {
        $chatgpt_assistant_access_for_logged_in = 'checked';
    } else {
        $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_logged_in'] = ( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_logged_in'] != '' ) ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_logged_in'] : 'off';
	    $chatgpt_assistant_access_for_logged_in = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_logged_in'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_logged_in'] == 'on' ? 'checked' : '';
    }
    
    if (!isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_guests'])) {
        $chatgpt_assistant_access_for_guests = 'checked';
    } else {
        $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_guests'] = ( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_guests'] != '' ) ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_guests'] : 'off';
	    $chatgpt_assistant_access_for_guests = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_guests'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'access_for_guests'] == 'on' ? 'checked' : '';
    }

    $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'password_protection'] = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'password_protection']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'password_protection'] != '' ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'password_protection'] : 'off';
	$chatgpt_assistant_password_protection = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'password_protection'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'password_protection'] == 'on' ? 'checked' : '';
?>