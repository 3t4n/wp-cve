<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// added 'wpim_core_loaded' action (called just after core
// added "search" setting
// added single-loop-search.php template
// added 'set_items' and 'rewind_items' functionality to WPIM Loop
// added 'additional class' parameter to wpinventory_get_class() function
// added support for 'post_id' in wpinventory_get_permalink functions

// TODO: Allow settings to configure weight of fields
// TODO: Allow settings for which fields to display in "search results".

class WPIMSearch extends WPIMCore {

	private static $loaded = FALSE;

	private static $search_term = '';

	private static $items = [];

	private static $WPIMLoop;

	private static $location;

	private static $no_wp_results = FALSE;

	private static $debug = FALSE;

	/**
	 * The ID of the page with the [wpinventory] shortcode on it.
	 *
	 * @var int
	 */
	private static $post_id;

	/**
	 * Bootstrap the class ONLY AFTER the inventory core has loaded.
	 */
	public static function initialize() {
		add_action( 'wpim_core_loaded', [ __CLASS__, 'add_actions' ] );
	}

	private static function debug( $string ) {

		self::debug_log( $string );

		if ( ! self::$debug ) {
			return;
		}

		echo PHP_EOL . '<!-- WPIM Search Debug: ' . esc_attr($string) . '. -->' . PHP_EOL;
	}

	/**
	 * Add the required actions to hook into search
	 * ONLY IF the search configuration is turned on
	 */
	public static function add_actions() {

		if ( isset( $_GET['debug'] ) ) {
			self::$debug = TRUE;
		}

		if ( self::$loaded ) {
			self::debug( 'Self already loaded. Exiting' );
			return;
		}

		self::$location = self::$config->get( 'include_in_search' );
		self::$loaded   = TRUE;

		if ( ! self::$location ) {
			self::debug( 'Search turned off in settings.  Exiting' );
			return;
		}

		self::$post_id = self::$config->get( 'search_page_id' );
		if ( ! self::$post_id ) {
			self::debug( 'Search Page ID not set in settings.  Attempting to find shortcode' );
			self::$post_id = wpinventory_find_shortcode( TRUE );
		}

		if ( ! self::$post_id ) {
			self::debug( 'Shortcode not found on any pages.' );
			return;
		}

		// Do some preparation.  If we don't have any posts in a search result, have to manipulate the results
		add_filter( 'the_posts', [ __CLASS__, 'the_posts' ], 10, 2 );
		add_action( 'loop_start', [ __CLASS__, 'loop_start' ], 10, 2 );
		add_action( 'loop_end', [ __CLASS__, 'loop_end' ], 10, 2 );
	}


	/**
	 * Manipulate the $posts returned for search.
	 * Hackiest hack of all hackdom.
	 *
	 * Sets a dummy post, but we don't want to show that, so also sets a flag
	 * so that we can insert a div wrapper that will hide the dummy post from display.
	 * Lame.
	 *
	 * ONLY When doing a search, we need to determine:
	 * a. Do we have inventory items available?
	 * b. If NO, then do not manipulate the results.
	 * c. If YES, then inject a single "dummy" post so that we can hook the display of Inventory Items.
	 *
	 * @param $posts
	 *
	 * @return array
	 */
	public static function the_posts( $posts, $wp_query ) {
		if ( ! is_search() ) {
			self::debug( 'Not a search.' );
			return $posts;
		}

		self::load_results( $wp_query );

		// If inventory results, then have to inject at least a single post to trick the search into displaying results.
		if ( self::$items && empty( $posts ) ) {

			self::$no_wp_results = TRUE;

			$posts[] = [
				'ID'         => 0,
				'post_title' => ''
			];

			self::debug( 'Injecting a single POST result to cause search to display' );
		} else {
			self::debug( 'WP POSTS match search OR no Inventory Items match search' );
		}

		return $posts;
	}

	/**
	 * Output the search results if setting matches.
	 * Output the opening container tag to hide fake WP results if necessary.
	 *
	 * @param $wp_query
	 */
	public static function loop_start() {
		if ( self::$location == 'before' ) {
			self::debug( 'Location set to "Before"' );
			self::display_items();
		}

		if ( self::$no_wp_results ) {
			self::debug( 'Hack.  Suppress "fake injected" POST - open div' );
			echo '<div id="wpinventory_search_hide" style="display: none !important;">';
		}
	}

