<?php
/**
 * Archive project template
 *
 * WordPress Coding Standart (WCS) note:
 * All camelCase object properties on this file are not converted to snake_case,
 * because it was the data from the model file.
 *
 * @package UpStream
 */

/* Prevent direct access. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

@define( 'OP_SCRIPT_DEBUG', '' );


/**
 * The Template for displaying all projects
 *
 * This template can be overridden by copying it to wp-content/themes/yourtheme/upstream/archive-project.php.
 */

/* Some hosts disable this function, so let's make sure it is enabled before call it. */
if ( function_exists( 'set_time_limit' ) ) {
	set_time_limit( 1200 );
}

$exception   = null;
$get_data    = isset( $_GET ) ? wp_unslash( $_GET ) : array();
$post_data   = isset( $_POST ) ? wp_unslash( $_POST ) : array();
$server_data = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();

try {
	if ( ! session_id() ) {
		session_start();
	}
} catch ( \Exception $e ) {
	$exception = $e;
}

add_action(
	'init',
	function() {
		try {
			if ( ! session_id() ) {
				session_start();
			}
		} catch ( \Exception $e ) {
			$exception = $e;
		}
	},
	9
);

if ( isset( $get_data['report'] ) ) {
	$nonce_passed = false;
	$nonce_keys   = array(
		'nonce'                      => 'upstream-nonce',
		'upstream_report_form_nonce' => 'upstream_report_form',
	);

	// check multiple nonce possibility.
	foreach ( $nonce_keys as $key => $value ) {
		if ( ! isset( $post_data[ $key ] ) ) {
			continue;
		}

		if ( wp_verify_nonce( $post_data[ $key ], $value ) ) {
			$nonce_passed = true;
		}
	}

	// load report template.
	if ( isset( $post_data['submit'] ) && $nonce_passed ) {
		include 'report-display.php';
	} else {
		include 'report-parameters.php';
	}
	return;
} elseif ( isset( $get_data['download'] ) ) {
	upstream_upfs_download( sanitize_text_field( $get_data['download'] ) );
	return;
}

$plugin_options = get_option( 'upstream_projects' );
$option_name    = 'project_number_per_page';
$total_per_page = isset( $plugin_options[ $option_name ] ) ? (int) $plugin_options[ $option_name ] : 1000;
$up_page        = isset( $get_data['page'] ) ? absint( $get_data['page'] ) : 1;
$up_search      = isset( $get_data['search'] ) ? sanitize_text_field( $get_data['search'] ) : '';

if ( $up_page <= 1 ) {
	$up_page = 1;
}

$display_start        = ( $up_page - 1 ) * $total_per_page;
$project_page_url     = get_post_type_archive_link( 'project' );
$are_clients_enabled  = ! upstream_is_clients_disabled();
$archive_closed_items = upstream_archive_closed_items();

$i18n = array(
	'LB_PROJECT'        => upstream_project_label(),
	'LB_PROJECTS'       => upstream_project_label_plural(),
	'LB_TASKS'          => upstream_task_label_plural(),
	'LB_BUGS'           => upstream_bug_label_plural(),
	'LB_LOGOUT'         => __( 'Log Out', 'upstream' ),
	'LB_ENDS_AT'        => __( 'Ends at', 'upstream' ),
	'MSG_SUPPORT'       => upstream_admin_support_label( $plugin_options ),
	'LB_TITLE'          => __( 'Title', 'upstream' ),
	'LB_TOGGLE_FILTERS' => __( 'Toggle Filters', 'upstream' ),
	'LB_EXPORT'         => __( 'Export', 'upstream' ),
	'LB_PLAIN_TEXT'     => __( 'Plain Text', 'upstream' ),
	'LB_CSV'            => __( 'CSV', 'upstream' ),
	'LB_CLIENT'         => upstream_client_label(),
	'LB_CLIENTS'        => upstream_client_label_plural(),
	'LB_STATUS'         => __( 'Status', 'upstream' ),
	'LB_STATUSES'       => __( 'Statuses', 'upstream' ),
	'LB_CATEGORIES'     => __( 'Categories' ),
	'LB_PROGRESS'       => __( 'Progress', 'upstream' ),
	'LB_NONE_UCF'       => __( 'None', 'upstream' ),
	'LB_NONE'           => __( 'none', 'upstream' ),
	// translators: %s: Completed item label.
	'LB_COMPLETE'       => __( '%s Complete', 'upstream' ),
);

