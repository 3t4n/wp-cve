<?php

class ScrollUp_Handler
{

    private $_detector;
    private $_settings;

    /**
     * Constructor
     */
    public function __construct()
    {
        //Mobile detection library
        if ( !class_exists( 'Mobile_Detect' ) ) {
            include_once plugin_dir_path( __FILE__ ) . '/class-mobile-detect.php';
        }
        $this->_detector = new Mobile_Detect;

        //Fetch settings
        $this->_settings = get_option( 'scrollup_settings' );

        $scrollup_mobile = ( isset( $this->_settings['scrollup_mobile'] ) ? $this->_settings['scrollup_mobile'] : '0' );
        if ( !( $scrollup_mobile == 0 && ( $this->_detector->isMobile() || $this->_detector->isIphone() ) ) ) {

            //Register scripts and styles
            add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );

            add_action( 'wp_footer', array( &$this, 'register_plugin_inline_scripts' ) );
            add_action( 'wp_head', array( &$this, 'register_plugin_inline_styles' ) );

            //Start up script
            add_action( 'wp_footer', array( &$this, 'scrollup_init' ) );

            //Show element in admin if is set from filter
            if( $show_element_in_admin = apply_filters( 'scrollup_filter_show_element_in_admin', FALSE ) ){
                add_action( 'admin_enqueue_scripts', array( &$this, 'register_plugin_scripts' ) );
                add_action( 'admin_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
                add_action( 'admin_footer', array( &$this, 'scrollup_init' ) );
            }
        }
    }

