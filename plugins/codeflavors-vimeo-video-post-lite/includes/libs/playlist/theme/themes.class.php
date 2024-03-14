<?php

namespace Vimeotheque\Playlist\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @ignore
 */
class Themes {

	/**
	 * @var Theme[]
	 */
	private $themes = [];

	/**
	 * Themes constructor.
	 *
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		$this->register_theme( $theme );
	}

	/**
	 * Register a new theme
	 *
	 * @param Theme $theme
	 */
	public function register_theme( Theme $theme ){
		$this->themes[ $theme->get_folder_name() ] = $theme;
		$functions = trailingslashit( $theme->get_path() ) . 'functions.php';
		if( file_exists( $functions ) ){
			include $functions;
		}
	}

	/**
	 * Return all registered themes
	 *
	 * @return Theme[]
	 */
	public function get_themes(){
		return $this->themes;
	}

	/**
	 * @param $theme
	 *
	 * @return Theme
	 */
	public function get_theme( $theme ){
		if( !is_wp_error( $theme ) && isset( $this->themes[ $theme ] ) ){
			return $this->themes[ $theme ];
		}
	}

}