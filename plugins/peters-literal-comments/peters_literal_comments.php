<?php
/*
Plugin Name: Peter's Literal Comments
Plugin URI: http://www.theblog.ca/literal-comments
Description: Everything that your commenter writes should display literally. Back to plain text! No allowed tags, period.
Author: Peter Keung
Version: 1.0.2
Author URI: http://www.theblog.ca
Change Log:
2011-07-23  Disable auto-linking by WordPress by default.
2008-04-06  Fixed the treatment of single quotes thanks to http://wordpress.org/extend/plugins/kb-backtick-comments/. So what I said about being an only version was wrong :P
2008-01-31  First, and probably only version.
*/

// This will occur when the comment is posted
function plc_comment_post( $incoming_comment )
{

	// convert everything in a comment to display literally
	$incoming_comment['comment_content'] = htmlspecialchars( $incoming_comment['comment_content'] );

	// the one exception is single quotes, which cannot be #039; because WordPress marks it as spam
	$incoming_comment['comment_content'] = str_replace( "'", '&apos;', $incoming_comment['comment_content'] );

	return( $incoming_comment );
}

// This will occur before a comment is displayed
function plc_comment_display( $comment_to_display )
{

	// Put the single quotes back in
	$comment_to_display = str_replace( '&apos;', "&#039;", $comment_to_display );

	return $comment_to_display;
}

add_filter( 'preprocess_comment', 'plc_comment_post', '', 1);
add_filter( 'comment_text', 'plc_comment_display', '', 1);
add_filter( 'comment_text_rss', 'plc_comment_display', '', 1);
add_filter( 'comment_excerpt', 'plc_comment_display', '', 1);
remove_filter( 'comment_text', 'make_clickable', 9 );
?>