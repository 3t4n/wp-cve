<?php

class GcCommentPairingDao
{
    private static $table_name = 'gc_comment_pairing';
    private $known_ids = null;

    public static function getTableName() {
      global $wpdb;
      return $wpdb->prefix . self::$table_name;
    }

    public static function createPairingTable() {
      global $wpdb;
      /*
       * Create table if not exists GC_ID <=> WP_ID
       * TODO put an index on wp_comment_id or put it in primary key. But be careful with the old release.
       */
      $charset_collate = $wpdb->get_charset_collate();
      $sql = 'CREATE TABLE IF NOT EXISTS ' . self::getTableName() . ' (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          wp_comment_id mediumint(9) NOT NULL,
        gc_comment_id tinytext NOT NULL,
        UNIQUE KEY id (id)
        ) ' . $charset_collate. ';';
      $ret = $wpdb->query($sql);

      if ($ret === false) {
        $log = 'CommentPairingDao::createPairingTable() - Error SQL: ('.$sql.') - last_error: ('.$wpdb->last_error.')';
        GcLogger::getLogger()->error($log);

        echo $wpdb->last_error;
        return false;
      }
      return true;
    }

    private function addKnownIds($new_known_ids) {
      if ($new_known_ids !== null) {
        if ($this->known_ids === null) {
          $this->known_ids = $new_known_ids;
        }
        else {
          $this->known_ids = array_merge($this->known_ids, $new_known_ids);
        }
      }
    }

    public function findIds($ids)
    {
      global $wpdb;

      $request = 'SELECT `wp_comment_id`, `gc_comment_id` FROM ' . self::getTableName() . ' ';
      $first_done = false;
      if (count($ids) > 0) {
          $request .= ' WHERE ( ';
          foreach ($ids as $key => $id) {
            if ($id == NULL) continue;
            if ($first_done) {
                $request .= ' OR ';
            }
            $request .= ' `gc_comment_id` LIKE \'' . $id . '\' ';
            $first_done = true;
          }
          $request .= ' ) ';
      }
      $new_known_ids = $wpdb->get_results($request);

      if ($new_known_ids === false) {
        GcLogger::getLogger()->error('GcCommentPairingDao::findIds() - Error SQL: ('.$request.') - last_error: ('.$wpdb->last_error.')');

        return array();
      }

      // This is to transform the stdObject to array type
      $new_known_ids = json_decode(json_encode($new_known_ids), true);
      $this->addKnownIds($new_known_ids);

      return $new_known_ids;
    }

    public function findWordpressId($GraphCommentId)
    {
      $pairsFound = array_filter($this->known_ids, function($p) use ($GraphCommentId) {
        if ($p['gc_comment_id'] === $GraphCommentId) {
          return $p;
        }
      });

      /*
      ** The ID of the parent was found in the known paring table, return false
      */
      if (count($pairsFound) === 0) {
        return false;
      }

      $res = array_shift($pairsFound);

      return $res['wp_comment_id'];
    }

    public function insertKnowPairIds($wordpress_id, $graphcomment_id) {
      global $wpdb;

      /*
      ** Inserting the new pair WordPress_ID <=> GraphComment_ID
      */
      $sql_req = 'INSERT INTO ' . self::getTableName() . ' (wp_comment_id, gc_comment_id) VALUES (%d, %s);';
      $sql = $wpdb->prepare($sql_req, $wordpress_id, $graphcomment_id);
      $ret = $wpdb->query($sql);

      if ($ret === false) {
        GcLogger::getLogger()->error('GcCommentPairingDao::insertKnowPairIds() - Error SQL: ('.$sql.') - last_error: ('.$wpdb->last_error.')');

        return false;
      }

      // Save the couple WP_ID <=> GC_ID in the $ids_parent local array
      $this->addKnownIds(array(array('wp_comment_id' => $wordpress_id, 'gc_comment_id' => $graphcomment_id)));
      return true;
    }
}