    /**
     * This function initializes the plugin
     */
    function scrollup_init()
    {

        $scrollup_specific_ids = ( isset( $this->_settings['scrollup_specific_ids'] ) ? $this->_settings['scrollup_specific_ids'] : '' );

        if ( !empty( $scrollup_specific_ids ) ) {

            $scrollup_specific_ids = explode(",", $scrollup_specific_ids );
            $scrollup_specific_ids_show_hide = ( isset( $this->_settings['scrollup_specific_ids_display_hide'] ) ? $this->_settings['scrollup_specific_ids_display_hide'] : 'hide' );

            if (( in_array(get_the_ID(), $scrollup_specific_ids ) && ( $scrollup_specific_ids_show_hide == "hide") ) || ( !in_array(get_the_ID(), $scrollup_specific_ids ) && ( $scrollup_specific_ids_show_hide == "display") ) ) {
                return;
            }
        }

        $scrollup_show = ( isset( $this->_settings['scrollup_show'] ) ? $this->_settings['scrollup_show'] : '0' );

        if ( $scrollup_show == "1" || ( $scrollup_show == "0" && !(is_home() || is_front_page() )) ) {

            //Fetch options
            $scrollup_type = ( isset( $this->_settings['scrollup_type'] ) ? $this->_settings['scrollup_type'] : 'icon' );
            $scrollup_position = ( isset( $this->_settings['scrollup_position'] ) ? $this->_settings['scrollup_position'] : 'right' );
            $scrollup_text = ( isset( $this->_settings['scrollup_text'] ) ? html_entity_decode( $this->_settings['scrollup_text'] ) : 'Scroll to top' );
            $scrollup_distance = ( ( isset( $this->_settings['scrollup_distance'] ) && ( $this->_settings['scrollup_distance']!='' ) ) ? html_entity_decode( $this->_settings['scrollup_distance'] ) : '300' );
            $scrollup_from = ( ( $scrollup_distance < 0 ) ? 'bottom' : 'top' );
            $scrollup_distance = abs( $scrollup_distance );
            $scrollup_speed = ( ( isset( $this->_settings['scrollup_speed'] ) && (int) $this->_settings['scrollup_speed'] != 0 ) ? html_entity_decode( $this->_settings['scrollup_speed'] ) : '300' );
            $scrollup_animation = ( isset( $this->_settings['scrollup_animation'] ) ? $this->_settings['scrollup_animation'] : 'fade' );
            $scrollup_attr = ( isset( $this->_settings['scrollup_attr'] ) ? html_entity_decode( $this->_settings['scrollup_attr'] ) : '' );

            //Scroll up type class
            $scrollup_type_class = 'scrollup-tab';
            if ( $scrollup_type == 'link' ) {

                $scrollup_type_class = 'scrollup-link';

            } else if( $scrollup_type == 'icon' ) {

                $scrollup_icon = ( isset( $this->_settings['scrollup_custom_icon'] ) ? $this->_settings['scrollup_custom_icon'] : 'fa-arrow-circle-up' );
                $scrollup_icon_size = ( isset( $this->_settings['scrollup_custom_icon_size'] ) ? $this->_settings['scrollup_custom_icon_size'] : 'fa-2x' );
                $scrollup_type_class = 'scrollup-link';
                $scrollup_text = '<id class="fa '.$scrollup_icon.' '.$scrollup_icon_size.'"></id>';

            } else if ( $scrollup_type == 'pill' ) {

                $scrollup_type_class = 'scrollup-pill';

            } else if ( $scrollup_type == 'image' ) {

                $scrollup_type_class = 'scrollup-image';
                $scrollup_text = "";

            } else {

                $scrollup_type_class = 'scrollup-tab';

            }

            //Scroll up position class
            $scrollup_position_class = 'scrollup-left';
            if ( $scrollup_position == 'center' ) {
                $scrollup_position_class = 'scrollup-center';
            } else if ( $scrollup_position == 'right' ) {
                $scrollup_position_class = 'scrollup-right';
            } else {
                $scrollup_position_class = 'scrollup-left';
            }

            //Creation script
            echo '<script> var $nocnflct = jQuery.noConflict();
			$nocnflct(function () {
			    $nocnflct.scrollUp({
				scrollName: \'scrollUp\', // Element ID
				scrollClass: \'scrollUp '.$scrollup_type_class.' '.$scrollup_position_class.'\', // Element Class
				scrollDistance: ' . $scrollup_distance . ', // Distance from top/bottom before showing element (px)
				scrollFrom: \''. $scrollup_from .'\', // top or bottom
				scrollSpeed: '.$scrollup_speed.', // Speed back to top (ms )
				easingType: \'linear\', // Scroll to top easing (see http://easings.net/)
				animation: \'' . $scrollup_animation . '\', // Fade, slide, none
				animationInSpeed: 200, // Animation in speed (ms )
				animationOutSpeed: 200, // Animation out speed (ms )
				scrollText: \'' . $scrollup_text . '\', // Text for element, can contain HTML
				scrollTitle: false, // Set a custom link title if required. Defaults to scrollText
				scrollImg: false, // Set true to use image
				activeOverlay: false, // Set CSS color to display scrollUp active point
				zIndex: 2147483647 // Z-Index for the overlay
			    });
			});';

            //Onclick function
            if ( $scrollup_attr != '' ) {
                echo '
				$nocnflct( document ).ready(function() {
					$nocnflct(\'#scrollUp\' ).attr(\'onclick\', \'' . $scrollup_attr . '\' );
				});
				';
            }

            echo '</script>';
        }
    }

    /**
     * This function registers inline scripts on the frontend
     */
    function register_plugin_inline_scripts() {

        $scrollup_custom_js = ( isset( $this->_settings['scrollup_custom_js'] ) ? $this->_settings['scrollup_custom_js'] : '' );
        if( $scrollup_custom_js ) {
            echo '<script type="text/javascript">';
            echo $scrollup_custom_js;
            echo '</script>';
        }
    }

    /**
     * This function registers scripts on the frontend
     */
    function register_plugin_scripts()
    {
        wp_enqueue_script( 'jquery' );

        wp_register_script(
            'scrollup-js', plugins_url( SMTH_SCRL_UP_PLUGIN_DIR . '/js/jquery.scrollUp.min.js' ), '', '', true
        );
        wp_enqueue_script( 'scrollup-js' );
    }

    /**
     * This function registers inline styles on the frontend
     */
    function register_plugin_inline_styles() {

        $scrollup_custom_css = ( isset( $this->_settings['scrollup_custom_css'] ) ? $this->_settings['scrollup_custom_css'] : '' );
        if( $scrollup_custom_css ) {
            echo '<style type="text/css">';
            echo $scrollup_custom_css;
            echo '</style>';
        }
    }

    /**
     * This function registers styles on the frontend
     */
    function register_plugin_styles()
    {
        $scrollup_type = ( isset( $this->_settings['scrollup_type'] ) ? $this->_settings['scrollup_type'] : 'icon' );
        $load_font_awesome = apply_filters( 'scrollup_filter_load_font_awesome', TRUE );

        if( $load_font_awesome && $scrollup_type == 'icon' ){
            wp_register_style(
                'font-awesome', plugins_url( SMTH_SCRL_UP_PLUGIN_DIR . '/css/font-awesome.min.css' ), array(), '4.6.3'
            );
            wp_enqueue_style( 'font-awesome' );
        }

        wp_register_style(
            'scrollup-css', plugins_url( SMTH_SCRL_UP_PLUGIN_DIR . '/css/scrollup.css' )
        );
        wp_enqueue_style( 'scrollup-css' );

        $scrollup_type = ( isset( $this->_settings['scrollup_type'] ) ? $this->_settings['scrollup_type'] : 'icon' );
        if ( $scrollup_type == 'image' ){
            $scrollup_custom_image = ( isset( $this->_settings['scrollup_custom_image'] ) ? $this->_settings['scrollup_custom_image'] : '../img/scrollup.png' );
            $scrollup_custom_image_custom_css = 'a.scrollup-image {background-image: url("' . $scrollup_custom_image . '") !important; }';
            wp_add_inline_style( 'scrollup-css', $scrollup_custom_image_custom_css );
        }
    }

}
