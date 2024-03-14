<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PL_Plugin {

	/**
	 * Array of theme names.
	 */
	protected static $_instance = null;

	const themes = array( 'Shapro', 'Shapro Child' );

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @return Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		$this->load();
	}

	public static function install() {

		self::add_actions();
		
		/**
		 * Shapro
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'shapro_init', array() ) ) ) {
			$is_already_setup=get_option('pl_default_setup');
 
			if (!$is_already_setup) {
				new PL_Theme_Shapro_Default_setup();
			}
		}

		/**
		 * Biznol
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'biznol_init', array() ) ) ) {
			$is_already_setup=get_option('pl_default_setup');
 
			if (!$is_already_setup) {
				new PL_Theme_Biznol_Default_setup();
			}
		}

		/**
		 * Corposet
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'corposet_init', array() ) ) ) {
			$is_already_setup=get_option('pl_default_setup');
 
			if (!$is_already_setup) {
				new PL_Theme_Corposet_Default_setup();
			}
		}

		/**
		 * Bizstrait
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'bizstrait_init', array() ) ) ) {
			$is_already_setup=get_option('pl_default_setup');
 
			if (!$is_already_setup) {
				new PL_Theme_Bizstrait_Default_setup();
			}
		}
	}

	public function load() {

		self::add_actions();

		/**
		 * Shapro
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'shapro_init', array() ) ) ) {
			new PL_Theme_Shapro_Load();
		}
		/**
		 * Biznol
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'biznol_init', array() ) ) ) {
			new PL_Theme_Biznol_Load();
		}
		/**
		 * Corposet
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'corposet_init', array() ) ) ) {
			new PL_Theme_Corposet_Load();
		}
		/**
		 * Bizstrait
		 */
		if ( in_array( wp_get_theme()->name, apply_filters( 'bizstrait_init', array() ) ) ) {
			new PL_Theme_Bizstrait_Load();
		}
	}

	static function add_actions(){
		add_action( 'init', array( __CLASS__, 'pluglab_textdomain' ) );

		add_filter( 'shapro_init', array( __CLASS__, 'shaproThemes' ), 10, 1 );
		add_filter( 'biznol_init', array( __CLASS__, 'biznolThemes' ), 10, 1 );
		add_filter( 'corposet_init', array( __CLASS__, 'corposetThemes' ), 10, 1 );
		add_filter( 'bizstrait_init', array( __CLASS__, 'bizstraitThemes' ), 10, 1 );
	}

	static function shaproThemes( $flavours = array() ) {
		return $flavours = array( 'Shapro', 'Shapro Child' );
	}

	static function biznolThemes( $flavours = array() ) {
		return $flavours = array( 'Biznol', 'Biznol Child' );
	}

	static function corposetThemes( $flavours = array() ) {
		return $flavours = array( 'Corposet', 'Corposet Child' , 'Corposys' );
	}

	static function bizstraitThemes( $flavours = array() ) {
		return $flavours = array( 'Bizstrait', 'Bizstrait Child' );
	}

	static function pluglab_textdomain() {
		load_plugin_textdomain( 'pluglab', false, plugin_dir_url( __FILE__ ) . 'languages' );
	}

}
