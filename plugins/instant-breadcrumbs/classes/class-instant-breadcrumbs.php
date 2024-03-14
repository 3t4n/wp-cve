<?php
/**
 * Class that handles the instant breadcrumbs functionality.
 *
 * @since 1.0
 */
require_once( dirname( __FILE__ ) . '/class-ib-fragment-dom.php' );
require_once( dirname( __FILE__ ) . '/class-ib-generator.php' );
require_once( dirname( __FILE__ ) . '/class-ib-breadcrumb-integrator.php' );

class Instant_Breadcrumbs
{
	private static $primary;
	public static $generator = null;
	
	private static function get_generator() {
		if ( self::$generator == null ) {
			self::$generator = IB_Generator::create();
			self::$generator->init();
		}
		return self::$generator;
	}
	
	public static function hook() {
		// init the generator here
		self::get_generator();
		// only bother hooking if auto is set
		if ( IB_Options::safe_boolean( 'auto' ) ) {
			add_filter( 'wp_nav_menu', array( 'Instant_Breadcrumbs', 'add_crumbs' ), 15, 2 );
			add_filter( 'wp_nav_menu_items', array( 'Instant_Breadcrumbs', 'empty_guard' ), 15, 2 );
			add_filter( 'wp_page_menu', array( 'Instant_Breadcrumbs', 'add_crumbs' ), 15, 2 );
			
			// get the correct location for primary. If it's empty, the first one in will count.
			self::$primary = IB_Options::safe_string( 'location' );
		}
	}
	public static function load_textdomain() {
		load_plugin_textdomain( 'instant-breadcrumbs', FALSE, basename( dirname( dirname( __FILE__ ) ) ) . '/languages/' );
	}
	private static function is_primary( $args ) {
		// safely check the theme location.
		$tl = '';
		if ( is_object( $args ) && isset( $args->theme_location ) ) {
			$tl = $args->theme_location;
		} elseif ( is_array( $args ) && isset( $args['theme_location'] ) ) {
			$tl = $args['theme_location'];
		}
		if ( empty( self::$primary ) ) {
			self::$primary = $tl;
		}
		return ( self::$primary == $tl );
	}
	public static function empty_guard( $menu, $args ) {
		// trap the case where there's a named menu, but it has no items in it.
		if ( self::is_primary( $args ) && empty( $menu ) ) {
			$menu = '<span></span>';
		}
		return $menu;
	}
	public static function add_crumbs( $menu, $args )
	{
		if ( self::is_primary( $args ) ) {
			$menu = self::do_crumbs( $menu );
		}

		return $menu;		// don't modify by default
	}
	
	public static function do_crumbs( $menu, $padding = '' ) {
		$parser = new IB_Fragment_DOM;
		$dom    = $parser->read( $menu );
		if ( $dom ) {
			$gen    = self::get_generator();
			$crumbs = $gen->generate_crumbs();
			$adder  = new IB_Breadcrumb_Integrator;
			$dom    = $adder->integrate( $dom, $crumbs, $padding );
		
			// put a span around the whole fragment
			$rdf = (object) array(
					'type' => 'element',
					'name' => 'span',
					'attr' => array( 'xmlns:ib' => 'http://rdf.data-vocabulary.org/#' ),
					'children' => $dom->children,
			);
			$menu = $parser->write( $rdf );
		}
		return $menu;
	}


}
