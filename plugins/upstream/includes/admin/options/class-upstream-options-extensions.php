<?php
/**
 * UpStream_Options_Extensions
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Options_Extensions' ) ) {

	/**
	 * CMB2 Theme Options
	 *
	 * @version 0.1.0
	 */
	class UpStream_Options_Extensions {


		/**
		 * Array of metaboxes/fields
		 *
		 * @var array
		 */
		public $id = 'upstream_extensions';

		/**
		 * Page title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * Menu Title
		 *
		 * @var string
		 */
		protected $menu_title = '';

		/**
		 * Menu Title
		 *
		 * @var string
		 */
		protected $description = '';

		/**
		 * Holds an instance of the object
		 *
		 * @var Myprefix_Admin
		 **/
		public static $instance = null;

		/**
		 * Constructor
		 *
		 * @param Container $container Container.
		 *
		 * @since 0.1.0
		 */
		public function __construct( $container ) {

			/**
			 * Add-ons page
			 */
			do_action( 'allex_enable_module_addons' );
			add_filter( 'allex_addons', array( 'UpStream_Options_Extensions', 'filter_allex_addons' ), 10, 2 );

			/**
			 * Upgrade link
			 */
			do_action( 'allex_enable_module_upgrade', 'https://upstreamplugin.com/pricing/' );

			// Set our title.
			$this->title       = __( 'Extensions', 'upstream' );
			$this->menu_title  = $this->title;
			$this->description = __(
				'These extensions add extra functionality to the UpStream Project Management plugin.',
				'upstream'
			);

			add_action(
				'cmb2_render_upstream_extensions_wrapper',
				array( 'UpStream_Options_Extensions', 'render_extensions_wrapper' ),
				10,
				5
			);

			add_filter( 'allex_upgrade_link', array( $this, 'filter_allex_upgrade_link' ), 10, 2 );
			add_action( 'allex_addon_update_license', array( $this, 'action_allex_addon_update_license' ), 10, 4 );
			add_filter( 'allex_addons_get_license_key', array( $this, 'filter_allex_addons_get_license_key' ), 10, 2 );
			add_filter( 'allex_addons_get_license_status', array( $this, 'filter_allex_addons_get_license_status' ), 10, 2 );

			do_action( 'allex_set_license_key_links', 'upstream', '/wp-admin/admin.php?page=upstream_extensions' );
		}

		/**
		 * Returns true if there is one or more installed extension.
		 *
		 * @return bool
		 */
		public static function there_are_installed_extensions() {
			$pool = apply_filters( 'allex_addons', array(), 'upstream' );

			foreach ( $pool as $addon ) {
				if ( file_exists( ABSPATH . 'wp-content/plugins/' . $addon['slug'] . '/' . $addon['slug'] . '.php' ) ) {
					return true;
				}
			}

			return false;
		}


		/**
		 * Filter Allex Addons
		 *
		 * @param mixed $addons Addons.
		 * @param mixed $plugin_name Plugin Name.
		 *
		 * @return array
		 */
		public static function filter_allex_addons( $addons, $plugin_name ) {
			if ( 'upstream' === $plugin_name ) {
				$addons = self::get_addons_list();
			}

			return $addons;
		}

		/**
		 * Get Addons List
		 *
		 * @return array
		 */
		protected static function get_addons_list() {
			$addons = array(
				'upstream-customizer'           => array(
					'slug'        => 'upstream-customizer',
					'title'       => __( 'Customizer', 'upstream' ),
					'description' => __(
						'Adds controls to easily customize the appearance of your projects.',
						'upstream'
					),
					'icon_class'  => 'fa fa-paint-brush',
					'edd_id'      => 4051,
				),
				'upstream-email-notifications'  => array(
					'slug'        => 'upstream-email-notifications',
					'title'       => __( 'Email Notifications', 'upstream' ),
					'description' => __(
						'Allows you to email project updates to people working on your projects.',
						'upstream'
					),
					'icon_class'  => 'fa fa-envelope',
					'edd_id'      => 4996,
				),
				'upstream-frontend-edit'        => array(
					'slug'        => 'upstream-frontend-edit',
					'title'       => __( 'Frontend Edit', 'upstream' ),
					'description' => __(
						'Allow users to add and edit items on the frontend.',
						'upstream'
					),
					'icon_class'  => 'fa fa-edit',
					'edd_id'      => 3925,
				),
				'upstream-project-timeline'     => array(
					'slug'        => 'upstream-project-timeline',
					'title'       => __( 'Project Timeline', 'upstream' ),
					'description' => __(
						'Add a Gantt style chart to visualize your projects.',
						'upstream'
					),
					'icon_class'  => 'fa fa-align-left',
					'edd_id'      => 3920,
				),
				'upstream-copy-project'         => array(
					'slug'        => 'upstream-copy-project',
					'title'       => __( 'Copy Project', 'upstream' ),
					'description' => __(
						'Allows you to duplicate an UpStream project including all the content and options.',
						'upstream'
					),
					'icon_class'  => 'fa fa-copy',
					'edd_id'      => 5471,
				),
				'upstream-calendar-view'        => array(
					'slug'        => 'upstream-calendar-view',
					'title'       => __( 'Calendar View', 'upstream' ),
					'description' => __(
						'This calendar display will allow you to easily see everything that’s happening in a project.',
						'upstream'
					),
					'icon_class'  => 'fa fa-calendar',
					'edd_id'      => 6798,
				),
				'upstream-custom-fields'        => array(
					'slug'        => 'upstream-custom-fields',
					'title'       => __( 'Custom Fields', 'upstream' ),
					'description' => __(
						'This extension allows you to add more information to Project, Milestone, Tasks and Bugs.',
						'upstream'
					),
					'icon_class'  => 'fa fa-reorder',
					'edd_id'      => 8409,
				),

				'upstream-advanced-permissions' => array(
					'slug'        => 'upstream-advanced-permissions',
					'title'       => __( 'Advanced Permissions', 'upstream' ),
					'description' => __(
						'This extension allows you to set permissions by user or role for objects and individual fields.',
						'upstream'
					),
					'icon_class'  => 'fa fa-lock',
					'edd_id'      => 24749,
				),
				'upstream-time-tracking'        => array(
					'slug'        => 'upstream-time-tracking',
					'title'       => __( 'Time Tracking', 'upstream' ),
					'description' => __(
						'This extension allows you track time spent on projects.',
						'upstream'
					),
					'icon_class'  => 'fa fa-clock-o',
					'edd_id'      => 27994,
				),
				'upstream-reporting'            => array(
					'slug'        => 'upstream-reporting',
					'title'       => __( 'Reporting', 'upstream' ),
					'description' => __(
						'This extension provides reports and a report dashboard.',
						'upstream'
					),
					'icon_class'  => 'fa fa-file-excel-o',
					'edd_id'      => 16229,
				),
				'upstream-name-replacement'     => array(
					'slug'        => 'upstream-name-replacement',
					'title'       => __( 'Naming', 'upstream' ),
					'description' => __(
						'This extension allows you to customize UpStream by replacing any names/text.',
						'upstream'
					),
					'icon_class'  => 'fa fa-gear',
					'edd_id'      => 27992,
				),
				'upstream-forms'                => array(
					'slug'        => 'upstream-forms',
					'title'       => __( 'Forms', 'upstream' ),
					'description' => __(
						'This extension allows you to create/edit projects using popular forms tools.',
						'upstream'
					),
					'icon_class'  => 'fa fa-book',
					'edd_id'      => 26850,
				),
				'upstream-api'                  => array(
					'slug'        => 'upstream-api',
					'title'       => __( 'API', 'upstream' ),
					'description' => __(
						'This extension provides API functionality for UpStream.',
						'upstream'
					),
					'icon_class'  => 'fa fa-desktop',
					'edd_id'      => 30921,
				),
			);

			return $addons;
		}

		/**
		 * Action Allex Addon Update License
		 *
		 * @param mixed $plugin_name Plugin Name.
		 * @param mixed $addon_slug Addon Slug.
		 * @param mixed $license_key License Key.
		 * @param mixed $license_status License Status.
		 */
		public function action_allex_addon_update_license( $plugin_name, $addon_slug, $license_key, $license_status ) {
			/**
			 * Duplicate the license key for backward compatibility with add-ons.
			 */
			$extensions = get_option( 'upstream:extensions' );

			if ( ! is_array( $extensions ) ) {
				$extensions = array();
			}

			$extensions[ $addon_slug ] = array(
				'key'    => $license_key,
				'status' => $license_status,
			);

			update_option( 'upstream:extensions', $extensions );
		}

		/**
		 * Filter Allex Addons Get License Key
		 *
		 * @param mixed $license_key License Key.
		 * @param mixed $addon_slug Addon Slug.
		 *
		 * @return string
		 */
		public function filter_allex_addons_get_license_key( $license_key, $addon_slug ) {
			$extensions = get_option( 'upstream:extensions' );

			if ( isset( $extensions[ $addon_slug ] ) && $extensions[ $addon_slug ]['key'] ) {
				return $extensions[ $addon_slug ]['key'];
			}

			return $license_key;
		}

		/**
		 * Filter Allex Addons Get License Status
		 *
		 * @param mixed $license_status License Status.
		 * @param mixed $addon_slug Addon Slug.
		 *
		 * @return string
		 */
		public function filter_allex_addons_get_license_status( $license_status, $addon_slug ) {
			$extensions = get_option( 'upstream:extensions' );

			if ( isset( $extensions[ $addon_slug ] ) && $extensions[ $addon_slug ]['status'] ) {
				return $extensions[ $addon_slug ]['status'];
			}

			return $license_status;
		}

		/**
		 * Renders all Extensions Page's HTML.
		 *
		 * @param \CMB2_Field $field       The current CMB2_Field object.
		 * @param string      $value       The field value passed through the escaping filter.
		 * @param mixed       $object_id   The object id.
		 * @param string      $object_type The type of object being handled.
		 * @param \CMB2_Types $field_type  Instance of the correspondent CMB2_Types object.
		 *
		 * @since   1.11.0
		 * @static
		 */
		public static function render_extensions_wrapper( $field, $value, $object_id, $object_type, $field_type ) {
			do_action( 'allex_echo_addons_page', 'https://upstreamplugin.com/pricing/', 'upstream' );
		}

		/**
		 * Filter Allex Upgrade Link
		 *
		 * @param string $ad_link Ad Link.
		 * @param string $plugin_name Plugin Name.
		 *
		 * @return array
		 */
		public function filter_allex_upgrade_link( $ad_link, $plugin_name ) {
			if ( 'upstream' === $plugin_name ) {
				$ad_link = 'https://upstreamplugin.com/welcome-coupon/';
			}

			return $ad_link;
		}

		/**
		 * Add the options metabox to the array of metaboxes.
		 *
		 * @return  array
		 * @since   0.1.0
		 */
		public function get_options() {
			$options = array(
				'id'         => $this->id,
				'title'      => $this->title,
				'menu_title' => $this->menu_title,
				'desc'       => $this->description,
				'show_on'    => array(
					'key'   => 'options-page',
					'value' => array( $this->id ),
				),
				'fields'     => array(
					array(
						'id'   => 'upstream_extensions_wrapper',
						'type' => 'upstream_extensions_wrapper',
					),
				),
			);

			return $options;
		}

		/**
		 * Returns the running object
		 *
		 * @return Myprefix_Admin
		 **/
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}
