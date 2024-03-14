<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Simple_Owl_Carousel_Shortcode Class
 *
 * This file contains shortcode of 'soc_slider' post type.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/includes
 * @author     PressTigers <support@presstigers.com>
 */

class Simple_Owl_Carousel_Shortcode
{
    /**
     * Initialize the class and set it's properties.
     *
     * @since    1.0.0
     */
     var $shortcode_args_vals = [];
    public function __construct()
    {
        // Hook -> 'soc_slider_shortcode' Shortcode
        add_shortcode('soc_slider_shortcode', array($this, 'soc_slider'));

        // Hook -> 'edit_form_after_title' Shortcode
        add_action('edit_form_after_title', array($this, 'soc_slider_helper'));

        // Hook -> 'the_content' Shortcode
        add_filter( 'the_content', array($this, 'soc_slider_shortcode_empty_paragraph_fix'));

        add_action('wp_footer', array($this, 'soc_slider_shortcode_print_script'));
    }

    /**
     * Simple Owl Carousel Shortcode Implementation
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function soc_slider($atts, $content)
    {
        // Shortcode Default Array
        $shortcode_args = array(
            'id' => '',
            'items' => 1,
            'navigation' => 'true',
            'single_item' => 'true',
            'slide_speed' => 1000,
            'margin' => 15,
            'lazy_load'   => 'false',
            'auto_height' => 'true',
            'auto_play' => 'false',
            'autoplay_timeout' => 1500,
            'autoplay_hover_pause' => true,
        );


        // Extract User Defined Shortcode Attributes
        $shortcode_args = shortcode_atts($shortcode_args, $atts);
        
        $margin = 15;
        if($shortcode_args['lazy_load'] == 'true' ){
          $margin = 0;
        }
        $this->shortcode_args_vals = $shortcode_args;

        // Get Slider's Slides
        $image_files = get_post_meta( intval( $shortcode_args['id'] ), '_soc_slider', TRUE);
        $image_files = array_filter( explode(',', $image_files) );

        // SOC
        $image_html = '<div id="soc-carousel-'.intval( $shortcode_args['id'] ).'" class="owl-carousel owl-theme">';
        foreach ($image_files as $file) {
            $alt = get_post_meta($file, '_wp_attachment_image_alt', true);
            $attachment_url = wp_get_attachment_url($file, 'thumbnail');
            $attachment_meta = get_post($file);

            $image_html .= '<div class="item">';
            if( "true" !== $shortcode_args['lazy_load'] ){
                $image_html .= '<img src="'. esc_url( $attachment_url ) .'" alt="'. esc_attr( $attachment_meta->post_title ) .'">';
            } else {
                $image_html .= '<img class="owl-lazy" data-src="'. esc_url( $attachment_url ) .'"  alt="'. esc_attr( $attachment_meta->post_title ) .'">';
            }
            if( !empty( $attachment_meta->post_excerpt ) ) {
                $image_html .= '<p class="text-center">'. wp_kses_data( $attachment_meta->post_excerpt ) .'</p>';
            }
            $image_html .= '</div>';

        }
        $image_html .= '</div>';

        wp_enqueue_script('simple-owl-carousel-owl-carousel');
        ob_start();
        ?>
        <!-- Script Adding Settings/Attributes of Shortcode -->
        <script type="text/javascript">
            (function ($) {
                'use strict';
                $(document).ready(function ($) {
                    var owl = $("#soc-carousel-<?php echo intval( $shortcode_args['id'] );?>");
                    owl.owlCarousel({

                        // Most important owl features
                        items: <?php echo intval( $shortcode_args['items'] ); ?>,
                        singleItem: <?php echo esc_attr( $shortcode_args['single_item'] ); ?>,
                        itemsScaleUp: true,

                        // Basic Speeds
                        smartSpeed: <?php echo intval( $shortcode_args['slide_speed'] ); ?>,



                        // Lazy load
                        lazyLoad :  <?php echo esc_attr( $shortcode_args['lazy_load'] ); ?>,

                        // Auto height
                        autoHeight: <?php echo esc_attr( $shortcode_args['auto_height'] ); ?>,

                        // Margin
                        margin: <?php echo $margin; ?>,

                        // Auto play
                        autoplay: <?php echo esc_attr( $shortcode_args['auto_play'] ); ?>,

                        // Auto timeout
                        autoplayTimeout: <?php echo esc_attr( $shortcode_args['autoplay_timeout'] ); ?>,

                        // Auto hoverstop
                        autoplayHoverPause: <?php echo esc_attr( $shortcode_args['autoplay_hover_pause'] ); ?>,

                        // Auto hoverstop
                        loop: true,
                        responsiveClass:true,
                        responsive:{
                          0 : { items : 1  }, // from zero to 480 screen width 4 items
                          480 : { items : 1  }, // from zero to 480 screen width 4 items
                          768 : { items : <?php echo intval( $shortcode_args['items'] ); ?>  }, // from 480 screen widthto 768 6 items
                          1024 : { items : <?php echo intval( $shortcode_args['items'] ); ?> } // from 768 screen width to 1024 8 items
                        },
                        // Navigation
                        dots:true,
                        nav: <?php echo esc_attr( $shortcode_args['navigation'] ); ?>,
                    		navText: [
                    			'<span aria-label="' + 'Previous' + '">Prev</span>',
                    			'<span aria-label="' + 'Next' + '">Next</span>'
                    		],
                    		navSpeed: false,
                    		navElement: 'button type="button" class="next-prev" role="presentation"',
                    		navContainer: false,
                    		navContainerClass: 'owl-nav',
                    		navClass: [
                    			'owl-prev',
                    			'owl-next'
                    		],
                    });
                });
            })(jQuery);
        </script>
        <?php
        $image_html = ob_get_clean() . $image_html;

        return $image_html;

    }

    /**
     * SOC Helper Function
     *
     * @since   1.0.0
     *
     * @global  object  $post   Post Object
     * @return  void
     */
    function soc_slider_helper()
    {
        global $post;
        if ($post->post_type != 'soc_slider')
            return;
        echo '<p>' . __('Paste this shortcode into a post or a page: ', 'simple-owl-carousel');
        echo '<strong>[soc_slider_shortcode id="'. intval( $post->ID ) .'"]</strong>';
        echo '</p>';
    }

    /**
     * Filters the content to remove any extra paragraph or break tags
     * caused by shortcodes.
     *
     * @since   1.0.0
     *
     * @param   string $content  String of HTML content.
     * @return  string $content Amended string of HTML content.
     */
    function soc_slider_shortcode_empty_paragraph_fix( $content )
    {
       $array = array(
           '<p>['    => '[',
           ']</p>'   => ']',
           ']<br />' => ']'
       );
       return strtr( $content, $array );
    }

    /**
     * Prints scripts for shortcode
     *
     * @since   1.0.0
     *
     */
    function soc_slider_shortcode_print_script()
    {
      wp_enqueue_script('simple-owl-carousel-owl-carousel');
      wp_enqueue_script('simple-owl-carousel-custom');
      wp_localize_script('simple-owl-carousel-custom', 'soc_args', $this->shortcode_args_vals); //THIS WORKS
    }
}
new Simple_Owl_Carousel_Shortcode();
