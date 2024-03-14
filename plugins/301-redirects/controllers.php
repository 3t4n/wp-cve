<?php
class Redirects
{
  function delete()
  {
    global $wpdb;

    $sql = "TRUNCATE TABLE {$wpdb->prefix}ts_redirects";
    $wpdb->query($sql);
  } // delete


  function edit($title, $section, $new_link, $old_link)
  {
    global $wpdb;
    $sql = $wpdb->prepare("INSERT INTO {$wpdb->prefix}ts_redirects (title, section, new_link, old_link) VALUES (%s, %s, %s, %s)", array($title, $section, $new_link, $old_link));
    $wpdb->query($sql);
  } // edit


  function getFields($id)
  {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ts_redirects WHERE id = %d", array($id));
    $result = $wpdb->query($sql);
    if ($result !== 0) {
      $fields = array();
      foreach ($wpdb->get_results($sql) as $row) {
        $fields['title'] = $row->title;
        $fields['section'] = $row->section;
        $fields['new_link'] = $row->new_link;
        $fields['old_link'] = $row->old_link;
      }

      return $fields;
    } else {
      return false;
    }
  } // getFields


  function createRedirectsTable()
  {
    global $wpdb;

    $sql = "CREATE TABLE {$wpdb->prefix}ts_redirects (id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,title TEXT,section TEXT, new_link TEXT, old_link TEXT)";
    $wpdb->query($sql);
  } // createRedirectsTable


  function checkForRedirectsTable()
  {
    global $wpdb;

    $sql = "SHOW TABLES LIKE 'ts_redirects'";
    $result = $wpdb->get_results($sql);
    if (sizeof($result) == 1) {
      $wpdb->query("RENAME TABLE ts_redirects TO {$wpdb->prefix}ts_redirects");
    }

    $sql = "SHOW TABLES LIKE '{$wpdb->prefix}ts_redirects'";
    $result = $wpdb->get_results($sql);
    if (sizeof($result) != 1) {
      $this->createRedirectsTable();
    }
  } // checkForRedirectsTable


  function getAll()
  {
    global $wpdb;

    $this->checkForRedirectsTable();

    $sql = "SELECT * FROM {$wpdb->prefix}ts_redirects ORDER by id ASC";
    $result = $wpdb->query($sql);
    if ($result !== 0) {

      $id_arr = array();
      foreach ($wpdb->get_results($sql) as $row) {
        $id_arr[] = $row->id;
      }

      return $id_arr;
    } else {
      return false;
    }
  } // getAll


  function remove($custom_id)
  {
    global $wpdb;

    $sql = $wpdb->prepare("DELETE FROM {$wpdb->prefix}ts_redirects WHERE id = %d", array($custom_id));
    $wpdb->query($sql);
  }
} // remove

$redirectsplugin = new Redirects();
$GLOBALS['redirectsplugins'] = $redirectsplugin;