	/**
	 * Output the search results if setting matches.
	 * Output the closing container tag to hide fake WP results if necessary.
	 *
	 * @param $wp_query
	 */
	public static function loop_end() {
		if ( self::$no_wp_results ) {
			echo '</div>';
			self::debug( 'Hack. Suppress "fake inject" POST - close div' );
		}

		if ( self::$location == 'after' ) {
			self::debug( 'Location set to "After"' );
			self::display_items();
		}
	}

	/**
	 * Detects if actually a search, and finds matches in Inventory items.
	 *
	 * @return array
	 */
	public static function load_results( $wp_query ) {

		if ( ! is_search() ) {
			self::debug( 'Not a search (in load results). Exiting' );
			return;
		}

		// Uses $wp_query->is_admin instead of is_admin() to help with Ajax queries that
		// use 'admin_ajax' hook (which sets is_admin() to true whether it's an admin search
		// or not.
		if ( is_search() && $wp_query->is_admin ) {
			self::debug( 'WP Query is admin. Exiting' );
			return;
		}

		if ( $wp_query->is_admin && empty( $wp_query->query_vars['s'] ) ) {
			self::debug( 'WP Query is admin, search string is empty. Exiting' );
			return;
		}

		if ( $wp_query->query_vars['post_type'] == 'attachment' && $wp_query->query_vars['post_status'] == 'inherit,private' ) {
			self::debug( 'Search query var for attachments or private' );
			return;
		}

		self::$search_term = get_search_query();

		if ( ! trim( self::$search_term ) ) {
			self::debug( 'No search term entered.' );
			return;
		}

		if ( ! self::$post_id ) {
			echo '<p>' . self::__( 'No WP Inventory shortcode found on the site.  Cannot display inventory search.' ) . '</p>';
			return;
		}

		self::$WPIMLoop = new WPIMLoop( [
			'search'    => self::$search_term,
			'page_size' => 0
		] );

		while ( self::$WPIMLoop->have_items() ) {
			self::$WPIMLoop->the_item();
			self::$items[] = self::$WPIMLoop->return_item();
		}
	}

	public static function display_items() {
		if ( self::$items ) {
			self::calculate_relevance();
			self::render_items();
		}
	}

	/**
	 * Iterates over the found items and calculates a relevance score based on the number of
	 * times the search word appears in all the fields, as well as which fields (title gets heavier weight, for example)
	 */
	public static function calculate_relevance() {

		if ( ! self::$search_term ) {
			return;
		}

		self::debug( 'Calculating relevance' );
		foreach ( self::$items AS $index => $item ) {
			$relevance = 0;
			foreach ( $item AS $field => $value ) {
				if ( is_string( $value ) ) {
					$count     = substr_count( strtolower( strip_tags( $value ) ), strtolower( self::$search_term ) );
					$factor    = ( $field == 'inventory_name' ) ? 2 : 1;
					$factor    = ( $field == 'inventory_slug' ) ? 1.5 : $factor;
					$factor    = ( $field == 'status' ) ? 0 : $factor;
					$relevance += ( $count * $factor );
				}
			}

			self::$items[ $index ]->relevance = $relevance;
		}

		usort( self::$items, [ __CLASS__, 'sort_by_relevance' ] );

		self::$WPIMLoop->set_items( self::$items );
	}

	/**
	 * usort's function used to determine sort order.  DESC (highest to lowest).
	 *
	 * @param $a - inventory item
	 * @param $b - inventory item
	 *
	 * @return int
	 */
	public static function sort_by_relevance( $a, $b ) {
		$a = ( ! empty( $a->relevance ) ) ? $a->relevance : 0;
		$b = ( ! empty( $b->relevance ) ) ? $b->relevance : 0;

		if ( $a == $b ) {
			return 0;
		}

		return ( $a < $b ) ? 1 : - 1;

	}

	/**
	 * Load the template (views) that render the search results for inventory items.
	 */
	public static function render_items() {
		self::debug( 'Rendering Items' );
		global $WPIMLoop;
		global $shortcode_post_id;

		$WPIMLoop          = self::$WPIMLoop;
		$shortcode_post_id = self::$post_id;

		wpinventory_get_template_part( 'loop-search' );
	}
}

WPIMSearch::initialize();
