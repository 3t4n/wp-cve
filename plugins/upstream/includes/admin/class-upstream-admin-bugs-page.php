<?php
/**
 * Upstream_Admin_Bugs_Page
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Upstream_Admin_Bugs_Page
 */
class Upstream_Admin_Bugs_Page {

	/**
	 * Instance
	 *
	 * @var mixed
	 */
	public static $instance;

	/**
	 * Customer WP_List_Table object
	 *
	 * @var mixed
	 */
	public $bugs_obj;


	/**
	 * Construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
	}

	/**
	 * Singleton instance
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set Screen
	 *
	 * @param  mixed $status Status.
	 * @param  mixed $option Option.
	 * @param  mixed $value Value.
	 */
	public function set_screen( $status, $option, $value ) {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$nonce     = isset( $post_data['screenoptionnonce'] ) ? $post_data['screenoptionnonce'] : null;

		if ( 'upstream_completed_bugs' === $option && wp_verify_nonce( $nonce, 'screen-options-nonce' ) ) {
			$value = sanitize_text_field( $post_data['upstream_hide_completed'] );
		}

		return $value;
	}

	/**
	 * Screen options
	 */
	public function screen_option() {
		$option = 'per_page';
		$args   = array(
			'label'   => upstream_bug_label_plural(),
			'default' => 10,
			'option'  => 'bugs_per_page',
		);

		add_screen_option( $option, $args );

		$screen = get_current_screen();
		if ( 'project_page_bugs' === $screen->id ) {
			$this->bugs_obj = new Upstream_Bug_List();
		}
	}

	/**
	 * Plugin Menu
	 *
	 * @return void
	 */
	public function plugin_menu() {
		if ( ! upstream_is_user_either_manager_or_admin() /* RSD: removed for new perms && $count <= 0 */ ) {
			return;
		}

		// counter number notification.
		$count       = (int) upstream_count_assigned_to_open( 'bugs' );
		$count_notif = $count ? " <span class='update-plugins count-1'><span class='update-count'>$count</span></span>" : '';

		// add_submenu_page hook.
		$hook = add_submenu_page(
			'edit.php?post_type=project',
			upstream_bug_label_plural(),
			upstream_bug_label_plural() . $count_notif,
			'edit_projects',
			'bugs',
			array( $this, 'plugin_settings_page' )
		);

		add_action( "load-$hook", array( $this, 'screen_option' ) );
	}

	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( upstream_bug_label_plural() ); ?></h1>

			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">
					<?php $this->bugs_obj->views(); ?>
					<?php
					// $this->bugs_obj->display_tablenav( 'top' );
					?>
					<?php
					// $this->bugs_obj->search_box('search', 'search_id');
					?>
					<form method="post">
						<?php
						$this->bugs_obj->prepare_items();
						$this->bugs_obj->display();
						?>
					</form>
				</div>
			</div>

			<br class="clear">
		</div>
		<?php
	}
}

/**
 * Class Upstream_Bug_List
 */
class Upstream_Bug_List extends WP_List_Table {

	/**
	 * Bugs Statuses
	 *
	 * @var mixed
	 */
	private static $bugs_statuses;

	/**
	 * Bugs Severities
	 *
	 * @var mixed
	 */
	private static $bugs_severities;

	/**
	 * Bug Label
	 *
	 * @var string
	 */
	public $bug_label = '';

	/**
	 * Bug Label Plural
	 *
	 * @var string
	 */
	public $bug_label_plural = '';

	/**
	 * Columns
	 *
	 * @var array
	 */
	private $columns = array();

	/*
	 * Displays the filtering links above the table
	 */

	/** Class constructor */
	public function __construct() {
		$this->bug_label        = upstream_bug_label();
		$this->bug_label_plural = upstream_bug_label_plural();

		parent::__construct(
			array(
				'singular' => $this->bug_label,
				'plural'   => $this->bug_label_plural,
				'ajax'     => false, // does this table support ajax?
			)
		);
	}

	/**
	 * Get Columns
	 */
	public function get_columns() {
		$columns = apply_filters(
			'upstream_admin_bug_page_columns',
			array(
				'title'       => $this->bug_label,
				'project'     => upstream_project_label(),
				'id'          => __( 'ID', 'upstream' ),
				'assigned_to' => __( 'Assigned To', 'upstream' ),
				'due_date'    => __( 'Due Date', 'upstream' ),
				'status'      => __( 'Status', 'upstream' ),
				'severity'    => __( 'Severity', 'upstream' ),
			)
		);

		return $columns;
	}

