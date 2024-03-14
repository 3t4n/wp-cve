<?php
/**
 * UpStream_Metaboxes_Projects
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Metaboxes_Projects' ) ) :

	/**
	 * CMB2 Theme Options
	 *
	 * @version 0.1.0
	 */
	class UpStream_Metaboxes_Projects {

		/**
		 * Post type
		 *
		 * @var string
		 */
		public $type = 'project';

		/**
		 * Metabox prefix
		 *
		 * @var string
		 */
		public $prefix = '_upstream_project_';

		/**
		 * Project Label
		 *
		 * @var string
		 */
		public $project_label = '';

		/**
		 * Holds an instance of the object
		 *
		 * @var Myprefix_Admin
		 **/
		public static $instance = null;

		/**
		 * Indicates if comments section is enabled.
		 *
		 * @since   1.13.0
		 * @access  private
		 * @static
		 *
		 * @var     bool $allow_project_comments
		 */
		private static $allow_project_comments = true;

		/**
		 * Construct
		 *
		 * @return void
		 */
		public function __construct() {
			$this->project_label = upstream_project_label();

			do_action( 'upstream_admin_notices_errors' );

			// Ensure WordPress can generate and display custom slugs for the project by making it public temporarily fast.
			add_action( 'edit_form_before_permalink', array( $this, 'make_project_temporarily_public' ) );

			// Ensure the made public project are non-public as it should.
			add_action( 'edit_form_after_title', array( $this, 'make_project_private_once_again' ) );

			add_action( 'cmb2_render_comments', array( $this, 'render_comments_field' ), 10, 5 );

			// Prevent action being hooked twice.
			global $wp_filter;
			if ( ! isset( $wp_filter['cmb2_render_select2'] ) ) {
				// Add select2 field type.
				add_action( 'cmb2_render_select2', array( $this, 'render_select2_field' ), 10, 5 );
			}

			if ( ! isset( $wp_filter['cmb2_sanitize_select2'] ) ) {
				// Add select2 field type sanitization callback.
				add_action( 'cmb2_sanitize_select2', array( $this, 'sanitize_select2_field' ), 10, 5 );
			}

			if ( upstream_filesytem_enabled() ) {
				add_action( 'post_edit_form_tag', array( $this, 'post_edit_form_tag' ) );

				if ( ! isset( $wp_filter['cmb2_render_upfs'] ) ) {
					add_action( 'cmb2_render_upfs', array( $this, 'render_upfs_field' ), 10, 5 );
				}

				if ( ! isset( $wp_filter['cmb2_sanitize_upfs'] ) ) {
					add_action( 'cmb2_sanitize_upfs', array( $this, 'sanitize_upfs_field' ), 10, 5 );
				}
			}

		}

		/**
		 * Post Edit Form Tag
		 *
		 * @return void
		 */
		public function post_edit_form_tag() {
			global $post;

			if ( ! $post || get_post_type( $post->ID ) != 'project' ) {
				return;
			}

			echo ' enctype="multipart/form-data" autocomplete="off"';
		}

		/**
		 * Returns the running object
		 *
		 * @return Myprefix_Admin
		 **/
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				if ( upstream_post_id() > 0 ) {
					self::$instance->overview();
				}

				if ( ! upstream_disable_milestones() ) {
					self::$instance->milestones();
				}

				if ( ! upstream_disable_tasks() ) {
					self::$instance->tasks();
				}

				if ( ! upstream_disable_bugs() ) {
					self::$instance->bugs();
				}

				if ( ! upstream_disable_files() ) {
					self::$instance->files();
				}

				self::$instance->details();
				self::$instance->sidebar_low();

				self::$allow_project_comments = upstream_are_project_comments_enabled();

				if ( self::$allow_project_comments ) {
					self::$instance->comments();
				}

				do_action( 'upstream_details_metaboxes' );
			}

			return self::$instance;
		}

		/*
		======================================================================================
												OVERVIEW
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function overview() {
			$are_milestones_disabled        = upstream_are_milestones_disabled();
			$are_milestones_disabled_at_all = upstream_disable_milestones();
			$are_tasks_disabled             = upstream_are_tasks_disabled();
			$are_bugs_disabled              = upstream_are_bugs_disabled();

			if ( ( ! $are_milestones_disabled && $are_milestones_disabled_at_all ) || ! $are_tasks_disabled || ! $are_bugs_disabled ) {
				$metabox = new_cmb2_box(
					array(
						'id'           => $this->prefix . 'overview',
						'title'        => $this->project_label . esc_html__( ' Overview', 'upstream' ) .
							'<span class="progress align-right"><progress value="' . upstream_project_progress() . '" max="100"></progress> <span>' . upstream_project_progress() . '%</span></span>',
						'object_types' => array( $this->type ),
					)
				);

				// Create a default grid.
				$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

				$columns_list = array();

				if ( ! $are_milestones_disabled && ! $are_milestones_disabled_at_all ) {
					$columns_list[] = $metabox->add_field(
						array(
							'name'  => '<span>' . upstream_count_total(
								'milestones',
								upstream_post_id()
							) . '</span> ' . upstream_milestone_label_plural(),
							'id'    => $this->prefix . 'milestones',
							'type'  => 'title',
							'after' => 'upstream_output_overview_counts',
						)
					);
				}

				if ( ! upstream_disable_tasks() ) {
					if ( ! $are_tasks_disabled ) {
						$grid2          = $metabox->add_field(
							array(
								'name'  => '<span>' . upstream_count_total(
									'tasks',
									upstream_post_id()
								) . '</span> ' . upstream_task_label_plural(),
								'desc'  => '',
								'id'    => $this->prefix . 'tasks',
								'type'  => 'title',
								'after' => 'upstream_output_overview_counts',
							)
						);
						$columns_list[] = $grid2;
					}
				}

				if ( ! $are_bugs_disabled ) {
					$grid3          = $metabox->add_field(
						array(
							'name'  => '<span>' . upstream_count_total(
								'bugs',
								upstream_post_id()
							) . '</span> ' . upstream_bug_label_plural(),
							'desc'  => '',
							'id'    => $this->prefix . 'bugs',
							'type'  => 'title',
							'after' => 'upstream_output_overview_counts',
						)
					);
					$columns_list[] = $grid3;
				}

				// Create now a Grid of group fields.
				$row = $cmb2_grid->addRow();
				$row->addColumns( $columns_list );
			}
		}


		/*
		======================================================================================
												MILESTONES
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function milestones() {
			$are_milestones_disabled        = upstream_are_milestones_disabled();
			$are_milestones_disabled_at_all = upstream_disable_milestones();
			$user_has_admin_permissions     = upstream_admin_permissions( 'disable_project_milestones' );

			if ( $are_milestones_disabled_at_all || ( $are_milestones_disabled && ! $user_has_admin_permissions ) ) {
				return;
			}

			$label        = upstream_milestone_label();
			$label_plural = upstream_milestone_label_plural();

			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'milestones',
					'title'        => '<span class="dashicons dashicons-flag"></span> ' . esc_html( $label_plural ),
					'object_types' => array( $this->type ),
				)
			);

			// Create a default grid.
			$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

			/*
			 * Outputs some hidden data for dynamic use.
			 */
			$metabox->add_field(
				array(
					'id'          => $this->prefix . 'hidden',
					'type'        => 'title',
					'description' => '',
					'after'       => 'upstream_admin_output_milestone_hidden_data',
					'attributes'  => array(
						'class'        => 'hidden',
						'data-publish' => upstream_admin_permissions( 'publish_project_milestones' ),
					),
				)
			);

			if ( ! $are_milestones_disabled ) {
				$group_field_id = $metabox->add_field(
					array(
						'id'           => $this->prefix . 'milestones',
						'type'         => 'group',
						'description'  => '',
						'permissions'  => 'delete_project_milestones', // also set on individual row level.
						'before_group' => $this->get_milestones_filters_html(),
						'options'      => array(
							'group_title'   => esc_html( $label ) . ' {#}',
							'add_button'    => sprintf(
								// translators: %s: Milestone label.
								__( 'Add %s', 'upstream' ),
								esc_html( $label )
							),
							'remove_button' => sprintf(
								// translators: %s: Milestone label.
								__( 'Delete %s', 'upstream' ),
								esc_html( $label )
							),
							'sortable'      => upstream_admin_permissions( 'sort_project_milestones' ),
						),
					)
				);

				$fields = array();

				$fields[0] = array(
					'id'         => 'id',
					'type'       => 'text',
					'before'     => 'upstream_add_field_attributes',
					'attributes' => array(
						'class' => 'hidden',
					),
				);

				$allow_comments = upstream_are_comments_enabled_on_milestones();
				if ( $allow_comments ) {
					$fields[0]['before_row'] = '
                    <div class="up-c-tabs-header nav-tab-wrapper nav-tab-wrapper">
                      <a href="#" class="nav-tab nav-tab-active up-o-tab up-o-tab-data" role="tab" data-target=".up-c-tab-content-data">' . __(
						'Data',
						'upstream'
					) . '</a>
                      <a href="#" class="nav-tab up-o-tab up-o-tab-comments" role="tab" data-target=".up-c-tab-content-comments">' . __( 'Comments' ) . '</a>
                    </div>
                    <div class="up-c-tabs-content">
                      <div class="up-o-tab-content up-c-tab-content-data is-active">';
				}

				$fields[1] = array(
					'id'         => 'created_by',
					'type'       => 'text',
					'attributes' => array(
						'class' => 'hidden',
					),
				);
				$fields[2] = array(
					'id'         => 'created_time',
					'type'       => 'text',
					'attributes' => array(
						'class' => 'hidden',
					),
				);

				// start row.
				$fields[10] = array(
					'name'        => esc_html( $label ),
					'id'          => 'milestone',
					'type'        => 'text',
					'permissions' => 'milestone_milestone_field',
					'before'      => 'upstream_add_field_attributes',
					'attributes'  => array(
						'class' => 'milestone',
					),
				);

				$index_assigned_to = 11;
				if ( ! upstream_disable_milestone_categories() ) {
					// Start row.
					$fields[11] = array(
						'name'             => upstream_milestone_category_label(),
						'id'               => 'categories',
						'type'             => 'select2',
						'permissions'      => 'milestone_milestone_field',
						'before'           => 'upstream_add_field_attributes',
						'show_option_none' => true,
						'options_cb'       => 'upstream_admin_get_milestone_categories',
					);
					// Move the Assigned To field to a next line.
					$index_assigned_to = 20;
				}

				$fields[ $index_assigned_to ] = array(
					'name'             => __( 'Assigned To', 'upstream' ),
					'id'               => 'assigned_to',
					'type'             => 'select2',
					'permissions'      => 'milestone_assigned_to_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true,
					'options_cb'       => 'upstream_admin_get_all_project_users',
				);

				// start row.
				$fields[30] = array(
					'name'        => __( 'Start Date', 'upstream' ),
					'id'          => 'start_date',
					'type'        => 'up_timestamp',
					'date_format' => 'Y-m-d',
					'permissions' => 'milestone_start_date_field',
					'before'      => 'upstream_add_field_attributes',
					'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
					'attributes'  => array(
						// 'data-validation'     => 'required',
					),
				);
				$fields[31] = array(
					'name'        => __( 'End Date', 'upstream' ),
					'id'          => 'end_date',
					'type'        => 'up_timestamp',
					'date_format' => 'Y-m-d',
					'permissions' => 'milestone_end_date_field',
					'before'      => 'upstream_add_field_attributes',
					'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
					'attributes'  => array(
						// 'data-validation'     => 'required',
					),
				);

				// start row.
				$fields[40] = array(
					'name'        => __( 'Notes', 'upstream' ),
					'id'          => 'notes',
					'type'        => 'wysiwyg',
					'permissions' => 'milestone_notes_field',
					'before'      => 'upstream_add_field_attributes',
					'options'     => array(
						'media_buttons' => true,
						'textarea_rows' => 5,
					),
					'escape_cb'   => 'upstream_apply_oembed_filters_to_wysiwyg_editor_content',
				);

				if ( $allow_comments ) {
					$fields[50] = array(
						'name'      => '&nbsp;',
						'id'        => 'comments',
						'type'      => 'comments',
						'after_row' => '</div><div class="up-o-tab-content up-c-tab-content-comments"></div></div>',
					);
				}

				// set up the group grid plugin.
				$cmb2_group_grid = $cmb2_grid->addCmb2GroupGrid( $group_field_id );

				// define nuber of rows.
				$rows = apply_filters( 'upstream_milestone_metabox_rows', 4 );

				// filter the fields & sort numerically.
				$fields = apply_filters( 'upstream_milestone_metabox_fields', $fields );
				ksort( $fields );

				// loop through ordered fields and add them to the group.
				if ( $fields ) {
					foreach ( $fields as $key => $value ) {
						$fields[ $key ] = $metabox->add_group_field( $group_field_id, $value );
					}
				}

				// loop through number of rows.
				for ( $i = 0; $i < $rows; $i++ ) {

					// add each row.
					$row[ $i ] = $cmb2_group_grid->addRow();

					// this is our hidden row that must remain as is.
					if ( 0 == $i ) {
						$row[0]->addColumns( array( $fields[0], $fields[1], $fields[2] ) );
					} else {
						// this allows up to 4 columns in each row.
						$array = array();
						if ( isset( $fields[ $i * 10 ] ) ) {
							$array[] = $fields[ $i * 10 ];
						}
						if ( isset( $fields[ $i * 10 + 1 ] ) ) {
							$array[] = $fields[ $i * 10 + 1 ];
						}
						if ( isset( $fields[ $i * 10 + 2 ] ) ) {
							$array[] = $fields[ $i * 10 + 2 ];
						}
						if ( isset( $fields[ $i * 10 + 3 ] ) ) {
							$array[] = $fields[ $i * 10 + 3 ];
						}

						// Ignore empty rows.
						if ( empty( $array ) ) {
							continue;
						}

						// add the fields as columns.
						// probably don't need this to be filterable but will leave it for now.
						$row[ $i ]->addColumns(
							apply_filters( "upstream_milestone_row_{$i}_columns", $array )
						);
					}
				}
			}

			if ( $user_has_admin_permissions ) {
				$metabox->add_field(
					array(
						'id'          => $this->prefix . 'disable_milestones',
						'type'        => 'checkbox',
						'description' => __( 'Disable Milestones for this project', 'upstream' ),
					)
				);
			}
		}

		/**
		 * Return HTML of all admin filters for Milestones.
		 *
		 * @return  string
		 * @since   1.15.0
		 * @access  private
		 */
		private function get_milestones_filters_html() {
			$users           = upstream_admin_get_all_project_users();
			$prefix          = 'milestones-filter-';
			$current_user_id = get_current_user_id();

			ob_start(); ?>
			<div class="up-c-filters">
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'milestone' ); ?>"><?php echo esc_html( upstream_milestone_label() ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'milestone' ); ?>" class="up-o-filter"
						data-column="milestone" data-trigger_on="keyup" data-compare-operator="contains">
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'assignee' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Assignee',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'assignee' ); ?>" class="up-o-filter o-select2"
							data-column="assigned_to" data-placeholder="" data-compare-operator="contains" multiple>
						<option></option>
						<option value="<?php echo esc_attr( $current_user_id ); ?>"><?php esc_html_e( 'Me', 'upstream' ); ?></option>
						<option value="__none__"><?php esc_html_e( 'Nobody', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Users' ); ?>">
							<?php foreach ( $users as $user_id => $user_name ) : ?>
								<?php
								if ( (int) $user_id === $current_user_id ) {
									continue;
								}
								?>
								<option value="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $user_name ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'start_date' ); ?>"><?php esc_html_e( 'Start Date', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'start_date' ); ?>" class="up-o-filter up-o-filter-date"
						data-column="start_date" data-compare-operator=">=">
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'end_date' ); ?>"><?php esc_html_e( 'End Date', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'end_date' ); ?>" class="up-o-filter up-o-filter-date"
						data-column="end_date" data-compare-operator="<=">
				</div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_clean();

			return $html;
		}

		/**
		 * Return HTML of all admin filters for Tasks.
		 *
		 * @return  string
		 * @since   1.15.0
		 * @access  private
		 */
		private function get_tasks_filters_html() {
			$users           = upstream_admin_get_all_project_users();
			$prefix          = 'tasks-filter-';
			$current_user_id = get_current_user_id();
			$statuses        = get_option( 'upstream_tasks' );
			$statuses        = $statuses['statuses'];
			$project_id      = upstream_post_id();

			$milestones = array();
			$rowset     = \UpStream\Milestones::getInstance()->get_milestones_from_project( $project_id, true );
			foreach ( $rowset as $data ) {
				if ( ! isset( $data['id'] )
					|| ! isset( $data['created_by'] )
					|| ! isset( $data['milestone'] )
				) {
					continue;
				}

				$milestones[ $data['id'] ] = $data['milestone'];
			}
			unset( $data, $rowset );

			ob_start();
			?>
			<div class="up-c-filters">
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'title' ); ?>"><?php esc_html_e( 'Title', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'title' ); ?>" class="up-o-filter" data-column="title"
						data-trigger_on="keyup" data-compare-operator="contains">
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'assignee' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Assignee',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'assignee' ); ?>" class="up-o-filter o-select2"
							data-column="assigned_to" multiple data-placeholder="" data-compare-operator="contains">
						<option></option>
						<option value="<?php echo esc_attr( $current_user_id ); ?>"><?php esc_html_e( 'Me', 'upstream' ); ?></option>
						<option value="__none__"><?php esc_html_e( 'Nobody', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Users' ); ?>">
							<?php foreach ( $users as $user_id => $user_name ) : ?>
								<?php
								if ( (int) $user_id === $current_user_id ) {
									continue;
								}
								?>
								<option value="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $user_name ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'status' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Status',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'status' ); ?>" class="up-o-filter o-select2" data-column="status"
							data-placeholder="" multiple data-compare-operator="contains">
						<option></option>
						<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Statuses', 'upstream' ); ?>">
							<?php foreach ( $statuses as $status ) : ?>
								<option value="<?php echo esc_attr( $status['name'] ); ?>"><?php echo esc_html( $status['name'] ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<?php
				if ( upstream_are_milestones_disabled()
					&& upstream_disable_milestones() ) :
					?>
					<div class="up-c-filter">
						<label for="<?php echo esc_attr( $prefix . 'milestone' ); ?>"
							class="up-s-mb-2"><?php echo esc_html( upstream_milestone_label() ); ?></label>
						<select id="<?php echo esc_attr( $prefix . 'milestone' ); ?>" class="up-o-filter o-select2"
							data-column="milestone" data-placeholder="" multiple data-compare-operator="contains">
							<option></option>
							<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
							<optgroup label="<?php echo esc_attr( upstream_milestone_label_plural() ); ?>">
								<?php foreach ( $milestones as $milestone_id => $milestone_title ) : ?>
									<option value="<?php echo esc_attr( $milestone_id ); ?>"><?php echo esc_html( $milestone_title ); ?></option>
								<?php endforeach; ?>
							</optgroup>
						</select>
					</div>
				<?php endif; ?>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'start_date' ); ?>"><?php esc_html_e( 'Start Date', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'start_date' ); ?>" class="up-o-filter up-o-filter-date"
						data-column="start_date" data-compare-operator=">=">
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'end_date' ); ?>"><?php esc_html_e( 'End Date', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'end_date' ); ?>" class="up-o-filter up-o-filter-date"
						data-column="end_date" data-compare-operator="<=">
				</div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_clean();

			return $html;
		}

		/**
		 * Return HTML of all admin filters for Bugs.
		 *
		 * @return  string
		 * @since   1.15.0
		 * @access  private
		 */
		private function get_bugs_filters_html() {
			$users           = upstream_admin_get_all_project_users();
			$prefix          = 'bugs-filter-';
			$current_user_id = get_current_user_id();
			$bugs_settings   = get_option( 'upstream_bugs' );
			$statuses        = $bugs_settings['statuses'];
			$severities      = $bugs_settings['severities'];

			unset( $bugs_settings );

			ob_start();
			?>
			<div class="up-c-filters">
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'title' ); ?>"><?php esc_html_e( 'Title', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'title' ); ?>" class="up-o-filter" data-column="title"
						data-trigger_on="keyup" data-compare-operator="contains">
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'assignee' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Assignee',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'assignee' ); ?>" class="up-o-filter o-select2"
							data-column="assigned_to" data-placeholder="" multiple data-compare-operator="contains">
						<option></option>
						<option value="<?php echo esc_attr( $current_user_id ); ?>"><?php esc_html_e( 'Me', 'upstream' ); ?></option>
						<option value="__none__"><?php esc_html_e( 'Nobody', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Users' ); ?>">
							<?php foreach ( $users as $user_id => $user_name ) : ?>
								<?php
								if ( (int) $user_id === $current_user_id ) {
									continue;
								}
								?>
								<option value="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $user_name ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'severity' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Severities',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'severity' ); ?>" class="up-o-filter o-select2"
							data-column="severity" data-placeholder="" multiple data-compare-operator="contains">
						<option></option>
						<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Severities', 'upstream' ); ?>">
							<?php foreach ( $severities as $severity ) : ?>
								<option
										value="<?php echo esc_attr( $severity['name'] ); ?>"><?php echo esc_html( $severity['name'] ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'status' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Status',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'status' ); ?>" class="up-o-filter o-select2" data-column="status"
							data-placeholder="" multiple data-compare-operator="contains">
						<option></option>
						<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Statuses', 'upstream' ); ?>">
							<?php foreach ( $statuses as $status ) : ?>
								<option value="<?php echo esc_attr( $status['name'] ); ?>"><?php echo esc_html( $status['name'] ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'due_date' ); ?>"><?php esc_html_e( 'Due Date', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'due_date' ); ?>" class="up-o-filter up-o-filter-date"
						data-column="due_date" data-compare-operator="<=">
				</div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_clean();

			return $html;
		}

		/**
		 * Return HTML of all admin filters for Files.
		 *
		 * @return  string
		 * @since   1.15.0
		 * @access  private
		 */
		private function get_files_filters_html() {
			$users           = upstream_admin_get_all_project_users();
			$prefix          = 'files-filter-';
			$current_user_id = get_current_user_id();

			ob_start();
			?>
			<div class="up-c-filters">
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'title' ); ?>"><?php esc_html_e( 'Title', 'upstream' ); ?></label>
					<input type="text" id="<?php echo esc_attr( $prefix . 'title' ); ?>" class="up-o-filter" data-column="title"
						data-trigger_on="keyup" data-compare-operator="contains">
				</div>
				<div class="up-c-filter">
					<label for="<?php echo esc_attr( $prefix . 'uploaded_by' ); ?>" class="up-s-mb-2">
						<?php
						esc_html_e(
							'Uploaded by',
							'upstream'
						);
						?>
					</label>
					<select id="<?php echo esc_attr( $prefix . 'uploaded_by' ); ?>" class="up-o-filter o-select2"
							data-column="created_by" data-placeholder="" multiple data-compare-operator="contains">
						<option></option>
						<option value="<?php echo esc_attr( $current_user_id ); ?>"><?php esc_html_e( 'Me', 'upstream' ); ?></option>
						<option value="__none__"><?php esc_html_e( 'Nobody', 'upstream' ); ?></option>
						<optgroup label="<?php esc_html_e( 'Users' ); ?>">
							<?php foreach ( $users as $user_id => $user_name ) : ?>
								<?php
								if ( (int) $user_id === $current_user_id ) {
									continue;
								}
								?>
								<option value="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $user_name ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_clean();

			return $html;
		}

		/*
		======================================================================================
												TASKS
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function tasks() {
			$are_tasks_disabled         = upstream_are_tasks_disabled();
			$user_has_admin_permissions = upstream_admin_permissions( 'disable_project_tasks' );

			if ( upstream_disable_tasks() || ( $are_tasks_disabled && ! $user_has_admin_permissions ) ) {
				return;
			}

			$label        = upstream_task_label();
			$label_plural = upstream_task_label_plural();

			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'tasks',
					'title'        => '<span class="dashicons dashicons-admin-tools"></span> ' . esc_html( $label_plural ),
					'object_types' => array( $this->type ),
				)
			);

			// Create a default grid.
			$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

			/*
			 * Outputs some hidden data for dynamic use.
			 */
			$metabox->add_field(
				array(
					'id'          => $this->prefix . 'hidden',
					'type'        => 'title',
					'description' => '',
					'after'       => 'upstream_admin_output_task_hidden_data',
					'attributes'  => array(
						'class'        => 'hidden',
						'data-empty'   => upstream_empty_group( 'tasks' ),
						'data-publish' => upstream_admin_permissions( 'publish_project_tasks' ),
					),
				)
			);

			$group_field_id = $metabox->add_field(
				array(
					'id'           => $this->prefix . 'tasks',
					'type'         => 'group',
					'description'  => '',
					'permissions'  => 'delete_project_tasks', // also set on individual row level.
					'options'      => array(
						'group_title'   => esc_html( $label ) . ' {#}',
						'add_button'    => sprintf(
							// translators: %s: task label.
							__( 'Add %s', 'upstream' ),
							esc_html( $label )
						),
						'remove_button' => sprintf(
							// translators: %s: task label.
							__( 'Delete %s', 'upstream' ),
							esc_html( $label )
						),
						'sortable'      => upstream_admin_permissions( 'sort_project_tasks' ), // beta.
					),
					'before_group' => $this->get_tasks_filters_html(),
				)
			);

			if ( ! $are_tasks_disabled ) {
				$fields = array();

				$fields[0] = array(
					'id'          => 'id',
					'type'        => 'text',
					'before'      => 'upstream_add_field_attributes',
					'permissions' => '',
					'attributes'  => array(
						'class' => 'hidden',
					),
				);

				$allow_comments = upstream_are_comments_enabled_on_tasks();
				if ( $allow_comments ) {
					$fields[0]['before_row'] = '
                    <div class="up-c-tabs-header nav-tab-wrapper nav-tab-wrapper">
                      <a href="#" class="nav-tab nav-tab-active up-o-tab up-o-tab-data" role="tab" data-target=".up-c-tab-content-data">' . __(
						'Data',
						'upstream'
					) . '</a>
                      <a href="#" class="nav-tab up-o-tab up-o-tab-comments" role="tab" data-target=".up-c-tab-content-comments">' . __( 'Comments' ) . '</a>
                    </div>
                    <div class="up-c-tabs-content">
                      <div class="up-o-tab-content up-c-tab-content-data is-active">';
				}

				$fields[1] = array(
					'id'         => 'created_by',
					'type'       => 'text',
					'attributes' => array(
						'class' => 'hidden',
					),
				);
				$fields[2] = array(
					'id'         => 'created_time',
					'type'       => 'text',
					'attributes' => array(
						'class' => 'hidden',
					),
				);

				// start row.
				$fields[10] = array(
					'name'        => __( 'Title', 'upstream' ),
					'id'          => 'title',
					'type'        => 'text',
					'permissions' => 'task_title_field',
					'before'      => 'upstream_add_field_attributes',
					'attributes'  => array(
						'class' => 'task-title',
						// 'data-validation'     => 'required',
					),
				);

				$fields[11] = array(
					'name'             => __( 'Assigned To', 'upstream' ),
					'id'               => 'assigned_to',
					'type'             => 'select2',
					'permissions'      => 'task_assigned_to_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true,
					'options_cb'       => 'upstream_admin_get_all_project_users',
				);

				// start row.
				$fields[20] = array(
					'name'             => __( 'Status', 'upstream' ),
					'id'               => 'status',
					'type'             => 'select',
					'permissions'      => 'task_status_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true,  // ** IMPORTANT - do not enforce a value in this field.
					// An row with no value here is considered to be a deleted row.
					'options_cb'       => 'upstream_admin_get_task_statuses',
					'attributes'       => array(
						'class' => 'task-status',
					),
				);

				$fields[21] = array(
					'name'        => __( 'Progress', 'upstream' ),
					'id'          => 'progress',
					'type'        => 'select',
					'permissions' => 'task_progress_field',
					'before'      => 'upstream_add_field_attributes',
					'options_cb'  => 'upstream_get_percentages_for_dropdown',
					'attributes'  => array(
						'class' => 'task-progress',
					),
				);

				// start row.
				$fields[30] = array(
					'name'        => __( 'Start Date', 'upstream' ),
					'id'          => 'start_date',
					'type'        => 'up_timestamp',
					'date_format' => 'Y-m-d',
					'permissions' => 'task_start_date_field',
					'before'      => 'upstream_add_field_attributes',
					'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
				);
				$fields[31] = array(
					'name'        => __( 'End Date', 'upstream' ),
					'id'          => 'end_date',
					'type'        => 'up_timestamp',
					'date_format' => 'Y-m-d',
					'permissions' => 'task_end_date_field',
					'before'      => 'upstream_add_field_attributes',
					'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
				);

				$fields[40] = array(
					'name'        => __( 'Notes', 'upstream' ),
					'id'          => 'notes',
					'type'        => 'wysiwyg',
					'permissions' => 'task_notes_field',
					'before'      => 'upstream_add_field_attributes',
					'options'     => array(
						'media_buttons' => true,
						'textarea_rows' => 5,
					),
					'escape_cb'   => 'upstream_apply_oembed_filters_to_wysiwyg_editor_content',
				);

				if ( ! upstream_are_milestones_disabled() && ! upstream_disable_milestones() ) {
					$fields[41] = array(
						'name'             => '<span class="dashicons dashicons-flag"></span> ' . esc_html( upstream_milestone_label() ),
						'id'               => 'milestone',
						'desc'             =>
							__(
								'Selecting a milestone will count this task\'s progress toward that milestone as well as overall project progress.',
								'upstream'
							),
						'type'             => 'select',
						'permissions'      => 'task_milestone_field',
						'before'           => 'upstream_add_field_attributes',
						'show_option_none' => true,
						'options_cb'       => 'upstream_admin_get_project_milestones',
						'attributes'       => array(
							'class' => 'task-milestone',
						),
					);
				} else {
					$fields[41] = array(
						'id'          => 'milestone',
						'type'        => 'text',
						'permissions' => 'task_milestone_field',
						'attributes'  => array(
							'class' => 'hidden',
						),
					);
				}

				if ( $allow_comments ) {
					$fields[50] = array(
						'name'      => '&nbsp;',
						'id'        => 'comments',
						'type'      => 'comments',
						'after_row' => '</div><div class="up-o-tab-content up-c-tab-content-comments"></div></div>',
					);
				}

				// set up the group grid plugin.
				$cmb2_group_grid = $cmb2_grid->addCmb2GroupGrid( $group_field_id );

				// define nuber of rows.
				$rows = apply_filters( 'upstream_task_metabox_rows', 10 );

				// filter the fields & sort numerically.
				$fields = apply_filters( 'upstream_task_metabox_fields', $fields );
				ksort( $fields );

				// loop through ordered fields and add them to the group.
				if ( $fields ) {
					foreach ( $fields as $key => $value ) {
						$fields[ $key ] = $metabox->add_group_field( $group_field_id, $value );
					}
				}

				// loop through number of rows.
				for ( $i = 0; $i < 10; $i++ ) {

					// add each row.
					$row[ $i ] = $cmb2_group_grid->addRow();

					// this is our hidden row that must remain as is.
					if ( 0 == $i ) {
						$row[0]->addColumns( array( $fields[0], $fields[1], $fields[2] ) );
					} else {

						// this allows up to 4 columns in each row.
						$array = array();
						if ( isset( $fields[ $i * 10 ] ) ) {
							$array[] = $fields[ $i * 10 ];
						}
						if ( isset( $fields[ $i * 10 + 1 ] ) ) {
							$array[] = $fields[ $i * 10 + 1 ];
						}
						if ( isset( $fields[ $i * 10 + 2 ] ) ) {
							$array[] = $fields[ $i * 10 + 2 ];
						}
						if ( isset( $fields[ $i * 10 + 3 ] ) ) {
							$array[] = $fields[ $i * 10 + 3 ];
						}

						if ( empty( $array ) ) {
							continue;
						}
						// add the fields as columns.
						$row[ $i ]->addColumns(
							apply_filters( "upstream_task_row_{$i}_columns", $array )
						);
					}
				}
			}

			if ( $user_has_admin_permissions ) {
				$metabox->add_field(
					array(
						'id'          => $this->prefix . 'disable_tasks',
						'type'        => 'checkbox',
						'description' => __( 'Disable Tasks for this project', 'upstream' ),
					)
				);
			}
		}

		/**
		 * Comments Fields Nonce
		 *
		 * @var bool
		 */
		private static $comments_fields_nonce = false;

		/**
		 * Items Comments Section Cache
		 *
		 * @var array
		 */
		private static $items_comments_section_cache = array();

		/**
		 * Render Comments Field
		 *
		 * @param  mixed $field Field.
		 * @param  mixed $escaped_value Escaped Value.
		 * @param  mixed $object_id Object Id.
		 * @param  mixed $object_type Object Type.
		 * @param  mixed $field_type Field Type.
		 * @return void
		 */
		public static function render_comments_field( $field, $escaped_value, $object_id, $object_type, $field_type ) {
			if ( ! self::$comments_fields_nonce ) {
				wp_nonce_field( 'project.get_all_items_comments', 'project_all_items_comments_nonce' );
				self::$comments_fields_nonce = true;
			}

			$field_id = $field->args['id'];

			if ( ! isset( self::$items_comments_section_cache[ $field_id ] ) ) {
				$editor_identifier = $field_id . '_editor';

				preg_match( '/^_upstream_project_([a-z]+)_([0-9]+)_comments/i', $field_id, $matches );

				echo '<div class="c-comments" data-type="' . esc_attr( rtrim( $matches[1], 's' ) ) . '"></div>';

				wp_nonce_field( 'upstream:project.' . $matches[1] . '.add_comment', $field_id . '_add_comment_nonce' );

				wp_editor(
					'',
					$editor_identifier,
					array(
						'media_buttons' => true,
						'textarea_rows' => 5,
						'textarea_name' => $editor_identifier,
					)
				);

				self::$items_comments_section_cache[ $field_id ] = 1;
			}
		}


		/*
		======================================================================================
												BUGS
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function bugs() {
			$are_bugs_disabled          = upstream_are_bugs_disabled();
			$user_has_admin_permissions = upstream_admin_permissions( 'disable_project_bugs' );

			if ( upstream_disable_bugs() || ( $are_bugs_disabled && ! $user_has_admin_permissions ) ) {
				return;
			}

			$label        = upstream_bug_label();
			$label_plural = upstream_bug_label_plural();

			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'bugs',
					'title'        => '<span class="dashicons dashicons-warning"></span> ' . esc_html( $label_plural ),
					'object_types' => array( $this->type ),
					'attributes'   => array( 'data-test' => 'test' ),
				)
			);

			// Create a default grid.
			$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

			/*
			 * Outputs some hidden data for dynamic use.
			 */
			$metabox->add_field(
				array(
					'id'          => $this->prefix . 'hidden',
					'type'        => 'title',
					'description' => '',
					'after'       => 'upstream_admin_output_bug_hidden_data',
					'attributes'  => array(
						'class'        => 'hidden',
						'data-empty'   => upstream_empty_group( 'bugs' ),
						'data-publish' => upstream_admin_permissions( 'publish_project_bugs' ),
					),
				)
			);

			$group_field_id = $metabox->add_field(
				array(
					'id'           => $this->prefix . 'bugs',
					'type'         => 'group',
					'description'  => '',
					'permissions'  => 'delete_project_bugs', // also set on individual row level.
					'options'      => array(
						'group_title'   => esc_html( $label ) . ' {#}',
						'add_button'    => sprintf(
							// translators: %s: Bug label.
							__( 'Add %s', 'upstream' ),
							esc_html( $label )
						),
						'remove_button' => sprintf(
							// translators: %s: Bug label.
							__( 'Delete %s', 'upstream' ),
							esc_html( $label )
						),
						'sortable'      => upstream_admin_permissions( 'sort_project_bugs' ),
					),
					'before_group' => $this->get_bugs_filters_html(),
				)
			);

			if ( ! $are_bugs_disabled ) {
				$fields = array();

				$fields[0] = array(
					'id'         => 'id',
					'type'       => 'text',
					'before'     => 'upstream_add_field_attributes',
					'attributes' => array(
						'class' => 'hidden',
					),
				);

				$allow_comments = upstream_are_comments_enabled_on_bugs();
				if ( $allow_comments ) {
					$fields[0]['before_row'] = '
                    <div class="up-c-tabs-header nav-tab-wrapper nav-tab-wrapper">
                      <a href="#" class="nav-tab nav-tab-active up-o-tab up-o-tab-data" role="tab" data-target=".up-c-tab-content-data">' . __(
						'Data',
						'upstream'
					) . '</a>
                      <a href="#" class="nav-tab up-o-tab up-o-tab-comments" role="tab" data-target=".up-c-tab-content-comments">' . __( 'Comments' ) . '</a>
                    </div>
                    <div class="up-c-tabs-content">
                      <div class="up-o-tab-content up-c-tab-content-data is-active">';
				}

				$fields[1] = array(
					'id'         => 'created_by',
					'type'       => 'text',
					'attributes' => array(
						'class' => 'hidden',
					),
				);
				$fields[2] = array(
					'id'         => 'created_time',
					'type'       => 'text',
					'attributes' => array(
						'class' => 'hidden',
					),
				);

				// start row.
				$fields[10] = array(
					'name'        => __( 'Title', 'upstream' ),
					'id'          => 'title',
					'type'        => 'text',
					'permissions' => 'bug_title_field',
					'before'      => 'upstream_add_field_attributes',
					'attributes'  => array(
						'class' => 'bug-title',
					),
				);

				$fields[11] = array(
					'name'             => __( 'Assigned To', 'upstream' ),
					'id'               => 'assigned_to',
					'type'             => 'select2',
					'permissions'      => 'bug_assigned_to_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true,
					'options_cb'       => 'upstream_admin_get_all_project_users',
				);

				// start row.
				$fields[20] = array(
					'name'        => __( 'Description', 'upstream' ),
					'id'          => 'description',
					'type'        => 'wysiwyg',
					'permissions' => 'bug_description_field',
					'before'      => 'upstream_add_field_attributes',
					'options'     => array(
						'media_buttons' => true,
						'textarea_rows' => 5,
					),
					'escape_cb'   => 'upstream_apply_oembed_filters_to_wysiwyg_editor_content',
				);

				// start row.
				$fields[30] = array(
					'name'             => __( 'Status', 'upstream' ),
					'id'               => 'status',
					'type'             => 'select',
					'permissions'      => 'bug_status_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true, // ** IMPORTANT - do not enforce a value in this field.
					// An row with no value here is considered to be a deleted row.
					'options_cb'       => 'upstream_admin_get_bug_statuses',
					'attributes'       => array(
						'class' => 'bug-status',
					),
				);
				$fields[31] = array(
					'name'             => __( 'Severity', 'upstream' ),
					'id'               => 'severity',
					'type'             => 'select',
					'permissions'      => 'bug_severity_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true,
					'options_cb'       => 'upstream_admin_get_bug_severities',
					'attributes'       => array(
						'class' => 'bug-severity',
					),
				);

				// start row.

				if ( upstream_filesytem_enabled() ) {
					$fields[40] = array(
						'name'        => __( 'Attachments', 'upstream' ),
						'desc'        => '',
						'id'          => 'file',
						'type'        => 'upfs',
						'permissions' => 'bug_files_field',
						'before'      => 'upstream_add_field_attributes',
						'options'     => array(
							'url' => false, // Hide the text input for the url.
						),
					);

				} else {
					$fields[40] = array(
						'name'        => __( 'Attachments', 'upstream' ),
						'desc'        => '',
						'id'          => 'file',
						'type'        => 'file',
						'permissions' => 'bug_files_field',
						'before'      => 'upstream_add_field_attributes',
						'options'     => array(
							'url' => false, // Hide the text input for the url.
						),
					);
				}

				$fields[41] = array(
					'name'        => __( 'Due Date', 'upstream' ),
					'id'          => 'due_date',
					'type'        => 'up_timestamp',
					'date_format' => 'Y-m-d',
					'permissions' => 'bug_due_date_field',
					'before'      => 'upstream_add_field_attributes',
					'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
				);

				if ( $allow_comments ) {
					$fields[50] = array(
						'name'      => '&nbsp;',
						'id'        => 'comments',
						'type'      => 'comments',
						'after_row' => '</div><div class="up-o-tab-content up-c-tab-content-comments"></div></div>',
					);
				}

				// set up the group grid plugin.
				$cmb2_group_grid = $cmb2_grid->addCmb2GroupGrid( $group_field_id );

				// define nuber of rows.
				$rows = apply_filters( 'upstream_bug_metabox_rows', 5 );

				// filter the fields & sort numerically.
				$fields = apply_filters( 'upstream_bug_metabox_fields', $fields );
				ksort( $fields );

				// loop through ordered fields and add them to the group.
				if ( $fields ) {
					foreach ( $fields as $key => $value ) {
						$fields[ $key ] = $metabox->add_group_field( $group_field_id, $value );
					}
				}

				// loop through number of rows.
				for ( $i = 0; $i < $rows; $i++ ) {

					// add each row.
					$row[ $i ] = $cmb2_group_grid->addRow();

					// this is our hidden row that must remain as is.
					if ( 0 == $i ) {
						$row[0]->addColumns( array( $fields[0], $fields[1], $fields[2] ) );
					} else {

						// this allows up to 4 columns in each row.
						$array = array();
						if ( isset( $fields[ $i * 10 ] ) ) {
							$array[] = $fields[ $i * 10 ];
						}
						if ( isset( $fields[ $i * 10 + 1 ] ) ) {
							$array[] = $fields[ $i * 10 + 1 ];
						}
						if ( isset( $fields[ $i * 10 + 2 ] ) ) {
							$array[] = $fields[ $i * 10 + 2 ];
						}
						if ( isset( $fields[ $i * 10 + 3 ] ) ) {
							$array[] = $fields[ $i * 10 + 3 ];
						}

						// add the fields as columns.
						$row[ $i ]->addColumns(
							apply_filters( "upstream_bug_row_{$i}_columns", $array )
						);
					}
				}
			}

			if ( $user_has_admin_permissions ) {
				$metabox->add_field(
					array(
						'id'          => $this->prefix . 'disable_bugs',
						'type'        => 'checkbox',
						'description' => __( 'Disable Bugs for this project', 'upstream' ),
					)
				);
			}
		}



		/*
		======================================================================================
												SIDEBAR TOP
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function details() {
			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'details',
					'title'        => '<span class="dashicons dashicons-admin-generic"></span> ' . sprintf(
						// translators: %s: Label.
						__(
							'%s Details',
							'upstream'
						),
						$this->project_label
					),
					'object_types' => array( $this->type ),
					'context'      => 'side',
					'priority'     => 'high',
				)
			);

			$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

			$fields = array();

			$fields[0] = array(
				'name'             => __( 'Status', 'upstream' ),
				'desc'             => '',
				'id'               => $this->prefix . 'status',
				'type'             => 'select',
				'show_option_none' => true,
				'permissions'      => 'project_status_field',
				'before'           => 'upstream_add_field_attributes',
				'options_cb'       => 'upstream_admin_get_project_statuses',
				'save_field'       => upstream_admin_permissions( 'project_status_field' ),
			);

			$fields[1] = array(
				'name'             => __( 'Owner', 'upstream' ),
				'desc'             => '',
				'id'               => $this->prefix . 'owner',
				'type'             => 'select',
				'show_option_none' => true,
				'permissions'      => 'project_owner_field',
				'before'           => 'upstream_add_field_attributes',
				'options_cb'       => 'upstream_admin_get_all_project_users',
				'save_field'       => upstream_admin_permissions( 'project_owner_field' ),
			);

			if ( ! upstream_is_clients_disabled() ) {
				$client_label = upstream_client_label();

				$fields[2] = array(
					'name'             => $client_label,
					'desc'             => '',
					'id'               => $this->prefix . 'client',
					'type'             => 'select',
					'show_option_none' => true,
					'permissions'      => 'project_client_field',
					'before'           => 'upstream_add_field_attributes',
					'options_cb'       => 'upstream_admin_get_all_clients',
					'save_field'       => upstream_admin_permissions( 'project_client_field' ),
				);

				$fields[3] = array(
					'name'              => sprintf(
						// translators: %s: client label.
						__( '%s Users', 'upstream' ),
						$client_label
					),
					'id'                => $this->prefix . 'client_users',
					'type'              => 'multicheck',
					'select_all_button' => false,
					'permissions'       => 'project_users_field',
					'before'            => 'upstream_add_field_attributes',
					'options_cb'        => 'upstream_admin_get_all_clients_users',
					'save_field'        => upstream_admin_permissions( 'project_users_field' ),
				);
			}

			$fields[10] = array(
				'name'        => __( 'Start Date', 'upstream' ),
				'desc'        => '',
				'id'          => $this->prefix . 'start',
				'type'        => 'up_timestamp',
				'date_format' => 'Y-m-d',
				'permissions' => 'project_start_date_field',
				'before'      => 'upstream_add_field_attributes',
				'show_on_cb'  => 'upstream_show_project_start_date_field',
				'save_field'  => upstream_admin_permissions( 'project_start_date_field' ),
				'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
			);
			$fields[11] = array(
				'name'        => __( 'End Date', 'upstream' ),
				'desc'        => '',
				'id'          => $this->prefix . 'end',
				'type'        => 'up_timestamp',
				'date_format' => 'Y-m-d',
				'permissions' => 'project_end_date_field',
				'before'      => 'upstream_add_field_attributes',
				'show_on_cb'  => 'upstream_show_project_end_date_field',
				'save_field'  => upstream_admin_permissions( 'project_end_date_field' ),
				'escape_cb'   => array( 'UpStream_Admin', 'escape_cmb2_timestamp_field' ),
			);

			$fields[12] = array(
				'name'        => __( 'Description', 'upstream' ),
				'desc'        => '',
				'id'          => $this->prefix . 'description',
				'type'        => 'wysiwyg',
				'permissions' => 'project_description',
				'before'      => 'upstream_add_field_attributes',
				'options'     => array(
					'media_buttons' => false,
					'textarea_rows' => 3,
					'teeny'         => true,
				),
				'save_field'  => upstream_admin_permissions( 'project_description' ),
			);

			// filter the fields & sort numerically.
			$fields = apply_filters( 'upstream_details_metabox_fields', $fields );
			ksort( $fields );

			// loop through ordered fields and add them to the group.
			if ( $fields ) {
				foreach ( $fields as $key => $value ) {
					$fields[ $key ] = $metabox->add_field( $value );
				}
			}

			$row = $cmb2_grid->addRow();
			$row->addColumns( array( $fields[10], $fields[11] ) );
		}


		/*
		======================================================================================
												Files
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function files() {
			$are_files_disabled         = upstream_are_files_disabled();
			$user_has_admin_permissions = upstream_admin_permissions( 'disable_project_files' );

			if ( upstream_disable_files() || ( $are_files_disabled && ! $user_has_admin_permissions ) ) {
				return;
			}

			$label        = upstream_file_label();
			$label_plural = upstream_file_label_plural();

			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'files',
					'title'        => '<span class="dashicons dashicons-paperclip"></span> ' . esc_html( $label_plural ),
					'object_types' => array( $this->type ),
				)
			);

			// Create a default grid.
			$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

			/*
			 * Outputs some hidden data for dynamic use.
			 */
			$metabox->add_field(
				array(
					'id'          => $this->prefix . 'hidden',
					'type'        => 'title',
					'description' => '',
					// 'after'       => 'upstream_admin_output_files_hidden_data',
					'attributes'  => array(
						'class'        => 'hidden',
						'data-empty'   => upstream_empty_group( 'files' ),
						'data-publish' => upstream_admin_permissions( 'publish_project_files' ),

					),
				)
			);

			$group_field_id = $metabox->add_field(
				array(
					'id'           => $this->prefix . 'files',
					'type'         => 'group',
					'description'  => '',
					'permissions'  => 'delete_project_files', // also set on individual row level.
					'before_group' => $this->get_files_filters_html(),
					'options'      => array(
						'group_title'   => esc_html( $label ) . ' {#}',
						'add_button'    => sprintf(
							// translators: %s: Label.
							__( 'Add %s', 'upstream' ),
							esc_html( $label )
						),
						'remove_button' => sprintf(
							// translators: %s: Label.
							__( 'Delete %s', 'upstream' ),
							esc_html( $label )
						),
						'sortable'      => upstream_admin_permissions( 'sort_project_files' ),
					),
				)
			);

			if ( ! $are_files_disabled ) {
				$fields = array();

				// start row.
				$fields[0] = array(
					'id'         => 'id',
					'type'       => 'text',
					'before'     => 'upstream_add_field_attributes',
					'attributes' => array( 'class' => 'hidden' ),
				);

				$allow_comments = upstream_are_comments_enabled_on_files();
				if ( $allow_comments ) {
					$fields[0]['before_row'] = '
                    <div class="up-c-tabs-header nav-tab-wrapper nav-tab-wrapper">
                      <a href="#" class="nav-tab nav-tab-active up-o-tab up-o-tab-data" role="tab" data-target=".up-c-tab-content-data">' . __(
						'Data',
						'upstream'
					) . '</a>
                      <a href="#" class="nav-tab up-o-tab up-o-tab-comments" role="tab" data-target=".up-c-tab-content-comments">' . __( 'Comments' ) . '</a>
                    </div>
                    <div class="up-c-tabs-content">
                      <div class="up-o-tab-content up-c-tab-content-data is-active">';
				}

				$fields[1] = array(
					'id'         => 'created_by',
					'type'       => 'text',
					'attributes' => array( 'class' => 'hidden' ),
				);
				$fields[2] = array(
					'id'         => 'created_time',
					'type'       => 'text',
					'attributes' => array( 'class' => 'hidden' ),
				);

				// start row.
				$fields[10] = array(
					'name'        => __( 'Title', 'upstream' ),
					'id'          => 'title',
					'type'        => 'text',
					'permissions' => 'file_title_field',
					'before'      => 'upstream_add_field_attributes',
					'attributes'  => array(
						'class' => 'file-title',
					),
				);

				$fields[11] = array(
					'name'             => __( 'Assigned To', 'upstream' ),
					'id'               => 'assigned_to',
					'type'             => 'select2',
					'permissions'      => 'file_assigned_to_field',
					'before'           => 'upstream_add_field_attributes',
					'show_option_none' => true,
					'options_cb'       => 'upstream_admin_get_all_project_users',
				);

				if ( upstream_filesytem_enabled() ) {
					$fields[20] = array(
						'name'        => esc_html( $label ),
						'desc'        => '',
						'id'          => 'file',
						'type'        => 'upfs',
						'permissions' => 'file_files_field',
						'before'      => 'upstream_add_field_attributes',
						'options'     => array(
							'url' => false, // Hide the text input for the url.
						),
					);

				} else {
					$fields[20] = array(
						'name'        => esc_html( $label ),
						'desc'        => '',
						'id'          => 'file',
						'type'        => 'file',
						'permissions' => 'file_files_field',
						'before'      => 'upstream_add_field_attributes',
						'options'     => array(
							'url' => false, // Hide the text input for the url.
						),
					);
				}

				// start row.
				$fields[30] = array(
					'name'        => __( 'Description', 'upstream' ),
					'id'          => 'description',
					'type'        => 'wysiwyg',
					'permissions' => 'file_description_field',
					'before'      => 'upstream_add_field_attributes',
					'options'     => array(
						'media_buttons' => true,
						'textarea_rows' => 3,
					),
				);

				if ( $allow_comments ) {
					$fields[40] = array(
						'name'      => '&nbsp;',
						'id'        => 'comments',
						'type'      => 'comments',
						'after_row' => '</div><div class="up-o-tab-content up-c-tab-content-comments"></div></div>',
					);
				}

				// set up the group grid plugin.
				$cmb2_group_grid = $cmb2_grid->addCmb2GroupGrid( $group_field_id );

				// define nuber of rows.
				$rows = apply_filters( 'upstream_file_metabox_rows', 4 );

				// filter the fields & sort numerically.
				$fields = apply_filters( 'upstream_file_metabox_fields', $fields );
				ksort( $fields );

				// loop through ordered fields and add them to the group.
				if ( $fields ) {
					foreach ( $fields as $key => $value ) {
						$fields[ $key ] = $metabox->add_group_field( $group_field_id, $value );
					}
				}

				// loop through number of rows.
				for ( $i = 0; $i < $rows; $i++ ) {

					// add each row.
					$row[ $i ] = $cmb2_group_grid->addRow();

					// this is our hidden row that must remain as is.
					if ( 0 == $i ) {
						$row[0]->addColumns( array( $fields[0], $fields[1], $fields[2] ) );
					} else {

						// this allows up to 4 columns in each row.
						$array = array();
						if ( isset( $fields[ $i * 10 ] ) ) {
							$array[] = $fields[ $i * 10 ];
						}
						if ( isset( $fields[ $i * 10 + 1 ] ) ) {
							$array[] = $fields[ $i * 10 + 1 ];
						}
						if ( isset( $fields[ $i * 10 + 2 ] ) ) {
							$array[] = $fields[ $i * 10 + 2 ];
						}
						if ( isset( $fields[ $i * 10 + 3 ] ) ) {
							$array[] = $fields[ $i * 10 + 3 ];
						}

						// add the fields as columns.
						$row[ $i ]->addColumns(
							apply_filters( "upstream_file_row_{$i}_columns", $array )
						);
					}
				}
			}

			if ( $user_has_admin_permissions ) {
				$metabox->add_field(
					array(
						'id'          => $this->prefix . 'disable_files',
						'type'        => 'checkbox',
						'description' => __( 'Disable Files for this project', 'upstream' ),
					)
				);
			}
		}


		/*
		======================================================================================
												SIDEBAR LOW
		======================================================================================
		*/

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function sidebar_low() {
			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'activity',
					'title'        => '<span class="dashicons dashicons-update"></span> ' . __( 'Activity', 'upstream' ),
					'object_types' => array( $this->type ),
					'context'      => 'side', // 'normal', 'advanced', or 'side'..
					'priority'     => 'low',  // 'high', 'core', 'default' or 'low'.
				)
			);

			// Create a default grid.
			$cmb2_grid = new \Cmb2Grid\Grid\Cmb2Grid( $metabox );

			/*
			 * Outputs some hidden data for dynamic use.
			 */
			$metabox->add_field(
				array(
					'name'   => '',
					'desc'   => '',
					'id'     => $this->prefix . 'activity',
					'type'   => 'title',
					'before' => 'upstream_activity_buttons',
					'after'  => 'upstream_output_activity',
				)
			);
		}

		/**
		 * Add the metaboxes
		 *
		 * @since  0.1.0
		 */
		public function comments() {
			$are_comments_disabled      = upstream_are_comments_disabled();
			$user_has_admin_permissions = upstream_admin_permissions( 'disable_project_comments' );

			if ( ! self::$allow_project_comments || ( $are_comments_disabled && ! $user_has_admin_permissions ) ) {
				return;
			}

			$metabox = new_cmb2_box(
				array(
					'id'           => $this->prefix . 'discussions',
					'title'        => '<span class="dashicons dashicons-format-chat"></span> ' . esc_html( upstream_discussion_label() ),
					'object_types' => array( $this->type ),
					'priority'     => 'low',
				)
			);

			if ( ! $are_comments_disabled ) {
				$metabox->add_field(
					array(
						'name'         => __( 'Add new Comment' ),
						'desc'         => '',
						'id'           => $this->prefix . 'new_message',
						'type'         => 'wysiwyg',
						'permissions'  => 'publish_project_discussion',
						'before'       => 'upstream_add_field_attributes',
						'after_field'  => '<p class="u-text-right"><button class="button button-primary" type="button" data-action="comments.add_comment" data-nonce="' . wp_create_nonce( 'upstream:project.add_comment' ) . '">' . __(
							'Add Comment',
							'upstream'
						) . '</button></p></div></div>',
						'after_row'    => 'upstream_render_comments_box',
						'options'      => array(
							'media_buttons' => true,
							'textarea_rows' => 5,
						),
						'escape_cb'    => 'upstream_apply_oembed_filters_to_wysiwyg_editor_content',
						'before_field' => '<div class="row"><div class="hidden-xs hidden-sm col-md-12 col-lg-12"><label for="' . $this->prefix . 'new_message">' . __( 'Add new Comment', 'upstream' ) . '</label>',
					)
				);
			}

			if ( $user_has_admin_permissions ) {
				$metabox->add_field(
					array(
						'id'          => $this->prefix . 'disable_comments',
						'type'        => 'checkbox',
						'description' => __( 'Disable Discussion for this project', 'upstream' ),
					)
				);
			}
		}

		/**
		 * This method ensures WordPress generate and show custom slugs based on project's title automaticaly below the field.
		 * Since it will do so only for public posts and Projects-post-type are not public (they would appear on sites searches),
		 * we rapidly make it public and switch back to non-public status. This temporary change will not cause search/visibility side effects.
		 *
		 * Called by the "edit_form_before_permalink" action right before the "edit_form_after_title" hook.
		 *
		 * @since   1.12.3
		 * @static
		 *
		 * @global  $post_type_object
		 */
		public static function make_project_temporarily_public() {
			global $post_type_object;

			if ( 'project' === $post_type_object->name ) {
				$post_type_object->public = true;
			}
		}

		/**
		 * This method is called right after the make_project_temporarily_public() and ensures the project is non-public once again. side effects.
		 *
		 * Called by the "edit_form_after_title" action right after the "edit_form_before_permalink" hook.
		 *
		 * @since   1.12.3
		 * @static
		 *
		 * @see     self::make_project_temporarily_public()
		 *
		 * @global  $post_type_object
		 */
		public static function make_project_private_once_again() {
			global $post_type_object;

			if ( 'project' === $post_type_object->name ) {
				$post_type_object->public = false;
			}
		}

		/**
		 * AJAX endpoint that retrieves all comments from all items on the give project.
		 *
		 * @since   1.13.0
		 * @throws \Exception Exception.
		 * @static
		 */
		public static function fetch_all_items_comments() {
			header( 'Content-Type: application/json' );

			$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();
			$response = array(
				'success' => false,
				'data'    => array(
					'milestones' => array(),
					'tasks'      => array(),
					'bugs'       => array(),
					'files'      => array(),
				),
				'error'   => null,
			);

			try {
				// Check if the request payload is potentially invalid.
				if (
					! defined( 'DOING_AJAX' )
					|| ! DOING_AJAX
					|| empty( $get_data )
					|| ! isset( $get_data['nonce'] )
					|| ! isset( $get_data['project_id'] )
					|| ! wp_verify_nonce( sanitize_text_field( $get_data['nonce'] ), 'project.get_all_items_comments' )
				) {
					throw new \Exception( __( 'Invalid request.', 'upstream' ) );
				}

				// Check if the project exists.
				$project_id = absint( $get_data['project_id'] );
				if ( $project_id <= 0 ) {
					throw new \Exception( __( 'Invalid Project.', 'upstream' ) );
				}

				$users_cache  = array();
				$users_rowset = get_users(
					array(
						'fields' => array(
							'ID',
							'display_name',
						),
					)
				);
				foreach ( $users_rowset as $user_row ) {
					$user_row->ID *= 1;

					$users_cache[ $user_row->ID ] = (object) array(
						'id'     => $user_row->ID,
						'name'   => $user_row->display_name,
						'avatar' => upstream_get_user_avatar_url( $user_row->ID ),
					);
				}
				unset( $user_row, $users_rowset );

				$date_format          = get_option( 'date_format' );
				$time_format          = get_option( 'time_format' );
				$the_date_time_format = $date_format . ' ' . $time_format;
				$current_timestamp    = time();

				$user                        = wp_get_current_user();
				$user_has_admin_capabilities = upstream_is_user_either_manager_or_admin( $user );
				$user_can_reply              = ! $user_has_admin_capabilities ? user_can(
					$user,
					'publish_project_discussion'
				) : true;
				$user_can_moderate           = ! $user_has_admin_capabilities ? user_can( $user, 'moderate_comments' ) : true;
				$user_can_delete             = ! $user_has_admin_capabilities ? $user_can_moderate || user_can(
					$user,
					'delete_project_discussion'
				) : true;

				$comments_statuses = array( 'approve' );
				if ( $user_has_admin_capabilities || $user_can_moderate ) {
					$comments_statuses[] = 'hold';
				}

				$items_types = array( 'milestones', 'tasks', 'bugs', 'files' );
				foreach ( $items_types as $item_type ) {
					$item_type_singular = rtrim( $item_type, 's' );

					if ( 'milestones' === $item_type ) {
						$rowset = \UpStream\Milestones::getInstance()->get_milestones_from_project( $project_id, true );
					} else {
						$rowset = array_filter(
							(array) get_post_meta(
								$project_id,
								'_upstream_project_' . $item_type,
								true
							)
						);
					}
					if ( count( $rowset ) > 0 ) {
						foreach ( $rowset as $row ) {
							if ( ! is_array( $row )
								|| ! isset( $row['id'] )
								|| empty( $row['id'] )
							) {
								continue;
							}

							$comments = (array) get_comments(
								array(
									'post_id'    => $project_id,
									'status'     => $comments_statuses,
									'meta_query' => array(
										'relation' => 'AND',
										array(
											'key'   => 'type',
											'value' => $item_type_singular,
										),
										array(
											'key'   => 'id',
											'value' => $row['id'],
										),
									),
								)
							);

							if ( count( $comments ) > 0 ) {
								$response['data'][ $item_type ][ $row['id'] ] = array();

								foreach ( $comments as $comment ) {
									$author = $users_cache[ (int) $comment->user_id ];

									$date = DateTime::createFromFormat( 'Y-m-d H:i:s', $comment->comment_date_gmt );

									$comment_data = json_decode(
										json_encode(
											array(
												'id'      => $comment->comment_ID,
												'parent_id' => $comment->comment_parent,
												'content' => $comment->comment_content,
												'state'   => $comment->comment_approved,
												'created_by' => $author,
												'created_at' => array(
													'localized' => '',
													'humanized' => sprintf(
														// translators: %s : human-readable time difference.
														_x( '%s ago', '%s = human-readable time difference', 'upstream' ),
														human_time_diff( $date->getTimestamp(), $current_timestamp )
													),
												),
												'current_user_cap' => array(
													'can_reply'    => $user_can_reply,
													'can_moderate' => $user_can_moderate,
													'can_delete'   => $user_can_delete || $author->id === $user->ID,
												),
											)
										)
									);

									$comment_data->created_at->localized = $date->format( $the_date_time_format );

									$comments_cache = array();
									if ( (int) $comment->comment_parent > 0 ) {
										$parent         = get_comment( $comment->comment_parent );
										$comments_cache = array(
											$parent->comment_ID => json_decode(
												json_encode(
													array(
														'created_by' => array(
															'name' => $parent->comment_author,
														),
													)
												)
											),
										);
									}

									ob_start();
									upstream_admin_display_message_item( $comment_data, $comments_cache );
									$response['data'][ $item_type ][ $row['id'] ][] = ob_get_contents();
									ob_end_clean();
								}
							}
						}
					}
				}

				$response['success'] = true;
			} catch ( Exception $e ) {
				$response['error'] = $e->getMessage();
			}

			wp_send_json( $response );
		}

		/**
		 * Define Upfs CMB2 field settings.
		 *
		 * @param \CMB2_Field $field      Current CMB2_Field object.
		 * @param string      $value      Current escaped field value.
		 * @param int         $object_id  Project ID.
		 * @param string      $object_type Current object type.
		 * @param \CMB2_Types $field_type  Current field type object.
		 *
		 * @since   1.16.0
		 * @static
		 */
		public static function render_upfs_field( $field, $value, $object_id, $object_type, $field_type ) {
			$field_name = $field->args['_name'];
			if ( ! preg_match( '/\[\]$/', $field_name ) ) {
				$field_name .= '[]';
			}

			$options = array();
			if ( count( $field->args['options'] ) === 0 ) {
				if ( ! empty( $field->args['options_cb'] ) && is_callable( $field->args['options_cb'] ) ) {
					$options = call_user_func( $field->args['options_cb'] );
				}
			}

			if ( is_array( $value ) && count( $value ) > 0 ) {
				$value = $value[0];
			}

			$file = upstream_upfs_info( $value );

			if ( $value && $file ) {
				$url = upstream_upfs_get_file_url( $value );
				?>
				<a href="<?php print esc_url( $url ); ?>"><?php echo esc_html( $file->orig_filename ); ?></a>
				<a href="#" onclick="jQuery('#<?php echo esc_attr( $field->args['id'] ); ?>').attr('type','file');jQuery(this).parent().children('a').remove();return false;"><?php esc_html_e( '(remove)', 'upstream' ); ?></a>
				<input type="hidden" class="upfs-hidden" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $field->args['id'] ); ?>"/>
				<?php

			} else {

				?>
				<input type="file" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $field->args['id'] ); ?>"/>
				<?php
			}
		}

		/**
		 * Sanitizes Upfs fields before they're saved.
		 *
		 * @param mixed          $override_value Sanitization override value to return.
		 * @param mixed          $value         Actual field value.
		 * @param int            $object_id     Project ID.
		 * @param string         $object_type   Current object type.
		 * @param \CMB2_Sanitize $sanitizer     Current sanitization object.
		 *
		 * @since   1.16.0
		 * @static
		 */
		public static function sanitize_upfs_field( $override_value, $value, $object_id, $object_type, $sanitizer ) {
			if ( is_array( $value ) && count( $value ) > 0 && $value[0] ) {
				return $value[0];
			}

			$files_data = isset( $_FILES ) ? wp_unslash( $_FILES ) : array();

			if ( $sanitizer->field->group && $sanitizer->field->group->cmb_id ) {

				$fitem = $sanitizer->field->group->cmb_id;
				$fno   = $sanitizer->field->group->index;
				$fid   = $object_type['_id'];
				$value = '';

				if ( ! empty( $files_data[ $fitem ]['name'][ $fno ][ $fid ][0] ) ) {

					// these are checked to be a valid file in the next function.
					$file = array(
						'name'     => sanitize_text_field( $files_data[ $fitem ]['name'][ $fno ][ $fid ][0] ),
						'type'     => sanitize_text_field( $files_data[ $fitem ]['type'][ $fno ][ $fid ][0] ),
						'tmp_name' => sanitize_text_field( $files_data[ $fitem ]['tmp_name'][ $fno ][ $fid ][0] ),
						'size'     => sanitize_text_field( $files_data[ $fitem ]['size'][ $fno ][ $fid ][0] ),
					);

					$value = upstream_upfs_upload( $file );

					if ( is_array( $value ) ) {
						// TODO: change how this handles a file too big or other file error.
						$value = '';
					}
				}
			} elseif ( ! empty( $object_type['_name'] ) && isset( $files_data[ $object_type['_name'] ]['name'] ) && is_string( $files_data[ $object_type['_name'] ]['name'][0] ) ) {

				// these are checked to be a valid file in the next function.
				$file = array(
					'name'     => sanitize_text_field( $files_data[ $object_type['_name'] ]['name'][0] ),
					'type'     => sanitize_text_field( $files_data[ $object_type['_name'] ]['type'][0] ),
					'tmp_name' => sanitize_text_field( $files_data[ $object_type['_name'] ]['tmp_name'][0] ),
					'size'     => sanitize_text_field( $files_data[ $object_type['_name'] ]['size'][0] ),
				);

				$value = upstream_upfs_upload( $file );

				if ( is_array( $value ) ) {
					// TODO: change how this handles a file too big or other file error.
					$value = '';
				}
			}

			return $value;
		}


		/**
		 * Define select2 CMB2 field settings.
		 *
		 * @param \CMB2_Field $field      Current CMB2_Field object.
		 * @param string      $value      Current escaped field value.
		 * @param int         $object_id  Project ID.
		 * @param string      $object_type Current object type.
		 * @param \CMB2_Types $field_type  Current field type object.
		 *
		 * @since   1.16.0
		 * @static
		 */
		public static function render_select2_field( $field, $value, $object_id, $object_type, $field_type ) {
			if ( ! is_array( $value ) ) {
				$value = explode( '#', (string) $value );
			}

			$value = array_filter( array_unique( $value ) );

			$field_name = $field->args['_name'];
			if ( ! preg_match( '/\[\]$/', $field_name ) ) {
				$field_name .= '[]';
			}

			$options = array();
			if ( count( $field->args['options'] ) === 0 ) {
				if ( ! empty( $field->args['options_cb'] ) && is_callable( $field->args['options_cb'] ) ) {
					$options = call_user_func( $field->args['options_cb'] );
				}
			}
			?>
			<select
					id="<?php echo esc_attr( $field->args['id'] ); ?>"
					name="<?php echo esc_attr( $field_name ); ?>"
					class="o-select2"
					multiple
					data-placeholder="<?php esc_attr_e( 'None', 'upstream' ); ?>"
					tabindex="-1">
				<?php foreach ( $options as $option_value => $option_title ) : ?>
					<option value="<?php echo esc_attr( $option_value ); ?>"
						<?php
						echo in_array(
							$option_value,
							$value
						) ? ' selected' : '';
						?>
					><?php echo esc_html( $option_title ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php
		}

		/**
		 * Sanitizes select2 fields before they're saved.
		 *
		 * @param mixed          $override_value Sanitization override value to return.
		 * @param mixed          $value         Actual field value.
		 * @param int            $object_id     Project ID.
		 * @param string         $object_type   Current object type.
		 * @param \CMB2_Sanitize $sanitizer     Current sanitization object.
		 *
		 * @since   1.16.0
		 * @static
		 */
		public static function sanitize_select2_field( $override_value, $value, $object_id, $object_type, $sanitizer ) {
			$value = array_filter( array_unique( (array) $value ) );
			return $value;
		}
	}

endif;
