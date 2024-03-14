<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Shortcode
 */

class WPB_GQB_Shortcode_Handler {

    public function __construct() {
        add_shortcode( 'wpb-quote-button', array( $this, 'contact_form_button_shortcode' ) );
        add_shortcode( 'wpb-quote-button-hook', array( $this, 'contact_form_button_hook' ) );
    }

    /**
     * Shortcode handler for the hook
     *
     * @param  array  $atts
     * @param  string  $content
     *
     * @return string
     */
    public function contact_form_button_hook( $atts, $content = '' ) {
        global $product;

        if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
            wpcf7_enqueue_scripts();
        }
         
        if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
            wpcf7_enqueue_styles();
        }

        if( isset($product) ){
            ob_start();
            echo do_action( 'wpb_gqb_custom_wc_hook' );
            $content .= ob_get_clean();

            return $content;
        }
    }

    /**
     * Shortcode handler for button
     *
     * @param  array  $atts
     * @param  string  $content
     *
     * @return string
     */
    public function contact_form_button_shortcode( $atts, $content = '' ) {

        ob_start();
        self::contact_form_button( $atts );
        $content .= ob_get_clean();

        return $content;
    }

    /**
     * Generic function for displaying docs
     *
     * @param  array   $args
     *
     * @return void
     */
    public static function contact_form_button( $args = array() ) {

        global $post;
        
        if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
            wpcf7_enqueue_scripts();
        }
         
        if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
            wpcf7_enqueue_styles();
        }

        $defaults = array(
            'id'                    => wpb_gqb_get_option( 'wpb_gqb_cf7_form_id', 'form_settings' ),
            'post_id'               => ( $post ? $post->ID : '' ),
            'class'                 => '',
            'text'                  => wpb_gqb_get_option( 'wpb_gqb_btn_text', 'btn_settings', esc_html__( 'Get a Quote', 'wpb-get-a-quote-button' ) ),
            'btn_size'              => wpb_gqb_get_option( 'wpb_gqb_btn_size', 'btn_settings', 'large' ),
            'form_style'            => ( wpb_gqb_get_option( 'wpb_gqb_form_style', 'popup_settings', 'on' ) == 'on' ? true : false ),
            'allow_outside_click'   => ( wpb_gqb_get_option( 'wpb_gqb_allow_outside_click', 'popup_settings' ) == 'on' ? true : false ),
            'width'                 => wpb_gqb_get_option( 'wpb_gqb_popup_width', 'popup_settings', 500 ) . wpb_gqb_get_option( 'wpb_gqb_popup_width_unit', 'popup_settings', 'px' ),
        );

        $args = wp_parse_args( $args, $defaults );
        
        if ( defined( 'WPCF7_PLUGIN' ) ) {
            if( $args['id'] ){
                echo apply_filters('wpb_gqb_button_html', sprintf( '<button data-id="%s" data-post_id="%s" data-form_style="%s" data-allow_outside_click="%s" data-width="%s" class="wpb-get-a-quote-button-form-fire wpb-get-a-quote-button-btn-%s wpb-get-a-quote-button-btn wpb-get-a-quote-button-btn-default%s">%s</button>', esc_attr($args['id']), esc_attr($args['post_id']), esc_attr($args['form_style']), esc_attr($args['allow_outside_click']), esc_attr($args['width']), esc_attr($args['btn_size']) ,( $args['class'] ? esc_attr( ' ' . $args['class']) : '' ), esc_html( $args['text'] ) ), $args);
            }else{
                printf( '<div class="wpb-get-a-quote-button-alert wpb-get-a-quote-button-alert-inline wpb-get-a-quote-button-alert-error">%s</div>', esc_html__( 'Form id required.', 'wpb-get-a-quote-button' ) );
            }
        }else{
            printf( '<div class="wpb-get-a-quote-button-alert wpb-get-a-quote-button-alert-inline wpb-get-a-quote-button-alert-error">%s</div>', esc_html__( 'Get a Quote Button required the Contact Form 7 plugin to work with.', 'wpb-get-a-quote-button' ) );
        }
    }
}