$up_current_user  = (object) upstream_user_data( @$_SESSION['upstream']['user_id'] );
$project_statuses = upstream_get_all_project_statuses();
$project_order    = array();
$statuses         = array();
$open_statuses    = array();

/* We start from 1 instead of 0 because the 0 position is used for "__none__". */
$status_index = 1;

foreach ( $project_statuses as $status_id => $up_status ) {
	$project_order[ $status_index++ ] = $status_id;

	/* If closed items will be archived, we do not need to display closed statuses. */
	if ( $archive_closed_items && 'open' !== $up_status['type'] ) {
		continue;
	}

	$statuses[ $up_status['id'] ] = $up_status;

	if ( 'open' === $up_status['type'] ) {
		$open_statuses[] = $up_status['id'];
	}
}

$projects_list  = array();
$count_pos      = 0;
$total_projects = 0;

if ( isset( $up_current_user->projects ) ) {
	if ( is_array( $up_current_user->projects ) && count( $up_current_user->projects ) > 0 ) {

		$projects_list = $up_current_user->projects;
		uasort(
			$projects_list,
			function( $a, $b ) {
				if ( ! isset( $a->post_title ) || ! isset( $b->post_title ) ) {
					return 0;
				}
				return strcasecmp( $a->post_title, $b->post_title );
			}
		);

		foreach ( $projects_list as $project_id => $project ) {

			$project = new UpStream_Project( $project_id );
			$start   = $project->get_meta( 'start' );
			if ( ! $start ) {
				$start = 0;
			}
			$end = $project->get_meta( 'end' );
			if ( ! $end ) {
				$end = 0;
			}

			$prog = $project->get_meta( 'progress' );
			if ( ! $prog ) {
				$prog = 0;
			}
			$stat = $project->get_meta( 'status' );

			$data = (object) array(
				'id'                 => $project_id,
				'author'             => (int) $project->post_author,
				'created_at'         => (string) $project->post_date_gmt,
				'modified_at'        => (string) $project->post_modified_gmt,
				'title'              => $project->post_title,
				'slug'               => $project->post_name,
				'status'             => $project->post_status,
				'permalink'          => get_permalink( $project_id ),
				'startDateTimestamp' => (int) $start,
				'endDateTimestamp'   => (int) $end,
				'progress'           => (float) round( $prog ),
				'status'             => (string) $stat,
				'clientName'         => null,
				'categories'         => array(),
				'features'           => array(
					'',
				),
			);

			/* If should archive closed items, we filter the rowset. */
			if ( $archive_closed_items ) {
				if ( ! empty( $data->status ) && ! in_array( $data->status, $open_statuses ) ) {
					continue;
				}
			}

			if ( ! empty( $up_search ) && ! stristr( $project->post_title, $up_search ) ) {
				continue;
			}

			$total_projects++;
			$count_pos++;

			if ( $count_pos - 1 < $display_start ) {
				continue;
			}
			if ( $count_pos - 1 >= $display_start + $total_per_page ) {
				continue;
			}

			$data->start_date = (string) upstream_format_date( $data->startDateTimestamp ); // phpcs:ignore
			$data->end_date   = (string) upstream_format_date( $data->endDateTimestamp ); // phpcs:ignore
			$ymd              = $project->get_meta( 'start.YMD' );

			if ( $ymd ) {
				if ( is_array( $ymd ) ) {
					$ymd = $ymd[0];
				}
				$data->startDateTimestamp = \UpStream_Model_Object::ymdToTimestamp( $ymd ); // phpcs:ignore
				$data->start_date         = date_i18n( get_option( 'date_format' ), $data->startDateTimestamp ); // phpcs:ignore
			}

			$ymd = $project->get_meta( 'end.YMD' );

			if ( $ymd ) {
				if ( is_array( $ymd ) ) {
					$ymd = $ymd[0];
				}
				$data->endDateTimestamp = \UpStream_Model_Object::ymdToTimestamp( $ymd ); // phpcs:ignore
				$data->end_date         = date_i18n( get_option( 'date_format' ), $data->endDateTimestamp ); // phpcs:ignore
			}

			if ( $are_clients_enabled ) {
				$data->clientName = trim( (string) upstream_project_client_name( $project_id ) ); // phpcs:ignore
			}

			if ( isset( $statuses[ $data->status ] ) ) {
				$data->status = $statuses[ $data->status ];
			}

			$data->timeframe = $data->start_date;
			if ( ! empty( $data->end_date ) ) {
				if ( ! empty( $data->timeframe ) ) {
					$data->timeframe .= ' - ';
				} else {
					$data->timeframe = '<i>' . $i18n['LB_ENDS_AT'] . '</i>';
				}

				$data->timeframe .= $data->end_date;
			}

			$categories = (array) wp_get_object_terms( $data->id, 'project_category' );
			if ( count( $categories ) > 0 ) {
				foreach ( $categories as $category ) {
					if ( is_object( $category ) ) {
						$data->categories[ $category->term_id ] = $category->name;
					}
				}
			}

			$projects_list[ $project_id ] = $data;
		}

		unset( $project, $project_id );
	}

	unset( $up_current_user->projects );
}

