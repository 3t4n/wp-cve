<?php
/**
 * The Template for displaying a single project
 *
 * This template can be overridden by copying it to yourtheme/upstream/single-project.php.
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

@define( 'OP_SCRIPT_DEBUG', '' );

/* redirect to projects if no permissions for this project */
if ( ! upstream_user_can_access_project( get_current_user_id(), upstream_post_id() ) ) {
	wp_redirect( get_post_type_archive_link( 'project' ) );
	exit;
}

/* Some hosts disable this function, so let's make sure it is enabled before call it. */
if ( function_exists( 'set_time_limit' ) ) {
	set_time_limit( 120 );
}

$exception = null;

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

$up_current_user = (object) upstream_user_data();
$projects_list   = array();

if ( isset( $up_current_user->projects ) ) {
	if ( is_array( $up_current_user->projects ) && count( $up_current_user->projects ) > 0 ) {
		foreach ( $up_current_user->projects as $project_id => $project ) {
			$data = (object) array(
				'id'          => $project_id,
				'author'      => (int) $project->post_author,
				'created_at'  => (string) $project->post_date_gmt,
				'modified_at' => (string) $project->post_modified_gmt,
				'title'       => $project->post_title,
				'slug'        => $project->post_name,
				'status'      => $project->post_status,
				'permalink'   => get_permalink( $project_id ),
			);

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

/*
 * upstream_single_project_before hook.
 */
do_action( 'upstream_single_project_before' );

$user = upstream_user_data();

$options                  = (array) get_option( 'upstream_general' );
$display_overview_section = ! isset( $options['disable_project_overview'] ) || false === (bool) $options['disable_project_overview'];
$display_details_section  = ! isset( $options['disable_project_details'] ) || false === (bool) $options['disable_project_details'];
$display_progress_section = ! isset( $options['disable_project_progress'] ) || false === (bool) $options['disable_project_progress'];

/**
 * Display Overview Section
 *
 * @param bool $display_overview_section
 *
 * @return bool
 */
$display_overview_section = apply_filters( 'upstream_display_overview_section', $display_overview_section );

/**
 * Display Details Section
 *
 * @param bool $display_details_section
 *
 * @return bool
 */
$display_details_section = apply_filters( 'upstream_display_details_section', $display_details_section );

unset( $options );

/*
 * Sections
 */
$sections = array(
	'details',
	'milestones',
	'tasks',
	'bugs',
	'files',
	'discussion',
);
$sections = apply_filters( 'upstream_panel_sections', $sections );

if ( $display_progress_section ) {
	array_splice( $sections, 1, 0, 'progress' );
}

/* Apply the order to the panels. */
$sections_order = (array) \UpStream\Frontend\upstream_get_panel_order();
$sections       = array_merge( $sections_order, $sections );
/* Remove duplicates. */
$sections = array_unique( $sections );

while ( have_posts() ) :
	the_post(); ?>

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="alerts">
			<?php do_action( 'upstream_frontend_projects_messages' ); ?>
			<?php do_action( 'upstream_single_project_before_overview' ); ?>
	</div>

		<?php do_action( 'upstream_single_project_top' ); ?>

		<div id="project-dashboard" class="sortable">
			<?php foreach ( $sections as $section ) : ?>
				<?php
				switch ( $section ) :
					case 'details':
						?>
						<div class="row" id="project-section-details">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
								<h3 style="display: inline-block;"><?php echo esc_html( get_the_title( get_the_ID() ) ); ?>
								<?php $up_status = upstream_project_status_color( $id ); ?>
								<?php if ( ! empty( $up_status['status'] ) ) : ?>
									<span class="badge up-o-label"
										style="background-color: <?php echo esc_attr( $up_status['color'] ); ?>"><?php echo esc_html( $up_status['status'] ); ?></span>
								<?php endif; ?>
								</h3>
							</div>

							<?php if ( $display_overview_section ) : ?>
								<?php include 'single-project/overview.php'; ?>
							<?php endif; ?>

							<?php if ( $display_details_section ) : ?>
								<?php do_action( 'upstream_single_project_before_details' ); ?>
								<?php upstream_get_template_part( 'single-project/details.php' ); ?>
							<?php endif; ?>
						</div>
						<?php
						break;

					case 'milestones':
						if ( ! upstream_are_milestones_disabled() && ! upstream_disable_milestones() ) :
							?>
							<div class="row" id="project-section-milestones">
							<?php do_action( 'upstream_single_project_before_milestones' ); ?>

							<?php upstream_get_template_part( 'single-project/milestones.php' ); ?>

							<?php do_action( 'upstream_single_project_after_milestones' ); ?>
						</div>
							<?php
						endif;
						break;

					case 'tasks':
						if ( ! upstream_are_tasks_disabled() && ! upstream_disable_tasks() ) :
							?>
							<div class="row" id="project-section-tasks">
							<?php do_action( 'upstream_single_project_before_tasks' ); ?>

							<?php upstream_get_template_part( 'single-project/tasks.php' ); ?>

							<?php do_action( 'upstream_single_project_after_tasks' ); ?>
						</div>
							<?php
						endif;
						break;

					case 'bugs':
						if ( ! upstream_disable_bugs() && ! upstream_are_bugs_disabled() ) :
							?>
							<div class="row" id="project-section-bugs">
							<?php do_action( 'upstream_single_project_before_bugs' ); ?>

							<?php upstream_get_template_part( 'single-project/bugs.php' ); ?>

							<?php do_action( 'upstream_single_project_after_bugs' ); ?>
						</div>
							<?php
						endif;
						break;

					case 'files':
						if ( ! upstream_are_files_disabled() && ! upstream_disable_files() ) :
							?>
							<div class="row" id="project-section-files">
							<?php do_action( 'upstream_single_project_before_files' ); ?>

							<?php upstream_get_template_part( 'single-project/files.php' ); ?>

							<?php do_action( 'upstream_single_project_after_files' ); ?>
						</div>
							<?php
						endif;
						break;

					case 'discussion':
						if ( upstream_are_project_comments_enabled() ) :
							?>
							<div class="row" id="project-section-discussion">
								<?php do_action( 'upstream_single_project_before_discussion' ); ?>

								<?php upstream_get_template_part( 'single-project/discussion.php' ); ?>

								<?php do_action( 'upstream_single_project_after_discussion' ); ?>
							</div>
							<?php
						endif;
						break;

					case 'progress':
						?>
							<div class="row" id="project-section-progress">
								<?php do_action( 'upstream_single_project_before_progress' ); ?>

								<?php upstream_get_template_part( 'single-project/progress.php' ); ?>

								<?php do_action( 'upstream_single_project_after_progress' ); ?>
							</div>
						<?php
						break;

					default:
						do_action( 'upstream_single_project_section_' . $section, upstream_post_id() );

						break;

				endswitch;
				?>
			<?php endforeach; ?>


		</div>
	</div>
	<input type="hidden" id="project_id" value="<?php echo esc_attr( upstream_post_id() ); ?>">
<?php endwhile;
/**
 * Upstream_after_project_content hook.
 */
do_action( 'upstream_after_project_content' );

if ( ! apply_filters( 'upstream_theme_override_footer', false ) ) {
	upstream_get_template_part( 'global/footer.php' );
}
?>
