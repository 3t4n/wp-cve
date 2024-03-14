<?php
/**
 * Main UpStream Class.
 *
 * @package UpStream
 */

use UpStream\Comments;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream' ) ) :

	/**
	 * Main UpStream Class.
	 *
	 * @since 1.0.0
	 */
	final class UpStream {

		/**
		 * The one true UpStream
		 *
		 * @var UpStream
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/**
		 * Twig Environment
		 *
		 * @var Twig_Environment
		 */
		protected $twig;

		/**
		 * Container
		 *
		 * @var Container
		 */
		protected $container;

		/**
		 * Main UpStream Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since   1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, 'You\'re not supposed to clone this class.', esc_html( UPSTREAM_VERSION ) );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since   1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, 'You\'re not supposed to unserialize this class.', esc_html( UPSTREAM_VERSION ) );
		}

		/**
		 * Prevent the class instance being serialized.
		 *
		 * @since   1.10.2
		 */
		public function __sleep() {
			_doing_it_wrong( __FUNCTION__, 'You\'re not supposed to serialize this class.', esc_html( UPSTREAM_VERSION ) );
		}

		/**
		 * Class constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->container = Container::get_instance();
			$this->init_framework();

			if ( UpStream_Debug::is_enabled() ) {
				UpStream_Debug::init();
			}

			$this->init_hooks();

			do_action( 'upstream_loaded' );
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since  1.0.0
		 */
		private function init_hooks() {
			add_action( 'init', array( $this, 'init' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_upstream/upstream.php', array( $this, 'handle_action_links' ) );
			add_filter( 'http_request_host_is_external', array( 'UpStream', 'allow_external_update_host' ), 10, 3 );
			add_filter( 'quicktags_settings', 'upstream_tinymce_quicktags_settings' );
			add_filter( 'tiny_mce_before_init', 'upstream_tinymce_before_init_setup_toolbar' );
			add_filter( 'tiny_mce_before_init', 'upstream_tinymce_before_init' );
			add_filter( 'teeny_mce_before_init', 'upstream_tinymce_before_init_setup_toolbar' );
			add_filter( 'comments_clauses', array( $this, 'filter_comments_on_dashboard' ), 10, 2 );
			add_filter( 'views_dashboard', array( 'UpStream_Admin', 'comment_status_links' ), 10, 1 );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			if ( is_admin() ) {
				add_action( 'admin_init', array( $this->container['reviews'], 'init' ) );
			}

			global $pagenow;

			if ( 'plugins.php' === $pagenow ) {
				add_action(
					'in_plugin_update_message-' . UPSTREAM_PLUGIN_BASENAME,
					array( $this, 'render_additional_update_info' ),
					20,
					2
				);
			}
		}

		/**
		 * Initialize the Alledia Framework.
		 */
		private function init_framework() {
			$this->container['framework']->init();
		}


		/**
		 * Prevent a Client User from accessing any page other than the profile.
		 *
		 * @since   1.11.0
		 *
		 * @global  $pagenow
		 */
		public function limit_client_users_admin_access() {
			global $pagenow;

			$profile_age = 'profile.php';
			if ( $pagenow !== $profile_age && 'edit.php' !== $pagenow && ! wp_doing_ajax() ) {
				wp_safe_redirect( admin_url( $profile_age ) );
				exit();
			}
		}

		/**
		 * Make sure Client Users can only see the Profile menu item.
		 *
		 * @since   1.11.0
		 *
		 * @global  $menu
		 */
		public function limit_client_users_menu() {
			global $menu;

			foreach ( $menu as $menu_index => $menu_data ) {
				$menu_file = isset( $menu_data[2] ) ? $menu_data[2] : null;

				if ( null !== $menu_file ) {
					if ( 'profile.php' === $menu_file || 'edit.php?post_type=project' === $menu_file ) {
						continue;
					}

					remove_menu_page( $menu_file );
				}
			}
		}

		/**
		 * Hide some toolbar items from Client Users.
		 *
		 * @param \WP_Admin_Bar $wp_admin_bar WordPress admin bar.
		 *
		 * @since   1.11.0
		 */
		public function limit_client_users_toolbar_items( $wp_admin_bar ) {
			$user       = wp_get_current_user();
			$user_roles = (array) $user->roles;

			if ( count( array_intersect( $user_roles, array( 'administrator', 'upstream_manager' ) ) ) === 0
				&& in_array( 'upstream_client_user', $user_roles, true )
			) {
				$menu_items = array( 'about', 'comments', 'new-content' );

				if ( ! is_admin() ) {
					$menu_items = array_merge( $menu_items, array( 'dashboard', 'edit' ) );
				}

				foreach ( $menu_items as $menu_item ) {
					$wp_admin_bar->remove_menu( $menu_item );
				}
			}
		}

		/**
		 * Get container.
		 *
		 * @return Container
		 */
		public function get_container() {
			return $this->container;
		}

		/**
		 * Define Constants.
		 *
		 * @since  1.0.0
		 */
		private function define_constants() {
			$upload_dir = wp_upload_dir();

			$this->define( 'UPSTREAM_PLUGIN_DIR', plugin_dir_path( UPSTREAM_PLUGIN_FILE ) );
			$this->define( 'UPSTREAM_PLUGIN_URL', plugin_dir_url( UPSTREAM_PLUGIN_FILE ) );
			$this->define( 'UPSTREAM_PLUGIN_BASENAME', plugin_basename( UPSTREAM_PLUGIN_FILE ) );
			$this->define( 'UPSTREAM_PLUGIN_RELATIVE_PATH', 'upstream' );

			include_once __DIR__ . '/includes.php';
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name Definition name.
		 * @param string|bool $value Definition value.
		 *
		 * @since  1.0.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type frontend or admin.
		 *
		 * @param string $type Request type.
		 * @return bool
		 * @since  1.0.0
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @since  1.0.0
		 */
		public function includes() {
			if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
				require_once __DIR__ . '/vendor/autoload.php';
			}

			include_once __DIR__ . '/includes/class-exception.php';
			include_once __DIR__ . '/includes/trait-up-singleton.php';
			include_once __DIR__ . '/includes/trait-up-post-metadata.php';
			include_once __DIR__ . '/includes/class-struct.php';
			include_once __DIR__ . '/includes/class-upstream-debug.php';
			include_once __DIR__ . '/includes/class-container.php';
			include_once __DIR__ . '/includes/up-install.php';
			include_once __DIR__ . '/includes/class-upstream-autoloader.php';
			include_once __DIR__ . '/includes/class-upstream-roles.php';
			include_once __DIR__ . '/includes/class-upstream-counts.php';
			include_once __DIR__ . '/includes/class-upstream-counter.php';
			include_once __DIR__ . '/includes/class-upstream-project-activity.php';
			include_once __DIR__ . '/includes/up-permalinks.php';
			include_once __DIR__ . '/includes/up-general-functions.php';
			include_once __DIR__ . '/includes/up-post-types.php';
			include_once __DIR__ . '/includes/up-labels.php';
			include_once __DIR__ . '/includes/class-milestones.php';
			include_once __DIR__ . '/includes/class-milestone.php';
			include_once __DIR__ . '/includes/class-factory.php';
			include_once __DIR__ . '/includes/up-install.php';
			include_once __DIR__ . '/includes/up-filesystem.php';
			include_once __DIR__ . '/includes/up-register-nonce-fields.php';
			include_once __DIR__ . '/includes/up-wcs-helper.php';

			$request = wp_unslash( $_REQUEST );

			if ( $this->is_request( 'admin' ) ) {
				global $pagenow;

				$is_multisite = (bool) is_multisite();
				$load_cmb2    = false;
				$server       = wp_unslash( $_SERVER );

				if ( $is_multisite ) {
					$current_page = isset( $_SERVER['PHP_SELF'] ) ? preg_replace(
						'/^\/wp-admin\//i',
						'',
						sanitize_text_field( $server['PHP_SELF'] )
					) : '';
				} else {
					$current_page = (string) $pagenow;
				}

				if ( in_array( $current_page, array( 'post.php', 'post-new.php' ), true ) ) {
					$post_type = isset( $request['post_type'] ) ? sanitize_text_field( $request['post_type'] ) : null;

					if ( empty( $post_type ) ) {
						$project_id = isset( $request['post'] ) ? absint( $request['post'] ) : 0;
						$post_type  = get_post_type( $project_id );
					}

					if ( ! empty( $post_type ) ) {
						$post_types_using_cmb2 = apply_filters( 'upstream:post_types_using_cmb2', array( 'project', 'client' ) );
						$load_cmb2             = in_array( $post_type, $post_types_using_cmb2, true );
					}
				} elseif (
					'admin.php' === $current_page
					&& isset( $request['page'] )
					&& preg_match( '/^upstream_/i', sanitize_text_field( $request['page'] ) )
				) {
					$load_cmb2 = true;
				}

				if ( $load_cmb2 ) {
					include_once __DIR__ . '/includes/libraries/cmb2/init.php';
					include_once __DIR__ . '/includes/libraries/cmb2-grid/Cmb2GridPlugin.php';
				}

				include_once __DIR__ . '/includes/admin/class-upstream-admin.php';
				include_once __DIR__ . '/includes/admin/class-upstream-admin-tasks-page.php';
				include_once __DIR__ . '/includes/admin/class-upstream-admin-bugs-page.php';
				include_once __DIR__ . '/includes/admin/class-upstream-admin-reviews.php';
			}

			if ( $this->is_request( 'frontend' ) ) {
				include_once __DIR__ . '/includes/frontend/class-upstream-template-loader.php';
				include_once __DIR__ . '/includes/frontend/class-upstream-login.php';
				include_once __DIR__ . '/includes/frontend/class-upstream-style-output.php';
				include_once __DIR__ . '/includes/frontend/up-enqueues.php';
				include_once __DIR__ . '/includes/frontend/up-template-functions.php';
				include_once __DIR__ . '/includes/frontend/up-table-functions.php';
				include_once __DIR__ . '/includes/frontend/class-upstream-view.php';
				include_once __DIR__ . '/includes/frontend/class-upstream-ajax.php';
			}

			include_once __DIR__ . '/includes/up-project-functions.php';
			include_once __DIR__ . '/includes/up-client-functions.php';
			include_once __DIR__ . '/includes/up-permissions-functions.php';
			include_once __DIR__ . '/includes/class-comments-migration.php';
			include_once __DIR__ . '/includes/class-comments.php';
			include_once __DIR__ . '/includes/class-comment.php';
		}

		/**
		 * Init UpStream when WordPress Initialises.
		 */
		public function init() {
			UpStream\Milestones::instantiate();

			do_action( 'before_upstream_init' );

			$this->project          = new UpStream_Project();
			$this->project_activity = new UpStream_Project_Activity();

			if ( version_compare( PHP_VERSION, '5.5', '<' ) ) {
				require_once UPSTREAM_PLUGIN_DIR . 'includes/libraries/password_compat-1.0.4/lib/password.php';
			}

			\UpStream\Migrations\Comments_Migration::run();

			$user       = wp_get_current_user();
			$user_roles = (array) $user->roles;

			if (
				count( array_intersect( $user_roles, array( 'administrator', 'upstream_manager' ) ) ) === 0
				&& in_array( 'upstream_client_user', $user_roles, true )
			) {
				add_filter( 'admin_init', array( $this, 'limit_client_users_admin_access' ) );
				add_filter( 'admin_head', array( $this, 'limit_client_users_menu' ) );
				add_action( 'admin_bar_menu', array( $this, 'limit_client_users_toolbar_items' ), 999 );
			}

			$edit_other_projects_permission_were_removed = (bool) get_option( 'upstream:role_upstream_users:drop_edit_others_projects' );

			if ( ! $edit_other_projects_permission_were_removed ) {
				$role = get_role( 'upstream_user' );

				if ( $role ) {
					$role->remove_cap( 'edit_others_projects' );
				}

				unset( $role );
				update_option( 'upstream:role_upstream_users:drop_edit_others_projects', 1 );
			}

			UpStream_Options_Projects::create_projects_statuses_ids();
			UpStream_Options_Tasks::create_tasks_statuses_ids();
			UpStream_Options_Bugs::create_bugs_statuses_ids();

			Comments::instantiate();

			if ( $this->is_request( 'frontend' ) ) {
				UpStream_Ajax::instantiate();
			}

			do_action( 'upstream_init' );
		}

		/**
		 * Load Localisation files.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'upstream', false, UPSTREAM_PLUGIN_RELATIVE_PATH . '/languages/' );
		}


		/**
		 * Show row meta on the plugin screen.
		 *
		 * @param mixed $links Plugin Row Meta.
		 * @param mixed $file  Plugin Base file.
		 *
		 * @return  array
		 */
		public function plugin_row_meta( $links, $file ) {
			if ( UPSTREAM_PLUGIN_BASENAME === $file ) {
				$row_meta = array(
					'docs'        => sprintf(
						'<a href="%s" title="%s">%s</a>',
						esc_url( 'http://upstreamplugin.com/documentation' ),
						esc_attr__( 'View Documentation', 'upstream' ),
						esc_html__( 'Docs', 'upstream' )
					),
					'quick-start' => sprintf(
						'<a href="%s" title="%s">%s</a>',
						esc_url( 'http://upstreamplugin.com/quick-start-guide' ),
						esc_attr__( 'View Quick Start Guide', 'upstream' ),
						esc_html__( 'Quick Start Guide', 'upstream' )
					),
				);

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}

		/**
		 * Callback called to setup the links to display on the plugins page, besides active/deactivate links.
		 *
		 * @param array $links The list of links to be displayed.
		 *
		 * @return  array
		 * @since   1.11.1
		 * @static
		 */
		public static function handle_action_links( $links ) {
			$links['settings'] = sprintf(
				'<a href="%s" title="%2$s" aria-label="%2$s">%3$s</a>',
				esc_url( admin_url( 'admin.php?page=upstream_general' ) ),
				esc_attr__( 'Open Settings Page', 'upstream' ),
				esc_html__( 'Settings', 'upstream' )
			);

			return $links;
		}

		/**
		 * Ensures the plugins update API's host is whitelisted to WordPress external requests.
		 *
		 * @param boolean $is_allowed Is allowed or not.
		 * @param string  $host Host.
		 * @param string  $url Url.
		 *
		 * @return  boolean
		 * @since   1.11.1
		 * @static
		 */
		public static function allow_external_update_host( $is_allowed, $host, $url ) {
			if ( 'upstreamplugin.com' === $host ) {
				return true;
			}

			return $is_allowed;
		}

		/**
		 * Render additional update info if needed.
		 *
		 * @param array  $plugin_data Plugin metadata.
		 * @param object $response   Metadata about the available plugin update.
		 *
		 * @since   1.12.5
		 * @static
		 *
		 * @see     https://developer.wordpress.org/reference/hooks/in_plugin_update_message-file
		 */
		public static function render_additional_update_info( $plugin_data, $response ) {
			$update_notice_title_html = sprintf(
				'<strong style="font-size: 1.25em; display: block; margin-top: 10px;">%s</strong>',
				esc_html__( 'Update notice:', 'upstream' )
			);

			if ( version_compare( UPSTREAM_VERSION, '1.12.5', '<' ) ) {
				printf(
					esc_html( $update_notice_title_html ) .
					// translators: '%1$s: plugin version, %2$s: capability name, %3$s: UpStream User role'.
					esc_html__(
						'Starting from <strong>%1$s</strong> <code>%2$s</code> capability was removed from <code>%3$s</code> users role.',
						'upstream'
					),
					'v1.12.5',
					'edit_others_projects',
					esc_html__( 'UpStream User', 'upstream' )
				);
			}
		}

		/**
		 * Make sure Recent Comments section on admin Dashboard display only comments
		 * current user is allowed to see from projects he's allowed to access.
		 *
		 * @param array            $query_args Query clauses.
		 * @param WP_Comment_Query $query Current query instance.
		 *
		 * @return  array $queryArgs
		 * @global $pagenow, $wpdb
		 *
		 * @since   1.13.0
		 * @static
		 */
		public static function filter_comments_on_dashboard( $query_args, $query ) {
			global $pagenow;

			if ( is_admin() && 'index.php' === $pagenow && ! upstream_is_user_either_manager_or_admin() ) {
				global $wpdb;

				$query_args['join'] = 'LEFT JOIN ' . $wpdb->prefix . 'posts AS post ON post.ID = ' . $wpdb->prefix . 'comments.comment_post_ID';
				$user               = wp_get_current_user();

				if ( in_array( 'upstream_user', $user->roles, true ) || in_array( 'upstream_client_user', $user->roles, true ) ) {
					$projects = (array) upstream_get_users_projects( $user );

					if ( count( $projects ) === 0 ) {
						$query_args['where'] = '(post.ID = -1)';
					} else {
						$query_args['where']        = "(post.post_type = 'project' AND post.ID IN (" . implode( ', ', array_keys( $projects ) ) . '))';
						$user_can_moderate_comments = user_can( $user, 'moderate_comments' );

						if ( ! $user_can_moderate_comments ) {
							$query_args['where'] .= " AND ( comment_approved = '1' )";
						} else {
							$query_args['where'] .= " AND ( comment_approved = '1' OR comment_approved = '0' )";
						}
					}
				} else {
					$query_args['where'] .= " AND (post.post_type != 'project')";
				}
			}

			return $query_args;
		}
	}
endif;


/**
 * Main instance of UpStream.
 *
 * Returns the main instance of UpStream to prevent the need to use globals.
 *
 * @return UpStream
 * @since  1.0.0
 */
function upstream() {
	return UpStream::instance();
}

upstream();
do_action( 'upstream_run' );
