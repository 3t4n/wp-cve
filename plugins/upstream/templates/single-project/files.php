<?php
/**
 * Single project: files
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! upstream_are_files_disabled() && ! upstream_disable_files() ) :

	$collapse_box = isset( $plugin_options['collapse_project_files'] )
		&& true === (bool) $plugin_options['collapse_project_files'];

	$collapse_box_state = \UpStream\Frontend\upstream_get_section_collapse_state( 'files' );

	if ( false !== $collapse_box_state ) {
		$collapse_box = 'closed' === $collapse_box_state;
	}

	$item_type       = 'file';
	$current_user_id = get_current_user_id();
	$users           = upstream_admin_get_all_project_users();
	$rowset          = array();
	$project_id      = upstream_post_id();

	$meta = (array) get_post_meta( $project_id, '_upstream_project_files', true );
	foreach ( $meta as $data ) {
		if ( ! isset( $data['id'] )
			|| ! isset( $data['created_by'] )
			|| ! upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_FILE, $data['id'], UPSTREAM_ITEM_TYPE_PROJECT, $project_id, UPSTREAM_PERMISSIONS_ACTION_VIEW )
		) {
			continue;
		}

		$data['created_by']   = (int) $data['created_by'];
		$data['created_time'] = isset( $data['created_time'] ) ? (int) $data['created_time'] : 0;
		$data['title']        = isset( $data['title'] ) ? (string) $data['title'] : '';
		$data['file_id']      = isset( $data['file_id'] ) ? (int) $data['file_id'] : 0;
		$data['description']  = isset( $data['description'] ) ? (string) $data['description'] : '';

		$rowset[ $data['id'] ] = $data;
	}

	$l = array(
		'LB_TITLE'       => __( 'Title', 'upstream' ),
		'LB_NONE'        => __( 'none', 'upstream' ),
		'LB_DESCRIPTION' => __( 'Description', 'upstream' ),
		'LB_COMMENTS'    => __( 'Comments', 'upstream' ),
		'LB_FILE'        => __( 'File', 'upstream' ),
		'LB_UPLOADED_AT' => __( 'Upload Date', 'upstream' ),
	);

	$are_comments_enabled = upstream_are_comments_enabled_on_files();

	$table_settings = array(
		'id'              => 'files',
		'type'            => 'file',
		'data-ordered-by' => 'created_at',
		'data-order-dir'  => 'DESC',
	);

	$columns_schema = \UpStream\Frontend\upstream_get_files_fields( $are_comments_enabled );
	?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" data-section="files">
			<div class="x_title">
				<h2>
					<i class="fa fa-bars sortable_handler"></i>
					<i class="fa fa-file"></i> <?php echo esc_html( upstream_file_label_plural() ); ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link">
							<i class="fa fa-chevron-<?php echo $collapse_box ? 'down' : 'up'; ?>"></i>
						</a>
					</li>
					<?php do_action( 'upstream_project_files_top_right', $project_id ); ?>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="display: <?php echo $collapse_box ? 'none' : 'block'; ?>;">
				<div class="c-data-table table-responsive">
					<form class="form-inline c-data-table__filters" data-target="#files">
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
									<a href="#files-filters" role="button" class="btn border btn-light btn-sm"
										data-bs-toggle="collapse" aria-expanded="false" aria-controls="files-filters">
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
								<a href="#files-filters" role="button" class="btn border btn-light btn-sm"
									data-bs-toggle="collapse" aria-expanded="false" aria-controls="files-filters">
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
						<div id="files-filters" class="collapse">
							<div class="form-group d-block d-sm-none">
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-search"></i>
									</div>
									<input type="search" class="form-control"
										placeholder="<?php echo esc_html( $l['LB_TITLE'] ); ?>" data-column="title"
										data-compare-operator="contains">
								</div>
							</div>
							<div class="form-group d-sm-inline-block">
								<div class="input-group flex-nowrap">
									<div class="input-group-text">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control o-select2" data-column="created_by"
											data-placeholder="<?php esc_html_e( 'Uploader', 'upstream' ); ?>" multiple>
										<option value></option>
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
								<div class="input-group">
									<div class="input-group-text">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control o-datepicker"
										placeholder="<?php echo esc_html( $l['LB_UPLOADED_AT'] ); ?>"
										id="files-filter-uploaded_at_from">
								</div>
								<input type="hidden" id="files-filter-uploaded_at_from_timestamp"
									data-column="created_time" data-compare-operator=">=">
							</div>

							<?php
							do_action(
								'upstream:project.files.filters',
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
						'file',
						$project_id
					);
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