$projects_list_count = count( $projects_list );

if ( ! apply_filters( 'upstream_theme_override_header', false ) ) {
	upstream_get_template_part( 'global/header.php' );
}

if ( ! apply_filters( 'upstream_theme_override_sidebar', false ) ) {
	upstream_get_template_part( 'global/sidebar.php' );
}

if ( ! apply_filters( 'upstream_theme_override_topnav', false ) ) {
	upstream_get_template_part( 'global/top-nav.php' );
}

$categories = (array) get_terms(
	array(
		'taxonomy'   => 'project_category',
		'hide_empty' => false,
	)
);

$projects_view = ! isset( $_GET['view'] );


/* Filters */
$table_settings = array(
	'id'              => 'projects',
	'type'            => 'project',
	'data-ordered-by' => 'start_date',
	'data-order-dir'  => 'DESC',
);

$columns_schema        = \UpStream\Frontend\upstream_get_project_fields();
$hidden_columns_schema = array();

foreach ( $columns_schema as $column_name => $column_args ) {
	if ( isset( $column_args['isHidden'] ) && true === (bool) $column_args['isHidden'] ) {
		$hidden_columns_schema[ $column_name ] = $column_args;
	}
}

$filter_closed_items = upstream_filter_closed_items();
$ordering            = \UpStream\Frontend\upstream_get_table_order( 'projects' );
$order_by            = '';
$order_dir           = '';

if ( ! empty( $ordering ) ) {
	$order_by  = $ordering['column'];
	$order_dir = $ordering['orderDir'];
}
?>

	<div class="right_col" role="main">
	<div class="alerts">
		<?php do_action( 'upstream_frontend_projects_messages' ); ?>
		<?php do_action( 'upstream_single_project_before_overview' ); ?>
	</div>

	<?php do_action( 'upstream_archive_project_top' ); ?>

	<div class="">
		<?php if ( $projects_view ) : ?>
			<?php if ( false ) : ?>
			<div class="row">
				<div class="col-md-12">
					<div class="x_panel" data-section="projects">
						<div class="x_title">
							<h2><i class="fa fa-bar-chart"></i> <?php echo esc_html( __( 'Status' ) ); ?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li>
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">

						</div>
					</div>
				</div>
			</div>
