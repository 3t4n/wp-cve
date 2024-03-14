<?php

require_once(__DIR__ . '/../services/gc_logger.class.php');
require_once(__DIR__ . '/../services/gc_import_comment.class.php');
require_once('gc_abstract_controller.class.php');
require_once(__DIR__ . '/../services/gc_import_service.class.php');

/**
 * @controller ImportController
 *
 * Handle the import tab.
 * Launch the import of old WordPress comments into GraphComment.
 *
 * The constructor must be called after the 'plugins_loaded' wp event.
 */

if (!class_exists('GcImportController')) {
  class GcImportController extends GcController {

    private $action = 'import_comment';

    public function handleOptionForm() {
      GcLogger::getLogger()->debug('GcImportController::handleOptionForm() (max execution time: ' . ini_get('max_execution_time') . ' seconds)');

      $gc_import_service = new GcImportService();
      $msg = null;

      $gc_public_key = GcParamsService::getInstance()->graphcommentGetWebsite();
      if (is_null($gc_public_key)) {
        GcLogger::getLogger()->error('GcGeneralController::handleOptionForm() - No gc_public_key');

        /*
        ** The user hasn't set the public key yet
        */
        update_option('gc-msg', json_encode(array('type' => 'danger', 'content' => 'No Public Key', 'active_tab' => 'importation')));
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      // The public key is set

      /*
       * The import is pending and the user want to stop it
       */
      if (isset($this->post['gc-import-stop']) && $this->post['gc-import-stop'] == 'true') {
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Action: Stop import');

        if (($msg = $gc_import_service->stopImportation()) !== true) {
          GcLogger::getLogger()->error('GcGeneralController::handleOptionForm() - Stop import fail');

          update_option('gc-msg', json_encode(array('active_tab' => 'importation')));
          $gc_import_service->setImportErrorMsg($msg);
        } else {
          GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Stop import success');

          update_option('gc-msg', json_encode(array('type' => 'warning', 'content' => __('Import Cancel Success', 'graphcomment-comment-system'), 'active_tab' => 'importation')));
        }
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }


      /*
       * The user restarted the importation
       * because an error happened
       */
      if (isset($this->post['gc-import-restart']) && $this->post['gc-import-restart'] == 'true') {
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Action: Restart import');

        if (($msg = $gc_import_service->restartImportationInit()) !== true) {
          GcLogger::getLogger()->error('GcGeneralController::handleOptionForm() - Restart import fail');

          update_option('gc-msg', json_encode(array('active_tab' => 'importation')));
          $gc_import_service->setImportErrorMsg($msg);
        }
        else {
          GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Stop import success');

          update_option('gc-msg', json_encode(array('type' => 'success', 'content' => __('Import Restart Success', 'graphcomment-comment-system'), 'active_tab' => 'importation')));

          // Launch the import_action bound asynchronously to the wp_async_import_action
          do_action($this->action, $this->post);
        }
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Init import');

      if (($msg = $gc_import_service->importInit()) !== true) {
        GcLogger::getLogger()->error('GcGeneralController::handleOptionForm() - Action: Restart import');
        update_option('gc-msg', json_encode(array('active_tab' => 'importation')));
        if ($msg !== 'skip') $gc_import_service->setImportationError($msg);
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      // Gonna put the user directly on the importation tab
      update_option('gc-msg', json_encode(array('active_tab' => 'importation')));

      // Launch the import_action bound asynchronously to the wp_async_import_action
      do_action($this->action, $this->post);

      return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
    }

    public static function registerAsyncAction() {
      function importComments()
      {
        $gc_import_service = new GcImportService();
        while ($gc_import_service->importNextComments()) { }
      }

      add_action('wp_async_import_comment', 'importComments');
    }
  }
}
