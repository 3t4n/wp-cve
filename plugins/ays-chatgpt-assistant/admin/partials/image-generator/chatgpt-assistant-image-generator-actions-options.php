<?php
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

    $models = array(
        'dall-e-2' => 'DALL·E 2',
        'dall-e-3' => 'DALL·E 3 (ChatGPT Plus)',
    );

    $styles = array(
        "vivid" => __("Vivid (hyper-real and dramatic images)", "chatgpt-assistant"),
        "natural" => __("Natural (natural, less hyper-real looking images)", "chatgpt-assistant"),
    );
    
    $photography = array(
        "Portrait" => __("Portrait", "chatgpt-assistant"),
        "Landscape" => __("Landscape", "chatgpt-assistant"),
        "Abstract" => __("Abstract", "chatgpt-assistant"),
        "Action" => __("Action", "chatgpt-assistant"),
        "Aerial" => __("Aerial", "chatgpt-assistant"),
        "Agricultural" => __("Agricultural", "chatgpt-assistant"),
        "Animal" => __("Animal", "chatgpt-assistant"),
        "Architectural" => __("Architectural", "chatgpt-assistant"),
        "Astrophotography" => __("Astrophotography", "chatgpt-assistant"),
        "Bird photography" => __("Bird photography", "chatgpt-assistant"),
        "Black and white" => __("Black and white", "chatgpt-assistant"),
        "Candid" => __("Candid", "chatgpt-assistant"),
        "Cityscape" => __("Cityscape", "chatgpt-assistant"),
        "Close-up" => __("Close-up", "chatgpt-assistant"),
        "Commercial" => __("Commercial", "chatgpt-assistant"),
        "Conceptual" => __("Conceptual", "chatgpt-assistant"),
        "Corporate" => __("Corporate", "chatgpt-assistant"),
        "Documentary" => __("Documentary", "chatgpt-assistant"),
        "Event" => __("Event", "chatgpt-assistant"),
        "Family" => __("Family", "chatgpt-assistant"),
        "Fashion" => __("Fashion", "chatgpt-assistant"),
        "Fine art" => __("Fine art", "chatgpt-assistant"),
        "Food" => __("Food", "chatgpt-assistant"),
        "Food photography" => __("Food photography", "chatgpt-assistant"),
        "Glamour" => __("Glamour", "chatgpt-assistant"),
        "Industrial" => __("Industrial", "chatgpt-assistant"),
        "Lifestyle" => __("Lifestyle", "chatgpt-assistant"),
        "Macro" => __("Macro", "chatgpt-assistant"),
        "Nature" => __("Nature", "chatgpt-assistant"),
        "Night" => __("Night", "chatgpt-assistant"),
        "Product" => __("Product", "chatgpt-assistant"),
        "Sports" => __("Sports", "chatgpt-assistant"),
        "Street" => __("Street", "chatgpt-assistant"),
        "Travel" => __("Travel", "chatgpt-assistant"),
        "Underwater" => __("Underwater", "chatgpt-assistant"),
        "Wedding" => __("Wedding", "chatgpt-assistant"),
        "Wildlife" => __("Wildlife", "chatgpt-assistant"),
        "None" => __("None", "chatgpt-assistant")
    );    
    
    $resolutions = array(
        "4K (3840x2160)" => "4K (3840x2160)",
        "1080p (1920x1080)" => "1080p (1920x1080)",
        "720p (1280x720)" => "720p (1280x720)",
        "480p (854x480)" => "480p (854x480)",
        "2K (2560x1440)" => "2K (2560x1440)",
        "1080i (1920x1080)" => "1080i (1920x1080)",
        "720i (1280x720)" => "720i (1280x720)",
        "None" => "None"
    );

    $sizes = array(
        '1024x1024' => '1024x1024 (Both)',
        '256x256' => '256x256 (DALL·E 2)',
        '512x512' => '512x512 (DALL·E 2)',
        '1792x1024' => '1792x1024 (DALL·E 3)',
        '1024x1792' => '1024x1792 (DALL·E 3)',
    );
    
?>