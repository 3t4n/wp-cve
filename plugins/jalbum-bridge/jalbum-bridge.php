<?php 
/* 
	Plugin Name: 		jAlbum Bridge
	Plugin URI: 		http://your-site-here.com/
	Contributors: 		mlaza, jalbum
	Description: 		With this plugin you can add spectacular projectors of jAlbum albums to your Wordpress site. 
	Tags: 				jAlbum, album, projector, slideshow, 3D, ken burns, coverflow, carousel, masonry, gallery, photo, gutenberg, block editor
	Version: 			2.0.14
	Author: 			jalbum
	Author URI: 		https://jalbum.net/
	Requires at least:	5.0
	License: 			GPLv2 or later

						This program is free software; you can redistribute it and/or
						modify it under the terms of the GNU General Public License
						as published by the Free Software Foundation; either version 2
						of the License, or (at your option) any later version.
						
						This program is distributed in the hope that it will be useful,
						but WITHOUT ANY WARRANTY; without even the implied warranty of
						MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
						GNU General Public License for more details.
						
						You should have received a copy of the GNU General Public License
						along with this program; if not, write to the Free Software
						Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA	02110-1301, USA.
						
						Copyright 2018 jAlbum AB
	License URI: 		https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain:		jalbum-bridge
*/
	// Legacy: Register Short code for blocks created with 1.x
	
	add_shortcode('jalbum_bridge', 'jalbumbridge_shortcode');
	
	// Legacy: Contructs HTML element from short code
	
	function jalbumbridge_shortcode( $atts, $content = null ) {
		$ar = isset($atts['ar'])? $atts['ar'] : '75';

		return '<div class="jalbum-block aligncenter"><div data-jalbum=\''.json_encode($atts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK).'\' style="padding-bottom:'.$ar.'%;">'.$content.'</div></div>';
	}
	
	// Sanitizing JSON data
	
	function sanitize_code( $code ) {
		
		if ( isset($code) ) {
			$code = str_replace('<', '&lt;', $code);
			$code = str_replace('>', '&gt;', $code);
			$code = str_replace('"', '\&#34;', $code);
		}
		
		return $code;
	}
	
	/**********************************************************
	 * Dynamic callback for rendering the block
	 */
	 
	function jalbumbridge_dynamic_render_callback( $attributes ) {
	    $json = '';
	    $align = isset($attributes['align'])? $attributes['align'] : 'center';
	    $ar = isset($attributes['ar'])? $attributes['ar'] : '75';
	    
	    foreach ($attributes as $key => $value) {
	    	
	    	$json .=  ($json != '' ? ',' : '') . '"' . $key . '":"' . sanitize_code( $value ) . '"';
	    }

		return '<div class="jalbum-block align' . $align . '"><div data-jalbum=\'{' . $json . '}\' style="padding-bottom:' . $ar . '%;"></div></div>';
	}

	/**********************************************************
	 * Registering jalbum bridge block
	 */
	 
	function jalbumbridge_register_block() {
		$dir 		= plugin_dir_path( __FILE__ );
		// Production script and style
		$f_script 	= 'js/jalbum.min.js';
		$f_style  	= 'css/jalbum.css';
		// Editor script and style
		$e_script 	= 'js/jalbum-bridge-block.js';
		$e_style  	= 'css/jalbum-bridge-block.css';
		
		if ( function_exists( 'register_block_type' ) ) {
			
			// Gutenberg is active => load editor style and script

			wp_register_style(
				'jalbum-bridge-block-css',
				plugins_url( $e_style, __FILE__ ),
				array(),
				filemtime( $dir . $e_style )
			);
			
			wp_register_script(
				'jalbum-bridge-block-js',
				plugins_url( $e_script , __FILE__ ),
				array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
				filemtime( $dir . $e_script )
			);
	
			register_block_type( 'jalbum-bridge/gallery', array(
					'render_callback' 	=> 'jalbumbridge_dynamic_render_callback',
					'editor_style' 		=> 'jalbum-bridge-block-css',
					'editor_script' 	=> 'jalbum-bridge-block-js',
			) );
		}
		
		wp_register_style(
			'jalbum-css', 
			plugins_url( $f_style , __FILE__ ),
			array(),
			filemtime( $dir . $f_style )
		);
		
		wp_enqueue_style( 'jalbum-css' );
		
		if ( WP_DEBUG ) {
			
			// Unminified scripts for debugging
			
			wp_register_script(
				'jalbum-util-js', 
				plugins_url( 'js/jalbum-util.js' , __FILE__ ), 
				array( 'jquery-core' ),
				filemtime( $dir . 'js/jalbum-util.js' )
			);
			
			wp_register_script(
				'jalbum-album-js', 
				plugins_url( 'js/jalbum-album.js' , __FILE__ ), 
				array( 'jquery-core', 'jalbum-util-js' ),
				filemtime( $dir . 'js/jalbum-album.js' )
			);
			
			wp_register_script(
				'jalbum-projector-js', 
				plugins_url( 'js/jalbum-projector.js' , __FILE__ ), 
				array( 'jquery-core', 'jalbum-util-js', 'jalbum-album-js' ),
				filemtime( $dir . 'js/jalbum-projector.js' )
			);
			
			wp_enqueue_script( 'jalbum-util-js' );
			wp_enqueue_script( 'jalbum-album-js' );
			wp_enqueue_script( 'jalbum-projector-js' );
		
		} else {
			
			wp_register_script(
				'jalbum-js', 
				plugins_url( $f_script , __FILE__ ), 
				array( 'jquery-core' ),
				filemtime( $dir . $f_script )
			);
			
			wp_enqueue_script( 'jalbum-js' );
		}

	}

	add_action( 'init', 'jalbumbridge_register_block' );
?>