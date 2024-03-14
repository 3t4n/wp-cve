<?php

namespace WPAdminify\Inc\Modules\CustomHeaderFooter;

use  WPAdminify\Inc\Utils ;
use  WPAdminify\Inc\Modules\CustomHeaderFooter\CustomHeaderFooterModel ;
// no direct access allowed
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * WPAdminify
 *
 * @package Custom CSS/JS
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */
class CustomHeaderFooter extends CustomHeaderFooterModel
{
    public  $url ;
    public  $options = array() ;
    public function __construct()
    {
        
        if ( is_multisite() && is_network_admin() ) {
            return;
            // only display to network admin if multisite is enbaled
        }
        
        $this->url = WP_ADMINIFY_URL . 'Inc/Modules/CustomHeaderFooter';
        $this->options = ( new CustomHeaderFooterSettings() )->get();
        $this->options = ( !empty($this->options['custom_scripts']) ? $this->options['custom_scripts'] : '' );
        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        }
        add_action( 'wp_head', [ $this, 'adminify_header_scripts' ], 9999 );
        add_action( 'wp_footer', [ $this, 'adminify_footer_scripts' ], 9999 );
        add_action( 'the_content', [ $this, 'adminify_content_scripts' ] );
    }
    
    // Add Header Scripts
    public function adminify_header_scripts()
    {
        $this->adminify_custom_css_js_script( 'header' );
    }
    
    // Add Footer Scripts
    public function adminify_footer_scripts()
    {
        $this->adminify_custom_css_js_script( 'footer' );
    }
    
    // Add Footer Scripts
    public function adminify_content_scripts( $content )
    {
        return $this->adminify_custom_css_js_script( false, $content );
    }
    
    /**
     * Render Snippet
     *
     * @param [since] 1.0.0
     *
     * @return void
     */
    public function render_snippet( $value )
    {
        // $device_display = wp_is_mobile() ? 'desktop' : 'mobile';
        
        if ( $value['script_type'] == 'css' ) {
            
            if ( !empty($value['custom_css']) ) {
                echo  wp_kses_post( "\n<!-- Start of WP Adminify Custom CSS - Snippet#{$value['title']} -->\n" ) ;
                echo  "<style>\n" ;
                echo  wp_kses_post( "\n{$value['custom_css']}\n" ) ;
                echo  "\n</style>" ;
                echo  "\n<!-- /End of WP Adminify Custom CSS -->\n" ;
            }
        
        } elseif ( $value['script_type'] === 'js' ) {
            
            if ( !empty($value['custom_js']) ) {
                echo  wp_kses_post( "\n<!-- Start of WP Adminify Custom JS - Snippet#{$value['title']} -->\n" ) ;
                echo  "<script>\n" ;
                echo  wp_kses_post( "\n{$value['custom_js']}\n" ) ;
                echo  "\n</script>" ;
                echo  "\n<!-- /End of WP Adminify Custom JS -->\n" ;
            }
        
        }
    
    }
    
    /**
     * Add Snippet
     *
     * @return void
     */
    public function adminify_custom_css_js_script( $location = '', $content = '' )
    {
        $options = $this->options;
        $before_content = '';
        $after_content = '';
        if ( !empty($options) ) {
            foreach ( $options as $key => $value ) {
                $output = '';
                if ( $value['location'] === $location ) {
                    switch ( $value['display_on'] ) {
                        case 'full_site':
                            $output = $this->render_snippet( $value );
                            break;
                        case 's_posts':
                            break;
                        case 's_pages':
                            break;
                        case 's_categories':
                            break;
                        case 's_custom_posts':
                            break;
                        case 's_tags':
                            break;
                    }
                }
            }
        }
        return $before_content . $content . $after_content;
    }
    
    /**
     * Scripts/Styles
     */
    public function enqueue_scripts()
    {
        global  $pagenow ;
        // Load Scripts/Styles only WP Adminify Custom CSS/JS Page
        if ( 'admin.php' === $pagenow && 'adminify-custom-css-js' === $_GET['page'] ) {
            $this->header_footer_admin_script();
        }
    }
    
    // WP Adminify Custom CSS/JS Page Style
    public function header_footer_admin_script()
    {
        echo  '<style>.wp-adminify-custom-css-js .adminify-container{ max-width:60%; margin:0 auto;} .wp-adminify-custom-css-js .adminify-header-inner{padding:0;}.wp-adminify-custom-css-js .adminify-field-subheading{font-size:20px; padding-left:0;}.adminify-custom-css-js .adminify-nav,.adminify-custom-css-js .adminify-search,.adminify-custom-css-js .adminify-footer,.adminify-custom-css-js .adminify-reset-all,.adminify-custom-css-js .adminify-expand-all,.adminify-custom-css-js .adminify-header-left,.adminify-custom-css-js .adminify-reset-section,.adminify-custom-css-js .adminify-nav-background{display: none !important;}.adminify-custom-css-js .adminify-nav-normal + .adminify-content{margin-left: 0;}

            /* If needed for white top-bar */
            .adminify-custom-css-js .adminify-header-inner {
                background-color: #fafafa !important;
                border-bottom: 1px solid #f5f5f5;
            }
        </style>' ;
    }

}