<?php

/*
version 1.3
*/

// Element Class
class SAR_box {

    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'simple_all_responsive_mapping' ) );
        add_shortcode( 'simple_all_responsive', array( $this, 'simple_all_responsive_html' ) );
    }

    // Element Mapping
    public function simple_all_responsive_mapping() {

        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => __("Simple All Responsive", 'vc-simple-all-responsive'),
                'base' => "simple_all_responsive",
                'show_settings_on_create' => false,
                //'content_element' => true,
                'is_container' => true,
                //'controls' => 'full',
                'js_view' => 'VcColumnView',
                'description' => __("Provides responsiveness to all builder internal elements", 'vc-simple-all-responsive'),
                'category' => __('Content', 'text-domain'),
                'icon' => plugins_url('assets/resp-icon.png', __FILE__),
                'params' => array(
                    array(
                        "type" => "checkbox",
                        "heading" => "",
                        "param_name" => "disable_envelope",
                        "admin_label" => true,                   
                        //"value" => "",
                        "value" => array(
                            __( "Hide All", 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("If checked inner content won't be visible on the public side of your website. You can switch it back any time.", 'vc-simple-all-responsive')
                    ),

                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("Visibility by width", 'vc-simple-all-responsive'),
                        "param_name" => "env_visibility",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Always visible", 'vc-simple-all-responsive' ) => 'vis_aw',
                            __( 'Hidden on Large Desktops (1920px and wider)', 'vc-simple-all-responsive' ) => 'hid_ld',
                            __( 'Hidden on Bigger and Large Desktops (1367px and wider)', 'vc-simple-all-responsive' ) => 'hid_bd',
                            __( 'Hidden on All Desktops (1024px and wider)', 'vc-simple-all-responsive' ) => 'hid_ad',
                            __( 'Hidden on Tablets Landscape (962px - 1366px)', 'vc-simple-all-responsive' ) => 'hid_tl',
                            __( 'Hidden on Tablets Portrait (600px - 1024px)', 'vc-simple-all-responsive' ) => 'hid_tp',
                            __( 'Hidden on Tablets (768px - 1024px)', 'vc-simple-all-responsive' ) => 'hid_at',
                            __( 'Hidden on Phones (767px and less)', 'vc-simple-all-responsive' ) => 'hid_ap',
                            __( 'Hidden on Phones Portrait (567px and less)', 'vc-simple-all-responsive' ) => 'hid_pp',
                            __( 'Visible only on Large Desktops (1920px and wider)', 'vc-simple-all-responsive' ) => 'vis_ld',
                            __( 'Visible only on Bigger and Large Desktops (1367px and wider)', 'vc-simple-all-responsive' ) => 'vis_bd',
                            __( 'Visible only on All Desktops (1024px and wider)', 'vc-simple-all-responsive' ) => 'vis_ad',
                            __( 'Visible only on Tablets Landscape (962px - 1366px)', 'vc-simple-all-responsive' ) => 'vis_tl',
                            __( 'Visible only on Tablets Portrait (600px - 1024px)', 'vc-simple-all-responsive' ) => 'vis_tp',
                            __( 'Visible only on Tablets (768px - 1024px)', 'vc-simple-all-responsive' ) => 'vis_at',
                            __( 'Visible only on Phones (767px and less)', 'vc-simple-all-responsive' ) => 'vis_ap',
                            __( 'Visible only on Phones Portrait (567px and less)', 'vc-simple-all-responsive' ) => 'vis_pp',
                        ),
                        'save_always' => true,
                        "description" => __("Select where inner content will be visible or hidden", 'vc-simple-all-responsive')
                    ),


                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("On mobile (by response)", 'vc-simple-all-responsive'),
                        "param_name" => "hide_mobile_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on mobile device (by HTTP response)", 'vc-simple-all-responsive')
                    ),


                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("On Android", 'vc-simple-all-responsive'),
                        "param_name" => "hide_android_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on Android device", 'vc-simple-all-responsive')
                    ),

                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("On Kindle", 'vc-simple-all-responsive'),
                        "param_name" => "hide_kindle_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on Kindle device", 'vc-simple-all-responsive')
                    ),

                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("On BlackBerry", 'vc-simple-all-responsive'),
                        "param_name" => "hide_blackberry_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on BlackBerry device", 'vc-simple-all-responsive')
                    ),

                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("On Silk", 'vc-simple-all-responsive'),
                        "param_name" => "hide_silk_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on Silk device", 'vc-simple-all-responsive')
                    ),

                    array(
                        "type" => "dropdown",
                        //"holder" => "div",
                        "heading" => __("On Opera Mini", 'vc-simple-all-responsive'),
                        "param_name" => "hide_operamini_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on Opera Mini-powered device", 'vc-simple-all-responsive')
                    ),                                        


                    array(
                        "type" => "dropdown",
                        "heading" => __("On Opera Mobi", 'vc-simple-all-responsive'),
                        "param_name" => "hide_operamobi_http",
                        "admin_label" => true,                   
                        "value" => array(
                            __( "Don't Hide (Do Nothing)", 'vc-simple-all-responsive' ) => 'false',
                            __( 'Hide', 'vc-simple-all-responsive' ) => 'true',
                        ),
                        'save_always' => true,
                        "description" => __("Hide or display inner content on Opera Mobi-powered device", 'vc-simple-all-responsive')
                    ),  

                ),
            )
        );     

    }

    // Element HTML
    public function simple_all_responsive_html( $atts, $content ) {

        // Params extraction
        extract( shortcode_atts( array(
                'disable_envelope' => false,
                'env_visibility' => 'vis_aw',
                'hide_mobile_http' => false,
                'hide_android_http' => false,
                'hide_kindle_http' => false,
                'hide_blackberry_http' => false,
                'hide_silk_http' => false,
                'hide_operamini_http' => false,
                'hide_operamobi_http' => false,
            ), $atts ) );

        // Disable element
        if($disable_envelope) {
            return null;
        } 	

        // Settings mobile by user agent
        $response_exist = false;
        $http_response = $_SERVER['HTTP_USER_AGENT'];

        
        if ( $hide_mobile_http == true ): 
            if ( strpos($http_response, 'Mobile') !== false
                || strpos($http_response, 'Android') !== false
                || strpos($http_response, 'Kindle') !== false
                || strpos($http_response, 'BlackBerry') !== false
                || strpos($http_response, 'Silk/') !== false
                || strpos($http_response, 'Opera Mini') !== false
                || strpos($http_response, 'Opera Mobi') !== false ):
                $response_exist = true;
            endif;
        endif;

        if ((strpos($http_response, 'Android') !== false) and $hide_android_http):
            $response_exist = true;
        endif; 

        if ((strpos($http_response, 'Kindle') !== false) and $hide_kindle_http):
            $response_exist = true;
        endif; 

        if ((strpos($http_response, 'BlackBerry') !== false) and $hide_blackberry_http):
            $response_exist = true;
        endif; 

        if ((strpos($http_response, 'Silk/') !== false) and $hide_silk_http):
            $response_exist = true;
        endif; 

        if ((strpos($http_response, 'Opera Mini') !== false) and $hide_operamini_http):
            $response_exist = true;
        endif; 

        if ((strpos($http_response, 'Opera Mobi') !== false) and $hide_operamobi_http):
            $response_exist = true;
        endif; 

        //display dependings 
        $css_rule = "disp_block"; 
        if($response_exist):                              // veto on mobile and/or user agent corespond - anyhow no display
            $css_rule = "disp_none";
        elseif($env_visibility === 'hid_ld'):
            $css_rule = 'disp_hid_ld';
        elseif($env_visibility === 'hid_bd'):
            $css_rule = 'disp_hid_bd';
        elseif($env_visibility === 'hid_ad'):    
            $css_rule = 'disp_hid_ad';
        elseif($env_visibility === 'hid_tl'): 
            $css_rule = 'disp_hid_tl';            
        elseif($env_visibility === 'hid_tp'): 
            $css_rule = 'disp_hid_tp';
        elseif($env_visibility === 'hid_at'): 
            $css_rule = 'disp_hid_at';
        elseif($env_visibility === 'hid_ap'): 
            $css_rule = 'disp_hid_ap';
        elseif($env_visibility === 'hid_pp'): 
            $css_rule = 'disp_hid_pp';
        elseif($env_visibility === 'vis_ld'): 
            $css_rule = 'disp_vis_ld';
        elseif($env_visibility === 'vis_bd'): 
            $css_rule = 'disp_vis_bd';
        elseif($env_visibility === 'vis_ad'): 
            $css_rule = 'disp_vis_ad';
        elseif($env_visibility === 'vis_tl'): 
            $css_rule = 'disp_vis_tl';
        elseif($env_visibility === 'vis_tp'): 
            $css_rule = 'disp_vis_tp';
        elseif($env_visibility === 'vis_at'): 
            $css_rule = 'disp_vis_at';
        elseif($env_visibility === 'vis_ap'): 
            $css_rule = 'disp_vis_ap';
        elseif($env_visibility === 'vis_pp'): 
            $css_rule = 'disp_vis_pp';
        endif;        

        // veto on mobile and/or user agent corespond - anyhow no display


        // Fill $html var with data
        $html = '<div class="' . $css_rule . '">' . do_shortcode($content) . '</div>';


        return $html;

    }

} // End Element Class

// Element Class Init
new SAR_box;
    if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
        class WPBakeryShortCode_simple_all_responsive extends WPBakeryShortCodesContainer {
        }
    }