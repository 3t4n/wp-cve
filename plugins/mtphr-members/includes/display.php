<?php

/* --------------------------------------------------------- */
/* !Get & display a member's title - 1.1.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_member_title') ) {
function mtphr_member_title( $id=false, $element='h3', $before='', $after='', $class='' ) {
	echo get_mtphr_member_title( $id, $element, $before, $after, $class );
}
}

if( !function_exists('get_mtphr_member_title') ) {
function get_mtphr_member_title( $id=false, $element='h3', $before='', $after='', $class='' ) {

	// Get the id
	$member = $id ? get_post( $id ) : get_post( get_the_id() );
	
	// Get the title
	$title = get_post_meta( $member->ID, '_mtphr_members_title', true );
	
	// Sanitize the classes
	$classes = mtphr_members_sanitize_class( $class );
	
	// Sanitize other elements
	$element = sanitize_text_field( $element );
	$before = html_entity_decode( sanitize_text_field($before) );
	$after = html_entity_decode( sanitize_text_field($after) );
	$title = html_entity_decode( $title );
	
	$html = '';
	if( $title != '' ) {
		$html = '<'.$element.' class="mtphr-member-title mtphr-member-title-'.$member->ID.' '.$classes.'">'.$before.$title.$after.'</'.$element.'>';
	}		
	return apply_filters( 'mtphr_member_title', $html, $id, $element, $before, $after, $class );
}
}


/* --------------------------------------------------------- */
/* !Get & display a member's contact info - 1.1.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_member_contact_info') ) {
function mtphr_member_contact_info( $id=false, $title='', $title_element='h3', $class='' ) {
	echo get_mtphr_member_contact_info( $id, $title, $title_element, $class );
}
}

if( !function_exists('get_mtphr_member_contact_info') ) {
function get_mtphr_member_contact_info( $id=false, $title='', $title_element='h3', $class='' ) {
	
	// Get the id
	$member = $id ? get_post( $id ) : get_post( get_the_id() );
	
	// Get the member info
	$contact_info = get_post_meta( $member->ID, '_mtphr_members_contact_info', true );
	
	// Sanitize the classes
	$classes = mtphr_members_sanitize_class( $class );
	
	$instance = apply_filters( 'mtphr_member_contact_info_instance', array(
		'title' => sanitize_text_field($title),
		'contact_info' => $contact_info
	), $id, $title, $contact_info );
	
	$args = apply_filters( 'mtphr_member_contact_info_args', array(
		'before_widget' => '<aside class="mtphr-member-contact-info '.$classes.'">',
		'after_widget' => '</aside>',
		'before_title' => '<'.$title_element.' class="mtphr-member-contact-info-title">',
		'after_title' => '</'.$title_element.'>'
	), $id, $title_element, $class );
	
	ob_start();
	the_widget( 'mtphr_contact_widget', $instance, $args );
	return ob_get_clean();
}
}


/* --------------------------------------------------------- */
/* !Get & display a member's social sites - 1.1.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_member_social_sites') ) {
function mtphr_member_social_sites( $id=false, $title='', $title_element='h3', $class='' ) {
	echo get_mtphr_member_social_sites( $id, $title, $title_element, $class );
}
}

if( !function_exists('get_mtphr_member_social_sites') ) {
function get_mtphr_member_social_sites( $id=false, $title='', $title_element='h3', $class='' ) {

	// Get the id
	$member = $id ? get_post( $id ) : get_post( get_the_id() );
		
	// Get the social sites & new tab
	$social_sites = get_post_meta( $member->ID, '_mtphr_members_social', true );
	$new_tab = get_post_meta( $member->ID, '_mtphr_members_social_new_tab', true );
	
	// Sanitize the classes
	$classes = mtphr_members_sanitize_class( $class );
	
	$instance = apply_filters( 'mtphr_member_social_instance', array(
		'title' => sanitize_text_field($title),
		'sites' => $social_sites,
		'new_tab' => $new_tab
	), $id, $title, $social_sites, $new_tab );
	
	$args = apply_filters( 'mtphr_member_social_args', array(
		'before_widget' => '<aside class="mtphr-member-social-sites '.$classes.'">',
		'after_widget' => '</aside>',
		'before_title' => '<'.$title_element.' class="mtphr-member-social-sites-title">',
		'after_title' => '</'.$title_element.'>'
	), $id, $title_element, $class );
	
	ob_start();
	the_widget( 'mtphr_social_widget', $instance, $args );
	return ob_get_clean();
}
}



/* --------------------------------------------------------- */
/* !Get & display a member's twitter feed - 1.1.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_member_twitter') ) {
function mtphr_member_twitter( $id=false, $title='', $title_element='h3', $limit='3', $image=false, $avatar=false, $class='' ) {
	echo get_mtphr_member_twitter( $id, $title, $title_element, $limit, $image, $avatar, $class );
}
}

if( !function_exists('get_mtphr_member_twitter') ) {
function get_mtphr_member_twitter( $id=false, $title='', $title_element='h3', $limit='3', $image=false, $avatar=false, $class='' ) {
	
	// Get the id
	$member = $id ? get_post( $id ) : get_post( get_the_id() );
	
	// Get the member twitter handle
	$twitter_name= get_post_meta( $member->ID, '_mtphr_members_twitter', true );
	
	// Sanitize the classes
	$classes = mtphr_members_sanitize_class( $class );
	
	$instance = array(
		'title' => sanitize_text_field($title),
		'twitter_name' => $twitter_name,
		'widget_limit' => intval($limit)
	);
	if( $image ) {
		$instance['widget_image'] = true;
	}
	if( $avatar ) {
		$instance['widget_avatar'] = true;
	}
	$instance = apply_filters( 'mtphr_member_twitter_instance', $instance, $id, $title, $twitter_name, $limit );	
	
	$args = apply_filters( 'mtphr_member_twitter_args', array(
		'before_widget' => '<aside class="mtphr-member-twitter '.$classes.'">',
		'after_widget' => '</aside>',
		'before_title' => '<'.$title_element.' class="mtphr-member-twitter-title">',
		'after_title' => '</'.$title_element.'>'
	), $id, $title_element, $class );
	
	ob_start();
	the_widget( 'mtphr_twitter_widget', $instance, $args );
	return ob_get_clean();
}
}



/* --------------------------------------------------------- */
/* !Display the thumbnail - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_thumbnail_display') ) {
function mtphr_members_thumbnail_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {
	echo get_mtphr_members_thumbnail_display( $post_id, $permalink, $disable_permalinks );
}
}
if( !function_exists('get_mtphr_members_thumbnail_display') ) {
function get_mtphr_members_thumbnail_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$permalink = $permalink ? $permalink : get_permalink( $post_id );

	if( $thumb_id = get_post_thumbnail_id($post_id) ) {

		$thumb_size = apply_filters( 'mtphr_members_thumbnail_size', 'thumbnail' );
		$thumbnail = get_mtphr_members_thumbnail( $post_id, $thumb_size );
		$thumbnail = $disable_permalinks ? $thumbnail : '<a href="'.$permalink.'">'.$thumbnail.'</a>';
		return apply_filters( 'mtphr_members_thumbnail', $thumbnail, $thumb_size, $permalink, $disable_permalinks );
	}
}
}
if( !function_exists('get_mtphr_members_thumbnail') ) {
function get_mtphr_members_thumbnail( $post_id=false, $thumb_size=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$thumb_size = $thumb_size ? $thumb_size : apply_filters( 'mtphr_members_thumbnail_size', 'thumbnail' );

	if( $thumb_id = get_post_thumbnail_id($post_id) ) {
		return get_the_post_thumbnail( $post_id, $thumb_size );
	}
}
}



/* --------------------------------------------------------- */
/* !Display the name - 1.0.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_name_display') ) {
function mtphr_members_name_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {
	echo get_mtphr_members_name_display( $post_id, $permalink, $disable_permalinks );
}
}
if( !function_exists('get_mtphr_members_name_display') ) {
function get_mtphr_members_name_display( $post_id=false, $permalink=false, $disable_permalinks=false ) {

	$post_id = $post_id ? $post_id : get_the_id();
	$permalink = $permalink ? $permalink : get_permalink( $post_id );

	$member_name = get_mtphr_members_name( $post_id );
	if( $disable_permalinks ) {
		echo '<h3 class="mtphr-members-name">'.$member_name.'</h3>';
	} else {
		echo '<h3 class="mtphr-members-name"><a href="'.$permalink.'">'.$member_name.'</a></h3>';
	}
}
}
if( !function_exists('get_mtphr_members_name') ) {
function get_mtphr_members_name( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	return apply_filters( 'mtphr_members_archive_name', get_the_title($post_id) );
}
}



/* --------------------------------------------------------- */
/* !Display the excerpt - 1.0.9 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_excerpt_display') ) {
function mtphr_members_excerpt_display( $post_id=false, $excerpt_length=140, $excerpt_more='&hellip;', $disable_permalinks=false ) {
	echo get_mtphr_members_excerpt_display( $post_id, $excerpt_length, $excerpt_more, $disable_permalinks );
}
}
if( !function_exists('get_mtphr_members_excerpt_display') ) {
function get_mtphr_members_excerpt_display( $post_id=false, $excerpt_length=140, $excerpt_more='&hellip;', $disable_permalinks=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$html = '';

	$links = array();
	preg_match('/{(.*?)\}/s', $excerpt_more, $links);
	if( isset($links[0]) ) {
		$more_link = $disable_permalinks ? $links[1] : '<a href="'.get_permalink($post_id).'">'.$links[1].'</a>';
		$excerpt_more = preg_replace('/{(.*?)\}/s', $more_link, $excerpt_more);
	}
	if( $excerpt_length <= 0 ) {
		if( !$excerpt = get_the_content() ) {
			$post = get_post( $post_id );
			$excerpt = $post->post_content;
		}
	} else {
		if( !$excerpt = get_the_excerpt() ) {
			$post = get_post( $post_id );
			$excerpt = ( $post->post_excerpt != '' ) ? $post->post_excerpt : $post->post_content;
		}
		$excerpt = wp_html_excerpt( $excerpt, intval($excerpt_length) );
	}
	$excerpt .= $excerpt_more;
	if( $excerpt_length <= 0 ) {
		$excerpt = apply_filters( 'the_content', $excerpt );
	}
	$html .= '<p class="mtphr-members-excerpt">'.apply_filters( 'mtphr_members_excerpt', $excerpt, $excerpt_length, $excerpt_more ).'</p>';

	return $html;
}
}