<?php
add_action('cg_get_user_ip_type','cg_get_user_ip_type');
if(!function_exists('cg_get_user_ip_type')){
    function cg_get_user_ip_type(){

        // the first three are the most usual variants that should work, HTTP_CLIENT_IP, REMOTE_ADDR, HTTP_X_FORWARDED_FOR
        // good explanation for many variants: https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php#answer-41382472
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $userIPtype = 'HTTP_CLIENT_IP';
        } else if (!empty($_SERVER['REMOTE_ADDR'])) {
            $userIPtype = 'REMOTE_ADDR';
        }else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIPtype = 'HTTP_X_FORWARDED_FOR';
        }
        else{

            // further unusual variants
            // good explanation for many variants: https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php#answer-41382472
            if (!empty($_SERVER['REMOTE_HOST'])) {
                $userIPtype = 'REMOTE_HOST';
            }else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
                $userIPtype = 'HTTP_X_CLUSTER_CLIENT_IP';
            }else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                $userIPtype = 'HTTP_FORWARDED_FOR';
            }else if (!empty($_SERVER['HTTP_FORWARDED'])) {
                $userIPtype = 'HTTP_FORWARDED';
            }else{
                $userIPtype = 'unknown';
            }

        }

        return $userIPtype;

    }
}
