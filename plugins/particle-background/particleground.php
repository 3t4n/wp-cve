<?php
/**
 * Plugin Name: Particle Background
 * Plugin URI: http://aasthasolutions.com/about-us/
 * Description: Particle Background is background particle systems with parallax effect also controlled by the mouse on desktop and mobile devices.
 * Version: 1.0.2
 * Author: Aastha Solutions
 * Author URI: http://aasthasolutions.com/
 * Requires at least: 4.4
 * Tested up to: 6.0.3
 * License: GPL2 or later
 * Text Domain: Particle Background
 *
 * @package Particle Background
 * @category Core
 * @author Aasthasolutions
 */
/*This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PG_Shortcoder{

  // Constructor
    function __construct() {
    	add_action('wp_enqueue_scripts',array( $this, 'pg_scripts' ) );
		add_shortcode( 'particleground', array($this, 'pg_display' ) );
	}


	/**
	 * Shortcode for add content and display content.
	 *
	 */

	function pg_display( $atts,$content = null ) {
		$name = shortcode_atts( array(
	        'bgcolor'  => '#16a085',
	        'dotcolor' => '#5cbdaa',
	        'linecolor'=> '#5cbdaa'
	    ), $atts );
		wp_enqueue_script( 'pg-main' );
	    wp_localize_script( 'pg-main', 'full', $name );
	    
		return '<div id="particles" style="background-color:'.$name["bgcolor"].'"><div id="pg-inner">'.$content.'</div></div>';
	}
	

	


	/**
	 * Insert all js for particleground.
	 */
	function pg_scripts() {
	    wp_enqueue_style( 'pg-style',plugins_url( 'assets/css/style.css', __FILE__ ), array());
	    wp_enqueue_script('pg-particleground', plugins_url( 'assets/js/jquery.particleground.min.js', __FILE__ ), array( 'jquery' ),'',true);
	    wp_enqueue_script('pg-main', plugins_url( 'assets/js/main.js', __FILE__ ), array( 'jquery' ),'',true);
	}
	


}

new PG_Shortcoder();
?>