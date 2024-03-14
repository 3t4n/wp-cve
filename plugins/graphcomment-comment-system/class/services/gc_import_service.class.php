<?php

/*
delete_option(GcImportService::getOptionsPrefix() . 'status');
delete_option(GcImportService::getOptionsPrefix() . 'batch_number');
delete_option(GcImportService::getOptionsPrefix() . 'nbr_comment_import');
delete_option(GcImportService::getOptionsPrefix() . 'error_msg');
delete_option(GcImportService::getOptionsPrefix() . 'total');
*/

require_once(__DIR__.'/gc_comment_pairing_dao.class.php');

class GcImportService
{
  private $status;

  public function __construct() {}

  public static function getOptionsPrefix() {
    $publicKey = GcParamsService::getInstance()->graphcommentGetWebsiteId();
    return 'gc_import_' . $publicKey . '_';
  }

  private static $allowedStatus = array( 'pending', 'error', 'finished' );

  public function migrateOldImport() {
    $this->setImportStatus('finished');

    $dateBegin = get_option('gc_import_date_begin');
    $this->setDateBegin($dateBegin);

    $dateEnd = get_option('gc_import_date_end');
    $this->setDateEnd($dateEnd);

    $total = get_option('gc_import_total');
    $this->setTotalComments($total);

    $nbrComments = get_option('gc_import_nbr_comment_import');
    $this->setNbrCommentsImport($nbrComments);
  }

  private function setImportStatus($status) {
    if (in_array($status, GcImportService::$allowedStatus)) {
      update_option(GcImportService::getOptionsPrefix() . 'status', $status);
      return true;
    }
    return false;
  }

  public function setImportErrorMsg($msg) {
    update_option(GcImportService::getOptionsPrefix() . 'error_msg', $msg);
  }

  public function getImportErrorMsg() {
    return get_option(GcImportService::getOptionsPrefix() . 'error_msg');
  }

  private function deleteImportErrorMsg() {
    delete_option(GcImportService::getOptionsPrefix() . 'error_msg');
  }

  private function setImportBatchNumber($batch_number) {
    update_option(GcImportService::getOptionsPrefix() . 'batch_number', $batch_number);
  }

  public function getImportStatus() {
    return get_option(GcImportService::getOptionsPrefix() . 'status', '');
  }

  private function getImportBatchNumber() {
    return get_option(GcImportService::getOptionsPrefix() . 'batch_number', '');
  }

  private function setTotalComments($nbr) {
    update_option(GcImportService::getOptionsPrefix() . 'total', $nbr);
  }

  public function getTotalComments() {
    return get_option(GcImportService::getOptionsPrefix() . 'total', 0);
  }

  public function getAjaxAdvancement() {
    $nbr_comment_import = get_option(GcImportService::getOptionsPrefix() . 'nbr_comment_import', 0);
    $percent = intval($nbr_comment_import) / intval(get_option(GcImportService::getOptionsPrefix() . 'total', 100)) * 100;
    return array('nbr_comment_import' => $nbr_comment_import, 'percent' => $percent, 'status' => get_option(GcImportService::getOptionsPrefix() . 'status'));
  }

  public function getNbrCommentsImport() {
    return get_option(GcImportService::getOptionsPrefix() . 'nbr_comment_import', 0);
  }

  private function setNbrCommentsImport($nbr) {
    update_option(GcImportService::getOptionsPrefix() . 'nbr_comment_import', $nbr);
  }

  private function addNbrCommentsImport($nbr) {
    $tmp = get_option(GcImportService::getOptionsPrefix() . 'nbr_comment_import');
    $tmp = intval($tmp) + intval($nbr);
    $this->setNbrCommentsImport($tmp);
  }

  private function setDateBegin() {
    update_option(GcImportService::getOptionsPrefix() . 'date_begin', date('y-m-d H:i:s'));
  }

  public function getDateBegin() {
    return get_option(GcImportService::getOptionsPrefix() . 'date_begin');
  }

  private function setDateEnd() {
    update_option(GcImportService::getOptionsPrefix() . 'date_end', date('y-m-d H:i:s'));
  }

  public function getDateEnd() {
    return get_option(GcImportService::getOptionsPrefix() . 'date_end');
  }

  public function setImportationError($msg) {
    $this->setImportStatus('error');
    $this->setImportErrorMsg($msg);
  }

