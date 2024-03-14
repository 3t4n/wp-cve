<?php


class GcThreadPairingService
{
  private static function handlePostNotFoundError($thread)
  {
    GcLogger::getLogger()->error('post not found in wordpress database: ' . json_encode($thread) . ')');

    // Error while getting the comments
    //update_option('gc-sync-error', json_encode(array('content' => __('Error Getting Sync', 'graphcomment-comment-system'))));
  }

  private static function extractIdentifier($thread, $gc_public_key)
  {
    $identifier = null;
    $substring = $gc_public_key . '@';

    if (substr($thread['full_id'], 0, strlen($substring)) == $substring) {
      $identifier = substr($thread['full_id'], strlen($substring));
    }

    return $identifier;
  }

  private static function extractUid($thread, $gc_public_key)
  {
    $uid = null;
    $substring = $gc_public_key . '@';

    if (!isset($thread['uid'])) {
      $uid = $thread['_id'];
    }
    else if (isset($thread['uid']) && substr($thread['uid'], 0, strlen($substring)) == $substring) {
      $uid = substr($thread['uid'], strlen($substring));
    }

    return $uid;
  }

  private static function getPostFromSlug($identifier, $uid)
  {
    // using the identifier
    $args = array(
      'name' => $identifier,
      'post_type' => 'post',
      'numberposts' => 1
    );
    $wp_posts = get_posts($args);

    if ($wp_posts) {
      return $wp_posts[0]->ID;
    }

    // using the uid
    /*
    $args = array(
      'id' => $uid,
      'post_type' => 'post',
      'numberposts' => 1
    );
    $wp_posts = get_posts($args);

    if ($wp_posts) {
      return $wp_posts[0]->ID;
    }
    */

    // else in some cases it could be a page
    $args = array(
      'name' => $identifier,
      'post_type' => 'page',
      'numberposts' => 1
    );
    $wp_posts = get_posts($args);

    if ($wp_posts) {
      return $wp_posts[0]->ID;
    }

    // else
    return 0;
  }

  public static function getPostFromThread($thread, $gc_public_key)
  {
    $identifier = self::extractIdentifier($thread, $gc_public_key);
    $thread_uid = self::extractUid($thread, $gc_public_key);
    $post_id = 0;

    if ($thread_uid > 0) {
      $post_id = $thread_uid;
    }

    // Still an old thread, identifier begin with `http`
    if (strpos($identifier, 'http') === 0 && $post_id === 0) {
      $post_id = url_to_postid($identifier);
    }

    // Fix for local debugging
    if (strpos($identifier, '127.0.0.1') === 0 && $post_id === 0) {
      $post_id = 'localhost';
    }

    // If not found, try with the new way
    if ($post_id === 0) {
      $post_id = self::getPostFromSlug($identifier, $thread_uid);
    }

    if ($post_id === 0) {
      self::handlePostNotFoundError($thread);
    }

    return $post_id;
  }
}
