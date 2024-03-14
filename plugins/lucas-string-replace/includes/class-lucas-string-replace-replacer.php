<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Lucas_String_Replace_Replacer {

    private static $_instance = null;
    public $parent = null;



    public function __construct( $parent ){

        $this->parent = $parent;

        if( $this->is_enabled() ) {
            add_action( 'wp_loaded', array($this, 'buffer_start') );
            add_action( 'shutdown', array($this, 'buffer_end') );
        }

    }



    public function callback( $buffer ) {

        $replacesets = get_option( $this->parent->_token . '_settings_replacesets' );

        if( ! $replacesets ) {
            return $buffer;
        }

        $replacesets = lsr_stripslashes( unserialize($replacesets) );

        foreach( $replacesets as $replaceset ){
            if( (isset($replaceset[0]) && is_array($replaceset[0])) && isset($replaceset[1]) ){
                $search = $replaceset[0];
                $replace = $replaceset[1];
            } else {
                continue;
            }
            $buffer = str_replace( $search, $replace, $buffer );
        }

        return $buffer;
    }



    public function is_enabled() {

        $enabled = (get_option( $this->parent->_token . '_settings_enable', 'on') == 'on') ? true : false;

        if( $enabled ){
            if( is_admin() ) {
                $enabled_on_admin = (get_option( $this->parent->_token . '_settings_enable_on_admin' ) == 'on') ? true : false;
                if( $enabled_on_admin ){
                    if( isset($_GET['page']) && $_GET['page'] == $this->parent->_token . '_settings' ) {
                        return false;
                    }
                    return true;
                }
                return false;
            }
            return true;
        }
        return false;
    }



    public function buffer_start() {
        ob_start( array($this, 'callback') );
    }



    public function buffer_end() {
        ob_end_flush();
    }
    


    public static function instance( $parent ) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self( $parent );
        }
        return self::$_instance;
	}

}