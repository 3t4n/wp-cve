<?php /*
Plugin Name: TinyMCE Table
Plugin URI: http://wordpress.org/plugins/tinymce-table/
Author: Gary PEGEOT
Description: Ajoute la création et l'édition des tables à TinyMCE. Plugin version 4.1.1
Licence: GPLv2
Version: 1.0
*/
// Ajout du plugin
add_filter('mce_external_plugins', 'ajout_table');
function ajout_table() {
	$plugin['table'] = plugins_url('plugin.min.js', __FILE__);	
	return $plugin;    
}

//Ajout du bouton
add_filter( 'mce_buttons', 'bouton_table' );
function bouton_table($arg) {
    // Position bouton alignright
    $pos = array_search( 'link', $arg );
    
    // Si pas de bouton alignright
    if ( ! $pos ) {
        $arg[] = 'table';
        return $arg;
    } else {
        $arg = array_merge( array_slice( $arg, 0, $pos ), array( 'table' ), array_slice( $arg, $pos ) );
        return $arg;	
    }
}
?>
