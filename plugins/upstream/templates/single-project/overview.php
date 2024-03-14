<?php
/**
 * Single project: overview
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id          = (int) get_current_user_id();
$project_id       = (int) upstream_post_id();
$progress_value    = upstream_project_progress();
$current_timestamp = time();
$counter          = \UpStream\Factory::get_project_counter( $project_id );

global $upstream_allcounts;

$are_milestones_enabled = ! upstream_are_milestones_disabled() && ! upstream_disable_milestones();
if ( $are_milestones_enabled ) {
	$milestones_counts = array(
		'open'     => 0,
		'mine'     => 0,
		'overdue'  => 0,
		'finished' => 0,
		'total'    => 0,
	);

	$all = $counter->get_items_of_type( 'milestones' );

	$milestones_counts['total'] = count( $all );

	if ( $milestones_counts['total'] > 0 ) {
		$milestones_counts['mine'] = $counter->get_total_assigned_to_current_user_of_type( 'milestones' );
		$milestones_counts['open'] = $counter->get_total_open_items_of_type( 'milestones' );

		foreach ( $all as $milestone ) {
			$progress = isset( $milestone['progress'] ) ? (float) $milestone['progress'] : 0;

			if ( $progress < 100 ) {
				if ( isset( $milestone['end_date'] )
					&& (int) $milestone['end_date'] > 0
					&& (int) $milestone['end_date'] < $current_timestamp
				) {
					$milestones_counts['overdue']++;
				}
			} else {
				$milestones_counts['finished']++;
			}
		}
	}

	$upstream_allcounts['milestonesCounts'] = $milestones_counts;
}

$are_tasks_enabled = ! upstream_are_tasks_disabled() && ! upstream_disable_tasks();
if ( $are_tasks_enabled ) {
	$tasks_counts = array(
		'open'    => 0,
		'mine'    => 0,
		'overdue' => 0,
		'closed'  => 0,
		'total'   => 0,
	);

	$tasks_options = get_option( 'upstream_tasks' );
	$tasks_map     = array();
	foreach ( $tasks_options['statuses'] as $task ) {
		if ( isset( $task['type'] ) ) {
			$tasks_map[ $task['id'] ] = $task['type'];
		}
	}
	unset( $tasks_options );

	$tasks = get_post_meta( $project_id, '_upstream_project_tasks' );
	$tasks = ! empty( $tasks ) ? (array) $tasks[0] : array();

	if ( isset( $tasks[0] ) && ! isset( $tasks[0]['id'] ) ) {
		$tasks = (array) $tasks[0];
	}

	$tasks_counts['total'] = count( $tasks );
	if ( $tasks_counts['total'] > 0 ) {
		foreach ( $tasks as $task ) {
			if ( isset( $task['assigned_to'] ) ) {
				$assigned_to = $task['assigned_to'];

				if ( is_array( $assigned_to ) && in_array( $user_id, $assigned_to ) ) {
					$tasks_counts['mine']++;
				}
			}

			$progress = isset( $task['progress'] ) ? (float) $task['progress'] : 0;
			if ( $progress < 100 ) {
				if ( isset( $task['status'] )
					&& isset( $tasks_map[ $task['status'] ] )
					&& 'closed' === $tasks_map[ $task['status'] ]
				) {
					$tasks_counts['closed']++;
				} else {
					$tasks_counts['open']++;

					if ( isset( $task['end_date'] )
						&& (int) $task['end_date'] > 0
						&& (int) $task['end_date'] < $current_timestamp
					) {
						$tasks_counts['overdue']++;
					}
				}
			} else {
				$tasks_counts['closed']++;
			}
		}
	}
	$upstream_allcounts['tasksCounts'] = $tasks_counts;

}

$are_bugs_enabled = ! upstream_disable_bugs() && ! upstream_are_bugs_disabled();
if ( $are_bugs_enabled ) {
	$bugs_counts = array(
		'open'    => 0,
		'mine'    => 0,
		'overdue' => 0,
		'closed'  => 0,
		'total'   => 0,
	);

	$bugs_options = get_option( 'upstream_bugs' );
	$bugs_map     = array();
	foreach ( $bugs_options['statuses'] as $bug ) {
		$bugs_map[ $bug['id'] ] = $bug['type'];
	}
	unset( $bugs_options );

	$bugs = get_post_meta( $project_id, '_upstream_project_bugs' );
	$bugs = ! empty( $bugs ) ? (array) $bugs[0] : array();

	if ( isset( $bugs[0] ) && ! isset( $bugs[0]['id'] ) ) {
		$bugs = (array) $bugs[0];
	}

	$bugs_counts['total'] = count( $bugs );
	if ( $bugs_counts['total'] > 0 ) {
		foreach ( $bugs as $bug ) {
			if ( isset( $bug['assigned_to'] ) ) {
				$assigned_to = $bug['assigned_to'];

				if ( is_array( $assigned_to ) && in_array( $user_id, $assigned_to ) ) {
					$bugs_counts['mine']++;
				}
			}

			if ( isset( $bug['status'] )
				&& ! empty( $bug['status'] )
				&& isset( $bugs_map[ $bug['status'] ] )
				&& 'closed' === $bugs_map[ $bug['status'] ]
			) {
				$bugs_counts['closed']++;
			} else {
				$bugs_counts['open']++;

				if ( isset( $bug['due_date'] )
					&& (int) $bug['due_date'] > 0
					&& (int) $bug['due_date'] < $current_timestamp
				) {
					$bugs_counts['overdue']++;
				}
			}
		}
	}

	$upstream_allcounts['bugsCounts'] = $bugs_counts;

}
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 c-upstream-overview">
	<?php if ( $are_milestones_enabled || $are_tasks_enabled || $are_bugs_enabled ) : ?>
		<?php if ( $are_milestones_enabled ) : ?>
			<div class="hidden-xs hidden-sm col-md-4 col-lg-4" style="min-width: 185px;">
				<div class="card" style="margin-bottom: 10px;">
					<div class="card-body" style="display: flex; position: relative;">
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Open', 'upstream' ); ?>">
							<span class="badge bg-primary"><?php echo esc_html( $milestones_counts['open'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Assigned to me', 'upstream' ); ?>">
							<span class="badge bg-info"><?php echo esc_html( $milestones_counts['mine'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Overdue', 'upstream' ); ?>">
							<span class="badge bg-danger"><?php echo esc_html( $milestones_counts['overdue'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Completed', 'upstream' ); ?>">
							<span class="badge bg-success"><?php echo esc_html( $milestones_counts['finished'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Total', 'upstream' ); ?>">
							<span class="badge"
								style="background-color: #ecf0f1; color: #3A4E66;"><?php echo esc_html( $milestones_counts['total'] ); ?></span>
						</div>
						<i class="fa fa-flag fa-2x" data-toggle="tooltip"
							title="
							<?php
							echo esc_attr(
								sprintf(
									'%s %s',
									upstream_milestone_label_plural(),
									__( 'Overview', 'upstream' )
								)
							);
							?>
							"
							style="position: absolute; color: #ECF0F1; right: 8px; margin-top: -2px"></i>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $are_tasks_enabled ) : ?>
			<div class="hidden-xs hidden-sm col-md-4 col-lg-4" style="min-width: 185px;">
				<div class="card" style="margin-bottom: 10px;">
					<div class="card-body" style="display: flex; position: relative;">
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Open', 'upstream' ); ?>">
							<span class="badge bg-primary"><?php echo esc_html( $tasks_counts['open'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Assigned to me', 'upstream' ); ?>">
							<span class="badge bg-info"><?php echo esc_html( $tasks_counts['mine'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Overdue', 'upstream' ); ?>">
							<span class="badge bg-danger"><?php echo esc_html( $tasks_counts['overdue'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Closed', 'upstream' ); ?>">
							<span class="badge bg-success"><?php echo esc_html( $tasks_counts['closed'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Total', 'upstream' ); ?>">
							<span class="badge"
								style="background-color: #ecf0f1; color: #3A4E66;"><?php echo esc_html( $tasks_counts['total'] ); ?></span>
						</div>
						<i class="fa fa-wrench fa-2x" data-toggle="tooltip"
							title="
							<?php
							echo esc_attr(
								sprintf(
									'%s %s',
									upstream_task_label_plural(),
									__( 'Overview', 'upstream' )
								)
							);
							?>
							"
							style="position: absolute; color: #ECF0F1; right: 8px; margin-top: -2px"></i>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $are_bugs_enabled ) : ?>
			<div class="hidden-xs hidden-sm col-md-4 col-lg-4" style="min-width: 185px;">
				<div class="card" style="margin-bottom: 10px;">
					<div class="card-body" style="display: flex; position: relative;">
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Open', 'upstream' ); ?>">
							<span class="badge bg-primary"><?php echo esc_html( $bugs_counts['open'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Assigned to me', 'upstream' ); ?>">
							<span class="badge bg-info"><?php echo esc_html( $bugs_counts['mine'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Overdue', 'upstream' ); ?>">
							<span class="badge bg-danger"><?php echo esc_html( $bugs_counts['overdue'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Closed', 'upstream' ); ?>">
							<span class="badge bg-success"><?php echo esc_html( $bugs_counts['closed'] ); ?></span>
						</div>
						<div data-toggle="tooltip" title="<?php esc_attr_e( 'Total', 'upstream' ); ?>">
							<span class="badge"
								style="background-color: #ecf0f1; color: #3A4E66;"><?php echo esc_html( $bugs_counts['total'] ); ?></span>
						</div>
						<i class="fa fa-bug fa-2x" data-toggle="tooltip"
							title="
							<?php
							echo esc_attr(
								sprintf(
									'%s %s',
									upstream_bug_label_plural(),
									__( 'Overview', 'upstream' )
								)
							);
							?>
							"
							style="position: absolute; color: #ECF0F1; right: 8px; margin-top: -2px"></i>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
