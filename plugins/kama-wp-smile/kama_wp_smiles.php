<?php
/**
 * Plugin Name: Kama WP Smiles
 * Description: Replace WP smilies. You can easily set your own package of smiles or select preferred from existing list.
 *
 * Author: Kama
 * Author URI: http://wp-kama.ru/
 * Plugin URI: http://wp-kama.ru/?p=18
 *
 * Text Domain: kama-wp-smile
 * Domain Path: /languages
 *
 * Requires PHP: 5.4
 *
 * Version: 1.9.12
 */

$data = get_file_data( __FILE__, [ 'ver' =>'Version', 'lang_dir' =>'Domain Path' ] );

define( 'KWS_VER', $data['ver'] );
define( 'KWS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'KWS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once KWS_PLUGIN_PATH . 'class.Kama_WP_Smiles.php';

if( is_admin() && ! defined( 'DOING_AJAX' ) ){
	require_once KWS_PLUGIN_PATH . 'class.Kama_WP_Smiles_Admin.php';
}

// init
add_action( 'init', 'kama_wp_smiles_init' );
function kama_wp_smiles_init(){

	remove_action( 'init',         'smilies_init',    5 );
	remove_filter( 'the_content',  'convert_smilies', 5 );
	remove_filter( 'the_excerpt',  'convert_smilies', 5 );
	remove_filter( 'comment_text', 'convert_smilies', 5 );

	kwsmile();
}


register_activation_hook( __FILE__, function(){
	Kama_WP_Smiles::instance()->activation();
} );


/**
 * Gets plugin instance.
 *
 * @return Kama_WP_Smiles|Kama_WP_Smiles_Admin
 */
function kwsmile(){
	return Kama_WP_Smiles::instance();
}

/**
 * Gets smiles HTML for specified textarea.
 *
 * @param  string $textarea_id textarea ID
 * @return string HTML
 */
function kws_get_smiles_html( $textarea_id ){
	return kwsmile()->get_all_smile_html( $textarea_id ) . kwsmile()->insert_smile_js();
}

/**
 * Convert smiles code to HTML IMG in passed content.
 *
 * @param  string $content Content where need smiles convert
 * @return string Filtered content
 */
function kws_convert_smiles( $content ){
	return kwsmile()->convert_smilies( $content );
}


// DEPRECATED --------------

function kama_sm_get_smiles_code( $textarea_id ){
	_deprecated_function( __FUNCTION__, '1.9.0', 'kws_get_smiles_html()' );

	return kws_get_smiles_html( $textarea_id );
}


