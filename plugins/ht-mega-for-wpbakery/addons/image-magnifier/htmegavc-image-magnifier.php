<?php
// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Image_Magnifire extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_image_magnifier', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_script( 'magnifier', HTMEGAVC_LIBS_URI . '/magnifier/magnifier.js', '', '', '');
    	wp_enqueue_script( 'magnifier' );

    	wp_register_style( 'htmegavc-magnifier', plugins_url('css/magnifier.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-magnifier' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           
            // Content
            'magnifier_image' => '',
            'magnifier_image_size' => 'large',
            'zoomable' => 'yes',
            'zoomlabel' => '2',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_image_magnifier_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_image_magnifier_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'zoom_image_area';

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);
        $output = '';

        ob_start();

        if(strpos($magnifier_image_size, 'x')){
            $size_arr = explode('x', $magnifier_image_size);
            $magnifier_image_size = array($size_arr[0],$size_arr[1]);
        }
        $image_url_small = wp_get_attachment_image_src( $magnifier_image, $magnifier_image_size );
        $image_url_large = wp_get_attachment_image_src( $magnifier_image, 'large' );

        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >
		    <div class="htmegavc_zoom_thumbnail_area">
		        <a class="htmegavc_magnifier-thumb-wrapper"><img id="htmegavc_thumb-<?php echo esc_attr($unique_class); ?>" alt="" src="<?php echo esc_url($image_url_small[0]); ?>" data-large-img-url="<?php echo esc_url($image_url_large[0]); ?>" data-mode="inside" data-zoomable="<?php echo esc_attr($zoomable == 'yes' ? 'true' : 'false'); ?>" data-zoom="<?php echo esc_attr($zoomlabel); ?>"></a>
		    </div>

			<script>
			    jQuery(document).ready(function($) {
			        'use strict';
			        m.attach({
			            thumb: '#htmegavc_thumb-<?php echo $unique_class; ?>',
			        });
			    });
			</script>

        </div>

        <?php
        $output .= ob_get_clean();
        return $output;
  }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Image Magnifire", 'htmegavc'),
            "description" => __("Add Image Magnifire to your page", 'htmegavc'),
            "base" => "htmegavc_image_magnifier",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_image_magnifier_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	    'param_name' => 'magnifier_image',
            	    'heading' => __( 'Thumbnail Image', 'htmegavc' ),
            	    'type' => 'attach_image',
            	),
            	array(
            	    'param_name' => 'magnifier_image_size',
            	    'heading' => __( 'Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	),
            	array(
            	  "param_name" => "zoomable",
            	  "heading" => __("Zoomable", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => 'yes',
            	  'description' => __( 'Enable zoom in / out using mouse wheel', 'htmegavc' ),
            	  'value' => [
            	      __( 'Yes', 'htmegavc' )  =>  'yes',
            	      __( 'No', 'htmegavc' )  =>  'no',
            	  ],
            	),
            	array(
            	    'param_name' => 'zoomlabel',
            	    'heading' => __( 'Zoom Level', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Initial zoom level. Example: 5', 'htmegavc' ),
            	),

                // extra class
                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra class name', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'htmegavc' ),
                ),
                array(
                  "param_name" => "wrapper_css",
                  "heading" => __( "Wrapper Styling", "htmevavc" ),
                  "type" => "css_editor",
                  'group'  => __( 'Wrapper Styling', 'htmegavc' ),
              ),
            )
        ) );
    }

}

// Finally initialize code
new Htmegavc_Image_Magnifire();