  /**
   * Get comment's status string from wordpress approved format
   *
   * @param $comment_status
   * @return string
   */
  private static function getGcStatus($comment_status) {
    switch($comment_status) {
      case '0':
        return 'pending';
      case '1':
        return 'approved';
      default:
        return 'deleted';
    }
  }

  public function importInit() {
    $this->status = $this->getImportStatus();

    if ($this->status !== '') {
      GcLogger::getLogger()->error('GcImportService::importInit() - An import is already pending');
      return __('Import Already Pending', 'graphcomment-comment-system');
    }

    $nbrComment = $this->getNbrCommentToImport();
    if ($nbrComment == '0') {
      GcLogger::getLogger()->error('GcImportService::importInit() - No comment to import');
      return __('Error Import Init No Comments', 'graphcomment-comment-system');
    }

    $res = GcApiService::importInit($nbrComment);

    if ($res['skip']) {
      return 'skip';
    }

    if ($res['error'] !== false) {
      return $res['error']; // importInit return the correct error message
    }

    $batch_number = $res['batch_number'];

    delete_option(GcImportService::getOptionsPrefix() . 'status_stopped');
    $this->setImportBatchNumber($batch_number);
    $this->setImportStatus('pending');
    $this->setTotalComments($nbrComment);
    $this->setNbrCommentsImport('0');
    $this->setDateBegin();

    return true;
  }

  public function restartImportationInit() {
    $public_key = get_option('gc_public_key');
    $batch_number = $this->getImportBatchNumber();
    if (!empty($batch_number)) {
      $res = wp_remote_post(constant('API_URL_IMPORT_RESTART'), array('sslverify' => constant('SSLVERIFY'), 'body' => array('public_key' => $public_key, 'platform' => 'wp', 'batch_import_number' => $batch_number)));
      // Extract the HTTP ret code
      $httpCode = wp_remote_retrieve_response_code($res);

      if ($httpCode !== 200) {
        GcLogger::getLogger()->error('GcImportService::restartImportationInit() - Got HTTP ret !== 200 ( url: '.constant('API_URL_IMPORT_RESTART').')');

        // The caller function should handle the error printing
        return __('Error Import Restart', 'graphcomment-comment-system');
      }

      $this->deleteImportErrorMsg();
      $this->setNbrCommentsImport(0);
      $this->setDateBegin();
      $this->setImportStatus('pending');

      return true;

    }

    return __('No Import', 'graphcomment-comment-system');
  }

  public function stopImportation() {
    $public_key = get_option('gc_public_key');
    $res = GcApiService::importCancel($public_key);

    if ($res['error'] !== false) {
      return $res['error']; // importCancel returns directly the translated error
    }

    $this->cleanImport();
    return true;
  }

  private function cleanImport() {
    $this->deleteImportErrorMsg();
    update_option(GcImportService::getOptionsPrefix() . 'status_stopped', 'true');
    delete_option(GcImportService::getOptionsPrefix() . 'status');
    delete_option(GcImportService::getOptionsPrefix() . 'batch_number');
    delete_option(GcImportService::getOptionsPrefix() . 'nbr_comment_import');
    delete_option(GcImportService::getOptionsPrefix() . 'total');
  }

  private function getNbrCommentToImport() {
    global $wpdb;

    $sql = 'SELECT COUNT(*) AS `nbr` FROM `' . $wpdb->comments . '` ';
    $sql .= ' LEFT JOIN ' . GcCommentPairingDao::getTableName() . ' ON ' . GcCommentPairingDao::getTableName() . '.wp_comment_id = ' . $wpdb->comments . '.comment_ID ';
    $sql .= ' WHERE ' . GcCommentPairingDao::getTableName() . '.wp_comment_id IS NULL ';

    $ret = $wpdb->get_results($sql);

    $wpdb->flush();

    if ($ret === false) {
      GcLogger::getLogger()->error('GcImportService::getNbrCommentToImport() - Error Sql ( request: '.$sql.' )');
      return false;
    }

    if (count($ret) === 1) {
      return $ret[0]->nbr;
    }

    return false;
  }

  private function finishImport() {
    $public_key = get_option('gc_public_key');
    $batch_number = get_option(GcImportService::getOptionsPrefix() . 'batch_number');

    if (empty($public_key) || empty($batch_number)) {
      GcLogger::getLogger()->error('finishImport - no gc_public_key or no batch_number');

      // Error importation
      return false;
    }

    $this->setDateEnd();
    $this->setImportStatus('finished');

    $res = GcApiService::importFinish($public_key, $batch_number);

    if ($res['error'] !== false) {
      return false;
    }

    $this->deleteImportErrorMsg();

    return true;
  }

