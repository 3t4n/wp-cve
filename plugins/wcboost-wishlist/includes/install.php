<?php
/**
 * Install plugin
 */
namespace WCBoost\Wishlist;

defined( 'ABSPATH' ) || exit;

/**
 * Installation class
 */
class Install {
	/**
	 * Init hooks
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'check_version' ], 5 );
		add_filter( 'plugin_row_meta', [ __CLASS__, 'plugin_row_meta' ], 10, 2 );
	}

	/**
	 * Check the plugin version and run the installer
	 *
	 * @return void
	 */
	public static function check_version() {
		if ( version_compare( get_option( 'wcboost_wishlist_version' ), Plugin::instance()->version, '<' ) ) {
			self::install();
		}
	}

	/**
	 * Install plugin
	 *
	 * @return void
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		if ( 'yes' === get_transient( 'wcboost_wishlist_installing' ) ) {
			return;
		}

		set_transient( 'wcboost_wishlist_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		Plugin::instance()->define_tables();
		self::create_tables();
		self::maybe_create_pages();
		self::update_version();
		flush_rewrite_rules();

		delete_transient( 'wcboost_wishlist_installing' );
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 *
	 * @return void
	 */
	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( self::get_schema() );
	}

	/**
	 * Get table schema.
	 *
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = "
		CREATE TABLE {$wpdb->wishlists} (
			wishlist_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			wishlist_title text NULL,
			wishlist_slug VARCHAR(200) NULL,
			wishlist_token VARCHAR(64) NOT NULL,
			description longtext NULL,
			menu_order INT(11) NOT NULL,
			status varchar(200) NOT NULL DEFAULT 'private',
			user_id BIGINT UNSIGNED NOT NULL,
			session_id VARCHAR(200) NULL,
			date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			date_expires datetime NULL DEFAULT NULL,
			is_default tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY  (wishlist_id),
			KEY user_id (user_id),
			UNIQUE KEY wishlist_token (wishlist_token)
		) $collate;
		CREATE TABLE {$wpdb->wishlist_items} (
			item_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			status varchar(200) NOT NULL DEFAULT 'publish',
			product_id BIGINT UNSIGNED NOT NULL,
			variation_id BIGINT UNSIGNED NOT NULL DEFAULT '0',
			quantity INT(11) NOT NULL,
			wishlist_id BIGINT UNSIGNED NOT NULL,
			date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			date_expires datetime NULL DEFAULT NULL,
			PRIMARY KEY  (item_id),
			KEY product_id (product_id),
			KEY wishlist_id (wishlist_id)
		) $collate;
		";

		return $tables;
	}

	/**
	 * Create pages on installation.
	 */
	public static function maybe_create_pages() {
		if ( empty( get_option( 'wcboost_wishlist_version' ) ) ) {
			self::create_pages();
		}
	}

	/**
	 * Create pages that the plugin relies on
	 *
	 * @return void
	 */
	public static function create_pages() {
		if ( ! function_exists( 'wc_create_page' ) && defined( 'WC_PLUGIN_FILE' ) ) {
			include_once dirname( WC_PLUGIN_FILE ) . '/includes/admin/wc-admin-functions.php';
		}

		wc_create_page(
			esc_sql( _x( 'wishlist', 'Page slug', 'wcboost-wishlist' ) ),
			'wcboost_wishlist_page_id',
			_x( 'Wishlist', 'Page title', 'wcboost-wishlist' ),
			'<!-- wp:shortcode -->[wcboost_wishlist]<!-- /wp:shortcode -->'
		);
	}

	/**
	 * Update plugin version to current
	 */
	public static function update_version() {
		update_option( 'wcboost_wishlist_version', Plugin::instance()->version );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 *
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( Plugin::instance()->plugin_basename() !== $file ) {
			return $links;
		}

		$row_meta = [
			'docs'    => '<a href="https://docs.wcboost.com/plugin/woocommerce-wishlist/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash" aria-label="' . esc_attr__( 'View wishlist documentation', 'wcboost-wishlist' ) . '">' . esc_html__( 'Docs', 'wcboost-wishlist' ) . '</a>',
			'support' => '<a href="https://wordpress.org/support/plugin/wcboost-wishlist/" aria-label="' . esc_attr__( 'Visit community forums', 'wcboost-wishlist' ) . '">' . esc_html__( 'Community support', 'wcboost-wishlist' ) . '</a>',
		];

		return array_merge( $links, $row_meta );
	}
}
