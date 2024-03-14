<?php
/**
 * Settings Class
 * 
 * @package Corona
 */
namespace CoderExpert\Corona;

defined( 'ABSPATH' ) or exit;

class Settings {
    public static function init() {
        self::admin_menu();
    }
    public static function display() {
        Helper::views( 'settings.php' );
    }
    public static function admin_menu(){
        \add_menu_page( 
            'Corona', 'Corona', 
            'delete_users', 'ce-corona', 
            array( __CLASS__, 'display' ), CE_CORONA_ASSETS . 'images/logo.png' 
        );
    }
}