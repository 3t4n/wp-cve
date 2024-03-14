<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Enqueue_Scripts {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 200 );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'public-tochatbe', TOCHATBE_PLUGIN_URL . 'assets/css/public-tochatbe-style.css', array(), TOCHATBE_PLUGIN_VER );
        wp_enqueue_script( 'public-tochatbe', TOCHATBE_PLUGIN_URL . 'assets/js/public-tochatbe-script.js', array( 'jquery' ), TOCHATBE_PLUGIN_VER, true );
        wp_localize_script( 'public-tochatbe', 'tochatbe', array(
            'ajax_url'             => admin_url( 'admin-ajax.php?ver=' . uniqid() ),
            'auto_popup_status'    => tochatbe_basic_option( 'auto_popup_status' ),
            'auto_popup_delay'     => tochatbe_basic_option( 'auto_popup_delay' ),
            'grpr_status'          => tochatbe_gdpr_option( 'status' ),
            'type_and_chat_number' => esc_html( tochatbe_type_and_chat_option( 'type_and_chat_number' ) ),
            'ga_status'            => tochatbe_google_analytics_option( 'status' ),
            'ga_category'          => tochatbe_google_analytics_option( 'category' ),
            'ga_action'            => tochatbe_google_analytics_option( 'action' ),
            'ga_label'             => tochatbe_google_analytics_option( 'label' ),
            'fb_status'            => tochatbe_facebook_analytics_option( 'status' ),
            'fb_event_name'        => tochatbe_facebook_analytics_option( 'name' ),
            'fb_event_label'       => tochatbe_facebook_analytics_option( 'label' ),
        ) );
        
        $css  = '';
        $css .= '.tochatbe-widget-close,
        .tochatbe-widget__body-header,
        .tochatbe-widget__trigger,
        .tochatbe-welcome-msg {
            background-color: ' . esc_html( tochatbe_appearance_option( 'background_color' ) ) . ';
            color: ' . esc_html( tochatbe_appearance_option( 'text_color' ) ) . ';
        }';

        if ( 'bl' === tochatbe_basic_option( 'location' ) ) {
            $css .= '.tochatbe-widget { 
                left: 12px;
                align-items: flex-start;
            }
            .tochatbe-widget-close {
                left: 0;
            }
            .tochatbe_jwi {
                left: 12px;
            }';
        } else {
            $css .= '.tochatbe-widget { 
                right: 12px;
                align-items: flex-end;
            }
            .tochatbe-widget-close {
                right: 0;
            }
            .tochatbe_jwi {
                right: 12px;
            }';
        }

        if ( tochatbe_appearance_option( 'welcome_message' ) ) {
            $css .= '.tochatbe-support-persons { 
                border-radius: 0 0 0px 0px; 
            }';
        }

        $css .= wp_kses_post( tochatbe_basic_option( 'custom_css' ) );

        wp_add_inline_style( 'public-tochatbe', $css );
    }

}

new TOCHATBE_Enqueue_Scripts;