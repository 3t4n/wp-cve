<?php
/**
 * @package FT_Password_Protect_Children_Pages
 * @author FullThrottleDevelopment.com
 * @version 0.3
 */
/*
Plugin Name: FT Password Protect Children Pages
Plugin URI: http://fullthrottledevelopment.com/password-protect-children-pages
Description: This plugin does one thing. If a page that is password protected has children pages, all children pages will be protected with the same password. If the correct password is entered on the parent page or any of its children pages, all related pages will be viewable to the user.
Author: FullThrottle Development
Version: 0.3
Author URI: http://fullthrottledevelopment.com/
Primary Developer: Glenn Ansley (glenn@glennansley.com)
*/

// This function prints the password form if the parent page is password protected. It is called whenever 'the_content' is invoked.
function ft_password_protect_children_page_contents( $org_content ){
	if ( is_page() ){
		global $post;

		// Grab ancestors
		$ancestors = $post->ancestors;
		
		// Loop through ancestors, grab first one that is password protected
		foreach ( $ancestors as $ancestor ) {
		
			if ( post_password_required( $ancestor ) ) {
				$real_post = $post;
				$post = get_post( $ancestor );
			
				echo get_the_password_form();
				$post = $real_post;
				return;
			}
		}
	}
	return $org_content;
}
add_filter( 'the_content', 'ft_password_protect_children_page_contents' );

// This function prints the "excerpt can't be displayed" message if the parent post is protected. It is called whenever 'get_the_excerpt' is invoked (which gets invoked by get_excerpt() ).
function ft_password_protect_children_page_excerpts( $org_excerpt ){
	if ( is_page() ){
		global $post;
		
		// Grab ancestors
		$ancestors = $post->ancestors;

		// Loop through ancestors, grab first one that is password protected
		foreach ( $ancestors as $ancestor ) {
			if ( post_password_required( $ancestor ) ) {
				$output = wpautop( __('There is no excerpt because this is a protected post.') );
				return $output;
			}
		}
	}
	return $org_excerpt;
}
add_filter( 'get_the_excerpt', 'ft_password_protect_children_page_excerpts' , 9);

// This function alter's the Post Title to include the protected_title_format
function ft_password_protect_children_page_titles( $org_title, $title_id='' ){
	if ( is_page() && in_the_loop() ){

		global $post;
		
		// Grab ancestors
		$ancestors = $post->ancestors;

		// Loop through ancestors, grab first one that is password protected
		foreach ( $ancestors as $ancestor ) {
			
			$ancestor_post = get_post( $ancestor );
			
			if ( post_password_required( $ancestor ) || ( isset( $ancestor_post->post_password ) && !empty( $ancestor_post->post_password ) ) ) {

				$protected_title_format = apply_filters( 'protected_title_format', __('Protected: %s') );
				$title = sprintf( $protected_title_format, $org_title );
				return $title;
			}
		}
	}
	return $org_title;
}
add_filter( 'the_title', 'ft_password_protect_children_page_titles', 10 , 2 );
?>