<?php endif; ?>

			<div class="row">
				<div class="col-md-12">
					<div class="x_panel" data-section="projects">
						<div class="x_title">
							<h2><i class="fa fa-briefcase"></i> <?php echo esc_html( $i18n['LB_PROJECTS'] ); ?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li>
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
								</li>
								<?php do_action( 'upstream_project_project_top_right' ); ?>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<?php if ( $projects_list_count > 0 || ! empty( $up_search ) ) : ?>
								<div class="c-data-table table-responsive">
									<form class="form-inline c-data-table__filters" data-target="#projects" method="get" action="<?php print esc_attr( $project_page_url ); ?>">
										<div class="d-none d-sm-flex justify-content-between">
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-text">
														<i class="fa fa-search"></i>
													</div>

														<input type="search" class="form-control"
															placeholder="<?php echo esc_attr( $i18n['LB_TITLE'] ); ?>"
															<?php if ( $total_projects > $total_per_page || ! empty( $up_search ) ) { ?>
																data-searchurl="<?php print esc_url( add_query_arg( 'search', '_SEARCH_STR_', $project_page_url ) ); ?>"
															<?php } ?>
															<?php if ( ! empty( $up_search ) ) { ?>
																value="<?php print esc_attr( $up_search ); ?>"
															<?php } ?>
															data-column="title" data-compare-operator="contains">

												</div>
											</div>
											<div class="form-group">
												<div class="btn-group">
													<a href="#projects-filters" role="button"
														class="btn border btn-light btn-sm"
														data-bs-toggle="collapse" aria-expanded="false"
														aria-controls="projects-filters">
														<i class="fa fa-filter"></i> <?php echo esc_html( $i18n['LB_TOGGLE_FILTERS'] ); ?>
													</a>
												</div>
												<div class="btn-group">
													<button type="button"
															class="btn btn-light border btn-sm dropdown-toggle upstream-export-button"
															data-bs-toggle="dropdown" aria-haspopup="true"
															aria-expanded="false">
														<i class="fa fa-download"></i> <?php echo esc_html( $i18n['LB_EXPORT'] ); ?>
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a class="dropdown-item" href="#" data-action="export" data-type="txt">
																<i class="fa fa-file-text-o"></i>&nbsp;&nbsp;<?php echo esc_html( $i18n['LB_PLAIN_TEXT'] ); ?>
															</a>
														</li>
														<li>
															<a class="dropdown-item" href="#" data-action="export" data-type="csv">
																<i class="fa fa-file-code-o"></i>&nbsp;&nbsp;<?php echo esc_html( $i18n['LB_CSV'] ); ?>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="d-flex justify-content-between d-sm-none">
											<div class="btn-group">
												<a href="#projects-filters" role="button"
													class="btn border btn-light btn-sm"
													data-bs-toggle="collapse" aria-expanded="false"
													aria-controls="projects-filters">
													<i class="fa fa-filter"></i> <?php echo esc_html( $i18n['LB_TOGGLE_FILTERS'] ); ?>
												</a>
											</div>
											<div class="btn-group">
												<button type="button"
														class="btn btn-light border btn-sm dropdown-toggle upstream-export-button"
														data-bs-toggle="dropdown" aria-haspopup="true"
														aria-expanded="false">
													<i class="fa fa-download"></i> <?php echo esc_html( $i18n['LB_EXPORT'] ); ?>
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li>
														<a class="dropdown-item" href="#" data-action="export" data-type="txt">
															<i class="fa fa-file-text-o"></i>&nbsp;&nbsp;<?php echo esc_html( $i18n['LB_PLAIN_TEXT'] ); ?>
														</a>
													</li>
													<li>
														<a class="dropdown-item" href="#" data-action="export" data-type="csv">
															<i class="fa fa-file-code-o"></i>&nbsp;&nbsp;<?php echo esc_html( $i18n['LB_CSV'] ); ?>
														</a>
													</li>
												</ul>
											</div>
										</div>
										<div id="projects-filters" class="collapse">
											<div class="form-group d-block d-sm-none">
												<div class="input-group">
													<div class="input-group-text">
														<i class="fa fa-search"></i>
													</div>
													<input type="search" class="form-control"
														placeholder="<?php echo esc_attr( $i18n['LB_TITLE'] ); ?>"
														data-column="title" data-compare-operator="contains">
												</div>
											</div>
											<?php if ( ! upstream_is_clients_disabled() ) : ?>
												<div class="form-group d-sm-inline-block">
													<div class="input-group">
														<div class="input-group-text">
															<i class="fa fa-user"></i>
														</div>
														<input type="search" class="form-control"
															placeholder="<?php echo esc_attr( $i18n['LB_CLIENTS'] ); ?>"
															data-column="client" data-compare-operator="contains">
													</div>
												</div>
											<?php endif; ?>
											<div class="form-group d-sm-inline-block">
												<div class="input-group flex-nowrap">
													<div class="input-group-text">
														<i class="fa fa-bookmark"></i>
													</div>
													<select class="form-control o-select2" data-column="status"
															data-placeholder="<?php echo esc_attr( $i18n['LB_STATUS'] ); ?>"
															multiple>
														<option value></option>
														<option
																value="__none__" <?php echo $filter_closed_items ? 'selected' : ''; ?>><?php echo esc_html( $i18n['LB_NONE_UCF'] ); ?></option>
														<optgroup label="<?php echo esc_html( $i18n['LB_STATUSES'] ); ?>">
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
											<div class="form-group d-sm-inline-block">
												<div class="input-group flex-nowrap">
													<div class="input-group-text">
														<i class="fa fa-tags"></i>
													</div>
													<select class="form-control o-select2" data-column="categories"
															data-placeholder="<?php echo esc_attr( $i18n['LB_CATEGORIES'] ); ?>"
															multiple data-compare-operator="contains">
														<option value></option>
														<option
																value="__none__"><?php echo esc_html( $i18n['LB_NONE_UCF'] ); ?></option>
														<optgroup
																label="<?php echo esc_html( $i18n['LB_CATEGORIES'] ); ?>">
															<?php foreach ( $categories as $category ) : ?>
																<option
																		value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
															<?php endforeach; ?>
														</optgroup>
													</select>
												</div>
											</div>

											<?php
											do_action(
												'upstream:project.filters',
												$table_settings,
												$columns_schema
											);
											?>
										</div>
									</form>
									<table id="projects"
										class="o-data-table table table-bordered table-responsive table-hover is-orderable"
										cellspacing="0"
										width="100%"
										data-type="project"
										data-ordered-by="<?php echo esc_attr( $order_by ); ?>"
										data-order-dir="<?php echo esc_attr( $order_dir ); ?>">
										<thead>
										<tr>
											<th class="is-clickable is-orderable" data-column="title" role="button">
												<?php echo esc_html( $i18n['LB_PROJECT'] ); ?>
												<span class="pull-right o-order-direction">
													<i class="fa fa-sort"></i>
												</span>
											</th>
											<th class="is-clickable is-orderable" data-column="startDate" role="button">
												<?php echo esc_html__( 'Start Date', 'upstream' ); ?>
												<span class="pull-right o-order-direction">
													<i class="fa fa-sort"></i>
												</span>
											</th>
											<th class="is-clickable is-orderable" data-column="endDate" role="button">
												<?php echo esc_html__( 'End Date', 'upstream' ); ?>
												<span class="pull-right o-order-direction">
													<i class="fa fa-sort"></i>
												</span>
											</th>
											<?php if ( $are_clients_enabled ) : ?>
												<th class="is-clickable is-orderable" data-column="client"
													role="button">
													<?php echo esc_html( $i18n['LB_CLIENT'] ); ?>
													<span class="pull-right o-order-direction">
												<i class="fa fa-sort"></i>
												</span>
												</th>
												<th data-column="client-users">
													<?php
													printf(
														// translators: %s: Client label.
														esc_html__( '%s Users', 'upstream' ),
														esc_html( $i18n['LB_CLIENT'] )
													);
													?>
												</th>
											<?php endif; ?>
											<th data-column="owner">
												<?php
												printf(
													// translators: %s: Project label.
													esc_html__( '%s Owner', 'upstream' ),
													esc_html( $i18n['LB_PROJECT'] )
												);
												?>
											</th>
											<th data-column="members">
												<?php
												printf(
													// translators: %s: Project label.
													esc_html__( '%s Members', 'upstream' ),
													esc_html( $i18n['LB_PROJECT'] )
												);
												?>
											</th>
											<th class="is-clickable is-orderable" data-column="progress" role="button">
												<?php echo esc_html( $i18n['LB_PROGRESS'] ); ?>
												<span class="pull-right o-order-direction">
												<i class="fa fa-sort"></i>
											</span>
											</th>
											<th class="is-clickable is-orderable" data-column="status" role="button">
												<?php echo esc_html( $i18n['LB_STATUS'] ); ?>
												<span class="pull-right o-order-direction">
													<i class="fa fa-sort"></i>
												</span>
											</th>
											<th style="max-width: 250px;" data-column="categories">
												<?php echo esc_html( $i18n['LB_CATEGORIES'] ); ?>
											</th>

											<?php
											do_action(
												'upstream:project.columns.header',
												$table_settings,
												$columns_schema
											);
											?>
										</tr>
										</thead>
										<tbody>
										<?php
										$is_project_index_odd = true;
										foreach ( $projects_list as $project_index => $project ) :
											?>
											<?php
											$project = apply_filters(
												'upstream_frontend_project_data',
												$project,
												$project->id
											);
											?>
											<tr class="t-row-<?php echo $is_project_index_odd ? 'odd' : 'even'; ?>"
												data-id="<?php echo esc_attr( $project->id ); ?>">

												<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'title', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
													<td data-column="title"
														data-value="<?php echo esc_attr( $project->title ); ?>">
														<?php
														do_action(
															'upstream:frontend.project.details.before_title',
															$project
														);
														?>
														<a href="<?php echo esc_url( $project->permalink ); ?>">
															<?php echo esc_html( $project->title ); ?>
														</a>
													</td>
												<?php else : ?>
													<td data-column="title"
														data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
													</td>
												<?php endif; ?>

												<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'start', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
													<td data-column="startDate"
														data-value="<?php echo esc_attr( $project->startDateTimestamp ); // phpcs:ignore ?>">
														<?php echo esc_html( $project->start_date ); ?>
													</td>
												<?php else : ?>
													<td data-column="startDate"
														data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
													</td>
												<?php endif; ?>

												<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'end', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
													<td data-column="endDate"
														data-value="<?php echo esc_attr( $project->endDateTimestamp ); // phpcs:ignore ?>">
														<?php echo esc_html( $project->end_date ); ?>
													</td>
												<?php else : ?>
													<td data-column="endDate"
														data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
													</td>
												<?php endif; ?>


												<?php if ( $are_clients_enabled ) : ?>

													<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'client', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
														<td data-column="client"
															data-value="<?php echo null !== $project->clientName ? esc_attr( $project->clientName ) : '__none__'; // phpcs:ignore ?>">
															<?php if ( null !== $project->clientName ) : // phpcs:ignore ?>
																<?php echo esc_html( $project->clientName ); // phpcs:ignore ?>
															<?php else : ?>
																<i class="s-text-color-gray"><?php echo esc_html( $i18n['LB_NONE'] ); ?></i>
															<?php endif; ?>
														</td>
													<?php else : ?>
														<td data-column="client"
															data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
														</td>
													<?php endif; ?>

													<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'client_users', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
														<td data-column="client-users">
															<?php upstream_output_client_users( $project->id ); ?>
														</td>
													<?php else : ?>
														<td data-column="client-users"
															data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
														</td>
													<?php endif; ?>

												<?php endif; ?>

												<?php $powner = upstream_get_project_owner( $project->id ); ?>
												<td data-column="owner" data-value="<?php echo $powner && is_array( $powner ) ? esc_attr( implode( ',', $powner[0] ) ) : ''; ?>">
													<?php print wp_kses_post( $powner[1] ); ?>
												</td>
												<td data-column="members">
													<?php upstream_output_project_members( $project->id ); ?>
												</td>

												<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'progress', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
													<td data-column="progress"
														data-value="<?php echo esc_attr( $project->progress ); ?>">
														<div class="progress" style="margin-bottom: 0; height: 10px;">
															<div
																	class="progress-bar<?php echo $project->progress >= 100 ? ' progress-bar-success' : ''; ?>"
																	role="progressbar"
																	aria-valuenow="<?php echo esc_attr( $project->progress ); ?>"
																	aria-valuemin="0" aria-valuemax="100"
																	style="width: <?php echo esc_attr( $project->progress ); ?>%;">
															<span class="sr-only">
															<?php
															echo esc_html(
																sprintf(
																	$i18n['LB_COMPLETE'],
																	$project->progress . '%'
																)
															);
															?>
																</span>
															</div>
														</div>
														<small>
														<?php
														echo esc_html(
															sprintf(
																$i18n['LB_COMPLETE'],
																$project->progress . '%'
															)
														);
														?>
															</small>
													</td>
												<?php else : ?>
													<td data-column="progress"
														data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
													</td>
												<?php endif; ?>



												<?php
												if ( null !== $project->status && is_array( $project->status ) ) {
													$up_status = $project->status;
												} else {
													$up_status = array(
														'id' => '',
														'name' => '',
														'color' => '#aaa',
														'order' => '0',
													);
												}

												$status_order = array_search( $up_status['id'], $project_order );
												?>

												<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'status', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>

													<td data-column="status"
														data-value="<?php echo ! empty( $up_status['id'] ) ? esc_attr( $up_status['id'] ) : '__none__'; ?>"
														data-order="<?php echo $status_order > 0 ? esc_attr( $status_order ) : '0'; ?>">
														<?php if ( null !== $project->status || empty( $up_status['id'] ) || empty( $up_status['name'] ) ) : ?>
															<span class="badge up-o-label"
																style="background-color: <?php echo esc_attr( $up_status['color'] ); ?>;"><?php echo ! empty( $up_status['name'] ) ? esc_html( $up_status['name'] ) : esc_html( $i18n['LB_NONE'] ); ?></span>
														<?php else : ?>
															<i class="s-text-color-gray"><?php echo esc_html( $i18n['LB_NONE'] ); ?></i>
														<?php endif; ?>
													</td>
												<?php else : ?>
													<td data-column="status"
														data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
													</td>
												<?php endif; ?>

												<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, null, 0, 'categories', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
													<td data-column="categories"
														data-value="
														<?php
														echo count( $project->categories ) ? esc_attr(
															implode(
																',',
																array_keys( (array) $project->categories )
															)
														) : '__none__';
														?>
														">
														<?php if ( count( $project->categories ) > 0 ) : ?>
															<?php
															echo esc_attr(
																implode(
																	', ',
																	array_values( (array) $project->categories )
																)
															);
															?>
														<?php else : ?>
															<i class="s-text-color-gray"><?php echo esc_html( $i18n['LB_NONE'] ); ?></i>
														<?php endif; ?>
													</td>
												<?php else : ?>
													<td data-column="categories"
														data-value="">
														<span class="badge up-o-label"
															style="background-color:#666;color:#fff">(hidden)</span>
													</td>
												<?php endif; ?>

												<?php
												do_action(
													'upstream:project.columns.data',
													$table_settings,
													$columns_schema,
													$project->id,
													$project
												);
												?>
											</tr>

											<?php if ( ! empty( $hidden_columns_schema ) ) : ?>
												<tr data-parent="<?php echo esc_attr( $project->id ); ?>" aria-expanded="false"
													style="display: none;">
													<td>
														<div>
															<?php
															foreach ( $hidden_columns_schema as $column_name => $column ) :
																$column_value = isset( $project->{$column_name} ) ? $project->{$column_name} : null;
																if ( is_null( $column_value ) ) {
																	continue;
																}

																if ( is_array( $column_value ) && isset( $column_value['value'] ) ) {
																	$column_value = $column_value['value'];
																}
																?>
																<div class="form-group"
																	data-column="<?php echo esc_attr( $column_name ); ?>">
																	<label><?php echo isset( $column['label'] ) ? esc_html( $column['label'] ) : ''; ?></label>
																	<?php
																	UpStream\Frontend\upstream_render_table_column_value(
																		$column_name,
																		$column_value,
																		$column,
																		(array) $project,
																		'project',
																		$project->id
																	);
																	?>
																</div>
															<?php endforeach; ?>
														</div>
													</td>
												</tr>
												<?php
											endif;

											$is_project_index_odd = ! $is_project_index_odd;
										endforeach;
										?>
										</tbody>
									</table>
								</div>

								<span class="p_count">
									Showing <?php print esc_html( $display_start + 1 ); ?> to <?php print esc_html( min( $display_start + $total_per_page, $total_projects ) ); ?> of <?php print esc_html( $total_projects ); ?>
								</span>
								<?php if ( $total_projects > $total_per_page ) { ?>
							<span class="pagination">
									<?php
									if ( $up_page > 1 ) {
										?>
										<a href="<?php print esc_url( add_query_arg( 'page', $up_page - 1, $project_page_url ) ); ?>">&lt; Previous</a> <?php } ?>

									<select name="" onchange="if (this.value) window.location='<?php print esc_url( add_query_arg( 'page', '__PAGE_N_', $project_page_url ) ); ?>'.replace('__PAGE_N_',this.value);">
										<option>(Jump to page...)</option>
										<?php for ( $j = 0; $j < ceil( $total_projects / $total_per_page ); $j++ ) : ?>
										<option value="<?php print esc_attr( $j + 1 ); ?>"><?php print esc_html( $j + 1 ); ?></option>
										<?php endfor; ?>
									</select>

									<?php
									if ( $display_start + $total_per_page < $total_projects ) {
										?>
										<a href="<?php print esc_url( add_query_arg( 'page', $up_page + 1, $project_page_url ) ); ?>">Next &gt;</a> <?php } ?>
							</span>

							<?php } ?>
							<?php else : ?>
								<p>
								<?php
								esc_html_e(
									"It seems that you're not participating in any project right now.",
									'upstream'
								);
								?>
								</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php do_action( 'upstream:frontend.renderAfterProjectsList' ); // phpcs:ignore ?>
	</div>
</div>

<?php
do_action( 'upstream_after_project_list_content' );

if ( ! apply_filters( 'upstream_theme_override_footer', false ) ) {
	upstream_get_template_part( 'global/footer.php' );
}
