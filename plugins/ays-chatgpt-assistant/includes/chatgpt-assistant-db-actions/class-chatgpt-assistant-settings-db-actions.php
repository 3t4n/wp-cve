<?php

if( ! class_exists( 'ChatGPT_Assistant_Settings_DB_Actions' ) ){
	ob_start();

    /*
     ChatGPT_Assistant_Settings_DB_Actions Stands for storing data in 
     Setting page extending main functions from ChatGPT_Assistant_Main_DB_Actions
    */ 
    class ChatGPT_Assistant_Settings_DB_Actions extends ChatGPT_Assistant_Main_DB_Actions{

        public function store_data(){

            if( isset( $_REQUEST["settings_action"] ) && wp_verify_nonce( $_REQUEST["settings_action"], 'settings_action' ) ){
                $success = 0;
                
                // * GENERAL SETTINGS *
                    // ===== Shortcode settings =====
                        // Full screen mode
                        $chatgpt_assistant_full_screen_mode = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_full_screen_mode']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_full_screen_mode'] == 'on') ? 'on' : 'off';
                    // ===== General settings =====
                        // Show chat window on front end
                        $chatbox_onoff = $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_onoff'];
                        // Chatbot position
                        $chatgpt_assistant_chatbox_position = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_position']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_position'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_position']) : 'right';
                        // Chatbot icon position
                        $chatgpt_assistant_chatbox_icon_position = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_icon_position']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_icon_position'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_icon_position']) : 'bottom-'.$chatgpt_assistant_chatbox_position;
                        // Chat icon size
                        $chatgpt_assistant_chat_icon_size = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_icon_size']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_icon_size'] != '') ? absint($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_icon_size']) : 70;
                        // Chat width
                        $chatgpt_assistant_chat_width = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_width']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_width'] != '') ? absint($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_width']) : 28;
                        // Chat width format
                        $chatgpt_assistant_chat_width_format = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_width_format']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_width_format'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_width_format']) : '%';
                        // Chat height
                        $chatgpt_assistant_chat_height = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_height']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_height'] != '') ? absint($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_height']) : 55;
                        // Chat height format
                        $chatgpt_assistant_chat_height_format = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_height_format']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_height_format'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_height_format']) : '%';
                        // Auto opening Chatbox
                        $chatgpt_assistant_auto_opening_chatbox = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_auto_opening_chatbox']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_auto_opening_chatbox'] == 'on') ? 'on' : 'off';
                        // Auto opening Chatbox delay
                        $chatgpt_assistant_auto_opening_chatbox_delay = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_auto_opening_chatbox_delay']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_auto_opening_chatbox_delay'] != '') ? absint($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_auto_opening_chatbox_delay']) : 0;
                        // Show dashboard chat
                        $chatgpt_assistant_show_dashboard_chat = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_show_dashboard_chat']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_show_dashboard_chat'] == 'on') ? 'on' : 'off';
                        // Regenerate response
                        $chatgpt_assistant_regenerate_response = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_regenerate_response']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_regenerate_response'] == 'on') ? 'on' : 'off';
                        // Enable rate chat
                        $chatgpt_assistant_enable_rate_chat = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_enable_rate_chat']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_enable_rate_chat'] == 'on') ? 'on' : 'off';
                        // Rate chat text
                        $chatgpt_assistant_rate_chat_text = isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_rate_chat_text']) ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_rate_chat_text'])) : __('How Satisfied are You?', 'chatgpt-assistant');

                        // Rate chat options
                        $chatgpt_assistant_rate_chat = isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_rate_chat']) && !empty($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_rate_chat']) ? $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_rate_chat'] : array();                      

                        $chatgpt_assistant_rate_chat['like'] = isset($chatgpt_assistant_rate_chat['like']) && !empty($chatgpt_assistant_rate_chat['like']) ? $chatgpt_assistant_rate_chat['like'] : array();
                        $chatgpt_assistant_rate_chat['like']['action'] = (isset($chatgpt_assistant_rate_chat['like']['action']) && ($chatgpt_assistant_rate_chat['like']['action'] == 'feedback' || $chatgpt_assistant_rate_chat['like']['action'] == 'help')) ? sanitize_text_field($chatgpt_assistant_rate_chat['like']['action']) : 'feedback';
                        $chatgpt_assistant_rate_chat['like']['text'] = (isset($chatgpt_assistant_rate_chat['like']['text']) && $chatgpt_assistant_rate_chat['like']['text'] != '') ? stripslashes(sanitize_text_field($chatgpt_assistant_rate_chat['like']['text'])) : '';

                        $chatgpt_assistant_rate_chat['dislike'] = isset($chatgpt_assistant_rate_chat['dislike']) && !empty($chatgpt_assistant_rate_chat['dislike']) ? $chatgpt_assistant_rate_chat['dislike'] : array();
                        $chatgpt_assistant_rate_chat['dislike']['action'] = (isset($chatgpt_assistant_rate_chat['dislike']['action']) && ($chatgpt_assistant_rate_chat['dislike']['action'] == 'feedback' || $chatgpt_assistant_rate_chat['dislike']['action'] == 'help')) ? sanitize_text_field($chatgpt_assistant_rate_chat['dislike']['action']) : 'feedback';
                        $chatgpt_assistant_rate_chat['dislike']['text'] = (isset($chatgpt_assistant_rate_chat['dislike']['text']) && $chatgpt_assistant_rate_chat['dislike']['text'] != '') ? stripslashes(sanitize_text_field($chatgpt_assistant_rate_chat['dislike']['text'])) : '';

                        // Greeting message
                        $chatgpt_assistant_greeting_message = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_greeting_message']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_greeting_message'] == 'on') ? 'on' : 'off';
                        // Greeting message text
                        $chatgpt_assistant_greeting_message_text = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_greeting_message_text']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_greeting_message_text'] != '') ? sanitize_text_field(stripslashes($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_greeting_message_text'])) : '';
                        // Message placeholder
                        $chatgpt_assistant_message_placeholder = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_placeholder']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_placeholder'] != '') ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_placeholder'])) : __('Enter your message', 'chatgpt-assistant');
                        // Message placeholder
                        $chatgpt_assistant_chatbot_name = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbot_name']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbot_name'] != '') ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbot_name'])) : __('AI Assistant', 'chatgpt-assistant');
                        // compliance text
                        $chatgpt_assistant_compliance_text = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_compliance_text']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_compliance_text'] != '') ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_compliance_text'])) :'';
                    // ===== Chat settings =====
                        // Model
                        $chatgpt_assistant_chat_model = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_model']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_model'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_model']) : 'gpt-3.5-turbo-16k';
                        // Temprature
                        $chatgpt_assistant_chat_temprature = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_temprature']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_temprature'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_temprature']) : '0.8';
                        // Top p
                        $chatgpt_assistant_chat_top_p = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_top_p']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_top_p'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chat_top_p']) : '1';
                        // Max tokens
                        $chatgpt_assistant_max_tokens = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_max_tokens']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_max_tokens'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_max_tokens']) : 1500;                    
                        // Frequency penalty
                        $chatgpt_assistant_frequency_penalty = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_frequency_penalty']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_frequency_penalty'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_frequency_penalty']) : '0.01';                    
                        // Presence penalty
                        $chatgpt_assistant_presence_penalty = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_presence_penalty']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_presence_penalty'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_presence_penalty']) : '0.01';
                        // Best of
                        $chatgpt_assistant_best_of = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_best_of']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_best_of'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_best_of']) : 1;
                        // Context
                        $chatgpt_assistant_context = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_context']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_context'] != '') ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_context'])) : '';
                        // Profession (Act as)
                        $chatgpt_assistant_profession = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_profession']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_profession'] != '') ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_profession'])) : '';
                        // Tone
                        $chatgpt_assistant_tone = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_tone']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_tone'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_tone']) : 'none';
                        // Language
                        $chatgpt_assistant_language = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_language']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_language'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_language']) : 'en';
                        // Name
                        $chatgpt_assistant_name = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_name']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_name'] != '') ? stripslashes(sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_name'])) : '';
                //

                // * STYLE SETTINGS *
                    // Chat theme
                    $chatbox_theme = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_theme']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_theme'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_theme']) : 'default' ;
                    // Chat Widget color
                    $chatbox_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_color']) : '#4e426d' ;
                    // Chat Widget background color
                    $chatgpt_assistant_chatbox_background_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_background_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_background_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_background_color']) : '#d3d3d3' ;
                    // Chat Widget header text color
                    $chatgpt_assistant_chatbox_header_text_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_header_text_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_header_text_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_header_text_color']) : '#ffffff' ;
                    // Chat dark mode
                    $chatbox_mode  = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_mode']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_mode'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_mode']) : 'light';
                    // Message font size
                    $message_font_size = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_font_size']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_font_size'] != '') ? sanitize_text_field(intval($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_font_size'])) : 16;
                    // Message spacing
                    $message_spacing = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_spacing']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_spacing'] != '') ? sanitize_text_field(intval($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_spacing'])) : 7;
                    // Message border radius
                    $message_border_radius = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_border_radius']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_border_radius'] != '') ? absint(esc_attr($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_border_radius'])) : 10;
                    // chatbot border radius
                    $chatbot_border_radius = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbot_border_radius']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbot_border_radius'] != '') ? absint(esc_attr($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbot_border_radius'])) : 10;

                    /* === USER MESSAGE STYLES START === */
                        // User message background color
                        $message_bg_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_bg_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_bg_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_bg_color']) : '#ffffff';
                        $message_text_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_text_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_text_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_message_text_color']) : '#ffffff';
                    /* === USER MESSAGE STYLES END === */

                    /* === CHATBOT MESSAGE STYLES START === */
                        // Response background color
                        $response_bg_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_bg_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_bg_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_bg_color']) : '#30ae8d';
                        $response_text_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_text_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_text_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_text_color']) : '#f1f1f1';
                        $response_icons_color = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_icons_color']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_icons_color'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_response_icons_color']) : '#636a84';
                    /* === CHATBOT MESSAGE STYLES END === */
                //

                $options = array(                    
                    // * GENERAL SETTINGS *
                    // ===== Shortcode settings =====
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'full_screen_mode'     => $chatgpt_assistant_full_screen_mode,
                    // ===== General settings =====
                    "chatbox_onoff" => $chatbox_onoff,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position'             => $chatgpt_assistant_chatbox_position,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_position'        => $chatgpt_assistant_chatbox_icon_position,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size'       => $chatgpt_assistant_chat_icon_size,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width'           => $chatgpt_assistant_chat_width,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width_format'    => $chatgpt_assistant_chat_width_format,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height'          => $chatgpt_assistant_chat_height,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height_format'   => $chatgpt_assistant_chat_height_format,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox' => $chatgpt_assistant_auto_opening_chatbox,                    
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay' => $chatgpt_assistant_auto_opening_chatbox_delay,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'  => $chatgpt_assistant_show_dashboard_chat,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'regenerate_response'  => $chatgpt_assistant_regenerate_response,                    
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_rate_chat'     => $chatgpt_assistant_enable_rate_chat,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat_text'        => $chatgpt_assistant_rate_chat_text,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat'            => $chatgpt_assistant_rate_chat,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message'  => $chatgpt_assistant_greeting_message,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text'  => $chatgpt_assistant_greeting_message_text,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'  => $chatgpt_assistant_message_placeholder,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'      => $chatgpt_assistant_chatbot_name,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'      => $chatgpt_assistant_compliance_text,
                    // ===== Chat settings =====
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_model'        => $chatgpt_assistant_chat_model,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_temprature'   => $chatgpt_assistant_chat_temprature,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_top_p'        => $chatgpt_assistant_chat_top_p,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'max_tokens'        => $chatgpt_assistant_max_tokens,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'frequency_penalty' => $chatgpt_assistant_frequency_penalty,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'presence_penalty'  => $chatgpt_assistant_presence_penalty,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'best_of'           => $chatgpt_assistant_best_of,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'context'           => $chatgpt_assistant_context,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'profession'        => $chatgpt_assistant_profession,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'tone'              => $chatgpt_assistant_tone,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'language'          => $chatgpt_assistant_language,
					CHATGPT_ASSISTANT_OPTIONS_PREFIX.'name'              => $chatgpt_assistant_name,
                    // * STYLE SETTINGS *
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_theme' => $chatbox_theme,
                    'chatbox_color' => $chatbox_color,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color' => $chatgpt_assistant_chatbox_background_color,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_header_text_color' => $chatgpt_assistant_chatbox_header_text_color,
                    'chatbox_mode' => $chatbox_mode,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_font_size'  => $message_font_size,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_spacing'  => $message_spacing,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_border_radius'  => $message_border_radius,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_border_radius'  => $chatbot_border_radius,
                    // USER MESSAGE STYLES
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_bg_color'  => $message_bg_color,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_text_color'  => $message_text_color,
                    // CHATBOT MESSAGE STYLES
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_bg_color' => $response_bg_color,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_text_color' => $response_text_color,
                    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_icons_color' => $response_icons_color,
                );

                $fields = array();
                
                $fields['options'] = $options;

                foreach ($fields as $key => $value) {
                    $result = $this->update_setting( $key, json_encode( $value ) );
                    if($result){
                        $success++;
                    }
                }

                $message = "saved";
                if($success > 0){
                    $tab = "";
                    if( isset( $_REQUEST['ays_tab'] ) ){
                        $tab = "&ays_tab=". sanitize_text_field( $_REQUEST['ays_tab'] );
                    }

                    $url = admin_url('admin.php') . "?page=". $this->plugin_name . $tab . '&status=' . $message;
                    wp_redirect( $url );
                    exit();
                }
            }

        }
    }
}
