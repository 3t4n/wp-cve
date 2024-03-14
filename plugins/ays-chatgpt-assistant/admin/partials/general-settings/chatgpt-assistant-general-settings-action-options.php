<?php

    if( isset( $_POST['ays_submit'] ) || isset( $_POST['ays_submit_top'] ) ) {
        $this->general_settings_obj->store_data();
    }
    if (isset($_GET['ays_tab'])) {
        $ays_tab = sanitize_text_field($_GET['ays_tab']);
    } else {
	    $ays_tab = 'tab1';
    }

    $loader_iamge = "<span class='display_none ays_chatgpt_assistant_loader_box'><img src=". esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ."/images/loaders/loading.gif></span>";

    $options = (empty($this->general_settings_obj->get_all_data())) ? array() : $this->general_settings_obj->get_all_data();
    
    $data = $this->db_obj->get_data();

    $api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';

    $check_openai_connection = ChatGPT_assistant_Data::makeRequest($api_key, 'GET', 'models');
    $check_openai_connection_code = false;

    if(is_array($check_openai_connection)){
        $check_openai_connection_code = isset($check_openai_connection['openai_response_code']) && $check_openai_connection['openai_response_code'] == 200 ? true : false; 
    }

    $chatgpt_assistant_textarea_height = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height'] ) && intval($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height']) > 0 ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'textarea_height']) : 70;

?>