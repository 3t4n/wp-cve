<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Backend;

use Glossary\Engine;

/**
 * Activate and deactive method of the plugin and relates.
 */
class ActDeact extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return false;
		}

		// Activate plugin when new blog is added
		\add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		\register_activation_hook( GT_TEXTDOMAIN . '/' . GT_SETTINGS . '.php', array( self::class, 'activate' ) );
		\register_deactivation_hook( GT_TEXTDOMAIN . '/' . GT_SETTINGS . '.php', array( self::class, 'deactivate' ) );
		\add_action( 'admin_init', array( $this, 'upgrade_procedure' ) );

		return true;
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param int $blog_id ID of the new blog.
	 * @since 2.0
	 * @return void
	 */
	public function activate_new_site( int $blog_id ) {
		if ( 1 !== \did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		\switch_to_blog( $blog_id );
		self::single_activate();
		\restore_current_blog();
	}

	/**
	 * Check if the page and user is admin to do the upgrade
	 *
	 * @return bool
	 */
	public static function can_current_page_upgrade() {
		return \is_admin() && ( \function_exists( 'wp_doing_ajax' ) && !\wp_doing_ajax() ) || !\defined( 'DOING_AJAX' ) && \current_user_can( 'manage_options' );
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param bool $network_wide True if active in a multiste, false if classic site.
	 * @since 2.0
	 * @return void
	 */
	public static function activate( bool $network_wide ) { //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
		if ( \function_exists( 'is_multisite' ) && \is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blogs = \get_sites();

				if ( \is_array( $blogs ) ) {
					foreach ( $blogs as $blog ) {
						\switch_to_blog( (int) $blog->blog_id );
						self::single_activate();
						\restore_current_blog();
					}
				}

				return;
			}
		}

		self::single_activate();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param bool $network_wide True if WPMU superadmin uses
	 * "Network Deactivate" action, false if
	 * WPMU is disabled or plugin is
	 * deactivated on an individual blog.
	 * @since 2.0
	 * @return void
	 */
	public static function deactivate( bool $network_wide ) { //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
		if ( \function_exists( 'is_multisite' ) && \is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blogs = \get_sites();

				if ( \is_array( $blogs ) ) {
					foreach ( $blogs as $blog ) {
						\switch_to_blog( (int) $blog->blog_id );
						self::single_deactivate();
						\restore_current_blog();
					}
				}

				return;
			}
		}

		self::single_deactivate();
	}

	/**
	 * Add admin capabilities
	 *
	 * @return void
	 */
	public static function add_capabilities() {
		// Add the capabilites to all the roles
		$caps  = array(
			'create_glossaries',
			'read_glossary',
			'read_private_glossaries',
			'edit_glossary',
			'edit_glossaries',
			'edit_private_glossaries',
			'edit_published_glossaries',
			'edit_others_glossaries',
			'publish_glossaries',
			'delete_glossary',
			'delete_glossaries',
			'delete_private_glossaries',
			'delete_published_glossaries',
			'delete_others_glossaries',
			'manage_glossaries',
		);
		$roles = array(
			\get_role( 'administrator' ),
			\get_role( 'editor' ),
			\get_role( 'author' ),
			\get_role( 'contributor' ),
			\get_role( 'subscriber' ),
		);

		foreach ( $roles as $role ) {
			foreach ( $caps as $cap ) {
				if ( \is_null( $role ) ) {
					continue;
				}

				$role->add_cap( $cap );
			}
		}

		self::remove_capabilities();
	}

	/**
	 * Remove capabilities to specific roles
	 *
	 * @return void
	 */
	public static function remove_capabilities() {
		$bad_caps = array(
			'create_glossaries',
			'read_private_glossaries',
			'edit_glossary',
			'edit_glossaries',
			'edit_private_glossaries',
			'edit_published_glossaries',
			'edit_others_glossaries',
			'publish_glossaries',
			'delete_glossary',
			'delete_glossaries',
			'delete_private_glossaries',
			'delete_published_glossaries',
			'delete_others_glossaries',
			'manage_glossaries',
		);
		$roles    = array(
			\get_role( 'author' ),
			\get_role( 'contributor' ),
			\get_role( 'subscriber' ),
		);

		foreach ( $roles as $role ) {
			foreach ( $bad_caps as $cap ) {
				if ( \is_null( $role ) ) {
					continue;
				}

				$role->remove_cap( $cap );
			}
		}
	}

	/**
	 * Upgrade procedure
	 *
	 * @return void
	 */
	public static function upgrade_procedure() {
		if ( !self::can_current_page_upgrade() ) {
			return;
		}

		$version = \strval( \get_option( 'glossary-version', true ) );

		if ( \version_compare( GT_VERSION, $version, '>' ) ) {
			self::add_capabilities();
			\update_option( 'glossary-version', GT_VERSION );
		}

		self::run_upgrades_by_version( $version );
	}

	/**
	 * Execute the various version upgrades
	 *
	 * @param string $version The actual version.
	 * @return void
	 */
	public static function run_upgrades_by_version( string $version ) {
		if ( !\version_compare( GT_VERSION, $version, '>' ) ) {
			return;
		}

		\flush_rewrite_rules();
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since 2.0
	 * @return void
	 */
	private static function single_activate() {
		self::add_capabilities();
		self::upgrade_procedure();
		// Clear the permalinks
		\flush_rewrite_rules();
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since 2.0
	 * @return void
	 */
	private static function single_deactivate() {
		// Clear the permalinks
		\flush_rewrite_rules();
	}

}
