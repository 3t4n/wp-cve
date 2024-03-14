<?php

/* --------------------------------------------------------- */
/* !Display the title - 1.1.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_title_display') ) {
function mtphr_members_title_display( $post_id=false ) {
	echo get_mtphr_member_title( $post_id );
}
}
if( !function_exists('get_mtphr_members_title_display') ) {
function get_mtphr_members_title_display( $post_id=false ) {
	return get_mtphr_member_title( $post_id );
}
}
if( !function_exists('get_mtphr_members_title') ) {
function get_mtphr_members_title( $post_id=false ) {
	return get_mtphr_member_title( $post_id );
}
}


/* --------------------------------------------------------- */
/* !Display the contact info - 1.1.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_info_display') ) {
function mtphr_members_info_display( $post_id=false ) {
	echo get_mtphr_member_contact_info( $post_id );
}
}
if( !function_exists('get_mtphr_members_info_display') ) {
function get_mtphr_members_info_display( $post_id=false ) {
	return get_mtphr_member_contact_info( $post_id );;
}
}


/* --------------------------------------------------------- */
/* !Display the social links - 1.1.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_social_sites_display') ) {
function mtphr_members_social_sites_display( $post_id=false ) {
	echo get_mtphr_members_social_sites_display( $post_id );
}
}
if( !function_exists('get_mtphr_members_social_sites_display') ) {
function get_mtphr_members_social_sites_display( $post_id=false ) {
	return get_mtphr_members_social_sites_display( $post_id );
}
}

