<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Lucas_String_Replace {

    private static $_instance = null;

    public $settings = null;
    public $replacer = null;

    public $_version;
    public $_token;
    public $file;
    public $dir;

    

    public function __construct( $version, $file ) {

        $this->_version = $version;
        $this->_token = 'lucas_strrep';
        $this->file = $file;
        $this->dir = dirname( $this->file );
        
        register_activation_hook( $this->file, array( $this, 'install' ) );

        if( is_admin() ){
            $this->load_plugin_textdomain();
            add_action( 'init', array( $this, 'load_localisation' ), 0 );
        }

    }



    public function install() {
        update_option( $this->_token . '_version', $this->_version );
        $this->upgrade();
    }


    
    public function upgrade() {

        $lsr_replace_from = get_option('lsr_from');
        $lsr_replace_to = get_option('lsr_to');

        if( $lsr_replace_from == false || $lsr_replace_to == false ) {
            return;
        }

        $lsr_replace_from = explode('||', $lsr_replace_from);

        if( ! is_array($lsr_replace_from) ){
            $lsr_replace_from = array($lsr_replace_from);
        }

        $value = array(array( $lsr_replace_from, $lsr_replace_to ));

        if( update_option($this->_token . '_settings_replacesets', serialize($value)) ){
            delete_option( 'lsr_from' );
            delete_option( 'lsr_to' );
            delete_option( 'lsr_workonadminpages' );
        }

    }



    public function load_localisation () {
		load_plugin_textdomain( 'lucas-string-replace', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}


	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'lucas-string-replace';
	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}



    public static function instance( $version, $file ) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self( $version, $file );
        }
        return self::$_instance;
	}

}