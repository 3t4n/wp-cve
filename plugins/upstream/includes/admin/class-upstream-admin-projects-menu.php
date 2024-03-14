<?php
/**
 * Setup menus in WP admin.
 *
 * @author   UpStream
 * @category Admin
 * @package  UpStream/Admin
 * @version  1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Admin_Projects_Menu' ) ) :

	/**
	 * UpStream_Admin_Menus Class.
	 */
	class UpStream_Admin_Projects_Menu {

		/**
		 * User Is Up Stream User
		 *
		 * @var undefined
		 */
		private static $user_is_up_stream_user = null;

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			if ( null === self::$user_is_up_stream_user ) {
				$user                         = wp_get_current_user();
				self::$user_is_up_stream_user = count(
					array_intersect(
						$user->roles,
						array( 'administrator', 'upstream_manager' )
					)
				) === 0;
			}

			add_action( 'admin_menu', array( $this, 'custom_menu_items' ), 9 );
			add_filter( 'custom_menu_order', array( $this, 'submenu_order' ) );
			add_action( 'admin_head', array( $this, 'hide_add_new_project_button_if_needed' ) );

			// highlight proper admin menu & submenus.
			add_filter( 'parent_file', array( $this, 'highlight_parent_file' ) );
			add_filter( 'submenu_file', array( $this, 'highlight_submenu_file' ) );
		}

		/**
		 * Hide Add New Project Button If Needed
		 *
		 * @return void
		 */
		public function hide_add_new_project_button_if_needed() {
			if ( is_admin() ) {
				global $pagenow;

				$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

				if ( 'edit.php' === $pagenow && isset( $get_data['post_type'] ) && 'project' === sanitize_text_field( $get_data['post_type'] ) ) {
					if ( self::$user_is_up_stream_user ) {
						echo '<style type="text/css">.page-title-action:not(.upstream-button) { display: none; }</style>';

						if ( upstream_override_access_object( false, UPSTREAM_ITEM_TYPE_PROJECT, 0, null, 0, UPSTREAM_PERMISSIONS_ACTION_CREATE ) ) {
							?>
							<script>
								jQuery(function(){
									jQuery("body.post-type-project .wrap h1").append('<a href="<?php print esc_attr( get_post_type_archive_link( 'project' ) ); ?>" class="upstream-button page-title-action"><?php esc_html_e( 'New ', 'upstream' ) . esc_html( upstream_project_label() ); ?></a>');
								});
							</script>
							<?php
						}
						?>
						<script>
							jQuery(function(){
								jQuery(".row-actions span.inline").remove();
								jQuery(".row-actions span.edit").remove();
							});
						</script>
						<?php
					}
				}
			}
		}

		/**
		 * Add menu item.
		 */
		public function custom_menu_items() {
			add_submenu_page(
				'edit.php?post_type=project',
				upstream_client_label_plural(),
				upstream_client_label_plural(),
				'edit_clients',
				'edit.php?post_type=client'
			);

			add_submenu_page(
				'edit.php?post_type=project',
				upstream_milestone_category_label_plural(),
				upstream_milestone_category_label_plural(),
				'edit_projects',
				'edit-tags.php?taxonomy=upst_milestone_category&post_type=upst_milestone'
			);
		}

		/**
		 * Submenu Order
		 *
		 * @param  mixed $menu Menu.
		 */
		public function submenu_order( $menu ) {
			global $submenu;

			$sub_menu_identifier = 'edit.php?post_type=project';
			if ( isset( $submenu[ $sub_menu_identifier ] )
				&& ! empty( $submenu[ $sub_menu_identifier ] )
			) {
				$upstream_submenu      = &$submenu[ $sub_menu_identifier ];
				$new_up_stream_submenu = array();
				$search_submenu_item   = function ( $needle ) use ( &$upstream_submenu ) {
					foreach ( $upstream_submenu as $submenu_index => $submenu ) {
						$regexp = '/' . $needle . '/i';
						if ( preg_match( $regexp, $submenu[2] ) ) {
							return $submenu;
						}
					}

					return null;
				};

				$submenu_projects = $search_submenu_item( '^edit\.php\?post_type=project$' );
				if ( null !== $submenu_projects ) {
					$new_up_stream_submenu[] = $submenu_projects;
				}
				unset( $submenu_projects );

				if ( self::$user_is_up_stream_user ) {
					$submenu_tasks = $search_submenu_item( '^tasks$' );
					if ( null !== $submenu_tasks
						&& strpos( $submenu_tasks[0], 'update-count' ) !== false
					) {
						$new_up_stream_submenu[] = $submenu_tasks;
					}
					unset( $submenu_tasks );

					$submenu_bugs = $search_submenu_item( '^bugs$' );
					if ( null !== $submenu_bugs
						&& strpos( $submenu_bugs[0], 'update-count' ) !== false
					) {
						$new_up_stream_submenu[] = $submenu_bugs;
					}
					unset( $submenu_bugs );
				} else {
					$are_categories_enabled = ! upstream_is_project_categorization_disabled();
					$are_clients_enabled    = ! upstream_is_clients_disabled();
					$milestones_enabled     = ! upstream_disable_milestones();
					$milestone_tags_enabled = ! upstream_disable_milestone_categories();

					if ( $milestones_enabled ) {
						$submenu_milestones = $search_submenu_item( '^edit\.php\?post_type=upst_milestone' );
						if ( null !== $submenu_milestones ) {
							$new_up_stream_submenu[] = $submenu_milestones;
						}
						unset( $submenu_milestones );
					}

					if ( $milestone_tags_enabled ) {
						$submenu_milestone_tags = $search_submenu_item( '^edit-tags\.php\?taxonomy=upst_milestone_category&post_type=upst_milestone' );
						if ( null !== $submenu_milestone_tags ) {
							$new_up_stream_submenu[] = $submenu_milestone_tags;
						}
						unset( $submenu_milestone_tags );
					}

					$submenu_tasks = $search_submenu_item( '^tasks$' );
					if ( null !== $submenu_tasks ) {
						$new_up_stream_submenu[] = $submenu_tasks;
					}
					unset( $submenu_tasks );

					$submenu_bugs = $search_submenu_item( '^bugs$' );
					if ( null !== $submenu_bugs ) {
						$new_up_stream_submenu[] = $submenu_bugs;
					}
					unset( $submenu_bugs );

					if ( $are_clients_enabled ) {
						$submenu_clients = $search_submenu_item( '^edit\.php\?post_type=client$' );
						if ( null !== $submenu_clients ) {
							$new_up_stream_submenu[] = $submenu_clients;
						}
						unset( $submenu_clients );
					}

					if ( $are_categories_enabled ) {
						$submenu_categories = $search_submenu_item( '^edit\-tags\.php\?taxonomy\=project_category\&amp;post_type=project$' );
						if ( null !== ! $submenu_categories ) {
							$new_up_stream_submenu[] = $submenu_categories;
						}
						unset( $submenu_categories );

						$submenu_tags = $search_submenu_item( '^edit\-tags\.php\?taxonomy\=upstream_tag\&amp;post_type=project$' );
						if ( null !== ! $submenu_tags ) {
							$new_up_stream_submenu[] = $submenu_tags;
						}
						unset( $submenu_tags );
					}
				}

				$upstream_submenu = apply_filters( 'upstream:custom_menu_order', $new_up_stream_submenu );
			}

			return $menu;
		}

		/**
		 * Highlight Parent File
		 *
		 * @param  mixed $parent_file Parent File.
		 */
		public function highlight_parent_file( $parent_file ) {
			global $current_screen;

			// milestone_categories submenu.
			if ( isset( $current_screen->taxonomy ) &&
				'upst_milestone_category' === $current_screen->taxonomy
			) {
				$parent_file = 'edit.php?post_type=project';
			}

			// clients submenu.
			if ( isset( $current_screen->post_type ) &&
				'post' === $current_screen->base &&
				'client' === $current_screen->post_type
			) {
				$parent_file = 'edit.php?post_type=project';
			}

			return $parent_file;
		}

		/**
		 * Highlight Submenu File
		 *
		 * @param  mixed $submenu_file Submenu File.
		 */
		public function highlight_submenu_file( $submenu_file ) {
			global $current_screen;

			// milestone_categories submenu.
			if ( isset( $current_screen->taxonomy ) &&
				'upst_milestone_category' === $current_screen->taxonomy
			) {
				$submenu_file = 'edit-tags.php?taxonomy=upst_milestone_category&post_type=upst_milestone';
			}

			// clients submenu.
			if ( isset( $current_screen->post_type ) &&
				'post' === $current_screen->base &&
				'client' === $current_screen->post_type
			) {
				$submenu_file = 'edit.php?post_type=client';
			}

			return $submenu_file;
		}
	}

endif;

return new UpStream_Admin_Projects_Menu();
