<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/includes
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Utils {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
    }

    function __get_default_date_time() {

        $user_accepted_date = "";
        // Format dete to the website preferred format

        $date = time();

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $user_accepted_date = wp_date("{$date_format} {$time_format}", $date);


        return $user_accepted_date;
    }

    public function __get_8_char_hash() {
        return hash('crc32', get_bloginfo("url"), FALSE);
    }

    function __get_4_char_hash() {
        $charset = $this->__get_8_char_hash();
        return substr($charset, 0, 4);
    }

    function __get_date_for_file_name() {

        $user_accepted_date = "";
        // Format dete to the website preferred format

        $date = time();

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $user_accepted_date = wp_date("Y-m-d-his", $date);


        return $user_accepted_date;
    }

    function __generate_random_string($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function __get_user_accepted_date($user_id) {
        $user_accepted_date = "";

        $user_state_manager = new TPUL_User_State($user_id);
        $the_user_accepted_terms_date = $user_state_manager->get_user_accepted_date_raw();

        // Format dete to the website preferred format
        if (!empty($the_user_accepted_terms_date)) {
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $user_accepted_date = wp_date("{$date_format} {$time_format}", $the_user_accepted_terms_date);
        }

        return $user_accepted_date;
    }

    function __get_user_accepted_date_for_file($user_id) {
        $user_accepted_date = "";

        $user_state_manager = new TPUL_User_State($user_id);
        $the_user_accepted_terms_date = $user_state_manager->get_user_accepted_date_raw();

        // Format dete to the website preferred format
        if (!empty($the_user_accepted_terms_date)) {
            $time_format = get_option('time_format');
            $user_accepted_date = wp_date("Y-m-d {$time_format}", $the_user_accepted_terms_date);
        }

        return $user_accepted_date;
    }
}
