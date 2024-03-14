<?php 
$template = "";
global $wordpress;
global $wpdb;
$current_user = wp_get_current_user();
$profile_button = null;
$eprofile_page = $this->check_eprofile_shortcode_lite();
if ($eprofile_page != null){
    $profile_button = '<button class="aioUserButton" href="' . esc_url(get_permalink($eprofile_page)) . '">' . esc_attr_x("Employee Profile", 'aio-time-clock-lite') . '</button> ';
}

if (get_current_user_id() > 0){
    $template .= 
    '<div id="aio_time_clock">
        <div class="aio_form">
            <form class="login-form">
                <h2>' . esc_attr($current_user->user_firstname) . ' ' . esc_attr($current_user->user_lastname) . '</h2>
                <p id="clockMessage"></p>
                <p id="jsTimer" style="padding-bottom: 10px;"><strong>' . esc_attr_x('Current Time', 'aio-time-clock-lite') . ': </strong></p>
                <button id="aio_clock_button" href="' . esc_url($link) . '"><div class="aio-spinner"></div></button>
                <button style="display:none;" id="newShift" class="button clock_in" href="' . esc_url(get_permalink($tc_page)) .'"> ' . esc_attr_x("New Shift", 'aio-time-clock-lite') . '</button>
                <div style="height:20px;"></div>
                '.$profile_button.'
                <div style="height:20px;"></div>
                <button class="aioUserButton" href="' . esc_url(wp_logout_url()) . '">' . esc_attr_x("Logout", 'aio-time-clock-lite') . '</button>
                <input type="hidden" name="clock_action" id="clock_action">
                <input type="hidden" name="open_shift_id" id="open_shift_id">
                <input type="hidden" name="wage_enabled" value="' . esc_attr(get_option("aio_wage_manage")) . '">
                <input type="hidden" name="employee" id="employee" value="' . esc_attr($current_user->ID) . '">
            </form>
        </div>
    </div>';
}
else{
    $template .= 
    '<div id="aio_time_clock">
        <div class="aio_form">
            <p>' . esc_attr_x('You must be logged in to use the time clock', 'aio-time-clock-lite') . '.</p>
            <a href="' . esc_url(wp_login_url()) . '"><button>' . esc_attr_x("Login", 'aio-time-clock-lite').'</button></a>
        </div>
    </div>';
}

echo $template;