<?php
/*-----------------------------------------------------------------------------------*/
/* The News custom post type
/*-----------------------------------------------------------------------------------*/

add_action('init', 'sakolawp_news_register'); 
function sakolawp_news_register() {

	$labels = array(
		'name'                => _x( 'News', 'Post Type General Name', 'sakolawp' ),
		'singular_name'       => _x( 'News', 'Post Type Singular Name', 'sakolawp' ),
		'menu_name'           => esc_html__( 'News', 'sakolawp' ),
		'parent_item_colon'   => esc_html__( 'Parent News:', 'sakolawp' ),
		'all_items'           => esc_html__( 'All News', 'sakolawp' ),
		'view_item'           => esc_html__( 'View News', 'sakolawp' ),
		'add_new_item'        => esc_html__( 'Add New News', 'sakolawp' ),
		'add_new'             => esc_html__( 'Add New', 'sakolawp' ),
		'edit_item'           => esc_html__( 'Edit News', 'sakolawp' ),
		'update_item'         => esc_html__( 'Update News', 'sakolawp' ),
		'search_items'        => esc_html__( 'Search News', 'sakolawp' ),
		'not_found'           => esc_html__( 'Not found', 'sakolawp' ),
		'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'sakolawp' ),
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => 'news',
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'rewrite'            => array( 'slug' => 'news' ),
		'supports'           => array('title','editor','thumbnail'),
		'menu_position'       => 7,

	); 
	register_post_type( 'sakolawp-news', $args );
	 
	register_taxonomy(
		"news-category", array("sakolawp-news"), array(
		"hierarchical"    => true,
		"label"       => "Categories", 
		"singular_label"  => "Categories", 
		"rewrite"     => true)
	);
  
	register_taxonomy_for_object_type('news-category', 'sakolawp-news'); 

	register_taxonomy(
		"news-tags", array("sakolawp-news"), array(
		"hierarchical"    => true,
		"label"       => "Tags",
		"singular_label"  => "Tags",
		"rewrite"     => true)
	);
  
	register_taxonomy_for_object_type('news-tags', 'sakolawp-news'); 

}

/*-----------------------------------------------------------------------------------*/
/* The Event custom post type
/*-----------------------------------------------------------------------------------*/

add_action('init', 'sakolawp_event_register'); 
function sakolawp_event_register() {
	$labels = array(
		'name'                => _x( 'Event', 'Post Type General Name', 'sakolawp' ),
		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'sakolawp' ),
		'menu_name'           => esc_html__( 'Event', 'sakolawp' ),
		'parent_item_colon'   => esc_html__( 'Parent Event:', 'sakolawp' ),
		'all_items'           => esc_html__( 'All Event', 'sakolawp' ),
		'view_item'           => esc_html__( 'View Event', 'sakolawp' ),
		'add_new_item'        => esc_html__( 'Add New Event', 'sakolawp' ),
		'add_new'             => esc_html__( 'Add New', 'sakolawp' ),
		'edit_item'           => esc_html__( 'Edit Event', 'sakolawp' ),
		'update_item'         => esc_html__( 'Update Event', 'sakolawp' ),
		'search_items'        => esc_html__( 'Search Event', 'sakolawp' ),
		'not_found'           => esc_html__( 'Not found', 'sakolawp' ),
		'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'sakolawp' ),
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => 'event',
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'rewrite'            => array( 'slug' => 'event' ),
		'supports'           => array('title','editor','thumbnail'),
		'menu_position'       => 7,
		'register_meta_box_cb' => 'sakolawp_event_date_meta_box'

	); 
	register_post_type( 'sakolawp-event', $args );

	register_taxonomy(
		"event-category", array("sakolawp-event"), array(
		"hierarchical"    => true,
		"label"       => "Categories", 
		"singular_label"  => "Categories", 
		"rewrite"     => true)
	);

	register_taxonomy_for_object_type('event-category', 'sakolawp-event'); 

	register_taxonomy(
		"event-tags", array("sakolawp-event"), array(
		"hierarchical"    => true,
		"label"       => "Tags",
		"singular_label"  => "Tags", 
		"rewrite"     => true)
	);

	register_taxonomy_for_object_type('event-tags', 'sakolawp-event'); 

	register_taxonomy_for_object_type('event-category', 'sakolawp-event'); 
}


// sakola event metabox
function sakolawp_event_date_meta_box() {
	add_meta_box(
		'sakolawp-event-metabox',
		esc_html__( 'Event Date', 'sakolawp' ),
		'sakolawp_event_date_meta_box_callback'
	);
}
add_action( 'add_meta_boxes_sakolawp-event', 'sakolawp_event_date_meta_box' );

function sakolawp_event_date_meta_box_callback( $post ) {
	// sakola date event field
	wp_nonce_field( 'sakolawp_event_date_nonce', 'sakolawp_event_date_nonce' );
	$value = get_post_meta( $post->ID, '_sakolawp_event_date', true );
	echo '<input type="date" id="sakolawp_event_date" name="sakolawp_event_date" value="' . esc_attr( $value ) . '">';

	// sakola time event field
	wp_nonce_field( 'sakolawp_event_date_clock_nonce', 'sakolawp_event_date_clock_nonce' );
	$value = get_post_meta( $post->ID, '_sakolawp_event_date_clock', true );
	echo '<input type="text" id="sakolawp_event_date_clock" name="sakolawp_event_date_clock" placeholder="HH:MM" value="' . esc_attr( $value ) . '">';
}

function save_sakolawp_event_date_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['sakolawp_event_date_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['sakolawp_event_date_nonce'], 'sakolawp_event_date_nonce' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	}
	else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Make sure that it is set.
	if ( ! isset( $_POST['sakolawp_event_date'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['sakolawp_event_date'] );
	$my_data2 = sanitize_text_field( $_POST['sakolawp_event_date_clock'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_sakolawp_event_date', $my_data );
	update_post_meta( $post_id, '_sakolawp_event_date_clock', $my_data2 );
}

add_action( 'save_post', 'save_sakolawp_event_date_meta_box_data' );