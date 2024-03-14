<?php
// Functions page
// Copy post permission check
function carbon_copy_is_current_user_allowed_to_copy()
{
	return current_user_can( 'copy_posts' );
}
// Copy post type permission check
function carbon_copy_is_post_type_enabled( $post_type )
{
	$carbon_copy_types_enabled = get_option( 'carbon_copy_types_enabled', array( 'post', 'page' ) );
	if( ! is_array( $carbon_copy_types_enabled ) )
			$carbon_copy_types_enabled = array( $carbon_copy_types_enabled );
	return in_array( $post_type, $carbon_copy_types_enabled );
}
// Wrapper for optoin 'carbon_copy_create_user_level'
function carbon_copy_get_copy_user_level()
{
	return get_option( 'carbon_copy_copy_user_level' );
}

/*
 * Retrieve carbon copy link for post.
 * @param int $id Optional. Post ID.
 * @param string $context Optional, default to display. How to write the '&', defaults to '&amp;'.
 * @param string $draft Optional, default to true
 * @return string
 * Template tag
*/
function carbon_copy_get_clone_post_link( $id = 0, $context = 'display', $draft = true )
{
	if( ! carbon_copy_is_current_user_allowed_to_copy() )
		return;
	
	if( ! $post = get_post( $id ) )
		return;
	
	if( ! carbon_copy_is_post_type_enabled( $post->post_type ) )
		return;
	
	if( $draft )
		$action_name = "carbon_copy_save_as_new_post_draft";
	else
		$action_name = "carbon_copy_save_as_new_post";
	
	if( 'display' == $context )
		$action = '?action='.$action_name.'&amp;post='.$post->ID;
	else
		$action = '?action='.$action_name.'&post='.$post->ID;

	$post_type_object = get_post_type_object( $post->post_type );

	if( ! $post_type_object )
		return;
	
	return wp_nonce_url(apply_filters( 'carbon_copy_get_clone_post_link', admin_url( "admin.php". $action ), $post->ID, $context ), 'carbon-copy_' . $post->ID);
}
/**
 * Display carbon copy link for post.
 * @param string $link Optional. Anchor text.
 * @param string $before Optional. Display before edit link.
 * @param string $after Optional. Display after edit link.
 * @param int $id Optional. Post ID.
 */
function carbon_copy_clone_post_link( $link = null, $before = '', $after = '', $id = 0 )
{
	if( ! $post = get_post( $id ) )
		return;
	
	if( ! $url = carbon_copy_get_clone_post_link( $post->ID ) )
		return;
	
	if( null === $link )
		$link = esc_html__('Copy to Draft', 'carbon-copy' );
	
	$link = '<a class="post-clone-link" href="' . $url . '" title="' . esc_attr__("Copy to Draft", 'carbon-copy') .'">' . $link . '</a>';

	echo $before . apply_filters( 'carbon_copy_clone_post_link', $link, $post->ID ) . $after;
}
/**
 * Get original post.
 * @param int $post Optional. Post ID or Post object.
 * @param string $output Optional, default is Object. Either OBJECT, ARRAY_A, or ARRAY_N.
 * @return mixed Post data
 */
function carbon_copy_get_original( $post = null , $output = OBJECT )
{
	if( ! $post = get_post( $post ) )
		return null;
	
	$original_ID = get_post_meta( $post->ID, '_cc_original' );
	
	if( empty( $original_ID ) )
		return null;
	
	$original_post = get_post( $original_ID[0], $output );
	
	return $original_post;
}
function carbon_copy_get_edit_or_view_link( $post )
{
	$post = get_post( $post );
	
	if( ! $post )
		return null;

	$can_edit_post    = current_user_can( 'edit_post', $post->ID );
	$title            = _draft_or_post_title( $post );
	$post_type_object = get_post_type_object( $post->post_type );

	if( $can_edit_post && 'trash' != $post->post_status )
	{
		return sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			get_edit_post_link( $post->ID ),
			esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'default' ), $title ) ),
			$title
		);
	}
	else if( carbon_copy_is_post_type_viewable( $post_type_object ) )
	{
		if( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) )
		{
			if( $can_edit_post )
			{
				$preview_link = get_preview_post_link( $post );
				return sprintf(
					'<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
					esc_url( $preview_link ),
					esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;', 'default' ), $title ) ),
					$title
				);
			}
		}
		elseif( 'trash' != $post->post_status )
		{
			return sprintf(
				'<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
				get_permalink( $post->ID ),
				esc_attr( sprintf( __( 'View &#8220;%s&#8221;', 'default' ), $title ) ),
				$title
			);
		}
	}
	return $title;
}

