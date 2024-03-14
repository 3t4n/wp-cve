<?php
/**
 * Single project: tasks
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! upstream_are_tasks_disabled() && ! upstream_disable_tasks() ) :

	$collapse_box = isset( $plugin_options['collapse_project_tasks'] )
		&& true === (bool) $plugin_options['collapse_project_tasks'];

	$collapse_box_state = \UpStream\Frontend\upstream_get_section_collapse_state( 'tasks' );

	if ( false !== $collapse_box_state ) {
		$collapse_box = 'closed' === $collapse_box_state;
	}

	$archive_closed_items = upstream_archive_closed_items();
	$tasks_statuses       = get_option( 'upstream_tasks' );
	$statuses             = array();
	$open_statuses        = array();

	foreach ( $tasks_statuses['statuses'] as $up_status ) {
		// If closed items will be archived, we do not need to display closed statuses.
		if ( $archive_closed_items && 'open' !== $up_status['type'] ) {
			continue;
		}

		$statuses[ $up_status['id'] ] = $up_status;

		if ( isset( $up_status['type'] ) && 'open' === $up_status['type'] ) {
			$open_statuses[] = $up_status['id'];
		}
	}

	$item_type              = 'task';
	$current_user_id        = get_current_user_id();
	$users                  = upstream_admin_get_all_project_users();
	$project_id             = upstream_post_id();
	$are_comments_enabled   = upstream_are_comments_enabled_on_tasks();
	$are_milestones_enabled = ! upstream_are_milestones_disabled() && ! upstream_disable_milestones();
	$milestones             = array();

	if ( $are_milestones_enabled ) {
		$milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project( $project_id );
	}

	$rowset = UpStream_View::get_tasks( $project_id );

	foreach ( array_keys( $rowset ) as $key ) {
		if ( ! empty( $rowset[ $key ]['milestone'] ) ) {
			try {
				$milestone                         = \UpStream\Factory::get_milestone( $rowset[ $key ]['milestone'] );
				$rowset[ $key ]['milestone_order'] = $milestone->getName();
			} catch ( \Exception $e ) {
				$rowset[ $key ]['milestone'] = '';
			}
		}
	}

	// If should archive closed items, we filter the rowset.
	if ( $archive_closed_items ) {
		foreach ( $rowset as $up_id => $task ) {
			if ( ! isset( $task['status'] ) ) {
				continue;
			}
			if ( ! in_array( $task['status'], $open_statuses ) && ! empty( $task['status'] ) ) {
				unset( $rowset[ $up_id ] );
			}
		}
	}

	$l = array(
		'LB_MILESTONE'          => upstream_milestone_label(),
		'LB_TITLE'              => __( 'Title', 'upstream' ),
		'LB_NONE'               => __( 'none', 'upstream' ),
		'LB_NOTES'              => __( 'Notes', 'upstream' ),
		'LB_COMMENTS'           => __( 'Comments', 'upstream' ),
		'MSG_INVALID_USER'      => sprintf(
			// translators: %s: column name. Error message when data reference is not found.
			_x( 'invalid %s', '%s: column name. Error message when data reference is not found', 'upstream' ),
			strtolower( __( 'User' ) )
		),
		'MSG_INVALID_MILESTONE' => __( 'invalid milestone', 'upstream' ),
		'LB_START_DATE'         => __( 'Starting after', 'upstream' ),
		'LB_END_DATE'           => __( 'Ending before', 'upstream' ),
	);

	$l['MSG_INVALID_MILESTONE'] = sprintf(
		// translators: %s: column name. Error message when data reference is not found.
		_x( 'invalid %s', '%s: column name. Error message when data reference is not found', 'upstream' ),
		strtolower( $l['LB_MILESTONE'] )
	);

	$table_settings = array(
		'id'              => 'tasks',
		'type'            => 'task',
		'data-ordered-by' => 'start_date',
		'data-order-dir'  => 'DESC',
	);

	$columns_schema = \UpStream\Frontend\upstream_get_tasks_fields(
		$statuses,
		$milestones,
		$are_milestones_enabled,
		$are_comments_enabled
	);

	$filter_closed_items = upstream_filter_closed_items();
	?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" data-section="tasks">
			<div class="x_title">
				<h2>
					<i class="fa fa-bars sortable_handler"></i>
					<i class="fa fa-wrench"></i> <?php echo esc_html( upstream_task_label_plural() ); ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link">
							<i class="fa fa-chevron-<?php echo $collapse_box ? 'down' : 'up'; ?>"></i>
						</a>
					</li>
					<?php do_action( 'upstream_project_tasks_top_right', $project_id ); ?>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="display: <?php echo $collapse_box ? 'none' : 'block'; ?>;">
				<div class="c-data-table table-responsive">
					<form class="form-inline c-data-table__filters" data-target="#tasks">
						<div class="d-none d-sm-flex justify-content-between">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-search"></i>
									</div>
									<input type="search" class="form-control"
										placeholder="<?php echo esc_attr( $l['LB_TITLE'] ); ?>" data-column="title"
										data-compare-operator="contains">
								</div>
							</div>
							<div class="form-group">
								<div class="btn-group">
									<a href="#tasks-filters" role="button" class="btn border btn-light btn-sm"
										data-bs-toggle="collapse" aria-expanded="false" aria-controls="tasks-filters">
										<i class="fa fa-filter"></i> <?php esc_html_e( 'Toggle Filters', 'upstream' ); ?>
									</a>
								</div>
								<div class="btn-group">
									<button class="btn btn-light border btn-sm dropdown-toggle upstream-export-button" 
										type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-download"></i> <?php esc_html_e( 'Export', 'upstream' ); ?>
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
										<li><a class="dropdown-item" href="#" data-action="export" data-type="txt">
											<i class="fa fa-file-text-o"></i>&nbsp;&nbsp;<?php esc_html_e( 'Plain Text', 'upstream' ); ?>
										</a></li>
										<li><a class="dropdown-item" href="#" data-action="export" data-type="csv">
											<i class="fa fa-file-code-o"></i>&nbsp;&nbsp;<?php esc_html_e( 'CSV', 'upstream' ); ?>
										</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between d-sm-none">
							<div class="btn-group">
								<a href="#tasks-filters" role="button" class="btn border btn-light btn-sm"
									data-bs-toggle="collapse" aria-expanded="false" aria-controls="tasks-filters">
									<i class="fa fa-filter"></i> <?php esc_html_e( 'Toggle Filters', 'upstream' ); ?>
								</a>
							</div>
							<div class="btn-group">
								<button class="btn btn-light border btn-sm dropdown-toggle upstream-export-button" 
									type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="fa fa-download"></i> <?php esc_html_e( 'Export', 'upstream' ); ?>
								</button>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
									<li><a class="dropdown-item" href="#" data-action="export" data-type="txt">
										<i class="fa fa-file-text-o"></i>&nbsp;&nbsp;<?php esc_html_e( 'Plain Text', 'upstream' ); ?>
									</a></li>
									<li><a class="dropdown-item" href="#" data-action="export" data-type="csv">
										<i class="fa fa-file-code-o"></i>&nbsp;&nbsp;<?php esc_html_e( 'CSV', 'upstream' ); ?>
									</a></li>
								</ul>
							</div>
						</div>
						<div id="tasks-filters" class="collapse">
							<div class="form-group d-block d-sm-none">
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-search"></i>
									</div>
									<input type="search" class="form-control"
										placeholder="<?php echo esc_attr( $l['LB_TITLE'] ); ?>" data-column="title"
										data-compare-operator="contains">
								</div>
							</div>
							<div class="form-group d-sm-inline-block">
								<div class="input-group flex-nowrap">
									<div class="input-group-text">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control o-select2" data-column="assigned_to" multiple
											data-placeholder="<?php esc_attr_e( 'Assignee', 'upstream' ); ?>">
										<option value></option>
										<option value="__none__"><?php esc_attr_e( 'Nobody', 'upstream' ); ?></option>
										<option value="<?php echo esc_attr( $current_user_id ); ?>">
											<?php
											esc_attr_e(
												'Me',
												'upstream'
											);
											?>
										</option>
										<optgroup label="<?php esc_attr_e( 'Users' ); ?>">
											<?php foreach ( $users as $user_id => $user_name ) : ?>
												<?php
												if ( $user_id === $current_user_id ) {
													continue;
												}
												?>
												<option value="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $user_name ); ?></option>
											<?php endforeach; ?>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group d-sm-inline-block">
								<div class="input-group flex-nowrap">
									<div class="input-group-text">
										<i class="fa fa-bookmark"></i>
									</div>
									<select class="form-control o-select2" data-column="status"
											data-placeholder="<?php esc_attr_e( 'Status', 'upstream' ); ?>" multiple>
										<option value="__none__" <?php echo $filter_closed_items ? 'selected' : ''; ?>>
											<?php
											esc_attr_e(
												'None',
												'upstream'
											);
											?>
										</option>
										<optgroup label="<?php esc_attr_e( 'Status', 'upstream' ); ?>">
											<?php foreach ( $statuses as $up_status ) : ?>
												<?php
												$attr = ' ';
												if ( $filter_closed_items && 'open' === $up_status['type'] ) :
													$attr .= ' selected';
												endif;
												?>
												<option
														value="<?php echo esc_attr( $up_status['id'] ); ?>"<?php echo esc_attr( $attr ); ?>><?php echo esc_html( $up_status['name'] ); ?></option>
											<?php endforeach; ?>
										</optgroup>
									</select>
								</div>
							</div>
							<?php
							if ( $are_milestones_enabled ) :
								?>
								<div class="form-group d-sm-inline-block">
									<div class="input-group flex-nowrap">
										<div class="input-group-text">
											<i class="fa fa-flag"></i>
										</div>
										<select class="form-control o-select2" data-column="milestone"
												data-placeholder="<?php echo esc_attr( $l['LB_MILESTONE'] ); ?>" multiple>
											<option value></option>
											<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
											<optgroup label="<?php echo esc_attr( upstream_milestone_label_plural() ); ?>">
												<?php foreach ( $milestones as $milestone ) : ?>
													<?php $milestone = \UpStream\Factory::get_milestone( $milestone ); ?>
													<option value="<?php echo esc_attr( $milestone->getId() ); ?>"><?php echo esc_html( $milestone->getName() ); ?></option>
												<?php endforeach; ?>
											</optgroup>
										</select>
									</div>
								</div>
							<?php endif; ?>
							<div class="form-group d-sm-inline-block">
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control o-datepicker"
										placeholder="<?php echo esc_attr( $l['LB_START_DATE'] ); ?>"
										id="tasks-filter-start_date">
								</div>
								<input type="hidden" id="tasks-filter-start_date_timestamp" data-column="start_date"
									data-compare-operator=">=">
							</div>
							<div class="form-group d-sm-inline-block">
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control o-datepicker"
										placeholder="<?php echo esc_attr( $l['LB_END_DATE'] ); ?>" id="tasks-filter-end_date">
								</div>
								<input type="hidden" id="tasks-filter-end_date_timestamp" data-column="end_date"
									data-compare-operator="<=">
							</div>

							<?php
							do_action(
								'upstream:project.tasks.filters',
								$table_settings,
								$columns_schema,
								$project_id
							);
							?>
						</div>
					</form>
					<?php
					\UpStream\Frontend\upstream_render_table(
						$table_settings,
						$columns_schema,
						$rowset,
						'task',
						$project_id
					);
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
