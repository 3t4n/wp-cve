<?php
/**
 * This file contains misc functions used by MP Easy Icons
 *
 * @since 1.0.0
 *
 * @package    MP Easy Icons
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Function which returns an array of font awesome icons
 */
function mp_easy_icons_get_font_awesome_icons(){

	//Get all font styles in the css document and put them in an array
	$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';

    $path = plugin_dir_path( dirname( __FILE__ ) ) . 'fonts/font-awesome/css/font-awesome.css';

    // We gotta get fancy here to include the CSS the way we need it. Standard wp_remote_get methods fail because it's local
    ob_start();
    require( $path );
    $response = ob_get_clean();

	preg_match_all($pattern, $response, $matches, PREG_SET_ORDER);

	$icons = array();

	foreach($matches as $match){
		$icons[$match[1]] = $match[1];
	}

	if ( empty( $icons ) ){

        // Fail silently here for now.

	}

	return $icons;
}
