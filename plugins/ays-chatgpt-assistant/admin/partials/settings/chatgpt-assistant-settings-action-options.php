<?php

    // API Key welocme page start

        $data = $this->db_obj->get_data();

        $id = isset( $data['id'] ) && $data['id'] > 0 ? intval( $data['id'] ) : 0;

        $api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';

        $data_options = isset( $data['options'] ) && $data['options'] != '' ? json_decode(esc_attr( $data['options'] ) ): '';

        $check_openai_connection = ChatGPT_assistant_Data::makeRequest($api_key, 'GET', 'models');
        $check_openai_connection_code = false;

        if(is_array($check_openai_connection)){
            $check_openai_connection_code = isset($check_openai_connection['openai_response_code']) && $check_openai_connection['openai_response_code'] == 200 ? true : false; 
        }

        $connection_text =  __('Disconnected' , "ays-chatgpt-assistant") . '!';
        $connection_button_text =  __('Connect' , "ays-chatgpt-assistant");
        $connection_button_class = 'connect';
        $connection_input_readonly = '';

        if($check_openai_connection_code){
            $connection_text = __('Connected' , "ays-chatgpt-assistant") . '!';
            $connection_button_text = __('Disconnect' , "ays-chatgpt-assistant");
            $connection_button_class = 'disconnect';
            $connection_input_readonly = 'readonly';
        }

        $api_loader_iamge = "<span class='display_none ays_chatgpt_assistant_loader_box ays_chatgpt_assistant_loader_box_connection'><img src=". CHATGPT_ASSISTANT_ADMIN_URL ."/images/loaders/loading.gif></span>";

    // API Key welocme page end

    // General settings options start

        $options = (empty($this->general_settings_obj->get_all_data())) ? array() : $this->general_settings_obj->get_all_data();
        
        $textarea_height = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height'] ) && intval($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height']) > 0 ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height']) : 70;

    // General settings options end

    $loader_iamge = "<span class='display_none ays_chatgpt_assistant_loader_box'><img src=". esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ."/images/loaders/loading.gif></span>";

    if (isset($_REQUEST['ays_submit'])) {
	    $this->settings_obj->store_data();
    }
    if (isset($_GET['ays_tab'])) {
        $ays_tab = sanitize_text_field($_GET['ays_tab']);
    } else {
	    $ays_tab = 'tab1';
    }

    $options = ($this->settings_obj->get_setting('options') === false) ? array() : json_decode($this->settings_obj->get_setting('options'), true);

    $chatbox_color = (isset($options['chatbox_color']) && $options['chatbox_color'] != '' ) ? sanitize_text_field($options['chatbox_color']) : '#4e426d';

    $chatbox_mode = ( isset( $options['chatbox_mode'] ) && $options['chatbox_mode'] != '' ) ? sanitize_text_field($options['chatbox_mode']) : 'light';    
	$chatbox_mode = isset( $chatbox_mode ) && $chatbox_mode == 'dark' ? 'checked' : '';

    $chatbox_onoff = isset($options['chatbox_onoff']) ? $options['chatbox_onoff'] : 'on';

    $chatbox_models = array(
        "gpt-3.5-turbo" => "gpt-3.5-turbo",
        "gpt-3.5-turbo-16k" => "gpt-3.5-turbo-16k",
    );

    $chatbox_tones = array(
        "professional" => "Professional",
        "sarcastic"    => "Sarcastic",
        "humorous"     => "Humorous",
        "friendly"     => "Friendly",
    );

    $chatbox_positions = array(
        "left" => "Left",
        "center" => "Center",
        "right" => "Right"
    );

    $chatbox_icon_positions = array(
        "bottom-right" => "Bottom Right",
        "bottom-left" => "Bottom Left",
        "bottom-center" => "Bottom Center",
        "top-left" => "Top Left",
        "top-center" => "Top Center",
        "top-right" => "Top Right",
    );

    $supported_coutries = ChatGPT_assistant_Data::get_supported_coutries();
?>
