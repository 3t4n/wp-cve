<?php

class TextScrollingAdmin
{

    public $path, $version, $file, $table_name_suffix, $includePath;

    function __construct($file, $version)
    {
        $this->version = $version;
        $this->file = $file;
        $this->path = plugins_url('gigabox');
        $this->includePath = dirname(__FILE__);
        $this->init();
    }


    function init() {
        register_activation_hook( $this->file, array($this, 'textScrollingInstall') );
        if (is_admin())
            add_action( 'admin_menu', array($this, 'adminMenuInit') );

        require_once $this->includePath  . '/TextScrollingFrontEnd.php';
        add_action('widgets_init', array($this,'text_scroll_register'));
        add_action('wp_enqueue_scripts', array($this,'admin_load_js'));
        add_action( 'admin_init', array($this,'tsw_setting_options'));
    }
    function text_scroll_register(){

        register_widget('TextScrollingFrontEnd');
    }

    function tsw_setting_options() {
        //register our settings
        register_setting( 'baw-settings-group', 'tsw_direction' );
        register_setting( 'baw-settings-group', 'tsw_speed' );

    }
    function admin_load_js(){

        wp_enqueue_script( 'custom_js', plugins_url( '/js/scrolltext_custom.js', __FILE__ ) );
    }

    function textScrollingInstall() {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        add_option( "textscrolling_db_version", $this->version );
    }


    function adminMenuInit() {
        $mainmenu = add_menu_page( 'Text Scrolling', 'Text Scrolling', 'manage_options', 'textscrolling', array( $this, 'textScrollingSetting' ) , '', '100.72' );
    }


    function textScrollingSetting() {
        include_once($this->includePath . '/templates/text-scrolling-setting.php');
    }
}

?>