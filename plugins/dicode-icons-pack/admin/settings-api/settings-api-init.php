<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit(); 

class Dicode_Icons_Admin_Setting_Init{

    public function __construct(){
        //add_action('admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        $this->dicode_admin_settings_page();
    }

    /*
    *  Setting Page
    */
    public function dicode_admin_settings_page() {
        if ( !file_exists('class.settings-api.php') ){
            require_once('class.settings-api.php');
        }
        if ( !file_exists('settings-fields.php') ){
            require_once('settings-fields.php');
        }    
    }


}

new Dicode_Icons_Admin_Setting_Init();