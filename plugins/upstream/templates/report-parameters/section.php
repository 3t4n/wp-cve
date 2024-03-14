<?php
/**
 * Report parameter template: section
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$up_type   = $option_info['type'];
$mm        = \UpStream_Model_Manager::get_instance();
$projects  = $mm->findAccessibleProjects();
$fields    = array();
$type_info = '';

$show = true;

switch ( $up_type ) {
	case 'project':
		$type_info = upstream_project_label();
		$fields    = UpStream_Model_Project::fields();
		break;
	case 'milestone':
		$type_info = upstream_milestone_label();
		$fields    = UpStream_Model_Milestone::fields();
		$show      = ! upstream_disable_milestones();
		break;
	case 'task':
		$type_info = upstream_task_label();
		$fields    = UpStream_Model_Task::fields();
		$show      = ! upstream_disable_tasks();
		break;
	case 'bug':
		$type_info = upstream_bug_label();
		$fields    = UpStream_Model_Bug::fields();
		$show      = ! upstream_disable_bugs();
		break;
	case 'file':
		$type_info = upstream_file_label();
		$fields    = UpStream_Model_File::fields();
		$show      = ! upstream_disable_files();
		break;
}

if ( $show ) {
	?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" data-section="report-parameters-<?php echo esc_attr( $section_id ); ?>">
			<div class="x_title">
				<h2>
					<?php echo esc_html( $type_info . __( ' Filters' ) ); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">

				<div class="row">

					<div class="col-lg-12 col-xs-12">
						<div class="form-group">
							<label><?php esc_html_e( 'Name' ); ?></label>
							<select class="form-control" multiple name="upstream_report__<?php echo esc_attr( $section_id ); ?>_id[]">
								<?php
								foreach ( $projects as $project ) :
									$user = upstream_user_data();
									if ( upstream_user_can_access_project( isset( $user['id'] ) ? $user['id'] : 0, $project->id ) ) :
										?>
										<?php if ( 'project' == $up_type ) : ?>
										<option selected value="<?php echo esc_attr( $project->id ); ?>"><?php echo esc_html( $project->title ); ?></option>
									<?php else : ?>
										<option disabled
												style="color:#DDDDDD;font-style: italic"><?php echo esc_html( $project->title ); ?></option>
									<?php endif; ?>

										<?php
										if ( 'milestone' == $up_type || 'bug' == $up_type || 'file' == $up_type || 'task' == $up_type ) {

											$children = array();
											if ( 'bug' == $up_type ) {
												$children = &$project->bugs();
											} elseif ( 'file' == $up_type ) {
												$children = &$project->files();
											} elseif ( 'task' == $up_type ) {
												$children = &$project->tasks();
											} elseif ( 'milestone' == $up_type ) {
												$children = $project->findMilestones();
											}

											foreach ( $children as $child ) {
												if ( upstream_override_access_object( true, $up_type, $child->id, UPSTREAM_ITEM_TYPE_PROJECT, $project->id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
													?>
													<option selected value="<?php echo esc_attr( $child->id ); ?>">
														&emsp;<?php echo esc_html( $child->title ); ?></option>
													<?php
												}
											}
										}
										?>
										<?php
									endif;
								endforeach;
								?>
							</select>
							<a onclick="jQuery('[name=\'upstream_report__<?php print esc_js( $section_id ); ?>_id[]\'] option').prop('selected', true)">Select
								all</a> | <a
									onclick="jQuery('[name=\'upstream_report__<?php print esc_js( $section_id ); ?>_id[]\'] option').prop('selected', false)">Select
								none</a>
						</div>

						<?php include 'search-fields.php'; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
