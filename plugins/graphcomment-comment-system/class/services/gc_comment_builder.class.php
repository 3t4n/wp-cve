<?php

/**
 * Class GcCommentBuilder
 *
 * Build a WordPress comment from a GraphComment one.
 */

class GcCommentBuilder
{

  private $comment;

  public function __construct($comment)
  {
    $this->comment = json_decode(json_encode($comment));
    $this->convertStatusGraphCommentToWordpress();
  }

  private function convertStatusGraphCommentToWordpress()
  {
    $new_status = null;
    if ($this->comment->status === 'pending') {
      $new_status = '0';
    }
    if ($this->comment->status === 'approved') {
      $new_status = '1';
    }
    if ($this->comment->status === 'refused' || $this->comment->status === 'deleted') {
      $new_status = 'trash';
    }
    if ($this->comment->spam === true) {
      $new_status = 'spam';
    }
    $this->comment->status = $new_status;
  }

  private function getCreationDatetime()
  {
    if (empty($this->comment->created_at)) {
      return null;
    }

    return date('Y-m-d H:i:s', strtotime($this->comment->created_at));
  }

  public function __toString()
  {
    return '(' . $this->comment->_id . ') ' . $this->comment->content;
  }

  public function isFirstLevel()
  {
    return !isset($this->comment->parent_id) || $this->comment->parent_id === null;
  }

  public function getGraphCommentId()
  {
    return $this->comment->_id;
  }

  public function getWordPressId()
  {
    return $this->comment->comment_ID;
  }

  public function setWordpressId($id)
  {
    $this->comment->comment_ID = $id;
  }

  public function getParentId()
  {
    return $this->comment->parent_id;
  }

  public function setParent($id)
  {
    $this->comment->parent_id = $id;
  }

  public function setPostId($post_ID)
  {
    $this->comment->post_ID = $post_ID;
  }

  public function updateCommentInDatabase()
  {
    global $wpdb;

    $data = array(
      'comment_ID' => $this->comment->comment_ID,
      'comment_author_email' => ($this->authorMeta('email') ? $this->authorMeta('email') : ''),
      'comment_content' => $this->comment->content,
      'comment_approved' => $this->comment->status
    );

    $up_res = wp_update_comment($data);

    if ($up_res === false) {
      GcLogger::getLogger()->error('GcParamsService::updateCommentInDatabase() - Update error ( request: ' . $this->toString() . ' )');
      GcLogger::getLogger()->error('GcParamsService::updateCommentInDatabase() - Last SQL error (' . $wpdb->last_error . ' )');
      return true;
    }

    return true;
  }

  public function my_own_maybe_encode_emoji( $string ) {
      global $wpdb;
      $db_charset = $wpdb->charset;
      if ( 'utf8mb4' != $db_charset ) {
          if ( function_exists('wp_encode_emoji') && function_exists( 'mb_convert_encoding' ) ) {
              $string = wp_encode_emoji( $string );
          }
      }
      return $string;
  }

  public function insertCommentInDatabase()
  {
    global $wpdb;
    // Also, consider using wp_new_comment(),
    // which sanitizes and validates comment data before calling wp_insert_comment()
    // to insert the comment into the database.

    $data = array(
        'comment_post_ID' => $this->comment->post_ID,
        'comment_author' => sanitize_user($this->authorMeta('username')),
        'comment_author_email' => $this->authorMeta('email') ? $this->authorMeta('email') : '',
        'comment_author_ip' => $this->authorMeta('ip') ? $this->authorMeta('ip') : '',
        'comment_content' => $this->my_own_maybe_encode_emoji($this->comment->content),
        'comment_parent' => $this->comment->parent_id ? $this->comment->parent_id : 0,
        'comment_date' => $this->getCreationDatetime(),
        'comment_approved' => $this->comment->status,
    );

    $comment_id = wp_insert_comment($data);

    if ($comment_id === false) {
      GcLogger::getLogger()
          ->error('GcParamsService::insertCommentInDatabase() - Insert error ( request: ' . $this->toString() . ' )');
      GcLogger::getLogger()
          ->error('GcParamsService::insertCommentInDatabase() - Last SQL error (' . $wpdb->last_error . ' )');
      return false;
    }

    $this->setWordpressId($comment_id);

    return true;
  }

  private function authorMeta($property)
  {
    if (!$this->comment->cached_author) return;

    if (!isset($this->comment->cached_author->$property)) return;

    return $this->comment->cached_author->$property;
  }

  public function toString()
  {
    return '{'.
      'comment_author : \'' . sanitize_user($this->authorMeta('username')) . '\',' .
      'comment_author_email \'' . ($this->authorMeta('email') ? $this->authorMeta('email') : '') . '\',' .
      'comment_author_ip \'' . ($this->authorMeta('ip') ? $this->authorMeta('ip') : '') . '\',' .
      'comment_content \'' . $this->my_own_maybe_encode_emoji($this->comment->content) . '\',' .
      'comment_parent \'' . (($this->comment->parent) ? $this->comment->parent : 0) . '\',' .
      'comment_date \'' . $this->getCreationDatetime() . '\',' .
      'comment_approved \'' . $this->comment->status . '\'' .
    '}';
  }
}
