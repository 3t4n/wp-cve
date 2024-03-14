<?php

class Post_Duplicator_Plus
{
	public function __construct()
	{
		add_filter( 'post_row_actions', array($this, 'post_row_action_hook'), 10, 2);
		add_filter( 'page_row_actions', array($this, 'post_row_action_hook'), 10, 2);
		add_filter( 'admin_init', array($this, 'post_duplicate_action_hook'), 10, 2);
	}

	/**
	** Show the duplicate link
	**/
	public function post_row_link($post)
	{
		$wp_list_table = _get_list_table('WP_Posts_List_Table');
		$duplicate_link = add_query_arg('duplicate_post_id', $post->ID ,$wp_list_table->current_action());
		$complete_url = wp_nonce_url($duplicate_link, 'duplicate_post');
		return '<a href="' . $complete_url . '">Duplicate</a>';
	}

	public function post_row_action_hook($actions, $post)
	{
		$actions['post_duplicator_plus'] =  $this->post_row_link($post);
		return $actions;
	}

	public function post_duplicate_action_hook()
	{
		if (empty($_GET['duplicate_post_id']))
		{
			return;
		}
		check_admin_referer('duplicate_post');

		// Get access to the database
		global $wpdb;
		$id = (int) $_GET['duplicate_post_id'];

		$duplicate_post = get_post( $id, 'ARRAY_A' );
		
		//Remove the id, guids, comment_counts of the old entry
		unset( $duplicate_post['ID'] );
		unset( $duplicate_post['guid'] );
		unset( $duplicate_post['comment_count'] );

		$duplicate_post_id = wp_insert_post( $duplicate_post );

		//Copy taxonomies 
		$taxonomies = get_object_taxonomies( $duplicate_post['post_type'] );
		foreach( $taxonomies as $taxonomy ) {
			$terms = wp_get_post_terms( $id, $taxonomy, array('fields' => 'names') );
			wp_set_object_terms( $duplicate_post_id, $terms, $taxonomy );
		}
	  
	  	//copy custom fields
		$custom_fields = get_post_custom( $id );
	  	foreach ( $custom_fields as $key => $value ) {
		  if( is_array($value) && count($value) > 0 ) {
				foreach( $value as $i=>$v ) {
					$result = $wpdb->insert( $wpdb->prefix.'postmeta', array(
						'post_id' => $duplicate_post_id,
						'meta_key' => $key,
						'meta_value' => $v
					));
				}
			}
	  	}

	  	$sendback = remove_query_arg( array('duplicate_post_id', '_wpnonce'), wp_get_referer());
	  	wp_redirect($sendback);
	  	exit;
	}
}