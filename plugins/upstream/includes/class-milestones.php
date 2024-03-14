<?php
/**
 * This class will act as a controller handling incoming requests regarding comments on UpStream items.
 *
 * @package UpStream
 */

namespace UpStream;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use UpStream\Traits\Singleton;

/**
 * Class Milestones
 *
 * @since   1.24.0
 */
class Milestones {

	use Singleton;

	/**
	 * Is post_type_created
	 *
	 * @var bool
	 */
	protected $post_type_created = false;

	/**
	 * Class constructor.
	 *
	 * @since   1.24.0
	 */
	public function __construct() {
		$this->attach_hooks();
	}

	/**
	 * Attach all relevant actions to handle comments.
	 *
	 * @since   1.24.0
	 */
	private function attach_hooks() {
		if ( upstream_disable_milestones() ) {
			return;
		}

		add_action( 'before_upstream_init', array( $this, 'create_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 8 );
		add_action( 'save_post', array( $this, 'save_post' ) );

		$post_type = $this->get_post_type();

		add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'manage_posts_columns' ), 10 );
		add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'render_post_columns' ), 10, 2 );
		add_filter( 'get_edit_post_link', array( $this, 'get_milestone_edit_post_link' ), 10, 2 );
		add_action( 'current_screen', array( $this, 'disable_new_milestone_button' ) );
	}

	/**
	 * Return the post type name.
	 *
	 * @return string
	 * @since 1.24.0
	 */
	public function get_post_type() {
		return Milestone::POST_TYPE;
	}

	/**
	 * MigrateLegacyMilestonesForProject
	 *
	 * @param int $project_id Project ID.
	 *
	 * @return bool
	 * @throws \Exception Exception.
	 */
	public static function migrate_legacy_milestones_for_project( $project_id ) {
		// Migrate the milestones.
		$default_milestones = get_option( 'upstream_milestones', array() );

		if ( ! empty( $default_milestones ) ) {
			$default_milestones = $default_milestones['milestones'];
			$legacy_milestones  = array();

			// Organize the milestones by id.
			foreach ( $default_milestones as $milestone_data ) {
				$legacy_milestones[ $milestone_data['id'] ] = $milestone_data;
			}

			global $wpdb;

			// Get the project's milestones to convert them into the new post types.
			$project_milestones = get_post_meta( $project_id, '_upstream_project_milestones', true );
			$project_tasks      = get_post_meta( $project_id, '_upstream_project_tasks', true );

			try {
				if ( ! empty( $project_milestones ) ) {
					// Check if the backup register doesn't exist.
					$legacy_milestones_backup = get_post_meta( $project_id, '_upstream_project_milestones_legacy', true );
					if ( empty( $legacy_milestones_backup ) ) {
						// Move the milestones to a backup register, temporarily.
						update_post_meta( $project_id, '_upstream_project_milestones_legacy', $project_milestones );
					}

					$wpdb->query( 'START TRANSACTION' );

					$updated_tasks = false;

					foreach ( $project_milestones as $project_milestone ) {

						$data = $legacy_milestones[ $project_milestone['milestone'] ];

						// Check if we already have this milestone in the project.
						$migrated_milestone = get_posts(
							array(
								'post_type'   => Milestone::POST_TYPE,
								'post_parent' => $project_id,
								'post_status' => 'publish',
								'meta_key'    => Milestone::META_LEGACY_MILESTONE_CODE,
								'meta_value'  => $project_milestone['milestone'],
							)
						);

						// If the milestone already exists, abort.
						if ( ! empty( $migrated_milestone ) ) {
							continue;
						}

						// The milestone doesn't exist. Let's create it.
						$milestone = Factory::create_milestone( $data['title'] )
											->setLegacyId( $project_milestone['id'] )
											->setLegacyMilestoneCode( $project_milestone['milestone'] )
											->setStartDate( $project_milestone['start_date'] )
											->setEndDate( $project_milestone['end_date'] )
											->setAssignedTo( $project_milestone['assigned_to'] )
											->setNotes( $project_milestone['notes'] )
											->setCreatedTimeInUtc( 1 === (int) $project_milestone['notes'] )
											->setProgress( (float) $project_milestone['progress'] )
											->setTaskCount( (int) $project_milestone['task_count'] )
											->setTaskOpen( (int) $project_milestone['task_open'] )
											->setColor( $data['color'] )
											// ->setOrder($data['title'])
											->setProjectId( $project_id );

						// Look for all the tasks to convert the milestone ID.
						if ( ! empty( $project_tasks ) ) {
							foreach ( $project_tasks as &$task ) {
								if ( $task['milestone'] === $milestone->getLegacyId() ) {
									$task['milestone'] = $milestone->getId();
									// Keep the legacy reference for a while.
									$task['milestone_legacy'] = $milestone->getLegacyId();

									$updated_tasks = true;
								}
							}
						}
					}

					update_post_meta( $project_id, '_upstream_milestones_migrated', 1 );

					// Remove the legacy Milestones.
					delete_post_meta( $project_id, '_upstream_project_milestones' );

					// Update the tasks in the project.
					if ( $updated_tasks ) {
						update_post_meta( $project_id, '_upstream_project_tasks', $project_tasks );
					}

					$wpdb->query( 'COMMIT' );
				} else {
					update_post_meta( $project_id, '_upstream_project_milestones_legacy', array() );
				}
			} catch ( \Exception $e ) {
				$wpdb->query( 'ROLLBACK' );

				throw new Exception( 'Error found while migrating a milestone. ' . $e->getMessage() );
			}
		}

		return true;
	}

	/**
	 * FixMilestoneOrdersOnProject
	 *
	 * @param int $project_id Project id.
	 *
	 * @return bool
	 * @throws \Exception Exception.
	 */
	public static function fix_milestone_orders_on_project( $project_id ) {
		try {
			$project_milestones = self::getInstance()->get_milestones_from_project( $project_id );

			if ( ! empty( $project_milestones ) ) {
				global $wpdb;

				$wpdb->query( 'START TRANSACTION' );

				foreach ( $project_milestones as $project_milestone ) {
					$milestone = Factory::get_milestone( $project_milestone );

					// $milestone->setOrder($milestone->getName());
				}

				$wpdb->query( 'COMMIT' );
			}
		} catch ( \Exception $e ) {
			$wpdb->query( 'ROLLBACK' );

			throw new Exception( 'Error found while fixing the order on a milestone. ' . $e->getMessage() );
		}

		return true;
	}

	/**
	 * Create the post type for milestones.
	 *
	 * @since 1.24.0
	 */
	public function create_post_type() {
		if ( $this->post_type_created ) {
			return;
		}

		$singular_label = upstream_milestone_label();
		$plural_label   = upstream_milestone_label_plural();

		$labels = array(
			'name'                  => $plural_label,
			'singular_name'         => $singular_label,
			'add_new'               => sprintf(
				// translators: %s: singular_label.
				_x( 'Add new %s', 'upstream' ),
				$singular_label
			),
			'edit_item'             => sprintf(
				// translators: %s: singular_label.
				__( 'Edit %s', 'upstream' ),
				$singular_label
			),
			'new_item'              => sprintf(
				// translators: %s: singular_label.
				__( 'New %s', 'upstream' ),
				$singular_label
			),
			'view_item'             => sprintf(
				// translators: %s: singular_label.
				__( 'View %s', 'upstream' ),
				$singular_label
			),
			'view_items'            => sprintf(
				// translators: %s: plural_label.
				__( 'View %s', 'upstream' ),
				$plural_label
			),
			'search_items'          => sprintf(
				// translators: %s: plural_label.
				__( 'Search %s', 'upstream' ),
				$plural_label
			),
			'not_found'             => sprintf(
				// translators: %s: plural_label.
				__( 'No %s found', 'upstream' ),
				$plural_label
			),
			'not_found_in_trash'    => sprintf(
				// translators: %s: singular_label.
				__( 'No %s found in Trash', 'upstream' ),
				$singular_label
			),
			'parent_item_colon'     => sprintf(
				// translators: %s: singular_label.
				__( 'Parent %s:', 'upstream' ),
				$singular_label
			),
			'all_items'             => $plural_label,
			'archives'              => sprintf(
				// translators: %s: singular_label.
				__( '%s Archives', 'upstream' ),
				$singular_label
			),
			'attributes'            => sprintf(
				// translators: %s: singular_label.
				__( '%s Attributes', 'upstream' ),
				$singular_label
			),
			'insert_into_item'      => sprintf(
				// translators: %s: singular_label.
				__( 'Insert into %s', 'upstream' ),
				$singular_label
			),
			'uploaded_to_this_item' => sprintf(
				// translators: %s: singular_label.
				__( 'Uploaded to this %s', 'upstream' ),
				$singular_label
			),
			'featured_image'        => __( 'Featured Image', 'upstream' ),
			'set_featured_image'    => __( 'Set featured image', 'upstream' ),
			'remove_featured_image' => __( 'Remove featured image', 'upstream' ),
			'use_featured_image'    => __( 'Use as featured image', 'upstream' ),
			'menu_name'             => $plural_label,
			'filter_items_list'     => $plural_label,
			'items_list_navigation' => $plural_label,
			'items_list'            => $plural_label,
			'name_admin_bar'        => $plural_label,
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=project',
			'rewrite'            => array( 'slug' => strtolower( $singular_label ) ),
			'capability_type'    => 'milestone',
			'has_archive'        => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'comments' ),
			'map_meta_cap'       => true,
		);

		register_post_type( $this->get_post_type(), $args );

		$this->post_type_created = true;
	}

	/**
	 * Add meta boxes to the post type.
	 *
	 * @param string $post_type Post type.
	 *
	 * @since 1.24.0
	 */
	public function add_meta_box( $post_type ) {
		if ( $this->get_post_type() !== $post_type ) {
			return;
		}

		add_meta_box(
			'upstream_mimlestone_data',
			__( 'Data', 'upstream' ),
			array( $this, 'render_meta_box' ),
			$this->get_post_type(),
			'advanced',
			'high'
		);
	}

	/**
	 * Render the metabox for data.
	 *
	 * @param \WP_Post $post Post data.
	 *
	 * @throws \Twig_Error_Loader Exception.
	 * @throws \Twig_Error_Runtime Exception.
	 * @throws \Twig_Error_Syntax Exception.
	 * @since 1.24.0
	 */
	public function render_meta_box( $post ) {
		$upstream = \UpStream::instance();

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'upstream_milestone_data_meta_box', 'upstream_milestone_data_meta_box_nonce' );

		// Projects.
		$projects_instances = get_posts(
			array(
				'post_type'      => 'project',
				'posts_per_page' => -1,
			)
		);
		$projects           = array();
		if ( ! empty( $projects_instances ) ) {
			foreach ( $projects_instances as $project ) {
				$projects[ $project->ID ] = $project->post_title;
			}
		}

		$milestone = Factory::get_milestone( $post->ID );

		$context = array(
			'field_prefix' => '_upstream_milestone_',
			'members'      => (array) $this->project_users_dropdown(),
			'projects'     => $projects,
			'permissions'  => array(
				'edit_assigned_to' => current_user_can( 'milestone_assigned_to_field' ),
				'edit_start_date'  => current_user_can( 'milestone_start_date_field' ),
				'edit_end_date'    => current_user_can( 'milestone_end_date_field' ),
				'edit_notes'       => current_user_can( 'milestone_notes_field' ),
				'edit_project'     => current_user_can( 'edit_projects' ),
			),
			'labels'       => array(
				'assigned_to' => __( 'Assigned To', 'upstream' ),
				'none'        => __( 'None', 'upstream' ),
				'start_date'  => __( 'Start Date', 'upstream' ),
				'end_date'    => __( 'End Date', 'upstream' ),
				'notes'       => __( 'Notes', 'upstream' ),
				'project'     => __( 'Project', 'upstream' ),
				'color'       => __( 'Color', 'upstream' ),
			),
			'data'         => array(
				'assigned_to' => get_post_meta( $post->ID, 'upst_assigned_to', false ),
				'start_date'  => $milestone->getStartDate( 'upstream' ),
				'end_date'    => $milestone->getEndDate( 'upstream' ),
				'notes'       => $milestone->getNotes(),
				'project_id'  => $milestone->getProjectId(),
				'color'       => $milestone->getColor(),
				'id'          => $milestone->getId(),
			),
		);

		?>

		<div class="cmb2-wrap">
			<?php if ( $context['permissions']['edit_project'] ) : ?>
			<div class="row upstream-milestone-project">
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right">
					<label for="<?php echo esc_attr( $context['field_prefix'] ); ?>project_id"><?php echo esc_html( $context['labels']['project'] ); ?></label>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
					<select id="<?php echo esc_attr( $context['field_prefix'] ); ?>project_id"
							name="milestone_data[project_id]" class="form-control"
							data-placeholder="<?php echo esc_attr( $context['labels']['none'] ); ?>">
						<?php foreach ( $context['projects'] as $project_id => $project_name ) : ?>
							<?php if ( $project_id ) : ?>
						<option value="<?php echo esc_attr( $project_id ); ?>" <?php echo $project_id == $context['data']['project_id'] ? 'selected' : ''; ?> ><?php echo esc_html( $project_name ); ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
			</div>
			<?php endif; ?>

			<?php if ( $context['permissions']['edit_assigned_to'] ) : ?>
			<div class="row upstream-milestone-assigned-to">
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right">
					<label for="<?php echo esc_attr( $context['field_prefix'] ); ?>assigned_to"><?php echo esc_html( $context['labels']['assigned_to'] ); ?></label>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
					<select id="<?php echo esc_attr( $context['field_prefix'] ); ?>assigned_to"
							name="milestone_data[assigned_to][]" class="form-control"
							data-placeholder="<?php echo esc_attr( $context['labels']['none'] ); ?>" multiple>
						<option></option>
						<?php foreach ( $context['members'] as $user_id => $user_name ) : ?>
						<option value="<?php echo esc_attr( $user_id ); ?>" <?php echo in_array( $user_id, $context['data']['assigned_to'] ) ? 'selected' : ''; ?>  ><?php echo esc_html( $user_name ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
			</div>
			<?php endif; ?>


			<?php if ( $context['permissions']['edit_start_date'] ) : ?>
			<div class="row upstream-milestone-start-date">
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right">
					<label for="<?php echo esc_attr( $context['field_prefix'] ); ?>start_date"><?php echo esc_html( $context['labels']['start_date'] ); ?></label>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
					<input type="text" id="<?php echo esc_attr( $context['field_prefix'] ); ?>start_date"
						name="milestone_data[start_date]" class="form-control o-datepicker"
						placeholder="<?php echo esc_attr( $context['labels']['none'] ); ?>" data-elt="end_date"
						autocomplete="off" value="<?php echo esc_attr( $context['data']['start_date'] ); ?>">
					<input type="hidden" id="<?php echo esc_attr( $context['field_prefix'] ); ?>start_date_timestamp'; ?>"
						data-name="start_date">
				</div>
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
			</div>
			<?php endif; ?>

			<?php if ( $context['permissions']['edit_end_date'] ) : ?>
			<div class="row upstream-milestone-end-date">
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right">
					<label for="<?php echo esc_attr( $context['field_prefix'] ); ?>end_date"><?php echo esc_html( $context['labels']['end_date'] ); ?></label>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
					<input type="text" id="<?php echo esc_attr( $context['field_prefix'] ); ?>end_date"
						name="milestone_data[end_date]" class="form-control o-datepicker"
						placeholder="<?php echo esc_attr( $context['labels']['none'] ); ?>" data-egt="start_date"
						autocomplete="off" value="<?php echo esc_attr( $context['data']['end_date'] ); ?>">
					<input type="hidden" id="<?php echo esc_attr( $context['field_prefix'] ); ?>end_date_timestamp"
						data-name="end_date">
				</div>
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
			</div>
			<?php endif; ?>


			<?php do_action( 'upstream.frontend-edit:render_after.project.items.end_dates', 'milestones' ); ?>

			<div class="row upstream-milestone-color">
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right">
					<label for=""><?php echo esc_html( $context['labels']['color'] ); ?></label>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
					<input class="color-field" type="text" name="milestone_data[color]" value="<?php echo esc_attr( $context['data']['color'] ); ?>"/>
				</div>
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
			</div>

			<?php if ( $context['permissions']['edit_notes'] ) : ?>
			<div class="row upstream-milestone-notes">
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right">
					<label for=""><?php echo esc_html( $context['labels']['notes'] ); ?></label>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
					<?php
					wp_editor(
						$context['data']['notes'],
						$context['field_prefix'] . 'notes',
						array(
							'media_buttons' => true,
							'textarea_rows' => 5,
							'textarea_name' => 'milestone_data[notes]',
						)
					);
					?>
				</div>
				<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
			</div>
			<?php endif; ?>

			<?php do_action( 'upstream.frontend-edit:render_additional_fields', 'milestone', $context['data'] ); ?>
		</div>


		<?php
	}

	/**
	 * Returns all users with select roles.
	 * For use in dropdowns.
	 */
	protected function project_users_dropdown() {
		$options = array(
			'' => __( 'None', 'upstream' ),
		);

		$project_users = upstream_admin_get_all_project_users();

		$options += $project_users;

		return $options;
	}

	/**
	 * SavePost
	 *
	 * @param int $post_id Post_id.
	 *
	 * @throws \Exception Exception.
	 * @since 1.24.0
	 */
	public function save_post( $post_id ) {
		$post_data = wp_unslash( $_POST );

		if ( ! isset( $post_data['milestone_data'] ) ) {
			return $post_id;
		}

		$data = $post_data['milestone_data']; // NOTE: Each field is checked in the code below.

		$project_id_field_name = 'project_id';
		$project_id            = absint( $data[ $project_id_field_name ] );

		if ( ! upstream_user_can_access_project( get_current_user_id(), $project_id ) ) {
			return $post_id;
		}

		$nonce = isset( $post_data['upstream_milestone_data_meta_box_nonce'] ) ? $post_data['upstream_milestone_data_meta_box_nonce'] : null;

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'upstream_milestone_data_meta_box' ) ) {
			return $post_id;
		}

		// Start date.
		$start_date_field_name = 'start_date';
		$start_date            = ! empty( $data[ $start_date_field_name ] ) ? sanitize_text_field( $data[ $start_date_field_name ] ) : '';

		// End date.
		$end_date_field_name = 'end_date';
		$end_date            = ! empty( $data[ $end_date_field_name ] ) ? sanitize_text_field( $data[ $end_date_field_name ] ) : '';

		// Notes.
		$notes = wp_kses_post( $data['notes'] );

		$color = sanitize_text_field( $data['color'] );

		// Store the values.
		$milestone = Factory::get_milestone( $post_id );
		$milestone->setProjectId( $project_id )
				->setStartDate( $start_date )
				->setEndDate( $end_date )
				->setNotes( $notes )
				->setColor( $color );

		// If there is no assigned user, there won't be any key assigned_to in the $data array.
		if ( isset( $data['assigned_to'] ) && is_array( $data['assigned_to'] ) ) {
			$assigned_to = array_map( 'intval', (array) $data['assigned_to'] );

			if ( $assigned_to > 0 ) {
				$milestone->setAssignedTo( $assigned_to );
			}
		}

		/**
		 * Upstream save milestone
		 *
		 * @param int $project_id
		 */
		do_action( 'upstream_save_milestone', $post_id, false );
	}

	/**
	 * Manage_posts_columns
	 *
	 * @param array $columns Post column.
	 *
	 * @return array
	 * @since 1.24.0
	 */
	public function manage_posts_columns( $columns ) {
		$new_columns['cb']                               = '<input type="checkbox" />';
		$new_columns['title']                            = __( 'Milestone', 'upstream' );
		$new_columns['taxonomy-upst_milestone_category'] = __( 'Milestone Category', 'upstream' );
		$new_columns['id']                               = __( 'ID', 'upstream' );
		$new_columns['project']                          = __( 'Project', 'upstream' );
		$new_columns['assigned_to']                      = __( 'Assigned To', 'upstream' );
		$new_columns['start_date']                       = __( 'Start Date', 'upstream' );
		$new_columns['end_date']                         = __( 'End Date', 'upstream' );

		return $new_columns;
	}

	/**
	 * Render_post_columns
	 *
	 * @param array $column Post column.
	 * @param int   $post_id Post id.
	 *
	 * @since 1.24.0
	 */
	public function render_post_columns( $column, $post_id ) {
		$milestone  = Factory::get_milestone( $post_id );
		$project_id = $milestone->getProjectId();

		if ( 'project' === $column && $project_id ) {
			$project = get_post( $project_id );

			if ( ! empty( $project ) ) {
				printf( '<a href="%s">%s</a>', esc_attr( get_edit_post_link( $project->ID ) ), esc_html( $project->post_title ) );
			}
		}

		if ( 'id' === $column ) {
			echo esc_html( $post_id );
		}

		if ( 'assigned_to' === $column ) {
			$users_id = $milestone->getAssignedTo();

			if ( empty( $users_id ) ) {
				echo '<span><i class="text-muted">' . esc_html__( 'none', 'upstream' ) . '</i></span>';

				return;
			}

			$users = array();

			foreach ( $users_id as $id ) {
				$u = get_user_by( 'id', $id );
				// RSD: fix error where $u is null.
				if ( $u ) {
					$users[] = $u->display_name;
				}
			}

			echo esc_html( implode( ', ', $users ) );
		}

		if ( 'start_date' === $column ) {
			echo esc_html( $milestone->getStartDate( 'upstream' ) );
		}

		if ( 'end_date' === $column ) {
			echo esc_html( $milestone->getEndDate( 'upstream' ) );
		}
	}

	/**
	 * Get_milestone_edit_post_link
	 *
	 * @param string $link Post link.
	 * @param int    $id Post id.
	 */
	public function get_milestone_edit_post_link( $link, $id ) {
		$post = get_post( $id );

		if ( 'upst_milestone' === $post->post_type ) {
			$milestone  = Factory::get_milestone( $id );
			$project_id = $milestone->getProjectId();

			if ( $project_id && 'project' === get_post_type( $project_id ) ) {
				$link = wp_specialchars_decode( get_edit_post_link( $project_id ) ); // remove amp; character.
			}
		}

		return $link;
	}

	/**
	 * Disable_new_milestone_button
	 */
	public function disable_new_milestone_button() {
		$cs = get_current_screen();

		if ( isset( $cs->post_type ) && 'upst_milestone' === $cs->post_type ) {
			echo '<style type="text/css">
			.page-title-action { display:none; }
			</style>';
		}
	}

	/**
	 * HasAnyMilestone
	 *
	 * @return bool
	 */
	public function has_any_milestone() {
		$posts = get_posts(
			array(
				'post_type'      => Milestone::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);

		return count( $posts ) > 0;
	}

	/**
	 * GetMilestonesAsRowset
	 *
	 * @param int $project_id Project_id.
	 *
	 * @return array|mixed|null
	 *
	 * @throws Exception Exception.
	 */
	public function get_milestones_as_rowset( $project_id ) {
		$project_milestones = $this->get_milestones_from_project( $project_id );
		$data               = array();

		if ( ! empty( $project_milestones ) ) {
			foreach ( $project_milestones as $milestone ) {
				$milestone = \UpStream\Factory::get_milestone( $milestone );

				$row = $milestone->convertToLegacyRowset();

				$data[ $row['id'] ] = $row;
			}

			$data = apply_filters( 'upstream_project_milestones', $data, $project_id );
		}

		return $data;
	}

	/**
	 * Returns all milestones from the project without permissions check
	 *
	 * @param int  $project_id project_id.
	 * @param bool $return_as_legacy_dataset return_as_legacy_dataset.
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function get_all_milestones_from_project( $project_id, $return_as_legacy_dataset = false ) {
		$posts = get_posts(
			array(
				'post_type'      => Milestone::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => Milestone::META_PROJECT_ID,
				'meta_value'     => $project_id,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);

		$milestones = array();

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( true ) {

					if ( $return_as_legacy_dataset ) {
						$data = Factory::get_milestone( $post )->convertToLegacyRowset();
					} else {
						$data = $post;
					}

					$milestones[ $post->ID ] = $data;
				}
			}
		}

		return $milestones;
	}


	/**
	 * GetMilestonesFromProjectUncached_NoPerms
	 *
	 * @param int  $project_id project_id.
	 * @param bool $return_as_legacy_dataset return_as_legacy_dataset.
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function get_milestones_from_project_uncached_no_perms( $project_id, $return_as_legacy_dataset = false ) {
		$posts = get_posts(
			array(
				'post_type'      => Milestone::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => Milestone::META_PROJECT_ID,
				'meta_value'     => $project_id,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);

		$milestones = array();

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( $return_as_legacy_dataset ) {
					$data = Factory::get_milestone( $post )->convertToLegacyRowset();
				} else {
					$data = $post;
				}

				$milestones[ $post->ID ] = $data;
			}
		}

		return $milestones;
	}

	/**
	 * GetMilestonesFromProject_NoPerms
	 *
	 * @param int  $project_id project_id.
	 * @param bool $return_as_legacy_dataset return_as_legacy_dataset.
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function get_milestones_from_project_no_perms( $project_id, $return_as_legacy_dataset = false ) {
		$key = 'get_milestones_from_project_no_perms' . ( (int) $project_id ) . '_' . ( (int) $return_as_legacy_dataset );

		$milestones = \Upstream_Cache::get_instance()->get( $key );

		// should be circumvented because it's only used on saves.
		if ( false === $milestones ) {
			$milestones = $this->get_milestones_from_project_uncached_no_perms( (int) $project_id, $return_as_legacy_dataset );
			\Upstream_Cache::get_instance()->set( $key, $milestones );
		}

		return $milestones;

	}

	/**
	 * GetMilestonesFromProjectUncached
	 *
	 * @param int  $project_id project_id.
	 * @param bool $return_as_legacy_dataset return_as_legacy_dataset.
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function get_milestones_from_project_uncached( $project_id, $return_as_legacy_dataset = false ) {
		$posts = get_posts(
			array(
				'post_type'      => Milestone::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => Milestone::META_PROJECT_ID,
				'meta_value'     => $project_id,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);

		$milestones = array();

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_MILESTONE, $post->ID, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {

					if ( $return_as_legacy_dataset ) {
						$data = Factory::get_milestone( $post )->convertToLegacyRowset();
					} else {
						$data = $post;
					}

					$milestones[ $post->ID ] = $data;
				}
			}
		}

		return $milestones;
	}

	/**
	 * GetMilestonesFromProject
	 *
	 * @param int  $project_id project_id.
	 * @param bool $return_as_legacy_dataset return_as_legacy_dataset.
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function get_milestones_from_project( $project_id, $return_as_legacy_dataset = false ) {
		$key = 'get_milestones_from_project' . ( (int) $project_id ) . '_' . ( (int) $return_as_legacy_dataset );

		$milestones = \Upstream_Cache::get_instance()->get( $key );
		if ( false === $milestones ) {
			$milestones = $this->get_milestones_from_project_uncached( (int) $project_id, $return_as_legacy_dataset );
			\Upstream_Cache::get_instance()->set( $key, $milestones );
		}

		return $milestones;

	}

	/**
	 * GetCategoriesNames
	 *
	 * @param array $categories categories.
	 *
	 * @return string
	 */
	public function get_categories_names( $categories ) {
		$names = array();

		if ( is_array( $categories ) && ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				if ( is_numeric( $category ) ) {
					$category = get_term( $category );
				}

				if ( is_object( $category ) ) {
					$names[] = $category->name;
				}
			}
		}

		return implode( ', ', $names );
	}
}
