<?php
if (!function_exists('njt_wp_saio_get_user_ip')) {
    function njt_wp_saio_get_user_ip()
    {
        $ip = $_SERVER['HTTP_CLIENT_IP']?$_SERVER['HTTP_CLIENT_IP']:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']);
        return $ip;
    }
}
