<?php

// Exit if accessed directly.
defined('ABSPATH') or die();

class AMSPAPDMS_POST_DUPLICATOR_META_INFO_COPY {

	function __construct() {
		add_action('amspd_ms_post-duplicator_meta_fields_do_post', array($this, 'amspapdms_post_and_page_duplicator_meta_data_copy_to_new_via_amspapdms'), 10, 2);
		add_action('amspd_ms_post-duplicator_meta_fields_do_page', array($this, 'amspapdms_post_and_page_duplicator_meta_data_copy_to_new_via_amspapdms'), 10, 2);
		add_action('amspd_ms_post-duplicator_taxonomy_do_post', array($this, 'amspapdms_post_and_page_duplicator_copy_taxonomies_to_new_via_amspapdms'), 10, 3);
		add_action('amspd_ms_post-duplicator_taxonomy_do_page', array($this, 'amspapdms_post_and_page_duplicator_copy_taxonomies_to_new_via_amspapdms'), 10, 3);
	}

	/**
	 * Copy the meta information of a post to another post
	 */
	function amspapdms_post_and_page_duplicator_meta_data_copy_to_new_via_amspapdms($amspapdms_new_post_id, $amspapdms_old_post_id) {
		$amspapdms_post_and_page_duplicator_field_keys = get_post_custom_keys( $amspapdms_old_post_id );
		
		foreach ( $amspapdms_post_and_page_duplicator_field_keys as $amspapdms_duplicator_field_key ) {
			$amspapdms_post_and_page_duplicator_values = get_post_custom_values( $amspapdms_duplicator_field_key, $amspapdms_old_post_id );
			
			foreach ( $amspapdms_post_and_page_duplicator_values as $amspapdms_duplicator_value ) {
				add_post_meta( $amspapdms_new_post_id, $amspapdms_duplicator_field_key, $amspapdms_duplicator_value );
			}
		}
	}

	// Copy taxonomies
	function amspapdms_post_and_page_duplicator_copy_taxonomies_to_new_via_amspapdms($new_post_id, $old_post_id, $post_type) {
		$taxonomies = get_object_taxonomies($post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($old_post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
	}
	
	// Actions to copy meta info, call from amspapd-post-and-page-duplictor-setup.php,
	// from function amspapdms_post_page_copy_to_new_via_amspapdms
	function amspapdms_post_and_page_duplicator_copy_meta_info_via_amspapdms($amspapdms_new_post_id, $amspapdms_old_post_id, $amspapdms_post_type) {
		if ($amspapdms_post_type == 'page' || (function_exists('is_post_type_hierarchical')
			&& is_post_type_hierarchical( $amspapdms_post_type )))
			do_action( 'amspd_ms_post-duplicator_taxonomy_do_page', $amspapdms_new_post_id, $amspapdms_old_post_id, $amspapdms_post_type );
		
		else
			do_action( 'amspd_ms_post-duplicator_taxonomy_do_post', $amspapdms_new_post_id, $amspapdms_old_post_id, $amspapdms_post_type );
			
		if ($amspapdms_post_type == 'page' || (function_exists('is_post_type_hierarchical')
			&& is_post_type_hierarchical( $amspapdms_post_type )))
			do_action( 'amspd_ms_post-duplicator_meta_fields_do_page', $amspapdms_new_post_id, $amspapdms_old_post_id );
		
		else
			do_action( 'amspd_ms_post-duplicator_meta_fields_do_post', $amspapdms_new_post_id, $amspapdms_old_post_id );
	}
}
?>