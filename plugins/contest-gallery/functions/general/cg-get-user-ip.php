<?php

// source https://stackoverflow.com/questions/13818064/check-if-an-ip-address-is-private
if(!function_exists('cg_check_if_ip_is_private')){
    function cg_check_if_ip_is_private ($ip) {
        $pri_addrs = array (
            '10.0.0.0|10.255.255.255', // single class A network
            '172.16.0.0|172.31.255.255', // 16 contiguous class B network
            '192.168.0.0|192.168.255.255', // 256 contiguous class C network
            '169.254.0.0|169.254.255.255', // Link-local address also refered to as Automatic Private IP Addressing
            '127.0.0.0|127.255.255.255' // localhost
        );

        $long_ip = ip2long ($ip);
        if ($long_ip != -1) {

            foreach ($pri_addrs AS $pri_addr) {
                list ($start, $end) = explode('|', $pri_addr);

                // IF IS PRIVATE
                if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) {
                    return true;
                }
            }
        }

        return false;
    }
}

if(!function_exists('cg_available_ip_getter_types')){
    function cg_available_ip_getter_types () {

        $array = array();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $array[] = 'HTTP_CLIENT_IP';
        }
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $array[] = 'REMOTE_ADDR';
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $array[] = 'HTTP_X_FORWARDED_FOR';
        }
        if (!empty($_SERVER['REMOTE_HOST'])) {
            $array[] = 'REMOTE_HOST';
        }
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $array[] = 'HTTP_X_CLUSTER_CLIENT_IP';
        }
        if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $array[] = 'HTTP_FORWARDED_FOR';
        }
        if (!empty($_SERVER['HTTP_FORWARDED'])) {
            $array[] = 'HTTP_FORWARDED';
        }

        return $array;

    }
}


add_action('cg_get_user_ip','cg_get_user_ip');
if(!function_exists('cg_get_user_ip')){
    function cg_get_user_ip(){

        // the first three are the most usual variants that should work, HTTP_CLIENT_IP, REMOTE_ADDR, HTTP_X_FORWARDED_FOR
        // good explanation for many variants: https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php#answer-41382472
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $userIP = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['REMOTE_ADDR'])) {// started with REMOTE_ADDR as second long time ago, localhost is often ONLY REMOTE_ADDR!!!
            $userIP = $_SERVER['REMOTE_ADDR'];
            if(cg_check_if_ip_is_private($userIP)){// IF is private then try to get another one, if exists!

                if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{

                    // further unusual variants
                    // good explanation for many variants: https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php#answer-41382472
                    if (!empty($_SERVER['REMOTE_HOST'])) {
                        $userIP = $_SERVER['REMOTE_HOST'];
                    }else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
                        $userIP = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
                    }else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $userIP = $_SERVER['HTTP_FORWARDED_FOR'];
                    }else if (!empty($_SERVER['HTTP_FORWARDED'])) {
                        $userIP = $_SERVER['HTTP_FORWARDED'];
                    }/*else{!IMPORTANT, NO unknown here! At the end, at least REMOTE_ADDR see above $userIP, has to be given to customer if exists, and it will be a private then.
                        $userIP = 'unknown';
                    }*/

                }

            }
        }else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{

            // further unusual variants
            // good explanation for many variants: https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php#answer-41382472
            if (!empty($_SERVER['REMOTE_HOST'])) {
                $userIP = $_SERVER['REMOTE_HOST'];
            }else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
                $userIP = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
            }else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                $userIP = $_SERVER['HTTP_FORWARDED_FOR'];
            }else if (!empty($_SERVER['HTTP_FORWARDED'])) {
                $userIP = $_SERVER['HTTP_FORWARDED'];
            }else{
                $userIP = 'unknown';
            }

        }

        return $userIP;

    }
}