	/**
	 * Get Views
	 */
	public function get_views() {
		$views        = array();
		$request_data = isset( $_REQUEST ) ? wp_unslash( $_REQUEST ) : array();

		if ( ! empty( $request_data['status'] ) ) {
			$current = sanitize_text_field( $request_data['status'] );
		} elseif ( ! empty( $request_data['view'] ) ) {
			$current = sanitize_text_field( $request_data['view'] );
		} else {
			$current = 'all';
		}

		// All link.
		$all_class    = ( 'all' === $current ? ' class="current"' : '' );
		$all_url      = remove_query_arg( array( 'status', 'view' ) );
		$all_count    = upstream_count_total( 'bugs' );
		$views['all'] = "<a href='" . esc_url( $all_url ) . "' {$all_class} >" . __(
			'All',
			'upstream'
		) . "</a>({$all_count})";

		// Mine link.
		$mine_class    = ( 'mine' === $current ? ' class="current"' : '' );
		$mine_url      = add_query_arg(
			array(
				'view'   => 'mine',
				'status' => false,
			)
		);
		$mine_count    = upstream_count_assigned_to( 'bugs' );
		$views['mine'] = "<a href='" . esc_url( $mine_url ) . "' {$mine_class} >" . __(
			'Mine',
			'upstream'
		) . "</a>({$mine_count})";

		// links for other statuses.
		$option   = get_option( 'upstream_bugs' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
		$counts   = self::count_statuses();

		if ( $statuses ) {
			// check if user wants to hide completed bugs.
			$hide = get_user_option( 'upstream_completed_bugs', get_current_user_id() );

			foreach ( $statuses as $status ) {
				if ( 'on' === $hide && self::hide_completed( $status['name'] ) ) {
					continue;
				}

				$stati           = strtolower( $status['id'] );
				$class           = ( $current == $stati ? ' class="current"' : '' );
				$url             = add_query_arg(
					array(
						'status' => $stati,
						'view'   => false,
						'paged'  => false,
					)
				);
				$count           = isset( $counts[ $status['name'] ] ) ? $counts[ $status['name'] ] : 0;
				$views[ $stati ] = "<a href='" . esc_url( $url ) . "' {$class} >{$status['name']}</a>({$count})";
			}
		}

		return $views;
	}

	/**
	 * Returns the count of each status
	 *
	 * @return array
	 */
	public static function count_statuses() {
		$statuses = upstream_get_bugs_statuses();
		$rowset   = self::get_bugs();

		$data = array();

		if ( empty( $rowset ) ) {
			return $data;
		}

		foreach ( $rowset as $row ) {
			if ( isset( $row['status'] )
				&& ! empty( $row['status'] )
				&& isset( $statuses[ $row['status'] ] )
			) {
				$status_title = $statuses[ $row['status'] ]['name'];
				if ( isset( $data[ $status_title ] ) ) {
					$data[ $status_title ]++;
				} else {
					$data[ $status_title ] = 1;
				}
			}
		}

		return $data;
	}

	/**
	 * Retrieve all bugs from all projects.
	 *
	 * @return array
	 */
	public static function get_bugs() {
		$args = array(
			'post_type'      => 'project',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => '_upstream_project_bugs',
					'compare' => 'EXISTS',
				),
			),
		);

		// The Query.
		$the_query = new WP_Query( $args );

		// The Loop.
		if ( ! $the_query->have_posts() ) {
			return;
		}

		$bugs = array();
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			$post_id = get_the_ID();

			if ( upstream_are_bugs_disabled( $post_id ) ) {
				continue;
			}

			$meta  = get_post_meta( $post_id, '_upstream_project_bugs', true );
			$owner = get_post_meta( $post_id, '_upstream_project_owner', true );

			if ( $meta ) :
				foreach ( $meta as $meta_val => $bug ) {
					// set up the data for each column.
					$bug['title']       = isset( $bug['title'] ) ? $bug['title'] : __( '(no title)', 'upstream' );
					$bug['project']     = get_the_title( $post_id );
					$bug['owner']       = $owner;
					$bug['assigned_to'] = isset( $bug['assigned_to'] ) ? $bug['assigned_to'] : 0;
					$bug['due_date']    = isset( $bug['due_date'] ) ? $bug['due_date'] : '';
					$bug['status']      = isset( $bug['status'] ) ? $bug['status'] : '';
					$bug['severity']    = isset( $bug['severity'] ) ? $bug['severity'] : '';
					$bug['description'] = isset( $bug['description'] ) ? $bug['description'] : '';
					$bug['project_id']  = $post_id; // add the post id to each bug.

					// check if we can add the bug to the list.
					$user_id = get_current_user_id();
					// $option     = get_option( 'upstream_bugs' );
					// $hide       = $option['hide_closed'];

					// // check if user wants to hide completed bugs
					// if ( $hide == 'on' && self::hide_completed( $bug['status'] ) )
					// continue;

					$bugs[] = $bug;
				}