  public function importNextComments() {
    global $wpdb;

    $fname = 'GcImportService::importNextComments()';

    $sql = 'SELECT * FROM ' . $wpdb->comments . ' ';
    $sql .= ' LEFT JOIN ' . GcCommentPairingDao::getTableName() . ' ON ' . GcCommentPairingDao::getTableName() . '.wp_comment_id = ' . $wpdb->comments . '.comment_ID ';
    $sql .= ' WHERE ' . GcCommentPairingDao::getTableName() . '.wp_comment_id IS NULL ';
    $sql .= ' ORDER BY comment_ID ASC LIMIT ' . intval($this->getNbrCommentsImport()) . ', ' . constant('NUMBER_COMMENTS_IMPORT_PARTS') . ' ;';

    GcLogger::getLogger()->debug($fname.' - request: Get Next Comments');

    $comments = $wpdb->get_results($sql);

    if (!is_array($comments)) {
      GcLogger::getLogger()->error($fname.' - Error SQL: ('.$sql.') - last_error: ('.$wpdb->last_error.')');

      $this->setImportationError(__('Error Import', 'graphcomment-comment-system'));
      return false;
    }

    GcLogger::getLogger()->debug($fname.' - SQL :'.count($comments).' comments found for sending');

    $wpdb->flush();

    // Look for the posts we need
    $posts = array_reduce($comments, function($postsOfComments, $next) {
      // Look if we already have it
      $founds = array_filter($postsOfComments, function($post) use ($next) {
        return intval($post->ID) === intval($next->comment_post_ID);
      });

      // Not Found, we can add the WP_Post Object
      if (count($founds) === 0) {
        array_push($postsOfComments, get_post($next->comment_post_ID));
      }

      return $postsOfComments;
    }, array());

    if (!is_array($posts)) {
      GcLogger::getLogger()->error($fname.' - get_posts failed '.json_encode($comments));
      $this->setImportationError(__('Error Import', 'graphcomment-comment-system'));
      return false;
    }

    GcLogger::getLogger()->debug($fname.' - SQL :'.count($posts).' posts found for sending');

    // Formatting the comments before sending
    $comments = array_map(function($comment) use ($posts) {

      // Extract the date of this comment's post, in case we need to create the thread
      $founds = array_filter($posts, function($post) use ($comment) {
        return intval($post->ID) === intval($comment->comment_post_ID);
      });

      $page_date = null;

      if (count($founds) > 0) {
        $page_date = array_shift($founds)->post_date;
      }

      return json_decode(json_encode(array(
        'id' => $comment->comment_ID,
        'parent_id' => $comment->comment_parent === '0' ? null : $comment->comment_parent,
        'content' => $comment->comment_content,
        'status' => self::getGcStatus($comment->comment_approved),
        'spam' => ($comment->comment_approved === 'spam' ? '1' : null),
        'author_username' => empty($comment->comment_author) ? null : $comment->comment_author,
        'author_email' => empty($comment->comment_author_email) ? null : $comment->comment_author_email,
        'author_ip' => empty($comment->comment_author_IP) ? null : $comment->comment_author_IP,
        'date' => $comment->comment_date_gmt,
        'page' => get_permalink($comment->comment_post_ID),
        'page_date' => $page_date,
        'identifier' => GcParamsService::getInstance()->graphcommentIdentifierGenerate($comment->comment_post_ID),
        'page_title' => get_the_title($comment->comment_post_ID),
        'uid' => $comment->comment_post_ID,
        'guid' => get_the_guid($comment->comment_post_ID)
      )));
    }, $comments);

    GcLogger::getLogger()->debug($fname.' - Nbr Comments Found: '. count($comments));

    if (!count($comments)) {
      GcLogger::getLogger()->debug($fname.' - No more comments');
      // Import finished
      $this->finishImport();
      return false;
    }

    $res = GcApiService::pushImportComments($this->getImportBatchNumber(), $comments);

    if ($res['error'] !== false) {
      if ($res['error'] !== true) {
        $this->setImportationError($res['error']); // if $res['error'] is not a boolean, it's a message
      }
      return false;
    }

    // Number comments added
    $this->addNbrCommentsImport(count($comments));

    return true;
  }
}
