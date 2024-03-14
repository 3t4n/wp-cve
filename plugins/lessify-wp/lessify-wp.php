<?php
/*
Plugin Name: Lessify Wordpress
Plugin URI: http://magnigenie.com/using-less-with-wordpress/
Description: Combine the power of wordpress with the power of Less and create something awesome. Just enqueue you less files in the traditional wordpress way and lessify will do the trick.
Version: 1.1
Author: Nirmal Kumar Ram
Author URI: http://magnigenie.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct file access
! defined( 'ABSPATH' ) AND exit;


// load lessphp Library
! class_exists( 'lessc' ) AND require_once( 'lessphp/lessc.inc.php' );


if ( ! class_exists( 'lessify_wp' ) ) {

	add_action( 'init', array( 'lessify_wp', 'instance' ) );

class lessify_wp {

	protected static $instance = null;
	
	//Create new instance in order to call outsite class.
	public static function instance() {
		null === self :: $instance AND self :: $instance = new self;
		return self :: $instance;
	}


	//@var string Compression class to use
	public $compression = 'compressed';

	//@var bool Whether to preserve comments when compiling
	public $preserve_comments = true;
	
	//Less Variables
	public $vars = array();

	//Constructor
	public function __construct() {

		// Every enqued file is passed through this filter.
		add_filter( 'style_loader_src', array( $this, 'lessify_parse_style' ), 100000, 2 );

		// editor stylesheet URLs are concatenated and run through this filter
		add_filter( 'mce_css', array( $this, 'parse_editor_stylesheets' ), 100000 );
		
	}

	//Lessify the stylesheet and return the href of the compiled file
	public function lessify_parse_style( $src, $handle ) {

		// we only want to handle .less files
		if ( ! preg_match( '/\.less(\.php)?$/', preg_replace( '/\?.*$/', '', $src ) ) )
			return $src;

		// get file path from $src
		if ( ! strstr( $src, '?' ) ) $src .= '?'; // prevent non-existent index warning when using list() & explode()

		list( $less_path, $query_string ) = explode( '?', str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src ) );

		// output css file name
		$css_path = trailingslashit( $this->lessify_cache_dir() ) . "{$handle}.css";


		// automatically regenerate css files if source's modified time has changed 
		try {

			// initialise the parser
			$less = new lessc;

			// load the cache
			$cache_path = "{$css_path}.cache";

			if ( file_exists( $cache_path ) )
				$cache = unserialize( file_get_contents( $cache_path ) );

			// vars to pass into the compiler - default @themeurl var for image urls etc...
			$this->vars[ 'themeurl' ] = '~"' . get_stylesheet_directory_uri() . '"';
			$this->vars[ 'lessurl' ]  = '~"' . dirname( $src ) . '"';
			
			// If the cache or root path in it are invalid then regenerate
			if ( empty( $cache ) || empty( $cache['less']['root'] ) || ! file_exists( $cache['less']['root'] ) )
				$cache = array( 'vars' => $this->vars, 'less' => $less_path );

			// less config
			$less->setFormatter( apply_filters( 'less_compression', $this->compression ) );
			$less->setPreserveComments( apply_filters( 'less_preserve_comments', $this->preserve_comments ) );
			$less->setVariables( $this->vars );

			$less_cache = $less->cachedCompile( $cache[ 'less' ], apply_filters( 'less_force_compile', false ) );

			if ( empty( $cache ) || empty( $cache[ 'less' ][ 'updated' ] ) || $less_cache[ 'updated' ] > $cache[ 'less' ][ 'updated' ] || $this->vars !== $cache[ 'vars' ] ) {
				file_put_contents( $cache_path, serialize( array( 'vars' => $this->vars, 'less' => $less_cache ) ) );
				file_put_contents( $css_path, $less_cache[ 'compiled' ] );
			}
		} catch ( exception $ex ) {
			wp_die( $ex->getMessage() );
		}

		// return the compiled stylesheet with the query string it had if any
		$url = trailingslashit( $this->lessify_cache_dir( false ) ) . "{$handle}.css" . ( ! empty( $query_string ) ? "?{$query_string}" : '' );
		return add_query_arg( 'ver', $less_cache[ 'updated' ], $url );
	}


	//Compile editor stylesheets registered via add_editor_style()
	public function parse_editor_stylesheets( $mce_css ) {

		// extract CSS file URLs
		$style_sheets = explode( ",", $mce_css );

		if ( count( $style_sheets ) ) {
			$compiled_css = array();

			// loop through editor styles, any .less files will be compiled and the compiled URL returned
			foreach( $style_sheets as $style_sheet )
				$compiled_css[] = $this->lessify_parse_style( $style_sheet, $this->url_to_handle( $style_sheet ) );

			$mce_css = implode( ",", $compiled_css );
		}

		// return new URLs
		return $mce_css;
	}


	//Get a nice handle to use for the compiled CSS file name
	public function url_to_handle( $url ) {

		$url = parse_url( $url );
		$url = str_replace( '.less', '', basename( $url[ 'path' ] ) );
		$url = str_replace( '/', '-', $url );

		return sanitize_key( $url );
	}


	//Get (and create if unavailable) the compiled CSS cache directory
	public function lessify_cache_dir( $path = true ) {

		// get path and url info
		$upload_dir = wp_upload_dir();

		if ( $path ) {
			$dir = apply_filters( 'lessify_cache_path', path_join( $upload_dir[ 'basedir' ], 'lessify-cache' ) );
			// create folder if it doesn't exist yet
			wp_mkdir_p( $dir );
		} else {
			$dir = apply_filters( 'lessify_cache_url', path_join( $upload_dir[ 'baseurl' ], 'lessify-cache' ) );
		}

		return rtrim( $dir, '/' );
	}

} // END

} // endif;