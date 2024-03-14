<?php
function event_register() {
	$GLOBALS['qem_ic'] = qem_get_incontext();
	// load_plugin_textdomain( 'quick-event-manager', false, basename( dirname( __FILE__ ) ) . '/languages' );
	if ( ! post_type_exists( 'event' ) ) {
		$labels = array(
			'name'               => _x( 'Events', 'post type general name', 'quick-event-manager' ),
			'singular_name'      => _x( 'Event', 'post type singular name', 'quick-event-manager' ),
			'add_new'            => _x( 'Add New', 'event', 'quick-event-manager' ),
			'add_new_item'       => __( 'Add New Event', 'quick-event-manager' ),
			'edit_item'          => __( 'Edit Event', 'quick-event-manager' ),
			'new_item'           => __( 'New Event', 'quick-event-manager' ),
			'view_item'          => __( 'View Event', 'quick-event-manager' ),
			'search_items'       => __( 'Search event', 'quick-event-manager' ),
			'not_found'          => __( 'Nothing found', 'quick-event-manager' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'quick-event-manager' ),
			'parent_item_colon'  => ''
		);
		$args   = array(
			'labels'              => $labels,
			'public'              => true,
			'menu_icon'           => 'dashicons-calendar-alt',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'query_var'           => true,
			'rewrite'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'capability_type'     => array( 'event', 'events' ),
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'has_archive'         => true,
			'menu_position'       => null,
			'taxonomies'          => array( 'category', 'post_tag' ),
			'supports'            => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'comments',
				'excerpt',
				'revisions'
			),
		);
		register_post_type( 'event', apply_filters( 'qem_event_register', $args ) );
	}
}

function qem_duplicate_new_post( $posts, $post_id, $publish ) {
	//  event date can be either string or epoch
	if ( is_numeric( $posts['event_date'] ) && $posts['event_date'] >= time() ) {
		$start = $posts['event_date'];
	} else {
		$start = strtotime( $posts['event_date'] );
		if ( false === $start ) {
			$start = time();
		}
	}
	$rules        = array(
		'frequency' => $posts['thenumber'],
		'target'    => $posts['theday'],
		'number'    => (int) $posts['therepetitions'] + 1,
		'for'       => $posts['thewmy'],
		'start'     => $start,
		'end'       => 0
	);
	$rules['end'] = qem_get_end( $rules );

	$dates        = array();
	$current_time = $rules['start'];

	while ( ( $time = qem_get_next( $rules, $current_time ) ) < $rules['end'] ) {
		$current_time = $time;
		if ( $time < $rules['start'] ) {
			continue;
		}

		qem_create_post( $time, $post_id, $publish, false );

		$dates[] = array( 'time' => $time, 'date' => date( 'l F jS, Y', $time ) );
	}
}

function qem_create_post( $date, $post_id, $publish, $duplicate ) {

	$current_user    = wp_get_current_user();
	$new_post_author = $current_user->ID;
	$post            = get_post( $post_id );

	$new_post = $args = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => $new_post_author,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => $publish,
		'post_title'     => $post->post_title,
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order
	);

	if ( true == $duplicate ) {
		$new_post['post_title'] = esc_html__('Copy of:', 'quick-event-manager') . ' ' . $new_post['post_title'];
	}
	$new_post_id = wp_insert_post( $new_post );
	$taxonomies  = get_object_taxonomies( $post->post_type );

	foreach ( $taxonomies as $taxonomy ) {
		$post_terms = wp_get_object_terms( $post_id, $taxonomy );
		for ( $i = 0; $i < count( $post_terms ); $i ++ ) {
			wp_set_object_terms( $new_post_id, $post_terms[ $i ]->slug, $taxonomy, true );
		}
	}

	$postmeta = get_post_meta( $post_id );
	// remove times from date stamps to get true days difference
	$end_diff = ( (int) $postmeta['event_end_date'][0] - qem_time( $postmeta['event_finish'][0] ) )
	            - ( (int) $postmeta['event_date'][0] - qem_time( $postmeta['event_start'][0] ) );
	$end_date = ( ( $end_diff <= 0 ) ? '' : $date + $end_diff );
	// now add back times
	$postmeta['event_date'][0]     = $date + qem_time( $postmeta['event_start'][0] );
	$postmeta['event_end_date'][0] = $end_date;
	if ( $end_date !== '' ) {
		$postmeta['event_end_date'][0] = $end_date + qem_time( $postmeta['event_finish'][0] );
	}

	foreach ( $postmeta as $key => $value ) {
		update_post_meta( $new_post_id, $key, $value[0] );
	}

	return $new_post_id;
}
