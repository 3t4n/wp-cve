<?php
/**
 * @file
 * Support for adding intelligence to pages and processing form submissions
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

/**
 * Implements hook_comment_post
 * @param $comment_ID
 * @param $comment_approved
 */
function intel_comment_post( $comment_ID, $comment_approved ) {
  d($comment_ID);
  d($comment_approved);
  $args = array(
    'ID' => $comment_ID,
  );
  $comments = get_comments($args);
  d($comments);
}
add_action( 'comment_post', 'intel_comment_post', 10, 2 );