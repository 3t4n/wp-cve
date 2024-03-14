<?php


if ( ! function_exists( 'bwfblocks_attr' ) ) {
    /**
     * Build list of attributes into a string and apply contextual filter on string.
     *
     * The contextual filter is of the form `bwfblocks_attr_{context}_output`.
     *
     * @since 1.2.0
     *
     * @param string $context    The context, to build filter name.
     * @param array  $attributes Optional. Extra attributes to merge with defaults.
     * @param array  $settings   Optional. Custom data to pass to filter.
     * @return string String of HTML attributes and values.
     */
    
    function bwfblocks_attr( $context, $attributes = array(), $settings = array() ) {
        $output = '';
    
        // Cycle through attributes, build tag attribute string.
        foreach ( $attributes as $key => $value ) {
    
            if ( ! $value ) {
                continue;
            }
    
            if ( true === $value ) {
                $output .= esc_html( $key ) . ' ';
            } else {
                $output .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
            }
        }
    
        $output = apply_filters( "bwfblocks_attr_{$context}_output", $output, $attributes, $settings, $context );
    
        return trim( $output );
    }
}

if( ! function_exists( 'bwfoptin_get_block_defaults' ) ) {
    function bwfoptin_get_block_defaults() {
        $defaults = array();

        $common = array();

        $defaults['optin-form'] = array_merge( $common, [
            'content'                => 'Send Me My Free Guide',
            'secondaryContent'       => '',
            'secondaryContentEnable' => false,
            'enableLabel'            => false,
            'classWrap'              => '',
            'submittingText'         => 'Submitting....',
            'firstNameSize'          => 'wffn-sm-100',
            'emailSize'              => 'wffn-sm-100',
            'columns'                => 10,
            'rows'                   => 10,
            'marginButton'           => array(
                'desktop' => array(
                    'bottom' => '15',
                    'unit'   => 'px', 
                ),
            ),
        ] );

        $defaults['popup-form'] = array_merge( $common, [
            'content'                => 'Signup Now',
            'secondaryContent'       => '',
            'secondaryContentEnable' => false,
            'heading'                => 'You\'re just one step away!',
            'subHeading'             => 'Enter your details below and we\'ll get you signed up',
            'classWrap'              => '',
            'progressWidth'          => '75',
            'progressBarText'        => '75% Complete',
            'textAfter'              => 'Your Information is 100% Secure',
            'progressStripEnable'    => false,
            'progressBarTextEnable'  => false,
            'progressBarEnable'      => false,
        ] );
        
        return $defaults;
    }
}