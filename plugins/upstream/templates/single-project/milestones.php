<?php
/**
 * Single project: milestones
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! upstream_are_milestones_disabled() && ! upstream_disable_milestones() ) :

	$collapse_box = isset( $plugin_options['collapse_project_milestones'] )
		&& true === (bool) $plugin_options['collapse_project_milestones'];

	$collapse_box_state = \UpStream\Frontend\upstream_get_section_collapse_state( 'milestones' );

	if ( false !== $collapse_box_state ) {
		$collapse_box = 'closed' === $collapse_box_state;
	}

	$item_type      = 'milestone';
	$current_user_id = get_current_user_id();
	$users         = upstream_admin_get_all_project_users();
	$project_id = upstream_post_id();
	$project_milestones = UpStream_View::get_milestones( $project_id );

	$l = array(
		'LB_MILESTONE'     => upstream_milestone_label(),
		'LB_TASKS'         => upstream_task_label_plural(),
		'LB_START_DATE'    => __( 'Starting after', 'upstream' ),
		'LB_END_DATE'      => __( 'Ending before', 'upstream' ),
		'LB_NONE'          => __( 'none', 'upstream' ),
		'LB_OPEN'          => _x( 'Open', 'Task status', 'upstream' ),
		'LB_NOTES'         => __( 'Notes', 'upstream' ),
		'LB_COMMENTS'      => __( 'Comments', 'upstream' ),
		'MSG_INVALID_USER' => __( 'invalid user', 'upstream' ),
	);

	$are_comments_enabled = upstream_are_comments_enabled_on_milestones();

	$table_settings = array(
		'id'              => 'milestones',
		'type'            => 'milestone',
		'data-ordered-by' => 'start_date',
		'data-order-dir'  => 'DESC',
	);

	$columns_schema = \UpStream\Frontend\upstream_get_milestones_fields();
	?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" data-section="milestones">
			<div class="x_title">
				<h2>
					<i class="fa fa-bars sortable_handler"></i>
					<i class="fa fa-flag"></i> <?php echo esc_html( upstream_milestone_label_plural() ); ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link">
							<i class="fa fa-chevron-<?php echo $collapse_box ? 'down' : 'up'; ?>"></i>
						</a>
					</li>
					<?php do_action( 'upstream_project_milestones_top_right', $project_id ); ?>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="display: <?php echo $collapse_box ? 'none' : 'block'; ?>;">
				<div class="c-data-table table-responsive">
					<form class="form-inline c-data-table__filters" data-target="#milestones">
						<div class="d-none d-sm-flex justify-content-between">
							<?php
							\UpStream\Frontend\upstream_render_table_filter(
								'search',
								'milestone',
								array(
									'attrs' => array(
										'placeholder' => $l['LB_MILESTONE'],
										'width'       => 200,
									),
								)
							);
							?>
							<div class="form-group">
								<div class="btn-group">
									<a href="#milestones-filters" role="button" class="btn border btn-light btn-sm"
									data-bs-toggle="collapse" aria-expanded="false" aria-controls="milestones-filters">
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
							<div class="dropdown">
								<a href="#milestones-filters" role="button" class="btn border btn-light btn-sm"
									data-bs-toggle="collapse" aria-expanded="false" aria-controls="milestones-filters">
									<i class="fa fa-filter"></i> <?php esc_html_e( 'Toggle Filters', 'upstream' ); ?>
								</a>
							</div>							
							<div class="dropdown">
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
						<div id="milestones-filters" class="collapse">
							<div class="form-group d-block d-sm-none">
								<?php
								\UpStream\Frontend\upstream_render_table_filter(
									'search',
									'milestone',
									array(
										'attrs' => array(
											'placeholder' => $l['LB_MILESTONE'],
											'width'       => 200,
										),
									),
									false
								);
								?>
							</div>
							<div class="form-group d-sm-inline-block">
								<div class="input-group flex-nowrap">
									<div class="input-group-text">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control o-select2 form-select" data-column="assigned_to"
											data-placeholder="<?php esc_html_e( 'Assignee', 'upstream' ); ?>" multiple>
										<option value></option>
										<option value="__none__"><?php esc_html_e( 'Nobody', 'upstream' ); ?></option>
										<option value="<?php echo esc_attr( $current_user_id ); ?>">
											<?php
											esc_html_e(
												'Me',
												'upstream'
											);
											?>
										</option>
										<optgroup label="<?php esc_html_e( 'Users' ); ?>">
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
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control o-datepicker"
										placeholder="<?php echo esc_attr( $l['LB_START_DATE'] ); ?>"
										id="milestones-filter-start_date">
								</div>
								<input type="hidden" id="milestones-filter-start_date_timestamp"
									data-column="start_date" data-compare-operator=">=">
							</div>
							<div class="form-group d-sm-inline-block">
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control o-datepicker"
										placeholder="<?php echo esc_attr( $l['LB_END_DATE'] ); ?>"
										id="milestones-filter-end_date">
								</div>
								<input type="hidden" id="milestones-filter-end_date_timestamp" data-column="end_date"
									data-compare-operator="<=">
							</div>

							<?php
							do_action(
								'upstream:project.milestones.filters',
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
						$project_milestones,
						'milestone',
						$project_id
					);
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
