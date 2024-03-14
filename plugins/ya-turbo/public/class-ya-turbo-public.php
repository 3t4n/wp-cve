<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 * @author     hardkod.ru <hello@hardkod.ru>
 */
class Ya_Turbo_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Wordpress init action
	 *
	 * @since    1.0.0
	 */
	public function init () {

		/* Register new feed type */
		add_feed(YATURBO_FEED, array('Ya_Turbo_Public', 'feed'));
	}

	public function get_lastpostmodified ( $lastpostmodified, $timezone ) {
		$lastpostmodified = date("Y-m-d H:i:s", strtotime("+10 day"));  // Now
	}

	/**
	 * Feed generator
	 *
	 * @since    1.0.1
	 */
	public function feed() {

		global $wpdb;

		nocache_headers();

		/* Feed */

		$feed_name = trim($_REQUEST['name']);

		$table_feed = $wpdb->prefix . YATURBO_DB_FEEDS;

		$sql = /** @lang sql */ "
			SELECT tf.* 
				FROM {$table_feed} AS tf
					WHERE slug = %s";

		$feed = $wpdb->get_row( $wpdb->prepare( $sql, $feed_name ) );

		if ( !$feed ) {
			status_header( 404 );
			wp_die( _e( 'Feed not found', YATURBO_FEED ) );
		}

		$feed->settings = unserialize( $feed->settings );

		/* cache */
		$cache_key = YATURBO_FEED . '-' . wp_hash($feed->slug);

		/* items */
		$args = array(
			'post_status'    => array( 'publish' ),
			'post_type'      => $feed->settings['post'],
			'orderby'        => $feed->settings['orderby'],
			'order'          => $feed->settings['order'],
			'posts_per_page' => $feed->limit,
		);

		$post__not_in = @$feed->settings['nopostid'];

		if ( !empty( $post__not_in) ) {
			$args['post__not_in'] = $post__not_in;
		}

		if ( false === ( $cache = get_transient( $cache_key ) ) ) {
			$cache = new WP_Query($args);
			set_transient( $cache_key, $cache, $feed->settings['cache'] * MINUTE_IN_SECONDS );
		}
		$feed->items = $cache; $cache = null;

		require YATURBO_PATH . '/public/partials/ya-turbo-public-feed.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/* This function is provided for demonstration purposes only. */
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css',
		//      array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/* This function is provided for demonstration purposes only. */
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js',
		//      array( 'jquery' ), $this->version, false );
	}

	/**
	 * Feed allowed html tags
	 *
	 * @return array
	 */
	public static function allowed_html () {
		return array(
			'figure'		=> array(),
			'h1'			=> array(),
			'h2'			=> array(),
			'h3'			=> array(),
			'h4'			=> array(),
			'h5'			=> array(),
			'h6'			=> array(),
			'p'				=> array(),
			'br'			=> array(),
			'ul'			=> array(),
			'ol'			=> array(),
			'li'			=> array(),
			'b'				=> array(),
			'strong'		=> array(),
			'i'				=> array(),
			'em'			=> array(),
			'sup'			=> array(),
			'sub'			=> array(),
			'ins'			=> array(),
			'del'			=> array(),
			'small'			=> array(),
			'big'			=> array(),
			'pre'			=> array(),
			'abbr'			=> array(),
			'u'				=> array(),
			'figcaption'	=> array(),
			'video'			=> array(),
			'source'		=> array(
				'src'	=> true,
				'type'	=> true,
			),
			'a'				=> array(
				'href'	=> true,
			),
			'img'			=> array(
				'src'	=> true,
			)
		);
	}
}
