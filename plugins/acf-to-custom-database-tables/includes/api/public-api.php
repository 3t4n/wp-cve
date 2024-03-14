<?php

/**
 * Return requested columns($selectors) or all from custom table
 * @param $custom_table_name
 * @param bool $selectors - string, array
 * @param bool $post_id
 * @return array|null
 */
function get_custom_table_fields( $custom_table_name, $selectors = false, $post_id = false ) {

	$post_id = acf_ct_get_valid_post_id( $post_id );
	$table_name = acf_ct_get_valid_table_name( $custom_table_name );
	$columns = acf_ct_get_column_names_string( $selectors );

	/**
	 * Check for cache
	 */
	$columns_string = preg_replace('/\s+/', '', $columns);
	$cache_key = "acf_ct:$post_id:$table_name:$columns_string:db_data";
	$cached_result = wp_cache_get($cache_key);

	if($cached_result !== false){
		return $cached_result;
	}

	global $wpdb;
	$sql = 'SELECT '.$columns.' FROM '.$table_name.' WHERE '.ACF_CUSTOM_TABLE_POST_ID_COLUMN.' = '.$post_id;
	$result = $wpdb->get_row($sql, ARRAY_A);

	$cache_value = (is_array($result) === false) ? [] : $result;
	wp_cache_set( $cache_key, $cache_value ); //save in cache

	return $result;
}

/**
 * Return single column value from custom table
 * @param $custom_table_name
 * @param bool $selector
 * @param bool $post_id
 * @return array|null
 */
function get_custom_table_field( $custom_table_name, $selector, $post_id = false ) {
	$result = get_custom_table_fields( $custom_table_name, $selector, $post_id );

	if(is_array($result) && array_key_exists($selector, $result)){
		return $result[$selector];
	}

	return $result;
}

/**
 * Print single column value from custom table
 * @param $custom_table_name
 * @param bool $selector
 * @param bool $post_id
 */
function the_custom_table_field( $custom_table_name, $selector, $post_id = false ) {
	echo get_custom_table_field($custom_table_name, $selector, $post_id);
}

/**
 * Insert or update data in a custom table
 * @param $custom_table_name
 * @param $values
 * @param bool $post_id
 * @return bool
 */
function update_custom_table_field( $custom_table_name, $values, $post_id = false ){

	/**
	 * Don't allow non-array values
	 * Allow only associative array
	 */
	if(is_array($values) === false || array_keys($values) === range(0, count($values) - 1)){
		return false;
	}

	$post_id = acf_ct_get_valid_post_id( $post_id );
	$table_name = acf_ct_get_valid_table_name( $custom_table_name );

	if(!$post_id){
		return false;
	}

	global $wpdb;
	$sql = 'SELECT post_id FROM '.$table_name.' WHERE '.ACF_CUSTOM_TABLE_POST_ID_COLUMN.' = '.$post_id;
	$row_exists = $wpdb->get_row($sql, ARRAY_A);

	$sql_result = false;
	if($row_exists){
		$sql_result = $wpdb->update( $table_name, $values, array( ACF_CUSTOM_TABLE_POST_ID_COLUMN => $post_id )  );
	}else{
		$values[ACF_CUSTOM_TABLE_POST_ID_COLUMN] = $post_id;
		$sql_result = $wpdb->insert( $table_name, $values );
	}

	return $sql_result !== false;
}