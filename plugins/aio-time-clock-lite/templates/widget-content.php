<?php 
$template = "";
global $wordpress;
global $wpdb;
global $current_user;
$profile_button = null;
$tc = new AIO_Time_Clock_Lite_Actions();
$eprofile_page = $tc->check_eprofile_shortcode_lite();
$tc_page = $tc->aio_check_tc_shortcode_lite();
$nonce = wp_create_nonce("clock_in_nonce");
$link = admin_url('admin-ajax.php?action=clock_in_nonce&post_id=' . get_the_ID() . '&nonce=' . esc_attr($nonce));
if ($eprofile_page != null){
    $profile_button = '<button class="aioUserButton" href="' . esc_url(get_permalink($eprofile_page)) . '">' . esc_attr_x("Employee Profile", 'aio-time-clock-lite').'</button> ';
}

if (is_user_logged_in()){
    $template .= 
    '<div id="aio_time_clock_widget"">
        <div class="aio_form">
            <form class="login-form">
                <h2>' . esc_attr($current_user->user_firstname) . ' ' . esc_attr($current_user->user_lastname) . '</h2>
                <p id="clockMessage"></p>
                <p id="jsTimer" style="padding-bottom: 10px;"><strong>' . esc_attr_x('Current Time', 'aio-time-clock-lite').': </strong></p>
                <button id="aio_clock_button" href="' . esc_url($link) . '"><div class="aio-spinner"></div></button>
                <button style="display:none;" id="newShift" class="button clock_in" href="' . esc_url(get_permalink($tc_page)) .'"> ' . esc_attr_x("New Shift", 'aio-time-clock-lite') . '</button>
                <div style="height:20px;"></div>
                '.$profile_button.'
                <div style="height:20px;"></div>
                <button class="aioUserButton" href="' . esc_url(wp_logout_url()) . '">' . esc_attr_x("Logout", 'aio-time-clock-lite').'</button>
                <input type="hidden" name="clock_action" id="clock_action">
                <input type="hidden" name="open_shift_id" id="open_shift_id">
            </form>
        </div>
    </div>';
}
else{
    $template .= 
    '<div id="aio_time_clock_widget">
        <div class="aio_form">
            <p>' . esc_attr_x('You must be logged in to use the time clock', 'aio-time-clock-lite') . '.</p>
            <a href="' . esc_url(wp_login_url()) . '"><button>' . esc_attr_x("Login", 'aio-time-clock-lite') . '</button></a>
        </div>
    </div>';
}

echo $template;