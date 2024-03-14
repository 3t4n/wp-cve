<?php
/**
 * Single project template: bugs
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! upstream_are_bugs_disabled() && ! upstream_disable_bugs() ) :

	$collapse_box = isset( $plugin_options['collapse_project_bugs'] )
		&& true === (bool) $plugin_options['collapse_project_bugs'];

	$collapse_box_state = \UpStream\Frontend\upstream_get_section_collapse_state( 'bugs' );

	if ( false !== $collapse_box_state ) {
		$collapse_box = 'closed' === $collapse_box_state;
	}

	$archive_closed_items = upstream_archive_closed_items();
	$bugs_settings        = get_option( 'upstream_bugs' );
	$bugs_statuses        = $bugs_settings['statuses'];
	$statuses             = array();
	$open_statuses        = array();

	foreach ( $bugs_statuses as $up_status ) {
		// If closed items will be archived, we do not need to display closed statuses.
		if ( $archive_closed_items && 'open' !== $up_status['type'] ) {
			continue;
		}

		$statuses[ $up_status['id'] ] = $up_status;

		if ( 'open' === $up_status['type'] ) {
			$open_statuses[] = $up_status['id'];
		}
	}

	$bugs_severities = $bugs_settings['severities'];
	$severities      = array();

	foreach ( $bugs_severities as $index => $severity ) {
		$severity['order'] = $index;

		$severities[ $severity['id'] ] = $severity;
	}

	unset( $bugs_severities );

	$item_type       = 'bug';
	$current_user_id = get_current_user_id();
	$users           = upstream_admin_get_all_project_users();
	$project_id      = upstream_post_id();
	$rowset          = UpStream_View::get_bugs( $project_id );

	// If should archive closed items, we filter the rowset.
	if ( $archive_closed_items ) {
		foreach ( $rowset as $up_id => $bug ) {
			if ( ! in_array( $bug['status'], $open_statuses ) && ! empty( $bug['status'] ) ) {
				unset( $rowset[ $up_id ] );
			}
		}
	}

	$l = array(
		'LB_TITLE'         => __( 'Title', 'upstream' ),
		'LB_NONE'          => __( 'none', 'upstream' ),
		'LB_DESCRIPTION'   => __( 'Description', 'upstream' ),
		'LB_COMMENTS'      => __( 'Comments', 'upstream' ),
		'MSG_INVALID_USER' => sprintf(
			// translators: %s: column name. Error message when data reference is not found.
			_x( 'invalid %s', '%s: column name. Error message when data reference is not found', 'upstream' ),
			strtolower( __( 'User' ) )
		),
		'LB_DUE_DATE'      => __( 'Due Date', 'upstream' ),
	);

	$are_comments_enabled = upstream_are_comments_enabled_on_bugs();

	$table_settings = array(
		'id'              => 'bugs',
		'type'            => 'bug',
		'data-ordered-by' => 'due_date',
		'data-order-dir'  => 'DESC',
	);

	$columns_schema = \UpStream\Frontend\upstream_get_bugs_fields( $severities, $statuses, $are_comments_enabled );

	$filter_closed_items = upstream_filter_closed_items();

	?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" data-section="bugs">
			<div class="x_title">
				<h2>
					<i class="fa fa-bars sortable_handler"></i>
					<i class="fa fa-bug"></i> <?php echo esc_html( upstream_bug_label_plural() ); ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link">
							<i class="fa fa-chevron-<?php echo $collapse_box ? 'down' : 'up'; ?>"></i>
						</a>
					</li>
					<?php do_action( 'upstream_project_bugs_top_right', $project_id ); ?>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="display: <?php echo $collapse_box ? 'none' : 'block'; ?>;">
				<div class="c-data-table table-responsive">
					<form class="form-inline c-data-table__filters" data-target="#bugs">
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
									<a href="#bugs-filters" role="button" class="btn border btn-light btn-sm"
										data-bs-toggle="collapse" aria-expanded="false" aria-controls="bugs-filters">
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
								<a href="#bugs-filters" role="button" class="btn border btn-light btn-sm"
									data-bs-toggle="collapse" aria-expanded="false" aria-controls="bugs-filters">
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
						<div id="bugs-filters" class="collapse">
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
									<select class="form-control o-select2" data-column="assigned_to"
											data-placeholder="<?php esc_attr_e( 'Assignee', 'upstream' ); ?>" multiple>
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
										<i class="fa fa-asterisk"></i>
									</div>
									<select class="form-control o-select2" data-column="severity"
											data-placeholder="<?php esc_attr_e( 'Severity', 'upstream' ); ?>" multiple>
										<option value></option>
										<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
										<optgroup label="<?php esc_attr_e( 'Severity', 'upstream' ); ?>">
											<?php foreach ( $severities as $severity ) : ?>
												<option
														value="<?php echo esc_attr( $severity['id'] ); ?>"><?php echo esc_html( $severity['name'] ); ?></option>
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
										<option value></option>
										<option value="__none__" <?php echo $filter_closed_items ? 'selected' : ''; ?>>
											<?php
											esc_html_e(
												'None',
												'upstream'
											);
											?>
										</option>
										<optgroup label="<?php esc_html_e( 'Status', 'upstream' ); ?>">
											<?php foreach ( $statuses as $up_status ) : ?>
												<?php
												$attr = ' ';
												if ( $filter_closed_items && 'open' === $up_status['type'] ) :
													$attr .= ' selected';
												endif;
												?>
												<option value="<?php echo esc_attr( $up_status['id'] ); ?>"<?php echo esc_attr( $attr ); ?>><?php echo esc_html( $up_status['name'] ); ?></option>
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
										placeholder="<?php echo esc_attr( $l['LB_DUE_DATE'] ); ?>"
										id="tasks-filter-due_date_from">
								</div>
								<input type="hidden" id="tasks-filter-due_date_from_timestamp" data-column="due_date"
									data-compare-operator=">=">
							</div>

							<?php
							do_action(
								'upstream:project.bugs.filters',
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
						'bug',
						$project_id
					);
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
