<?php

class Youzify_Profile_Custom_Widget {

    public $widget_name;

    public function __construct( $widget_name ) {
        $this->widget_name = $widget_name;
    }

    /**
     * Content.
     */
    function widget() {

        // Get Widgets.
        $custom_widgets = youzify_option( 'youzify_custom_widgets' );

        // Get Widget.
        $widget = $custom_widgets[ $this->widget_name ];

        // init Array.
        $widget_class = array( 'youzify-custom-widget-box' );

        // Add Padding class.
        $widget_class[] = 'true' == $widget['display_padding'] ? 'youzify-custom-widget-box-padding' : null;

        // Filter Content
        $content = youzify_convert_content_tags( urldecode( stripcslashes( $widget['content'] ) ) );

        // Display Widget.

        $wp_content = apply_filters( 'the_content', $content );

        if ( empty( $wp_content ) ) {
            $wp_content = $content;
        }

        if ( ! empty( trim( $wp_content ) ) ) {
            echo "<div class='" . youzify_generate_class( $widget_class ) . "'>" . $wp_content . '</div>';
        }

    }

    /**
     * Get Custom Widget data.
     */
    function get_all_data( $widget_name ) {
        $widgets = youzify_option( 'youzify_custom_widgets' );
        return $widgets[ $widget_name ];
    }

    /**
     * Get Custom Widget data.
     */
    function args() {

        $widgets = youzify_option( 'youzify_custom_widgets' );

        // Get Custom Widgets
        $custom_widget_data = $widgets[ $widget_name ];

        // Get Custom Widget Args.
        $args = array(
            'id'                => 'custom_widgets',
            'function_options'  => $widget_name,
            'main_data'         => 'youzify_custom_widgets',
            'widget_title'      => $custom_widget_data['name'],
            'icon'              => $custom_widget_data['icon'],
            'display_title'     => $custom_widget_data['display_title'],
            'display_padding'   => $custom_widget_data['display_padding'],
            'load_effect'       => youzify_option( 'youzify_custom_widgets_load_effect', 'fadeIn' )
        );

        return $args;
    }

    /**
     * Get Custom Widget data.
     */
    function get_widget_data( $widget_name, $data_type ) {
        $widgets = youzify_option( 'youzify_custom_widgets' );
        return $widgets[ $widget_name ][ $data_type ];
    }
}