<?php
/**
 * Handle WordPress Coding Standard (WCS) helper
 *
 * @package UpStream
 */

/**
 * Convert variable from snake to camel case
 *
 * @return array
 */
function upstream_wcs_updated_variables() {
	$variables = array(
		'file_id'                => 'fileId',
		'severity_code'          => 'severityCode',
		'status_code'            => 'statusCode',
		'due_date'               => 'dueDate',
		'time_records'           => 'timeRecords',
		'elapsed_time'           => 'elapsedTime',
		'user_ids'               => 'userIds',
		'file_url'               => 'fileURL',
		'created_at'             => 'createdAt',
		'parent_id'              => 'parentId',
		'category_ids'           => 'categoryIds',
		'start_date'             => 'startDate',
		'end_date'               => 'endDate',
		'created_by'             => 'createdBy',
		'assigned_to'            => 'assignedTo',
		'assigned_to:byUsername' => 'assignedTo:byUsername',
		'assigned_to:byEmail'    => 'assignedTo:byEmail',
		'client_id'              => 'clientId',
		'client_user_ids'        => 'clientUserIds',
		'member_user_ids'        => 'memberUserIds',
		'milestone_id'           => 'milestoneId',
		'start_timestamp'        => 'startTimestamp',
	);

	return $variables;
}

/**
 * Filter variable on model files
 *
 * @param string $variable Variable to filter.
 * @return string
 */
function upstream_wcs_model_variable( $variable ) {
	$all_variables = upstream_wcs_updated_variables();

	if ( isset( $all_variables[ $variable ] ) ) {
		$variable = $all_variables[ $variable ];
	}

	return $variable;
}
add_filter( 'upstream_wcs_model_variable', 'upstream_wcs_model_variable', 10, 1 );
