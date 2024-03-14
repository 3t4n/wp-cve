<?php

namespace WpLHLAdminUi\Helpers;

class Utils {

    
    public static function __get_default_date_time(){

        $user_accepted_date = "";
        // Format dete to the website preferred format

        $date = time();

            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $user_accepted_date = wp_date("{$date_format} {$time_format}", $date);


        return $user_accepted_date;
    }

    public static function __get_8_char_hash(){
        return hash('crc32', get_bloginfo("url"), FALSE);
    }

    public static function __get_4_char_hash(){
        $charset = self::__get_8_char_hash();
        return substr($charset, 0, 4);
    }

    public static function __get_date_for_file_name(){

        $user_accepted_date = "";
        // Format dete to the website preferred format

        $date = time();

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $user_accepted_date = wp_date("Y-m-d-his", $date);


        return $user_accepted_date;
    }

    public static function __generate_random_string($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}