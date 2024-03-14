<?php

class CleanLogin_Frontend{
    function load(){
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );                
    }

    function enqueue() {
        if( !CleanLogin_Shortcode::has_clean_login() )
            return;

        wp_enqueue_style( 'clean-login-css', CLEAN_LOGIN_URL . 'content/style.css' );
        wp_enqueue_style( 'clean-login-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css' );
    }

    static function get_template_file( $template, $param = array() ){
        if ( $overridden_template = locate_template( 'clean-login/' . $template ) ) {
            require( $overridden_template );
        } else {
            require( CLEAN_LOGIN_PATH . 'content/' . $template );
        }
    }
    
    static function gcaptcha_script() {
        $lang_gcaptcha_options = array('nb_NO' => 'no', 'en_US' => 'en', 'en_GB' => 'en', 'es_ES' => 'es');
        $lang = '';

        if( isset( $lang_gcaptcha_options[ get_locale() ] ) ){
            $lang = '?hl='.$lang_gcaptcha_options[get_locale()];
        }
        echo '<script src="https://www.google.com/recaptcha/api.js' . $lang . '" async defer></script>' . "\r\n";
    }
}