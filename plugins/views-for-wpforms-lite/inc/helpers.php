<?php
function wpforms_views_get_form_fields( $form_id ) {
	if ( empty( $form_id ) ) {
		return '{}';
	}
	$form_fields_obj = new stdClass();
	$form            = wpforms()->form->get( absint( $form_id ), array( 'content_only' => true ) );
	foreach ( $form['fields'] as $field ) {

		if ( $field['type'] !== 'html' && $field['type'] !== 'layout' ) {
			$values = array();
			if ( ! empty( $field['choices'] ) ) {
				foreach ( $field['choices'] as $choice ) {
					// TODO: Check if values are different then label
					$values[ $choice['label'] ] = $choice['label'];
				}
			}

			$field['label']                  = isset( $field['label'] ) ? $field['label'] : '';
			$form_fields_obj->{$field['id']} = (object) array(
				'id'        => $field['id'],
				'label'     => $field['label'],
				'fieldType' => $field['type'],
				'values'    => $values,
			);
		}
	}
	return json_encode( $form_fields_obj );

}


/**
 * Get submissions based on specific critera.
 *
 * @since 2.7
 * @param array $args
 * @return array $sub_ids
 */
function wpforms_views_get_submissions( $args ) {
	global $wpdb;
	$form_id = $args['form_id'];

	// if filter is used
	if ( isset( $args['filter'] ) ) {
		$limit  = ! empty( $args['posts_per_page'] ) ? absint( $args['posts_per_page'] ) : 25;
		$offset = ! empty( $args['offset'] ) ? absint( $args['offset'] ) : 0;

		$where              = array();
		$join_sql           = array();
		$i                  = 1;
		$entry_table        = WPForms_Views_Common::get_entry_table_name();
		$entry_fields_table = WPForms_Views_Common::get_entry_fields_table_name();
		foreach ( $args['filter'] as $filter ) {
			$field_id            = $filter['field'];
			$comparison_operator = $filter['compare'];
			$value               = $filter['value'];
			$join[]              = "LEFT JOIN `$entry_fields_table` AS `m$i` ON ( `m$i`.`entry_id` = `t1`.`entry_id` AND `m$i`.`field_id` = '$field_id') ";
			$where[]             = "(`m$i`.`field_id` = '$field_id' AND `m$i`.`value` $comparison_operator '$value')";
			$i++;
		}
			// Don't display partial entries
			$where[] = "`t1`.status != 'partial'";

		$join_sql  = implode( ' ', $join );
		$where_sql = implode( ' AND ', $where );
		$sql_query = "SELECT `t1`.* FROM `$entry_table` AS `t1` $join_sql WHERE `t1`.`form_id` IN ($form_id) AND( $where_sql ) ORDER BY `t1`.`entry_id` DESC";
		$results   = $wpdb->get_results( " {$sql_query} LIMIT {$offset},{$limit} " );

		// Total entries count
		$sql_query_for_total_rows   = "SELECT `t1`.* FROM `$entry_table` AS `t1` $join_sql WHERE `t1`.`form_id` IN ($form_id) AND( $where_sql ) ORDER BY `t1`.`entry_id` DESC";
		$total_rows_results         = $wpdb->get_results( "{$sql_query_for_total_rows}" );
		$submissions['total_count'] = count( $total_rows_results );
		$submissions['subs']        = $results;
		return $submissions;
	}

	$entries_args = array(
		'form_id' => absint( $args['form_id'] ),
	);

	// Narrow entries by user if user_id shortcode attribute was used.
	if ( ! empty( $args['user'] ) ) {
		if ( $args['user'] === 'current' && is_user_logged_in() ) {
			$entries_args['user_id'] = get_current_user_id();
		} else {
			$entries_args['user_id'] = absint( $args['user'] );
		}
	}

	// TODO --- Show single entry only
	if ( ! empty( $args['submission_id'] ) ) {
		$entries_args['entry_id'] = absint( $args['submission_id'] );
	}

	// Number of entries to show. If empty, defaults to 25.
	if ( ! empty( $args['posts_per_page'] ) ) {
		$entries_args['number'] = absint( $args['posts_per_page'] );
	}

	// Number of entries to show. If empty, defaults to 25.
	if ( ! empty( $args['offset'] ) ) {
		$entries_args['offset'] = absint( $args['offset'] );
	}

	// Get all entries for the form, according to arguments defined.
	// There are many options available to query entries. To see more, check out
	// the get_entries() function inside class-entry.php (https://a.cl.ly/bLuGnkGx).
	$entries = wpforms()->entry->get_entries( $entries_args );

	$total_entries_count = wpforms()->entry->get_entries( $entries_args, true );

	$submissions['total_count'] = $total_entries_count;
	$submissions['subs']        = $entries;

	return $submissions;
}
