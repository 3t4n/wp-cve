<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Image_Justifay_Gallery extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_image_justify_gallery', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_script( 'jquery-colorbox', HTMEGAVC_LIBS_URI . '/colorbox/jquery.colorbox-min.js', '', '', '');
    	wp_enqueue_script( 'jquery-colorbox' );

    	wp_register_style( 'colorbox', HTMEGAVC_LIBS_URI . '/colorbox/colorbox.css');
    	wp_enqueue_style( 'colorbox' );

    	wp_register_script( 'justifiedgallery', HTMEGAVC_LIBS_URI . '/justified-gallery/justifiedgallery.js', '', '', '');
    	wp_enqueue_script( 'justifiedgallery' );

    	wp_register_style( 'justifiedgallery', HTMEGAVC_LIBS_URI . '/justified-gallery/justifiedgallery.css' );
    	wp_enqueue_style( 'justifiedgallery' );

    	wp_register_style( 'htmegavc-justify-gallery', plugins_url('css/image-justify-gallery.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-justify-gallery' );


    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
        	// Content
        	'gallery_images' => '',
        	'row_height' => '300',
        	'space_margin' => '20',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_image_justify_gallery_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_image_justify_gallery_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-image-justify-gallery';

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        $output = '';
        ob_start();

        $gallery_images = $gallery_images ? explode(',', $gallery_images) : array();
        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >
		
		<?php
			if( $gallery_images ):
			    echo '<div class="'. esc_attr($wrapper_class) .'" id="'. esc_attr($unique_class) .'">';
			        foreach ( $gallery_images as $image ) {
			            $image_src = wp_get_attachment_image_url( $image, 'large' );
			            ?>
			                <div class="htmegavc-justify-single-image">
			                    <div class="thumb">
			                        <a href="<?php echo esc_url( $image_src );?>" rel="npgallery">
			                            <img src="<?php echo esc_url( $image_src );?>" alt="<?php echo( esc_attr( get_post_meta( $image, '_wp_attachment_image_alt', true) ) );?>">
			                        </a>
			                    </div>
			                </div>

			            <?php
			        }
			    echo '</div>';
			endif;
		?>

		<script>
		    jQuery(document).ready(function($) {

		        'use strict';
		        jQuery('#<?php echo esc_js($unique_class); ?>').justifiedGallery({
		            rowHeight: <?php echo $row_height; ?>,
		            maxRowHeight: null,
		            margins: <?php echo $space_margin; ?>,
		            border: 0,
		            rel: '<?php echo esc_js($unique_class); ?>',
		            lastRow: 'nojustify',
		            captions: true,
		            randomize: false,
		            sizeRangeSuffixes: {
		                lt100: '_t',
		                lt240: '_m',
		                lt320: '_n',
		                lt500: '',
		                lt640: '_z',
		                lt1024: '_b'
		            }
		        }).on('jg.complete', function () {
                    $(this).find('a').colorbox({
                        maxWidth: '80%',
                        maxHeight: '80%',
                        opacity: 0.8,
                        transition: 'elastic',
                        current: ''
                    });
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
            "name" => __("HT Image Justifay Gallery", 'htmevavc'),
            "description" => __("Add Image Justifay Gallery to your page", 'htmevavc'),
            "base" => "htmegavc_image_justify_gallery",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_image_justify_gallery_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmevavc'),
            "params" => array(

            	// cotnent
            	array(
            	    'param_name' => 'gallery_images',
            	    'heading' => __( 'Gallery Images', 'htmevavc' ),
            	    'type' => 'attach_images',
            	),
            	array(
            	    'param_name' => 'row_height',
            	    'heading' => __( 'Row Height', 'htmevavc' ),
            	    'type' => 'textfield',
            	    'value' => __( '300', 'htmevavc' ),
            	),
            	array(
            	    'param_name' => 'space_margin',
            	    'heading' => __( 'Space', 'htmevavc' ),
            	    'type' => 'textfield',
            	    'value' => __( '30', 'htmevavc' ),
            	),


                // extra class
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

}

// Finally initialize code
new Htmegavc_Image_Justifay_Gallery();