<?php

require_once(__DIR__.'/../services/gc_logger.class.php');
require_once(__DIR__.'/../../lib/WP_Async_Task.php');

class GcImportComment extends WP_Async_Task {

  protected $action = 'import_comment';

  /**
   * Prepare data for the asynchronous request
   * @throws Exception If for any reason the request should not happen
   * @param array $data An array of data sent to the hook
   * @return array
   */
  protected function prepare_data($data) {
    $prepare_data = array(
      'gc_import_action' => $data
    );
    return $prepare_data;
  }

  /**
   * Run the async task action
   */
  protected function run_action() {
    do_action("wp_async_$this->action", $_POST);
  }
}
new GcImportComment;