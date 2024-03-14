<?php
/*
Plugin Name: Autopreenchimento de endereço em formulários
Description: Preenchimento automático de campos de endereço baseado no CEP informado.
Plugin URI: https://br.wordpress.org/plugins/cf7-cep-autofill/
Author: FabbricaWeb
Author URI: http://www.fabbricaweb.com.br
Version: 1.2
*/

if ( ! ABSPATH ) exit;

/**
 * Function init plugin
**/
function wpcf7autocep_init(){
	add_action( 'wp_enqueue_scripts', 'wpcf7autocep_do_enqueue_scripts' );
	//add_filter( 'wpcf7_validate_mask*', 'wpcf7autocep_mask_validation_filter', 10, 2 );
}
add_action( 'plugins_loaded', 'wpcf7autocep_init' , 20 );

/**
 * Function enqueue script
 * @version 1.0 
**/
function wpcf7autocep_do_enqueue_scripts() {
    //wp_enqueue_script( 'wpcf7mf-mask', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.mask.js', array('jquery'), '1.4', true );
	wp_enqueue_script( 'wpcf7mf-app', plugin_dir_url( __FILE__ ) . 'assets/js/cf7-cep-autofill.js', array('jquery'), '1.4', true );
}
add_action( 'wp_enqueue_scripts', 'wpcf7autocep_do_enqueue_scripts' );