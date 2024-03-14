<?php
namespace Skb_Cife;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*--------------------------
*   Class Scripts Manager
* -------------------------*/
class Skb_Cife_Scripts{

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {


        // Admin Scripts
        add_action('admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );        

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ], 15 );

    }

    /*----------------
    *   Admin Scripts
    * ----------------*/    
    public function enqueue_admin_scripts(){
        
            // skyboot admin css
            if( is_admin() ){
                wp_enqueue_style(
                    'skb-cife-skyboot-admin',
                    SKB_CIFE_ASSETS . 'css/skyboot-admin.css',
                    NULL,
                    SKB_CIFE_VERSION
                );
            }

    }

    /*----------------
    *   Enqueue frontend scripts
    * ----------------*/  
    public function enqueue_frontend_scripts() {

        // CSS

        //  Brands icons css
        if ( skb_cife_get_option( 'brands_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-brands_icon', 
                SKB_CIFE_ASSETS . 'css/icomoon_brands.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }

        //  Devicons icons css
        if ( skb_cife_get_option( 'devicons_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-devicons_icon', 
                SKB_CIFE_ASSETS . 'css/devicons.min.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }    

        // elegant icon css
        if ( skb_cife_get_option( 'elegant_icon', 'skb_cife_manage_icon', 'on' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-elegant_icon', 
                SKB_CIFE_ASSETS . 'css/elegant.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }   

        //  Elusive icons css
        if ( skb_cife_get_option( 'elusive_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-elusive_icon', 
                SKB_CIFE_ASSETS . 'css/elusive-icons.min.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }     
        
        //  Ico font icons css
        if ( skb_cife_get_option( 'icofont_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-icofont_icon', 
                SKB_CIFE_ASSETS . 'css/icofont.min.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }

        //  Icomoon icons css
        if ( skb_cife_get_option( 'icomoon_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-icomoon_icon', 
                SKB_CIFE_ASSETS . 'css/icomoon.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }

        //  Iconic icons css
        if ( skb_cife_get_option( 'iconic_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-iconic_icon', 
                SKB_CIFE_ASSETS . 'css/iconic.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }     
        
        //  ion icons css
        if ( skb_cife_get_option( 'ion_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-ion_icon', 
                SKB_CIFE_ASSETS . 'css/ionicons.min.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }   

        // linearicons icon
        if ( skb_cife_get_option( 'linearicons_icon', 'skb_cife_manage_icon', 'on' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-linearicons_icon', 
                SKB_CIFE_ASSETS . 'css/linearicons.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }   

        //  Line Awesome icons css
        if ( skb_cife_get_option( 'lineawesome_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-lineawesome_icon', 
                SKB_CIFE_ASSETS . 'css/line-awesome.min.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }        

        //  line-icons css
        if ( skb_cife_get_option( 'line_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-line_icon', 
                SKB_CIFE_ASSETS . 'css/lineicons.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }   

        //  material design icons css
        if ( skb_cife_get_option( 'materialdesign_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-materialdesign_icon', 
                SKB_CIFE_ASSETS . 'css/materialdesignicons.min.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }

        //  Open Iconic icons
        if ( skb_cife_get_option( 'open_iconic_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-open_iconic', 
                SKB_CIFE_ASSETS . 'css/open-iconic.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }

        //  simple-line-icons css
        if ( skb_cife_get_option( 'simpleline_icon', 'skb_cife_manage_icon', 'off' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-simpleline_icon', 
                SKB_CIFE_ASSETS . 'css/simple-line-icons.css',
                NULL,
                SKB_CIFE_VERSION
            );     
        }    
        
        // themify icon
        if ( skb_cife_get_option( 'themify_icon', 'skb_cife_manage_icon', 'on' ) == 'on' ){
            wp_enqueue_style(
                'skb-cife-themify_icon',
                SKB_CIFE_ASSETS . 'css/themify.css',
                NULL,
                SKB_CIFE_VERSION
            );
        } 

    }

}

Skb_Cife_Scripts::instance();