<?php
namespace Skb_Cife;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

/*--------------
*   Base Class
* -------------*/
class Skb_Cife_Base{
    
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '5.6';

    private static $_instance = null;

    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        if ( ! function_exists('is_plugin_active') ){ 
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
        }
        add_action( 'init', [ $this, 'skb_cife_text_domain' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

    }


    /*--------------------
    *   Load Text Domain
    * -------------------*/    
    public function skb_cife_text_domain() {
        load_plugin_textdomain( 'skb_cife', false, dirname( plugin_basename( SKB_CIFE_PL_ROOT ) ) . '/languages/' );
    }


    /*-----------------------------------------
    *   Plugins Loaded Hook Call Back Function
    * ----------------------------------------*/        
    public function init(){

        // Elementor Installed and Activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_for_missing_elementor' ] );
            return;
        }

        // Minimum Elementor Version Check
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_for_minimum_elementor_version' ] );
            return;
        }

        // Minimum PHP Version Check
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_for_minimum_php_version' ] );
            return;
        }

        // Include File
        $this->include_files();

    }

    /*---------------------------------
    *   Notice For Minimum PHP Version
    *---------------------------------*/        
    public function admin_notice_for_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            esc_html__( '"%1$s" requires version %2$s or greater.', 'skb_cife' ),
            '<strong>' . esc_html__( 'PHP', 'skb_cife' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }    
  
    /*--------------------------------------------
    *   Notice For Elementor Install / Activation
    *--------------------------------------------*/       
    public function admin_notice_for_missing_elementor() {

        $elementor = 'elementor/elementor.php';
        /*
            @function skb_cife_is_plugins_active form helper function file
        */
        if( skb_cife_is_plugins_active( $elementor ) ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );

            $message = '<p>' . __( 'Skyboot - Custom Icons for Elementor are not working because you need to activate the Elementor plugin.', 'skb_cife' ) . '</p>';
            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'skb_cife' ) ) . '</p>';
        } else {
            if ( ! current_user_can( 'install_plugins' ) ) {
                return;
            }
            $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
            $message = '<p>' . __( 'Skyboot - Custom Icons for Elementor are not working because you need to install the Elementor plugin', 'skb_cife' ) . '</p>';
            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'skb_cife' ) ) . '</p>';
        }
        echo '<div class="error"><p>' . $message . '</p></div>';
    }


    /*---------------------------------
    *   Notice For Minimum Elementor Version
    *---------------------------------*/        
    public function admin_notice_for_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            esc_html__( '"%1$s" requires version %2$s or greater.', 'skb_cife' ),
            '<strong>' . esc_html__( 'Elementor', 'skb_cife' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /*---------------
    *   Include File
    * --------------*/     
    public function include_files() {

        // Admin Init file
        if( is_admin() ){
           require( SKB_CIFE_PL_PATH.'settings-api/settings-api-init.php' );
        }

        // script manager file
        if ( !file_exists('class-scripts-manager.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/class-scripts-manager.php' );
        }

        // Ico moon Brands icons
        if ( skb_cife_get_option( 'brands_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-brands-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-brands-icon.php' );
        }

        // devicons Icon
        if ( skb_cife_get_option( 'devicons_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-devicons-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-devicons-icon.php' );
        }        

        // Elegant icon
        if ( skb_cife_get_option( 'elegant_icon', 'skb_cife_manage_icon', 'on') === 'on' && !file_exists('class-elegant-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-elegant-icon.php' );
        }

        // Elusive icons
        if ( skb_cife_get_option( 'elusive_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-elusive-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-elusive-icon.php' );
        }

        // Ico Font
        if ( skb_cife_get_option( 'icofont_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-icofont-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-icofont-icon.php' );
        }        

        // Icomoon Icon
        if ( skb_cife_get_option( 'icomoon_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-icomoon-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-icomoon-icon.php' );
        }

        // Iconic icons
        if ( skb_cife_get_option( 'iconic_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-iconic-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-iconic-icon.php' );
        }        

        // Ion icon
        if ( skb_cife_get_option( 'ion_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-ion-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-ion-icon.php' );
        }

        // linearicons
        if ( skb_cife_get_option( 'linearicons_icon', 'skb_cife_manage_icon', 'on') === 'on' && !file_exists('class-linearicons-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-linearicons-icon.php' );
        } 
        
        // Line Awesome Icon
        if ( skb_cife_get_option( 'lineawesome_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-lineawesome-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-lineawesome-icon.php' );
        }

        // Line icon
        if ( skb_cife_get_option( 'line_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-line-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-line-icon.php' );
        }    

        // Material Design Icon
        if ( skb_cife_get_option( 'materialdesign_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-materialdesign-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-materialdesign-icon.php' );
        }

        // Open Iconic icons
        if ( skb_cife_get_option( 'open_iconic_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-open-iconic-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-open-iconic-icon.php' );
        }

        // simpleline icon
        if ( skb_cife_get_option( 'simpleline_icon', 'skb_cife_manage_icon', 'off') === 'on' && !file_exists('class-simpleline-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-simpleline-icon.php' );
        }      
        
        // themify icon
        if ( skb_cife_get_option( 'themify_icon', 'skb_cife_manage_icon', 'on') === 'on' && !file_exists('class-themify-icon.php') ){
            require( SKB_CIFE_PL_PATH . 'classes/icons/class-themify-icon.php' );
        }

    }


}

