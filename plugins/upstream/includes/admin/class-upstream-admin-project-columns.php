<?php
/**
 * UpStream_Admin_Project_Columns
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Admin_Project_Columns' ) ) :

	/**
	 * Admin columns
	 *
	 * @version 0.1.0
	 */
	class UpStream_Admin_Project_Columns {

		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		public function __construct() {
			$this->hooks();
			$this->filter_allowed_projects();

			self::$none_tag = '<i style="color: #CCC;">' . __( 'none', 'upstream' ) . '</i>';
		}

		/**
		 * Array of projects ids current user is allowed to access.
		 *
		 * @since   1.12.2
		 * @access  private
		 *
		 * @see     $this->filter_allowed_projects()
		 *
		 * @var     array $allowed_projects
		 */
		private $allowed_projects = array();

		/**
		 * Indicates either user can access all projects due his/her current roles.
		 *
		 * @since   1.12.2
		 * @access  private
		 *
		 * @see     $this->filter_allowed_projects()
		 *
		 * @var     bool $allow_all_projects
		 */
		private $allow_all_projects = false;

		/**
		 * Hooks
		 *
		 * @return void
		 */
		public function hooks() {
			add_filter( 'manage_project_posts_columns', array( $this, 'project_columns' ) );
			add_action( 'manage_project_posts_custom_column', array( $this, 'project_data' ), 10, 2 );

			// sorting.
			add_filter( 'manage_edit-project_sortable_columns', array( $this, 'table_sorting' ) );
			add_filter( 'request', array( $this, 'project_orderby_status' ) );
			add_filter( 'request', array( $this, 'project_orderby_dates' ) );
			add_filter( 'request', array( $this, 'project_orderby_progress' ) );

			// filtering.
			add_action( 'restrict_manage_posts', array( $this, 'table_filtering' ) );
			add_action( 'parse_query', array( $this, 'filter' ) );
		}

		/**
		 * Retrieve all projects current user are allowed to access.
		 * This info is used on filter() method to ensure the user will see only projects he's allowed to see.
		 * We cannot do this check within filter() itself to avoid infinite loops.
		 *
		 * @since   1.12.2
		 *
		 * @see     $this->filter()
		 */
		public function filter_allowed_projects() {
			// Fetch current user.
			$user = wp_get_current_user();

			$this->allow_all_projects = count(
				array_intersect(
					(array) $user->roles,
					array( 'administrator', 'upstream_manager' )
				)
			) > 0;
			if ( ! $this->allow_all_projects ) {
				// Retrieve all projects current user can access.
				$allowed_projects = upstream_get_users_projects( $user );
				// Stores the projects ids so they can be used on filter() function.
				$this->allowed_projects = array_keys( $allowed_projects );
				// Retrieve the global query object.
				global $wp_query;
				// Assign this custom property so we know only this time the query will be filtered based on these ids.
				$wp_query->filter_allowed_projects = true;
			}
		}

		/**
		 * Set columns for project
		 *
		 * @param  mixed $defaults Defaults.
		 */
		public function project_columns( $defaults ) {
			$post_type  = 'project';
			$columns    = array();
			$taxonomies = array();

			/* Get taxonomies that should appear in the manage posts table. */
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$taxonomies = wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name' );

			/* Allow devs to filter the taxonomy columns. */
			$taxonomies = apply_filters(
				"manage_taxonomies_for_upstream_{$post_type}_columns",
				$taxonomies,
				$post_type
			);
			$taxonomies = array_filter( $taxonomies, 'taxonomy_exists' );

			/* Loop through each taxonomy and add it as a column. */
			foreach ( $taxonomies as $taxonomy ) {
				$columns[ 'taxonomy-' . $taxonomy ] = get_taxonomy( $taxonomy )->labels->name;
			}

			$defaults['id']    = __( 'ID', 'upstream' );
			$defaults['owner'] = __( 'Owner', 'upstream' );

			if ( ! upstream_is_clients_disabled() ) {
				$defaults['client'] = __( 'Client', 'upstream' );
			}

			$defaults['start'] = __( 'Start', 'upstream' );
			$defaults['end']   = __( 'End', 'upstream' );

			if ( ! upstream_disable_tasks() ) {
				$defaults['tasks'] = upstream_task_label_plural();
			}

			if ( ! upstream_disable_bugs() ) {
				$defaults['bugs'] = upstream_bug_label_plural();
			}
			$defaults['progress'] = __( 'Progress', 'upstream' );
			$defaults['messages'] = '<div style="text-align: center;"><span class="dashicons dashicons-admin-comments"></span><span class="s-hidden-on-tables">' . __( 'Comments' ) . '</span></div>';

			$defaults = array( 'project-status' => '' ) + $defaults;

			return $defaults;
		}

		/**
		 * None Tag
		 *
		 * @var string
		 */
		private static $none_tag = '';

		/**
		 * Users Cache
		 *
		 * @var array
		 */
		private static $users_cache = array();

		/**
		 * Clients Cache
		 *
		 * @var array
		 */
		private static $clients_cache = array();

		/**
		 * Tasks Statuses
		 *
		 * @var array
		 */
		private static $tasks_statuses = array();

		/**
		 * Bugs Statuses
		 *
		 * @var array
		 */
		private static $bugs_statuses = array();

		/**
		 * Are Tasks Disabled
		 *
		 * @var undefined
		 */
		private static $are_tasks_disabled = null;

		/**
		 * Are Bugs Disabled
		 *
		 * @var undefined
		 */
		private static $are_bugs_disabled = null;

		/**
		 * Project Data
		 *
		 * @param  mixed $column_name Column Name.
		 * @param  mixed $post_id Post Id.
		 * @return void
		 */
		public function project_data( $column_name, $post_id ) {
			if ( 'project-status' === $column_name ) {
				$status = upstream_project_status_color( $post_id );

				if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $post_id, null, 0, 'status', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
					if ( ! empty( $status['status'] ) ) {
						echo '<div title="' . esc_attr( $status['status'] ) . '" style="width: 100%; position: absolute; top: 0px; left: 0px; overflow: hidden; height: 100%; border-left: 2px solid ' . esc_attr( $status['color'] ) . '" class="' . esc_attr( strtolower( $status['status'] ) ) . '"></div>';
					}
				}

				return;
			}

			if ( 'id' === $column_name ) {
				echo esc_html( $post_id );
			}

			if ( 'owner' === $column_name ) {
				$owner_id = (int) upstream_project_owner_id( $post_id );

				if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $post_id, null, 0, 'owner', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
					if ( $owner_id > 0 ) {
						if ( ! isset( self::$users_cache[ $owner_id ] ) ) {
							$user = get_user_by( 'id', $owner_id );
							if ( isset( $user ) && $user ) {
								self::$users_cache[ $user->ID ] = $user->display_name;
							}
							unset( $user );
						}

						if ( isset( self::$users_cache[ $owner_id ] ) ) {
							echo wp_kses_post( self::$users_cache[ $owner_id ] );
						} else {
							echo wp_kses_post( self::$none_tag );
						}
					} else {
						echo wp_kses_post( self::$none_tag );
					}
				} else {
					echo '<span class="upstream-label-tag" style="background-color:#666;color:#fff">(hidden)</span>';
				}

				return;
			}

			if ( 'client' === $column_name ) {
				$client_id = (int) upstream_project_client_id( $post_id );

				if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $post_id, null, 0, 'client', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
					if ( $client_id > 0 ) {
						if ( ! isset( $clients_cache[ $client_id ] ) ) {
							$client                            = get_post( $client_id );
							self::$clients_cache[ $client_id ] = $client->post_title;
							unset( $client );
						}

						echo wp_kses_post( self::$clients_cache[ $client_id ] );
					} else {
						echo wp_kses_post( self::$none_tag );
					}
				} else {
					echo '<span class="upstream-label-tag" style="background-color:#666;color:#fff">(hidden)</span>';
				}

				return;
			}

			if ( 'start' === $column_name ) {
				$start_date = (int) upstream_project_start_date( $post_id );

				if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $post_id, null, 0, 'start', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
					if ( $start_date > 0 ) {
						echo '<span class="start-date">' . esc_html( upstream_format_date( $start_date ) ) . '</span>';
					} else {
						echo wp_kses_post( self::$none_tag );
					}
				} else {
					echo '<span class="upstream-label-tag" style="background-color:#666;color:#fff">(hidden)</span>';
				}

				return;
			}

			if ( 'end' === $column_name ) {
				$end_date = (int) upstream_project_end_date( $post_id );

				if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $post_id, null, 0, 'end', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {

					if ( $end_date > 0 ) {
						echo '<span class="end-date">' . esc_html( upstream_format_date( $end_date ) ) . '</span>';
					} else {
						echo wp_kses_post( self::$none_tag );
					}
				} else {
					echo '<span class="upstream-label-tag" style="background-color:#666;color:#fff">(hidden)</span>';
				}

				return;
			}

			if ( 'tasks' === $column_name ) {
				if ( null === self::$are_tasks_disabled ) {
					self::$are_tasks_disabled = (bool) upstream_are_tasks_disabled();
				}

				if ( ! self::$are_tasks_disabled ) {
					$counts = upstream_project_tasks_counts( $post_id );

					if ( empty( $counts ) ) {
						echo wp_kses_post( self::$none_tag );
					} else {
						if ( empty( self::$tasks_statuses ) ) {
							self::$tasks_statuses = upstream_get_tasks_statuses();
						}

						foreach ( $counts as $task_status_id => $count ) {
							$task_status = isset( self::$tasks_statuses[ $task_status_id ] )
							? self::$tasks_statuses[ $task_status_id ]
							: array(
								'color' => '#aaaaaa',
								'name'  => $task_status_id,
							);

							printf(
								'<span class="status %s" style="border-color: %s">
                                <span class="count" style="background-color: %2$s">%3$s</span> %4$s
                            	</span>',
								esc_attr( strtolower( $task_status['name'] ) ),
								isset( $task_status['color'] ) ? esc_attr( $task_status['color'] ) : esc_attr( '' ),
								esc_html( $count ),
								esc_html( $task_status['name'] )
							);
						}
					}
				}

				return;
			}

			if ( 'bugs' === $column_name ) {
				if ( null === self::$are_bugs_disabled ) {
					self::$are_bugs_disabled = (bool) upstream_are_bugs_disabled();
				}

				if ( ! self::$are_bugs_disabled ) {
					$counts = upstream_project_bugs_counts( $post_id );
					if ( empty( $counts ) ) {
						echo wp_kses_post( self::$none_tag );
					} else {
						if ( empty( self::$bugs_statuses ) ) {
							self::$bugs_statuses = upstream_get_bugs_statuses();
						}

						foreach ( $counts as $bug_status_id => $count ) {
							$bug_status = isset( self::$bugs_statuses[ $bug_status_id ] )
							? self::$bugs_statuses[ $bug_status_id ]
							: array(
								'color' => '#aaaaaa',
								'name'  => $bug_status_id,
							);

							printf(
								'<span class="status %s" style="border-color: %s">
                                <span class="count" style="background-color: %2$s">%3$s</span> %4$s
                            </span>',
								esc_attr( strtolower( $bug_status['name'] ) ),
								esc_attr( $bug_status['color'] ),
								esc_html( $count ),
								esc_html( $bug_status['name'] )
							);
						}
					}
				}

				return;
			}

			if ( 'progress' === $column_name ) {
				$progress = (int) upstream_project_progress( $post_id );

				if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $post_id, null, 0, 'progress', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
					echo '<div style="text-align: center;">' . esc_html( $progress ) . '%</div>';
				} else {
					echo '<span class="upstream-label-tag" style="background-color:#666;color:#fff">(hidden)</span>';
				}

				return;
			}

			if ( 'messages' === $column_name ) {
				echo '<div style="text-align: center;">';

				$count = (int) get_project_comments_count( $post_id );
				if ( $count > 0 ) {
					echo '<a href="' . esc_url( get_edit_post_link( $post_id ) . '#_upstream_project_discussions' ) . '"><span>' . esc_html( $count ) . '</a></span>';
				} else {
					echo wp_kses_post( self::$none_tag );
				}

				echo '</div>';
			}
		}

		/**
		 * Sorting the table
		 *
		 * @param  mixed $columns Columns.
		 */
		public function table_sorting( $columns ) {
			$columns['project-status'] = 'project-status';
			$columns['start']          = 'start';
			$columns['end']            = 'end';
			$columns['progress']       = 'progress';

			return $columns;
		}

		/**
		 * Project Orderby Status
		 *
		 * @param  mixed $vars Vars.
		 */
		public function project_orderby_status( $vars ) {
			if ( isset( $vars['orderby'] ) && 'project-status' === $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => '_upstream_project_status',
						'orderby'  => 'meta_value',
					)
				);
			}

			return $vars;
		}

		/**
		 * Project Orderby Dates
		 *
		 * @param  mixed $vars Vars.
		 */
		public function project_orderby_dates( $vars ) {
			if ( isset( $vars['orderby'] ) && 'start' === $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => '_upstream_project_start',
						'orderby'  => 'meta_value_num',
					)
				);
			}

			if ( isset( $vars['orderby'] ) && 'end' == $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => '_upstream_project_end',
						'orderby'  => 'meta_value_num',
					)
				);
			}

			return $vars;
		}

		/**
		 * Project Orderby Progress
		 *
		 * @param  mixed $vars Vars.
		 */
		public function project_orderby_progress( $vars ) {
			if ( isset( $vars['orderby'] ) && 'progress' === $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => '_upstream_project_progress',
						'orderby'  => 'meta_value_num',
					)
				);
			}

			return $vars;
		}

		/**
		 * Table Filtering
		 *
		 * @return void
		 */
		public function table_filtering() {
			global $pagenow;

			$is_multisite = is_multisite();
			$server_data  = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();
			$get_data     = isset( $_GET ) ? wp_unslash( $_GET ) : array();

			if ( $is_multisite ) {
				$current_page = isset( $server_data['PHP_SELF'] ) ? preg_replace(
					'/^\/wp-admin\//i',
					'',
					sanitize_text_field( $server_data['PHP_SELF'] )
				) : '';
			} else {
				$current_page = $pagenow;
			}

			$post_type = isset( $get_data['post_type'] ) ? sanitize_text_field( $get_data['post_type'] ) : null;
			if ( 'edit.php' === $current_page
				&& 'project' === $post_type
			) {
				$project_options = get_option( 'upstream_projects' );
				$statuses        = $project_options['statuses'];
				unset( $project_options );

				$selected_status = isset( $get_data['project-status'] ) ? sanitize_text_field( $get_data['project-status'] ) : ''; ?>
				<select name="project-status" id="project-status" class="postform">
					<option value="">
						<?php
						printf(
							// translators: %s: statuses label.
							esc_html__( 'Show all %s', 'upstream' ),
							esc_html__( 'statuses', 'upstream' )
						);
						?>
					</option>
					<?php foreach ( $statuses as $status ) : ?>
						<option value="<?php echo esc_attr( $status['name'] ); ?>" 
							<?php
							selected(
								$selected_status,
								$status['name']
							);
							?>
						>
							<?php echo esc_html( $status['name'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<?php
				// Filter by Project Owner.
				$users = upstream_admin_get_all_project_users();

				$selected_owner = isset( $get_data['project-owner'] ) ? absint( $get_data['project-owner'] ) : -1;
				?>
				<select name="project-owner" id="project-owner" class="postform">
					<option value="">
						<?php
						printf(
							// translators: %s: owners label.
							esc_html__( 'Show all %s', 'upstream' ),
							esc_html__( 'owners', 'upstream' )
						);
						?>
					</option>
					<?php foreach ( $users as $owner_id => $owner_name ) : ?>
						<option
							value="<?php echo esc_attr( $owner_id ); ?>" <?php echo esc_attr( $selected_owner === $owner_id ? ' selected' : '' ); ?>>
							<?php echo esc_html( $owner_name ); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<?php
				if ( ! upstream_is_clients_disabled() ) {
					// Filter by Project Client.
					$clients            = upstream_wp_get_clients();
					$selected_client_id = isset( $get_data['project-client'] ) ? absint( $get_data['project-client'] ) : -1;
					?>
					<select name="project-client" id="project-client" class="postform">
						<option value="">
							<?php
							printf(
								// translators: %s: upstream_client_label_plural.
								esc_html__( 'Show all %s', 'upstream' ),
								esc_html( upstream_client_label_plural( true ) )
							);
							?>
						</option>
						<?php foreach ( $clients as $client_id => $client_name ) : ?>
							<option
									value="<?php echo esc_attr( $client_id ); ?>" <?php echo esc_attr( $selected_client_id === (int) $client_id ? ' selected' : '' ); ?>>
								<?php echo esc_html( $client_name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php
				}
			}
		}

		/**
		 * Filter
		 *
		 * @param  mixed $query Query.
		 * @return void
		 */
		public function filter( $query ) {
			$server_data = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();
			$get_data    = isset( $_GET ) ? wp_unslash( $_GET ) : array();

			$is_admin = is_admin();
			if ( ! $is_admin ) {
				return;
			}

			$post_type = isset( $get_data['post_type'] ) ? sanitize_text_field( $get_data['post_type'] ) : 'post';
			if ( 'project' !== $post_type ) {
				return;
			}

			$is_multisite = is_multisite();
			if ( $is_multisite ) {
				$current_page = isset( $server_data['PHP_SELF'] ) ? preg_replace(
					'/^\/wp-admin\//i',
					'',
					sanitize_text_field( $server_data['PHP_SELF'] )
				) : '';
			} else {
				global $pagenow;

				$current_page = $pagenow;
			}

			if ( 'edit.php' !== $current_page ) {
				return;
			}

			// RSD: moved this for item 886/887.
			if ( ! $this->allow_all_projects && $query->filter_allowed_projects ) {
				$query->query_vars              = array_merge(
					$query->query_vars,
					array(
						'post__in' => count( $this->allowed_projects ) === 0 ? array( 'making_sure_no_project_is_returned' ) : $this->allowed_projects,
					)
				);
				$query->filter_allowed_projects = null;
			}

			$should_exit = true;
			$filters     = array( 'status', 'owner', 'client' );

			foreach ( $filters as $filter_name ) {
				$filter_key = 'project-' . $filter_name;

				// just check if it is there.
				if ( isset( $get_data[ $filter_key ] ) && ! empty( $get_data[ $filter_key ] ) ) {
					$should_exit = false;
				}
			}

			if ( $should_exit ) {
				return;
			}

			$meta_query = array();

			$project_status = isset( $get_data['project-status'] ) ? sanitize_text_field( $get_data['project-status'] ) : '';
			if ( strlen( $project_status ) > 0 ) {
				$meta_query[] = array(
					'key'     => '_upstream_project_status',
					'value'   => $project_status,
					'compare' => '=',
				);
			}

			$project_owner_id = isset( $get_data['project-owner'] ) ? absint( $get_data['project-owner'] ) : 0;
			if ( $project_owner_id > 0 ) {
				$meta_query[] = array(
					'key'     => '_upstream_project_owner',
					'value'   => $project_owner_id,
					'compare' => '=',
				);
			}

			$project_client_id = isset( $get_data['project-client'] ) ? absint( $get_data['project-client'] ) : 0;
			if ( $project_client_id > 0 ) {
				$meta_query[] = array(
					'key'     => '_upstream_project_client',
					'value'   => $project_client_id,
					'compare' => '=',
				);
			}

			$meta_query_count = count( $meta_query );
			if ( $meta_query_count > 0 ) {
				if ( 1 === $meta_query_count ) {
					$query->query_vars['meta_key']   = $meta_query[0]['key'];
					$query->query_vars['meta_value'] = $meta_query[0]['value'];
				} else {
					$meta_query['relation'] = 'AND';

					$query->query_vars['meta_query'] = $meta_query;
				}

				$query->meta_query = $meta_query;
			}
		}
	}

	new UpStream_Admin_Project_Columns();

endif;
