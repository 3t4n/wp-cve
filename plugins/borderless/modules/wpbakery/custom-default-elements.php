<?php
/**
 * Customize default elements in Visual Composer
 *
 * @package Borderless
 *
 */

/*-----------------------------------------------------------------------------------*/
/*	Customize default elements in Visual Composer
/*-----------------------------------------------------------------------------------*/

add_action( 'vc_after_init', 'vc_after_init_actions' );
 
function vc_after_init_actions() {
    
    // Ref: http://www.wpelixir.com/how-to-customize-default-elements-visual-composer/ 
    // Remove Params Example

    /*
    if( function_exists('vc_remove_param') ){ 
        vc_remove_param( 'vc_row', 'css_animation' ); 
        vc_remove_param( 'vc_row', 'el_class' ); 
    }
 
    // Add Params For Row Element
    $vc_row_new_params = array(
        
        array(
			'type' => 'dropdown',
			'heading' => __( 'Color Mode', 'borderless' ),
			'description' => __( 'Select Color Mode For The Row.', 'borderless' ),
            'param_name' => 'borderless_row_color_mode',
            'group' => 'Borderless',
			'value' => array(
				__( 'None', 'borderless' ) => '',
                __( 'Light Mode', 'borderless' ) => 'light',
                __( 'Dark Mode', 'borderless' ) => 'dark',
                __( 'Light to Dark Mode', 'borderless' ) => 'light-to-dark',
				__( 'Dark to Light Mode', 'borderless' ) => 'dark-to-light',
			),
		),
     
    );
     
    vc_add_params( 'vc_row', $vc_row_new_params ); 


    // Add Params For Column Element
    $vc_column_new_params = array(
        
        array(
			'type' => 'dropdown',
			'heading' => __( 'Color Mode', 'borderless' ),
			'description' => __( 'Select Color Mode For The Row.', 'borderless' ),
            'param_name' => 'borderless_column_color_mode',
            'group' => 'Borderless',
			'value' => array(
				__( 'None', 'borderless' ) => '',
                __( 'Light Mode', 'borderless' ) => 'light',
                __( 'Dark Mode', 'borderless' ) => 'dark',
                __( 'Light to Dark Mode', 'borderless' ) => 'light-to-dark',
				__( 'Dark to Light Mode', 'borderless' ) => 'dark-to-light',
			),
		),
     
    );
     
    vc_add_params( 'vc_column', $vc_column_new_params ); 
    */
}