				endif;

		endwhile;

		return $bugs;
	}


	/**
	 * Hide Completed
	 *
	 * @param  mixed $status Status.
	 */
	public static function hide_completed( $status ) {
		$option   = get_option( 'upstream_bugs' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';

		if ( ! $statuses ) {
			return false;
		}

		$types = wp_list_pluck( $statuses, 'type', 'name' );

		foreach ( $types as $key => $value ) {
			if ( $key === $status && 'open' === $value ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Extra Tablenav
	 *
	 * @param  mixed $which Which.
	 * @return void
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}

		$request_data = isset( $_REQUEST ) ? wp_unslash( $_REQUEST ) : array();
		$get_data     = isset( $_GET ) ? wp_unslash( $_GET ) : array();
		?>

		<div class="alignleft actions">

			<?php
			if ( ! is_singular() ) {
				$projects = (array) $this->get_projects_unique();
				if ( ! empty( $projects ) ) {
					?>

					<select name='project' id='project' class='postform'>
						<option value=''>
						<?php
						printf(
							// translators: %s: tablenav name.
							esc_html__( 'Show all %s', 'upstream' ),
							'projects'
						);
						?>
							</option>
						<?php
						foreach ( $projects as $project_id => $title ) {
							?>
							<option
								value="<?php echo esc_attr( $project_id ); ?>"
								<?php
								isset( $get_data['project'] ) ? selected(
									sanitize_text_field( $get_data['project'] ),
									$project_id
								) : '';
								?>
							><?php echo esc_html( $title ); ?></option>
							<?php
						}
						?>
					</select>

					<?php
				}

				$users = (array) $this->get_assigned_to_unique();
				if ( count( $users ) > 0 ) {
					$assigned_to = isset( $request_data['assigned_to'] ) ? intval( $request_data['assigned_to'] ) : 0;
					?>
					<select id="assigned_to" name="assigned_to" class="postform">
						<option value="">
						<?php
						printf(
							// translators: %s: assigned_to name.
							esc_html__( 'Show all %s', 'upstream' ),
							'users'
						);
						?>
							</option>
						<?php foreach ( $users as $user_id => $user_name ) : ?>
							<option
									value="<?php echo esc_attr( $user_id ); ?>" <?php echo $assigned_to === $user_id ? 'selected' : ''; ?>><?php echo esc_html( $user_name ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php
				}

				$status = self::getBugsStatuses();
				if ( ! empty( $status ) ) {
					?>

					<select name='status' id='status' class='postform'>
						<option value=''>
						<?php
						printf(
							// translators: %s: status name.
							esc_html__( 'Show all %s', 'upstream' ),
							'statuses'
						);
						?>
							</option>
						<?php
						foreach ( $status as $stati ) {
							if ( is_array( $stati ) ) {
								$status_title = $stati['name'];
								$status_id    = $stati['id'];
							} else {
								$status_title = $stati;
								$status_id    = $stati;
							}
							?>
							<option value="<?php echo esc_attr( $status_id ); ?>" 
								<?php
								isset( $get_data['status'] ) ? selected(
									sanitize_text_field( $get_data['status'] ),
									$status_title
								) : '';
								?>
							><?php echo esc_html( $status_title ); ?></option>
							<?php
						}
						?>
					</select>

					<?php
				}

				$severities = self::getBugsSeverities();
				if ( ! empty( $severities ) ) {
					?>

					<select name='severity' id='severity' class='postform'>
						<option value=''>
							<?php
							printf(
								// translators: %s severity label.
								esc_html__( 'Show all %s', 'upstream' ),
								'severities'
							);
							?>
							</option>
						<?php
						foreach ( $severities as $severity ) {
							if ( is_array( $severity ) ) {
								$severity_title = $severity['name'];
								$severity_id    = $severity['id'];
							} else {
								$severity_title = $severity;
								$severity_id    = $severity;
							}
							?>
							<option
									value="<?php echo esc_attr( $severity_id ); ?>" 
									<?php
									isset( $get_data['severity'] ) ? selected(
										sanitize_text_field( $get_data['severity'] ),
										$severity_title
									) : '';
									?>
							><?php echo esc_html( $severity_title ); ?></option>
							<?php
						}
						?>
					</select>

					<?php
				}

				submit_button( __( 'Filter', 'upstream' ), 'button', 'filter', false );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Get Projects Unique
	 *
	 * @return void
	 */
	private function get_projects_unique() {
		$bugs = self::get_bugs();
		if ( empty( $bugs ) ) {
			return;
		}

		$items = wp_list_pluck( $bugs, 'project', 'project_id' );
		$items = array_unique( $items );
		$items = array_filter( $items );

		return $items;
	}

	/**
	 * Get Assigned To Unique
	 *
	 * @return void
	 */
	private function get_assigned_to_unique() {
		$bugs = (array) self::get_bugs();
		if ( count( $bugs ) === 0 ) {
			return;
		}

		$rowset = wp_list_pluck( $bugs, 'assigned_to' );

		$data = array();

		$set_user_name_into_data = function ( $user_id ) use ( &$data ) {
			if ( ! isset( $data[ $user_id ] ) ) {
				$data[ $user_id ] = upstream_users_name( $user_id );
			}
		};

		foreach ( $rowset as $assignees ) {
			if ( ! is_array( $assignees ) ) {
				$assignees = (array) $assignees;
			}

			$assignees = array_unique( array_filter( array_map( 'intval', $assignees ) ) );
			foreach ( $assignees as $assignee_id ) {
				$set_user_name_into_data( $assignee_id );
			}
		}

		return $data;
	}

	/**
	 * Get Bugs Statuses
	 *
	 * @return void
	 */
	private static function getBugsStatuses() {
		if ( empty( self::$bugs_statuses ) ) {
			$rowset = self::get_bugs();
			if ( empty( $rowset ) || count( $rowset ) === 0 ) {
				return;
			}

			$statuses = upstream_get_bugs_statuses();

			$data = array();

			foreach ( $rowset as $row ) {
				if ( ! empty( $row['status'] )
					 && isset( $row['status'] )
				) {
					$data[ $row['status'] ] = isset( $statuses[ $row['status'] ] )
						? $statuses[ $row['status'] ]
						: $row['status'];
				}
			}

			self::$bugs_statuses = $data;
		} else {
			$data = self::$bugs_statuses;
		}

		return $data;
	}

	/**
	 * Get Bugs Severities
	 *
	 * @return void
	 */
	private static function getBugsSeverities() {
		if ( empty( self::$bugs_severities ) ) {
			$rowset = self::get_bugs();
			if ( empty( $rowset ) || count( $rowset ) === 0 ) {
				return;
			}

			$statuses = upstream_get_bugs_severities();

			$data = array();

			foreach ( $rowset as $row ) {
				if ( ! empty( $row['severity'] )
					 && isset( $row['severity'] )
				) {
					$data[ $row['severity'] ] = isset( $statuses[ $row['severity'] ] )
						? $statuses[ $row['severity'] ]
						: $row['severity'];
				}
			}

			self::$bugs_severities = $data;
		} else {
			$data = self::$bugs_severities;
		}

		return $data;
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array  $item Item.
	 * @param string $column_name Column Name.
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {

			case 'title':
				$output = '<a class="row-title" href="' . get_edit_post_link( $item['project_id'] ) . '">' . $item['title'] . '</a>';

				return $output;

			case 'id':
				return $item['id'];

			case 'project':
				$owner = upstream_project_owner_name( $item['project_id'] ) ? '(' . upstream_project_owner_name( $item['project_id'] ) . ')' : '';

				$output  = '<a href="' . get_edit_post_link( $item['project_id'] ) . '">' . $item['project'] . '</a>';
				$output .= '<br>' . $owner;

				return $output;

			case 'assigned_to':
				$assignees = isset( $item['assigned_to'] ) ? array_filter( (array) $item['assigned_to'] ) : array();
				if ( count( $assignees ) > 0 ) {
					$users = get_users(
						array(
							'fields'  => array(
								'ID',
								'display_name',
							),
							'include' => $assignees,
						)
					);

					$html = array();

					$current_user_id = get_current_user_id();
					foreach ( $users as $user ) {
						if ( (int) $user->ID === $current_user_id ) {
							$html[] = '<span class="mine">' . esc_html( $user->display_name ) . '</span>';
						} else {
							$html[] = '<span>' . esc_html( $user->display_name ) . '</span>';
						}
					}

					return implode( ',<br>', $html );
				} else {
					return '<span><i style="color: #CCC;">' . __( 'none', 'upstream' ) . '</i></span>';
				}
				// no break.
			case 'due_date':
				if ( isset( $item['due_date'] ) && (int) $item['due_date'] > 0 ) {
					return '<span class="end-date">' . upstream_format_date( $item['due_date'] ) . '</span>';
				} else {
					return '<span><i style="color: #CCC;">' . __( 'none', 'upstream' ) . '</i></span>';
				}

				// no break.
			case 'status':
				if ( ! isset( $item['status'] ) || empty( $item['status'] ) ) {
					return '<span><i style="color: #CCC;">' . __( 'none', 'upstream' ) . '</i></span>';
				}

				$status = self::$bugs_statuses[ $item['status'] ];

				if ( is_array( $status ) ) {
					$status_title = $status['name'];
					$status_color = $status['color'];
				} else {
					$status_title = $status;
					$status_color = '#aaaaaa';
				}

				$output = sprintf(
					'<span class="status %s" style="border-color: %s">
                        <span class="count" style="background-color: %2$s">&nbsp;</span> %3$s
                    </span>',
					esc_attr( strtolower( $status_title ) ),
					esc_attr( $status_color ),
					esc_html( $status_title )
				);

				return $output;

			case 'severity':
				if ( ! isset( $item['severity'] )
					|| empty( $item['severity'] )
				) {
					return '<span><i style="color: #CCC;">' . __( 'none', 'upstream' ) . '</i></span>';
				}

				$severity = self::$bugs_severities[ $item['severity'] ];

				if ( is_array( $severity ) ) {
					$severity_title = $severity['name'];
					$severity_color = $severity['color'];
				} else {
					$severity_title = $severity;
					$severity_color = '#aaaaaa';
				}

				$output = sprintf(
					'<span class="status %s" style="border-color: %s">
                        <span class="count" style="background-color: %2$s">&nbsp;</span> %3$s
                    </span>',
					esc_attr( strtolower( $severity_title ) ),
					esc_attr( $severity_color ),
					esc_html( $severity_title )
				);

				return $output;

			default:
				// return print_r( $item, true ); //Show the whole array for troubleshooting purposes.
		}
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'title'       => array( 'title', true ),
			// 'project'       => array( 'project', false ),
			// 'milestone'     => array( 'milestone', false ),
			'assigned_to' => array( 'assigned_to', false ),
			'due_date'    => array( 'due_date', false ),
			'status'      => array( 'status', false ),
			'severity'    => array( 'severity', false ),
		);

		return $sortable_columns;
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		printf(
			// translators: %s: item label.
			esc_html__( 'No %s avaliable.', 'upstream' ),
			esc_html( strtolower( $this->bug_label_plural ) )
		);
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		$per_page     = $this->get_items_per_page( 'bugs_per_page', 10 );
		$current_page = $this->get_pagenum();

		$unpaginated_items = self::get_bugs();
		$unpaginated_items = self::sort_filter( $unpaginated_items );

		$total_items = count( $unpaginated_items );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items, // We have to calculate the total number of items.
				'per_page'    => $per_page, // We have to determine how many items to show on a page.
			)
		);

		$this->items = self::output_bugs( $per_page, $current_page );
	}

	/**
	 * Output bugs
	 *
	 * @param int $per_page Per Page.
	 * @param int $page_number Page Number.
	 *
	 * @return mixed
	 */
	public static function output_bugs( $per_page = 10, $page_number = 1 ) {
		// get the bugs.
		$bugs = self::get_bugs();
		// sort & filter the bugs.
		$bugs = self::sort_filter( $bugs );
		// does the paging.
		if ( ! $bugs ) {
			$output = 0;
		} else {
			$output = array_slice( $bugs, ( $page_number - 1 ) * $per_page, $per_page );
		}

		return $output;
	}

	/**
	 * Sort Filter
	 *
	 * @param  mixed $bugs Bugs.
	 */
	public static function sort_filter( $bugs = array() ) {
		if ( ! is_array( $bugs ) || count( $bugs ) === 0 ) {
			return array();
		}

		$request_data = isset( $_REQUEST ) ? wp_unslash( $_REQUEST ) : array();

		// filtering.
		$the_bugs = $bugs; // store the bugs array.

		// NOTE: this is being checked against the list below.
		$status = isset( $request_data['status'] ) && ! empty( $request_data['status'] ) ? sanitize_text_field( $request_data['status'] ) : 'all';
		if ( ! empty( $status ) && 'all' !== $status ) {
			$bugs = array_filter(
				$the_bugs,
				function ( $row ) use ( $status ) {
					return isset( $row['status'] ) && $row['status'] === $status;
				}
			);
		}

		// NOTE: this is being checked against the list below.
		$severity = isset( $request_data['severity'] ) && ! empty( $request_data['severity'] ) ? sanitize_text_field( $request_data['severity'] ) : 'all';
		if ( ! empty( $severity ) && 'all' !== $severity ) {
			$bugs = array_filter(
				$bugs,
				function ( $row ) use ( $severity ) {
					return isset( $row['severity'] ) && $row['severity'] === $severity;
				}
			);
		}

		// NOTE: this is checking against a known string.
		if ( isset( $request_data['view'] ) && sanitize_text_field( $request_data['view'] ) === 'mine' ) {
			$current_user_id = (int) get_current_user_id();

			$bugs = array_filter(
				$bugs,
				function ( $row ) use ( $current_user_id ) {
					if ( isset( $row['assigned_to'] ) ) {
						if ( ( is_array( $row['assigned_to'] ) && in_array( $current_user_id, $row['assigned_to'] ) )
						|| (int) $row['assigned_to'] === $current_user_id
						) {
							return true;
						}
					}

					return false;
				}
			);
		} else {
			// the cast will set  any non-ints to 0.
			$assigned_to = isset( $request_data['assigned_to'] ) ? intval( $request_data['assigned_to'] ) : 0;
			if ( $assigned_to > 0 ) {
				$bugs = array_filter(
					$bugs,
					function ( $row ) use ( $assigned_to ) {
						return isset( $row['assigned_to'] ) && $row['assigned_to'] === $assigned_to;
					}
				);
			}
		}

		$project_id = isset( $request_data['project'] ) && ! empty( $request_data['project'] ) ? absint( $request_data['project'] ) : 0;
		if ( $project_id > 0 ) {
			$bugs = array_filter(
				$bugs,
				function ( $row ) use ( $project_id ) {
					return isset( $row['project_id'] ) && $row['project_id'] === $project_id;
				}
			);
		}

		// sorting the bugs.
		if ( ! empty( $request_data['orderby'] ) ) {
			if ( ! empty( $request_data['order'] ) && sanitize_text_field( $request_data['order'] ) == 'asc' ) {
				$tmp = array();
				foreach ( $bugs as &$ma ) {
					$tmp[] = &$ma[ esc_html( sanitize_text_field( $request_data['orderby'] ) ) ];
				}
				array_multisort( $tmp, SORT_ASC, $bugs );
			}
			if ( ! empty( $request_data['order'] ) && sanitize_text_field( $request_data['order'] ) == 'desc' ) {
				$tmp = array();
				foreach ( $bugs as &$ma ) {
					$tmp[] = &$ma[ esc_html( sanitize_text_field( $request_data['orderby'] ) ) ];
				}
				array_multisort( $tmp, SORT_DESC, $bugs );
			}
		}

		$rowset = array();
		foreach ( $bugs as $bug ) {
			if ( ! isset( $rowset[ $bug['id'] ] ) ) {
				$rowset[ $bug['id'] ] = $bug;
			}
		}

		return array_values( $rowset );
	}

	/**
	 * Get Table Classes
	 */
	protected function get_table_classes() {
		return array( 'widefat', 'striped', $this->_args['plural'] );
	}

	/**
	 * Get Status Unique
	 *
	 * @return void
	 */
	private function get_status_unique() {
		$bugs = self::get_bugs();
		if ( empty( $bugs ) ) {
			return;
		}

		$items = wp_list_pluck( $bugs, 'status' );
		$items = array_unique( $items );
		$items = array_filter( $items );

		return $items;
	}

	/**
	 * Get Severity Unique
	 *
	 * @return void
	 */
	private function get_severity_unique() {
		$bugs = self::get_bugs();
		if ( empty( $bugs ) ) {
			return;
		}

		$items = wp_list_pluck( $bugs, 'severity' );
		$items = array_unique( $items );
		$items = array_filter( $items );

		return $items;
	}
}

add_action(
	'plugins_loaded',
	function () {
		if ( upstream_disable_bugs() ) {
			return;
		}
		Upstream_Admin_Bugs_Page::get_instance();
	}
);
