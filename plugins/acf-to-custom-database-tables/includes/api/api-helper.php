<?php

function acf_ct_get_valid_post_id( $post_id = 0 ){

	if(!$post_id){
		$post_id = (int) get_the_ID();
	}

	if(!$post_id){
		global $post;
		if (!is_null($post)) {
			$post_id = $post->ID;
		}
	}

	return $post_id;
}

function acf_ct_get_valid_table_name( $table_name ){
	global $wpdb;

	if (strpos($table_name, $wpdb->prefix) === 0) {
		return $table_name;
	}

	return $wpdb->prefix.$table_name;
}

function acf_ct_get_column_names_string($columns = false){

	if(is_array($columns)){
		$columns = implode(", ", $columns);
	}else if(!$columns){
		$columns = "*";
	}

	return $columns;
}

function acf_ct_serialize($data)
{

	if(apply_filters('acf_ct/settings/serialize_array', false)) {
		return apply_filters('acf_ct/format_serialize_data', maybe_serialize($data));
	}

	if(is_array($data)) {
		$data = json_encode($data, JSON_UNESCAPED_SLASHES);
	}

	return apply_filters('acf_ct/format_serialize_data', $data);
}

function acf_ct_unserialize($data)
{

	if(apply_filters('acf_ct/settings/unserialize_array', false)) {
		return apply_filters('acf_ct/format_unserialize_data', maybe_unserialize($data));
	}

	if(is_array($data)) {
		return $data;
	}

	$json = json_decode($data, true);

	if (json_last_error() !== JSON_ERROR_NONE) return $data;

	return apply_filters('acf_ct/format_unserialize_data', $json);
}
