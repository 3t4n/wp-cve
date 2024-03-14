<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Google_Map extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_google_map', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {

    	$api = 'https://maps.googleapis.com/maps/api/js';
    	$api_key = htmegavc_get_option('google_map_api_key', 'htmegavc_general_tabs', 'AIzaSyCfmCVTjRI007pC1Yk2o2d_EhgkjTsFVN8');
    	if($api_key != false) {
    		$arr_params = array(
    			'key' => $api_key
    		);
    		$api = esc_url( add_query_arg( $arr_params , $api ));
    	}
    	wp_register_script("google_map_api", $api, '', '', '');
    	wp_enqueue_script("google_map_api");

    	wp_register_script( 'mapmarker-jquery', HTMEGAVC_LIBS_URI . '/mapmarker/mapmarker.jquery.js' , '', '', false );
    	wp_enqueue_script( 'mapmarker-jquery' );

    	wp_register_script( 'htmegavc_google_map_active', plugins_url('js/map-active.js', __FILE__), '', '', true );
    	wp_enqueue_script( 'htmegavc_google_map_active' );
    }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Google Map", 'htmevavc'),
            "description" => __("Add Google Map to your page", 'htmevavc'),
            "base" => "htmegavc_google_map",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_google_map_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmevavc'),
            "params" => array(

            	// cotnent
                array(
                    'param_name' => 'htmegavc_map_default_zoom',
                    'heading' => __( 'Zoom Level', 'htmevavc' ),
                    'type' => 'textfield',
                    'value' => '5',
                    'description' => __( 'Eg: 10', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'htmegavc_google_map_height',
                    'heading' => __( 'Map Height', 'htmevavc' ),
                    'type' => 'textfield',
                    'value' => '500px',
                    'description' => __( 'Eg: 500px', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'htmegavc_center_address',
                    'heading' => __( 'Center Address', 'htmevavc' ),
                    'type' => 'textarea',
                    'value' => __( 'Bangladesh', 'htmevavc' ),
                    'description' => __( 'Enter your center address.', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'htmegavc_style_json',
                    'heading' => __( 'Style Address', 'htmevavc' ),
                    'type' => 'textarea_raw_html',
                    'placeholder' => __( 'Enter your style JSON code.', 'htmevavc' ),
                    'description' => __( '<a href="https://snazzymaps.com/" target="_blank">Here is a tool</a>  where you can find and generate your prefered map style', 'htmevavc' ),
                ),

                // Map Marker
                array(
                    'param_name' => 'htmegavc_map_marker_list',
                    "heading" => __("Marker", 'text_domainn'),
                    'type' => 'param_group',
                    'group' => 'Map Marker',
                    'value' => urlencode( json_encode (array(
                        array(
                            'marker_title'   => __('This is <strong>Rangpur</strong>','htmevavc'),
                            'marker_lat'   => __('25.743893','htmevavc'),
                            'marker_lng'   => __('89.275230','htmevavc'),
                        ),
                     ))),
                    'params' => array(
                       array(
                           'param_name' => 'marker_lat',
                           'heading' => __( 'Latitude ', 'htmevavc' ),
                           'type' => 'textfield',
                           'value' => '25.743893',
                           'description' => __( '<a href="https://www.latlong.net/" target="_blank">Here is a tool</a> where you can find Latitude & Longitude of your location', 'htmevavc' ),
                       ),
                       array(
                           'param_name' => 'marker_lng',
                           'heading' => __( 'Longitude ', 'htmevavc' ),
                           'type' => 'textfield',
                           'value' => '89.275230',
                           'description' => __( '<a href="https://www.latlong.net/" target="_blank">Here is a tool</a> where you can find Latitude & Longitude of your location', 'htmevavc' ),
                       ),
                       array(
                           'param_name' => 'marker_title',
                           'heading' => __( 'Title ', 'htmevavc' ),
                           'type' => 'textarea',
                           'value' => 'Another Place',
                       ),
                       array(
                           'param_name' => 'custom_marker',
                           'heading' => __( 'Custom marker', 'htmevavc' ),
                           'type' => 'attach_image',
                           'description' => __( 'Use max 32x32 px size', 'htmevavc' ),
                       ),
                    )
                ),



                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra class name', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'htmevavc' ),
                ),
                array(
                  "param_name" => "wrapper_css",
                  "heading" => __( "Wrapper Styling", "htmevavc" ),
                  "type" => "css_editor",
                  'group'  => __( 'Wrapper Styling', 'htmevavc' ),
              ),
            )
        ) );
    }
    

    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style' => '1', 
            'htmegavc_map_default_zoom' => '5',
            'htmegavc_google_map_height' => '500px',
            'htmegavc_center_address' => __('Bangladesh', 'htmevavc'),
            'htmegavc_style_json' => __('Enter your style address', 'htmevavc'),
            
            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_google_map_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_google_map_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-animated-heading htmegavc-style-'. $style;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);


        $htmegavc_map_marker_list = isset($atts['htmegavc_map_marker_list']) ? vc_param_group_parse_atts($atts['htmegavc_map_marker_list']) : array();
        $map_options     = [];
        $marker_opts     = [];
        $all_markerslist = [];

        foreach ( $htmegavc_map_marker_list as $marker_item ) {
        	$marker_img_url = isset($marker_item['custom_marker']) ? wp_get_attachment_image_src($marker_item['custom_marker'], 'large') : array();
        	$marker_img_url = isset($marker_img_url[0]) ? $marker_img_url[0] : '';

            $marker_opts['latitude'] = ( $marker_item['marker_lat'] ) ? $marker_item['marker_lat'] : '';
            $marker_opts['longitude'] = ( $marker_item['marker_lng'] ) ? $marker_item['marker_lng'] : '';
            $marker_opts['baloon_text'] = ( $marker_item['marker_title'] ) ? $marker_item['marker_title'] : '';
            $marker_opts['icon'] = $marker_img_url;
            $marker_opts['animation'] = 'google.maps.Animation.BOUNCE';
            $all_markerslist[] = $marker_opts;
        };


        $map_options['zoom'] = !empty( $htmegavc_map_default_zoom ) ? $htmegavc_map_default_zoom : 5;
        $map_options['center'] = !empty( $htmegavc_center_address ) ? $htmegavc_center_address : 'Bangladesh';

        $map_style = urldecode(base64_decode($htmegavc_style_json));

        $output = '';
        ob_start(); ?>

        <div class="htmegavc-google-map" id="htmegavc-google-map" data-mapmarkers='<?php echo wp_json_encode( $all_markerslist ); ?>' data-mapoptions='<?php echo wp_json_encode( $map_options ); ?>' data-mapstyle='<?php echo $map_style ?>' style="min-height: <?php  echo esc_attr($htmegavc_google_map_height); ?>">&nbsp;</div>

        <?php 
        $output .= ob_get_clean();
        return $output;
  }

}

// Finally initialize code
new Htmegavc_Google_Map();