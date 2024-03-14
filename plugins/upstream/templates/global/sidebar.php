<?php
/**
 * Sidebar template
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_options      = get_option( 'upstream_general' );
$site_url            = get_bloginfo( 'url' );
$page_title          = get_bloginfo( 'name' );
$up_current_user     = (object) upstream_user_data();
$projects_list_url   = get_post_type_archive_link( 'project' );
$is_single           = is_single();
$support_url         = upstream_admin_support( $plugin_options );
$log_out_url         = upstream_logout_url();
$are_clients_enabled = ! upstream_is_clients_disabled();

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
	// translators: %s: completed item label.
	'LB_COMPLETE'       => __( '%s Complete', 'upstream' ),
);

if ( $is_single ) {
	$are_milestones_disabled_at_all           = upstream_disable_milestones();
	$are_milestones_disabled_for_this_project = upstream_are_milestones_disabled();
	$are_tasks_disabled_at_all                = upstream_disable_tasks();
	$are_tasks_disabled_for_this_project      = upstream_are_tasks_disabled();
	$are_bugs_disabled_at_all                 = upstream_disable_bugs();
	$are_bugs_disabled_for_this_project       = upstream_are_bugs_disabled();
	$are_files_disabled_for_this_project      = upstream_are_files_disabled();
	$are_comments_disabled                    = upstream_are_comments_disabled();
}

$projects = upstream_user_projects();
$reports  = UpStream_Report_Generator::get_instance()->get_all_reports();
usort(
	$reports,
	function ( $a, $b ) {
		return strcmp( $a->title, $b->title );
	}
);
?>

<?php do_action( 'upstream_before_sidebar' ); ?>

<div class="col-md-3 left_col">
	<div class="left_col scroll-view">
		<div class="navbar nav_title">
			<a href="<?php echo esc_url( $site_url ); ?>" class="site_title">
				<span><?php echo esc_html( $page_title ); ?></span>
			</a>
		</div>
		<div class="clearfix"></div>

		<?php if ( is_user_logged_in() ) : ?>
		<!-- menu profile quick info -->
		<div class="profile">
			<div class="profile_pic">
				<img src="<?php echo esc_url( $up_current_user->avatar ); ?>" alt="" class="img-circle rounded-circle profile_img">
			</div>
			<div class="profile_info">
				<h2><?php echo esc_html( $up_current_user->display_name ); ?></h2>
				<p><?php echo esc_html( $up_current_user->role ); ?></p>
			</div>
		</div>
		<?php endif; ?>

		<!-- /menu profile quick info -->
		<br/>
		<!-- sidebar menu -->
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			<div class="menu_section">
				<h3>&nbsp;</h3>
				<ul class="nav side-menu">
					<li>
						<a href="javascript:void(0);">
							<i class="fa fa-home"></i>
							<?php echo esc_html( $i18n['LB_PROJECTS'] ); ?>
						</a>
						<ul class="nav child_menu">
							<li id="nav-projects">
								<a href="<?php echo esc_attr( $projects_list_url ); ?>">
									<i class="fa fa-columns"></i> 
									<?php
									printf(
										// translators: %s: project label.
										esc_html__( 'All %s', 'upstream' ),
										esc_html( $i18n['LB_PROJECTS'] )
									);
									?>
								</a>

								<?php do_action( 'upstream_sidebar_after_all_projects_link' ); ?>
							</li>

							<?php do_action( 'upstream_sidebar_projects_submenu' ); ?>
						</ul>
					</li>
				</ul>
			</div>
			<?php if ( $is_single && get_post_type() === 'project' ) : ?>
				<?php $project_id = get_the_ID(); ?>
				<div class="menu_section active">
					<ul class="nav side-menu">
						<li class="current-page active">
							<a href="#">
								<i class="fa fa-folder"></i>
								<?php echo esc_html( get_the_title( $project_id ) ); ?>
							</a>

							<ul class="nav child_menu" style="display: block;">
								<?php do_action( 'upstream_sidebar_before_single_menu' ); ?>

								<?php if ( ! $are_milestones_disabled_for_this_project && ! $are_milestones_disabled_at_all ) : ?>
									<li id="nav-milestones">
										<a href="#milestones">
											<i class="fa fa-flag"></i> <?php echo esc_html( upstream_milestone_label_plural() ); ?>
											<?php
											if ( function_exists( 'count_items_for_user_on_project' ) ) {
												$items_count = count_items_for_user_on_project(
													'milestones',
													get_current_user_id(),
													upstream_post_id()
												);
											} else {
												$items_count = (int) upstream_count_assigned_to( 'milestones' );
											}

											if ( $items_count > 0 ) :
												?>
												<span class="badge bg-info pull-right" data-toggle="tooltip"
													title="<?php esc_html_e( 'Assigned to me', 'upstream' ); ?>"
													><?php echo esc_html( $items_count ); ?></span>
											<?php endif; ?>
										</a>
									</li>
								<?php endif; ?>

								<?php if ( ! $are_tasks_disabled_for_this_project && ! $are_tasks_disabled_at_all ) : ?>
									<li id="nav-tasks">
										<a href="#tasks">
											<i class="fa fa-wrench"></i> <?php echo esc_html( $i18n['LB_TASKS'] ); ?>
											<?php
											if ( function_exists( 'count_items_for_user_on_project' ) ) {
												$items_count = count_items_for_user_on_project(
													'tasks',
													get_current_user_id(),
													upstream_post_id()
												);
											} else {
												$items_count = (int) upstream_count_assigned_to( 'tasks' );
											}

											if ( $items_count > 0 ) :
												?>
												<span class="badge bg-info pull-right" data-toggle="tooltip"
													title="<?php esc_html_e( 'Assigned to me', 'upstream' ); ?>"
													><?php echo esc_html( $items_count ); ?></span>
											<?php endif; ?>
											<?php do_action( 'upstream_sidebar_after_tasks_menu' ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php if ( ! $are_bugs_disabled_at_all && ! $are_bugs_disabled_for_this_project ) : ?>
									<li id="nav-bugs">
										<a href="#bugs">
											<i class="fa fa-bug"></i> <?php echo esc_html( $i18n['LB_BUGS'] ); ?>
											<?php
											if ( function_exists( 'count_items_for_user_on_project' ) ) {
												$items_count = count_items_for_user_on_project(
													'bugs',
													get_current_user_id(),
													upstream_post_id()
												);
											} else {
												$items_count = (int) upstream_count_assigned_to( 'bugs' );
											}

											if ( $items_count > 0 ) :
												?>
												<span class="badge bg-info pull-right" data-toggle="tooltip"
													title="<?php esc_html_e( 'Assigned to me', 'upstream' ); ?>"
													><?php echo esc_html( $items_count ); ?></span>
											<?php endif; ?>
											<?php do_action( 'upstream_sidebar_after_bugs_menu' ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php if ( ! $are_files_disabled_for_this_project && ! upstream_disable_files() ) : ?>
									<li id="nav-files">
										<a href="#files">
											<i class="fa fa-file"></i> <?php echo esc_html( upstream_file_label_plural() ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php if ( ! $are_comments_disabled ) : ?>
									<li id="nav-discussion">
										<a href="#discussion">
											<i class="fa fa-comments"></i>
											<?php echo esc_html( upstream_discussion_label() ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php do_action( 'upstream_sidebar_after_single_menu' ); ?>
							</ul>
						</li>

						<?php do_action( 'upstream_sidebar_menu' ); ?>
					</ul>
				</div>
			<?php endif; ?>

<?php

if ( ! isset( $plugin_options['disable_reports'] ) || 0 == $plugin_options['disable_reports'] ) :
	?>
			<div class="menu_section">
				<ul class="nav side-menu">
					<li>
						<a href="#">
							<i class="fa fa-bar-chart"></i>

							<?php esc_html_e( 'Reports', 'upstream' ); ?>
						</a>

						<ul class="nav child_menu">
							<?php
							foreach ( $reports as $report ) :
								$rurl = add_query_arg( 'report', $report->id, esc_attr( $projects_list_url ) );
								?>
								<li id="nav-reports">
									<a target="_blank" href="<?php echo esc_url( $rurl ); ?>">
										<i class="fa fa-file-text"></i> <?php echo esc_html( $report->title ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				</ul>
			</div>
			<?php
endif;

?>

			<?php
			$min_projects_count = $is_single ? 1 : 0;
			if ( ! $is_single ) {
				$project_id = 0;
			}
			?>
			<?php if ( count( $projects ) > $min_projects_count && upstream_show_all_projects_in_sidebar() ) : ?>
				<div class="menu_section">
					<ul class="nav side-menu">
						<?php foreach ( $projects as $project ) : ?>
							<?php if ( $project_id != $project->id ) : ?>
								<li class="current-page active">
									<a href="<?php echo esc_url( $project->permalink ); ?>">
										<i class="fa fa-folder"></i>
										<?php echo esc_html( $project->title ); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
		<!-- /sidebar menu -->
		<!-- /menu footer buttons -->
		<div class="sidebar-footer hidden-small">
			<a href="<?php echo esc_attr( $projects_list_url ); ?>" data-toggle="tooltip" data-placement="top"
				title="<?php printf( esc_attr( 'My %s', 'upstream' ), esc_attr( $i18n['LB_PROJECTS'] ) ); ?>">
				<i class="fa fa-home"></i>
			</a>
			<a href="<?php echo esc_url( $support_url ); ?>" data-toggle="tooltip" data-placement="top"
				title="<?php echo esc_attr( $i18n['MSG_SUPPORT'] ); ?>" target="_blank" rel="noreferrer noopener">
				<i class="fa fa-question-circle"></i>
			</a>
			<a href="<?php echo esc_url( $log_out_url ); ?>" data-toggle="tooltip" data-placement="top"
				title="<?php echo esc_attr( $i18n['LB_LOGOUT'] ); ?>">
				<i class="fa fa-sign-out"></i>
			</a>
		</div>
		<!-- /menu footer buttons -->
	</div>
</div>

<?php do_action( 'upstream_after_sidebar' ); ?>
