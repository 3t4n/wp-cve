<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classJsportRequest
{
    public static function get($var, $request = 'request', $type = 'string')
    {
        switch ($request) {
            case 'post':
                if(isset($_POST[$var]) && is_array($_POST[$var])){
                    $return = isset($_POST[$var]) ? array_map('sanitize_text_field', wp_unslash($_POST[$var])) : '';
                }else{
                    $return = isset($_POST[$var]) ? sanitize_text_field($_POST[$var]) : '';
                }


                break;
            case 'get':
                if(isset($_GET[$var]) && is_array($_GET[$var])){
                    $return = isset($_GET[$var]) ? array_map('sanitize_text_field', wp_unslash($_GET[$var])) : '';
                }else {
                    $return = isset($_GET[$var]) ? sanitize_text_field($_GET[$var]) : '';
                }

                break;
            default:
                if(isset($_REQUEST[$var]) && is_array($_REQUEST[$var])){
                    $return = isset($_REQUEST[$var]) ? array_map('sanitize_text_field', wp_unslash($_REQUEST[$var])) : '';
                }else {
                    $return = isset($_REQUEST[$var]) ? sanitize_text_field($_REQUEST[$var]) : '';
                }
                break;
        }

        switch ($type) {
            case 'int':
                $return = intval($return);

                break;
            case 'float':
                $return = floatval($return);

                break;
            default:
                break;
        }

        return $return;
    }
}
