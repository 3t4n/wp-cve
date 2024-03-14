<?php

/* --- Enqueue plugin stylsheet --- */
add_action('wp_enqueue_scripts', 'add_topbar_style');
function add_topbar_style()
{
    wp_enqueue_style('topbar', plugins_url('../css/topbar_style.css', __FILE__));
    wp_enqueue_script('topbar_frontjs', plugins_url('../js/tpbr_front.min.js', __FILE__), ['jquery']);

    if (is_admin_bar_showing()) {
        $tpbr_is_admin_bar = 'yes';
    } else {
        $tpbr_is_admin_bar = 'no';
    }

    if (is_user_logged_in()) {
        $who_match = 'loggedin';
    } else {
        $who_match = 'notloggedin';
    }

    // getting the options
    $tpbr_guests_or_users = (!empty(esc_attr(get_option('tpbr_guests_or_users')))) ? esc_attr(get_option('tpbr_guests_or_users')) : 'all';
    $tpbr_fixed = esc_attr(get_option('tpbr_fixed'));
    $tpbr_message = esc_attr(get_option('tpbr_message'));
    $tpbr_status = esc_attr(get_option('tpbr_status'));
    $tpbr_yn_button = esc_attr(get_option('tpbr_yn_button'));
    $tpbr_color = sanitize_hex_color(get_option('tpbr_color'));
    $tpbr_button_text = esc_attr(get_option('tpbr_btn_text'));
    $tpbr_button_url = esc_url(get_option('tpbr_btn_url'));
    $tpbr_button_behavior = esc_attr(get_option('tpbr_btn_behavior'));
    $tpbr_detect_sticky = esc_attr(get_option('tpbr_detect_sticky'));
    $tpbr_settings = [
        'fixed' => $tpbr_fixed,
        'user_who' => $who_match,
        'guests_or_users' => $tpbr_guests_or_users,
        'message' => (!empty($tpbr_message)) ? $tpbr_message : 'Welcome to our website!',
        'status' => $tpbr_status,
        'yn_button' => $tpbr_yn_button,
        'color' => $tpbr_color,
        'button_text' => $tpbr_button_text,
        'button_url' => $tpbr_button_url,
        'button_behavior' => $tpbr_button_behavior,
        'is_admin_bar' => $tpbr_is_admin_bar,
        'detect_sticky' => $tpbr_detect_sticky,
    ];
    // sending the options to the js file
    wp_localize_script('topbar_frontjs', 'tpbr_settings', $tpbr_settings);
}