// Patch for 'is_post_type_viewable' (introduced in WP 4.4)
function carbon_copy_is_post_type_viewable( $post_type )
{
	if( function_exists( 'is_post_type_viewable' ) )
	{
		return is_post_type_viewable( $post_type );
	}
	else
	{
		if( is_scalar( $post_type ) )
		{
			$post_type = get_post_type_object( $post_type );
			if( ! $post_type )
			{
				return false;
			}
		}
		return $post_type->publicly_queryable || ( $post_type->_builtin && $post_type->public );
	}
}

// Admin bar
function carbon_copy_admin_bar_render()
{
	if( ! is_admin_bar_showing() )
		return;
	
	global $wp_admin_bar;
	
	$current_object = get_queried_object();
	
	if( ! empty( $current_object ) )
	{
		if( ! empty( $current_object->post_type )
			&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )
			&& carbon_copy_is_current_user_allowed_to_copy()
			&& ( $post_type_object->show_ui || 'attachment' == $current_object->post_type )
			&& ( carbon_copy_is_post_type_enabled($current_object->post_type ) ) )
		{
			$wp_admin_bar->add_menu( array(
	        	'id' => 'new_draft',
	        	'title' => esc_attr__("Copy to Draft", 'carbon-copy'),
	        	'href' => carbon_copy_get_clone_post_link( $current_object->ID )
			) );
		}
	}
	else if( is_admin() && isset( $_GET['post'] ) )
	{
		$id = intval( $_GET['post'] );
		$post = get_post( $id );
		if( ! is_null( $post ) 
			&& carbon_copy_is_current_user_allowed_to_copy()
			&& carbon_copy_is_post_type_enabled( $post->post_type ) )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'new_draft',
				'title' => esc_attr__("Copy to Draft", 'carbon-copy'),
				'href' => carbon_copy_get_clone_post_link( $id )
			) );
		}
	}
}

function carbon_copy_add_css()
{
	if( ! is_admin_bar_showing() )
		return;
	
	$current_object = get_queried_object();
	
	if( ! empty( $current_object ) )
	{
		if( ! empty( $current_object->post_type )
			&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )
			&& carbon_copy_is_current_user_allowed_to_copy()
			&& ( $post_type_object->show_ui || 'attachment' == $current_object->post_type )
			&& ( carbon_copy_is_post_type_enabled( $current_object->post_type ) ) )
		{
			wp_enqueue_style( 'carbon-copy', plugins_url('/carbon-copy.css', __FILE__), array(), CARBON_COPY_CURRENT_VERSION );
		}
	}
	else if( is_admin() && isset( $_GET['post'] ) )
	{
		$id = intval( $_GET['post'] );
		$post = get_post( $id );
		if( ! is_null( $post )
			&& carbon_copy_is_current_user_allowed_to_copy()
			&& carbon_copy_is_post_type_enabled($post->post_type))
		{
			wp_enqueue_style( 'carbon-copy', plugins_url('/carbon-copy.css', __FILE__), array(), CARBON_COPY_CURRENT_VERSION );
		}
	}
}

add_action( 'init', 'carbon_copy_init' );
function carbon_copy_init()
{
	if( get_option( 'carbon_copy_show_adminbar' ) == 1 )
	{
		add_action( 'wp_before_admin_bar_render', 'carbon_copy_admin_bar_render' );
		add_action( 'wp_enqueue_scripts', 'carbon_copy_add_css' );
		add_action( 'admin_enqueue_scripts', 'carbon_copy_add_css' );
	}
}

// Sort taxonomy objects: first public, then private
function carbon_copy_tax_obj_cmp( $a, $b )
{
	return( $a->public < $b->public );
}