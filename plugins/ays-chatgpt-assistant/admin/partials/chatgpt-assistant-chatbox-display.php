<?php

$data = $this->db_obj->get_data();
$api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';

// Get global styles for chatbot
$this->set_global_settings();

$options = ($this->settings_obj->get_setting('options') === false) ? array() : json_decode($this->settings_obj->get_setting('options'), true);
$chatbox_color = (isset($options['chatbox_color']) && $options['chatbox_color'] != '' ) ? $options['chatbox_color'] : '#4e426d';
$chatbox_mode  = ( isset( $options['chatbox_mode'] ) && $options['chatbox_mode'] != '' ) ? $options['chatbox_mode'] : 'light';

// Set global styles for chatbot
$chatbot_global_styles = array(
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position' => $this->chatbox_position,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_position' => $this->chatbox_icon_position,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size' => $this->chatgpt_assistant_chat_icon_size,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width' => $this->chatgpt_assistant_chat_width,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width_format' => $this->chatgpt_assistant_chat_width_format,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height' => $this->chatgpt_assistant_chat_height,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height_format' => $this->chatgpt_assistant_chat_height_format,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_color' => $chatbox_color,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color' => $this->chatgpt_assistant_chatbox_background_color,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_header_text_color' => $this->chatgpt_assistant_chatbox_header_text_color,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_mode' => $chatbox_mode,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_font_size' => $this->chatgpt_assistant_message_font_size,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_spacing' => $this->chatgpt_assistant_message_spacing,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_border_radius' => $this->chatgpt_assistant_message_border_radius,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_border_radius' => $this->chatgpt_assistant_chatbot_border_radius,
    // USER MESSAGE STYLES
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_bg_color' => $this->message_bg_color,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_text_color' => $this->message_text_color,
    // CHATBOT MESSAGE STYLES
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_bg_color' => $this->response_bg_color,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_text_color' => $this->response_text_color,
    CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_icons_color' => $this->response_icons_color,
);

$chatbot_main_box_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'regenerate_response'] = $this->chatgpt_assistant_regenerate_response;
$chatbot_main_box_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'] = $this->chatgpt_assistant_message_placeholder;
$chatbot_main_box_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'] = $this->chatgpt_assistant_chatbot_name;
$chatbot_main_box_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'] = $this->chatgpt_assistant_compliance_text;
$chatbot_main_box_options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_theme']       = $this->chatbox_theme;

if (isset($api_key) && $api_key != '' && $this->chatgpt_assistant_show_dashboard_chat) {
    echo ChatGPT_assistant_Data::get_chatbot_main_box( $chatbox_mode, $api_key, $chatbot_main_box_options);
}
echo ChatGPT_assistant_Data::get_chatbot_styles($chatbot_global_styles);

?>