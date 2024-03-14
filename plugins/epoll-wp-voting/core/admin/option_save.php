<?php 
$string_args = array(
    'type' => 'string', 
    'sanitize_callback' => 'sanitize_text_field',
    'default' => NULL,
    );

$int_args = array(
    'type' => 'integer', 
    'sanitize_callback' => 'sanitize_text_field',
    'default' => NULL,
    );

//General Settings Options
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_hcaptcha_voting', $int_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_hcaptcha_key', $string_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_hcaptcha_salt', $string_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_enable_comments', $int_args);



//Social Sharing Options
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_voting_social_sharing', $int_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_poll_social_sharing', $int_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_social_option_facebook', $int_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_social_option_twitter', $int_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_social_option_whatsapp', $int_args);

//Advanced Options
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_hide_voting_result', $int_args);
register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_hide_poll_result', $int_args);
