<?php
if (!function_exists('add_action'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}

if (!class_exists('pfmdz_front')){

    class pfmdz_front {

        //CONSTRUCTOR
        public function __construct(){

            //check settings
            $is_active = get_option('pfmdz_isactive');
            $is_login = get_option('pfmdz_loginfonts');
            $is_front_fonts = get_option('pfmdz_frontfonts');

            //add fonts to login-page
            if ($is_active == '1' && $is_login == '1'){
                add_action('login_enqueue_scripts', arraY($this, 'addlogin_fonts'));
            }

            //add front fonts if enabled
            if ($is_front_fonts == '1'){
                add_action( 'wp_enqueue_scripts', array($this, 'add_front_fonts'));
            }
  
        }

        //FUNCTIONS
        public function addlogin_fonts(){

            $tmp_version = get_option("pfmdz_tmpversion");
            $final_version = PFMDZ_VERSION.".".$tmp_version;
            wp_enqueue_style( 'pfmdz-loginFont', persianfontsmdez_URL . 'libs/fonts/css/dynamicAdminFont.css', false, $final_version);

        }

        //front fonts adder
        public function add_front_fonts(){

            $tmp_version = get_option("pfmdz_tmpversion");
            $final_version = PFMDZ_VERSION.".".$tmp_version;
            wp_enqueue_style( 'pfmdz-frontfonts', persianfontsmdez_URL . 'libs/fonts/css/dynamic-front-fonts.css', true, $final_version);

        }
    }
}

new pfmdz_front(); //init
//close the PHP tag to reduce the blank spaces ?>