<?php

class Element_Ready_Notice
{

  private static $instance = null;
  private $notice_url = 'https://plugins.quomodosoft.com/templates/wp-json/quomodo-notice/v1/remote?type=quomodo-notice-element-ready';
  // The constructor is private
  // to prevent initiation with outer code.
  private function __construct()
  {

    add_action('admin_notices', [$this, 'add_admin_remote_notice']);
  }

  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new Element_Ready_Notice();
    }

    return self::$instance;
  }

  public function add_admin_remote_notice()
  {

    $data = wp_remote_retrieve_body(wp_remote_get(esc_url_raw($this->notice_url)));

    $_data = json_decode($data, true);

    if (!isset($_data['show'])) {
      return;
    }
    if ($_data['show'] == false) {
      return;
    }

    if (is_wp_error($_data)) {
      return false;
    }

    if ($_data['msg'] == '""') {
      return;
    }

    require_once(__DIR__ . '/views/notice.php');

  } // end method

}

Element_Ready_Notice::getInstance();