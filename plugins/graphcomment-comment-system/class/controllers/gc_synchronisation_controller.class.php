<?php

require_once('gc_abstract_controller.class.php');

/**
 * @controller SynchronisationController
 *
 * Handle the synchronisation tab.
 * Set up a wp cron to synchronize GraphComment comments to WordPress database
 */

if (!class_exists('GcSynchronisationController')) {
  class GcSynchronisationController extends GcController {

    public function handleOptionForm() {
      GcLogger::getLogger()->debug('GcSynchronisationController::handleOptionForm()');

      if (!isset($this->post['gc_sync_comments'])) {
        GcLogger::getLogger()->error('GcSynchronisationController::handleOptionForm() - gc_sync_comments params not sent, redirect to \'settings\' page');

        // Param not sent, nothing to do
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      if (isset($this->post['gc_sync_interval'])) {
        update_option('gc_sync_interval', $this->post['gc_sync_interval']);
      }

      // The user had disable the synchronization, update the option
      if ($this->post['gc_sync_comments'] === 'false') {
        GcLogger::getLogger()->debug('GcSynchronisationController::handleOptionForm() - Action: Disable the sync');

        // Disable the synchronization
        update_option('gc_sync_comments', 'false');
        delete_option('gc_sync_last_success');
        delete_option('gc-sync-error');
        wp_clear_scheduled_hook('graphcomment_cron_task_sync_comments_action');
        // Print message
        update_option('gc-msg', json_encode(array('type' => 'warning', 'content' => __('Sync Deactivated', 'graphcomment-comment-system'), 'active_tab' => 'synchronization')));
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      if ($this->post['gc_sync_comments'] !== 'true') {
        GcLogger::getLogger()->debug('GcSynchronisationController::handleOptionForm() - params gc_sync_comments !== \'true\', redirect to settings page');

        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      $gc_public_key = GcParamsService::getInstance()->graphcommentGetWebsite();
      if (is_null($gc_public_key)) {
        GcLogger::getLogger()->error('GcSynchronisationController::handleOptionForm() - No gc_public_key');


        /*
        ** The user hasn't set the public key yet
        */
        update_option('gc_sync_comments', 'false');
        delete_option('gc-sync-error');
        wp_clear_scheduled_hook('graphcomment_cron_task_sync_comments_action');
        update_option('gc-msg', json_encode(array('type' => 'danger', 'content' => 'No Public Key', 'active_tab' => 'synchronization')));
        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }

      GcLogger::getLogger()->debug('GcSynchronisationController::handleOptionForm() - Everything is ok');

      // Everything is good, we can save the option
      update_option('gc_sync_comments', 'true');
      delete_option('gc-sync-error');

      GcParamsService::getInstance()->fetchApiKeys();

      // Init CRON task to sync comments
      GcLogger::getLogger()->debug('GcSynchronisationController::handleOptionForm() - Init sync cron: ' . get_option('gc_sync_interval'));
      wp_clear_scheduled_hook('graphcomment_cron_task_sync_comments_action');
      wp_schedule_event(time(), get_option('gc_sync_interval'), 'graphcomment_cron_task_sync_comments_action');
      //do_action('graphcomment_cron_task_sync_comments_action');

      update_option('gc-msg', json_encode(array('type' => 'success', 'content' => __('Sync Activated', 'graphcomment-comment-system'), 'active_tab' => 'synchronization')));

      return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
    }
  }
}
