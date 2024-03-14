<?php

add_action( 'init', 'gmoshowtime_create_initial_post_types' );
function gmoshowtime_create_initial_post_types() {
	$labels = array(
		'name' => sprintf( __( '%s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'singular_name' => sprintf( __( '%s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'add_new_item' => sprintf( __( 'Add New %s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'edit_item' => sprintf( __( 'Edit %s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'new_item' => sprintf( __( 'New %s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'view_item' => sprintf( __( 'View %s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'search_items' => sprintf( __( 'Search %s', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'not_found' => sprintf( __( 'No %s found.', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
		'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'gmoshowtime' ), __( 'Carousel', 'gmoshowtime' ) ),
	);
	$args = array(
		'labels' => $labels,
		'public' => false, // false ; show_ui=false, publicly_queryable=false, exclude_from_search=true, show_in_nav_menus=false
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		'rewrite' => false,
	);
	register_post_type( 'gmo-showtime', $args );
}

add_action( 'admin_menu', 'gmoshowtime_add_meta_boxes' );
function gmoshowtime_add_meta_boxes() {
	add_meta_box( 'add-gmo-showtime-link', __( 'Slide Links', 'gmo-showtime' ), 'gmoshowtime_add_link_box', 'gmo-showtime', 'normal', 'high' );
}

function gmoshowtime_add_link_box() {
	$post_id = get_the_ID();
	$get_noncename = 'slide_link_noncename';
	$url = esc_url( get_post_meta( $post_id, '_slide_link', true ) );
	$blank = (int) get_post_meta( $post_id, '_slide_blank', true );
	echo '<input type="hidden" name="' . $get_noncename . '" id="' . $get_noncename . '" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';
	echo '<p><label for="slide_link">' . __( 'Link : ', 'gmo-showtime' );
	echo '<input type="text" name="slide_link" id="slide_link" value="' . $url . '" size="30"></label></p>';
	echo '<p><label for="slide_blank"><input type="checkbox" name="slide_blank" id="slide_blank" value="1"' . checked( 1, $blank, false ) . '> ' . __( 'Open link in a new window/tab' ) . '</label></p>';
}

add_action( 'save_post', 'gmoshowtime_link_save' );
function gmoshowtime_link_save( $post_id ) {
	$get_noncename = 'slide_link_noncename';
	$key1 = '_slide_link';
	$post1 = 'slide_link';
	$key2 = '_slide_blank';
	$post2 = 'slide_blank';
	$get1 = esc_url( @$_POST[$post1] );
	$get2 = (int) @$_POST[$post2];
	if ( !isset( $_POST[$get_noncename] ) )
		return;
	if ( !wp_verify_nonce( $_POST[$get_noncename], plugin_basename( __FILE__ ) ) ) {
		return $post_id;
	}
	if ( '' == get_post_meta( $post_id, $key1 ) ) {
		add_post_meta( $post_id, $key1, $get1, true );
	} else if ( $get1 != get_post_meta( $post_id, $key1 ) ) {
		update_post_meta( $post_id, $key1, $get1 );
	} else if ( '' == $get1 ) {
		delete_post_meta( $post_id, $key1 );
	}

	if ( '' == get_post_meta( $post_id, $key2 ) ) {
		add_post_meta( $post_id, $key2, $get2, true );
	} else if ( $get2 != get_post_meta( $post_id, $key2 ) ) {
		update_post_meta( $post_id, $key2, $get2 );
	} else if ( '' == $get2 ) {
		delete_post_meta( $post_id, $key2 );
	}
}


function gmoshowtime_manage_posts_columns( $posts_columns ) {
	$new_columns = array();
	foreach ( $posts_columns as $column_name => $column_display_name ) {
		if ( $column_name == 'date' ) {
			$new_columns['thumbnail'] = __('Thumbnail');
			$new_columns['order'] = __( 'Order' );
			add_action( 'manage_posts_custom_column', 'gmoshowtime_add_column', 10, 2 );
		}
		$new_columns[$column_name] = $column_display_name;
	}
	return $new_columns;

}

function gmoshowtime_add_column($column_name, $post_id) {
	$post_id = (int)$post_id;

	if ( $column_name == 'thumbnail') {
		$thum = ( get_the_post_thumbnail( $post_id, array(50,50), 'thumbnail' ) ) ? get_the_post_thumbnail( $post_id, array(50,50), 'thumbnail' ) : __('None') ;
		echo $thum;
	}

	if ( $column_name == 'order' ) {
		$post = get_post( $post_id );
		echo $post->menu_order;
	}
}

function gmoshowtime_add_menu_order_column_styles() {
	if ('gmo-showtime' == get_post_type()) {
		
?>
<style type="text/css" charset="utf-8">
.fixed .column-thumbnail {
	width: 10%;
}
.fixed .column-order {
	width: 7%;
	text-align: center;
}
	        .post-php #message a {
	            display: none;
	        }
	        .wp-list-table .post-title span.more-link {
	            display: none;
	        }
</style>
<?php
	}
}

// sort by order
function add_menu_order_sortable_column( $sortable_column ) {
	$sortable_column['order'] = 'menu_order';
	return $sortable_column;
}

add_filter( 'manage_gmo-showtime_posts_columns', 'gmoshowtime_manage_posts_columns' );
add_action( 'admin_print_styles-edit.php', 'gmoshowtime_add_menu_order_column_styles' );
add_filter( 'manage_edit-gmo-showtime_sortable_columns', 'add_menu_order_sortable_column' );


function cpt_public_false() {
	if ( get_post_type() == 'gmo-showtime' ) {
		?>
		<style type="text/css">
		.post-php #message a {
			display: none;
		}
		.wp-list-table .post-title span.more-link {
			display: none;
		}
		</style>
		<?php
	}
}
add_action( 'admin_head', 'cpt_public_false' );

