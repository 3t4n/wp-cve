<?php

require_once('gc_abstract_controller.class.php');

/**
 * @controller GeneralController
 *
 * Handle the activation tab
 */

if (!class_exists('GcGeneralController')) {
  class GcGeneralController extends GcController {

    public function handleOptionForm() {
      GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm()');

      // Print some messages with these cases
      $done = false;

      if ($this->post['gc_activated'] === 'true') {
        GcParamsService::getInstance()->graphcommentActivate();
        update_option('gc-msg', json_encode(array('type' => 'success', 'content' => __('GraphComment Activated', 'graphcomment-comment-system'), 'active_tab' => 'general')));
        $done = true;
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Activate');
      }

      if ($this->post['gc_activated_type'] === 'all') {
        GcParamsService::getInstance()->graphcommentActivateAll();
        update_option('gc-msg', json_encode(array('type' => 'success', 'content' => __('GraphComment Activated All', 'graphcomment-comment-system'), 'active_tab' => 'general')));
        $done = true;
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Activate All');
      }
      else if ($this->post['gc_activated_type'] === 'from') {
        GcParamsService::getInstance()->graphcommentActivateFrom($this->post['gc_activated_from']);
        update_option('gc-msg', json_encode(array('type' => 'success', 'content' => (__('GraphComment Activated From', 'graphcomment-comment-system') . ' ' . $this->post['gc_activated_from'] . '.'), 'active_tab' => 'general')));
        $done = true;
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Activate From ('.$this->post['gc_activated_from'].')');
      }

      if (!$done) {
        GcParamsService::getInstance()->graphcommentDeactivate();
        update_option('gc-msg', json_encode(array('type' => 'warning', 'content' => __('GraphComment Deactivated', 'graphcomment-comment-system'), 'active_tab' => 'general')));
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Deactivate');
      }

      if (!isset($this->post['gc_activated'])) {
        GcParamsService::getInstance()->graphcommentDeactivate();

        // Disable the synchronization
        update_option('gc_sync_comments', 'false');
        delete_option('gc-sync-error');
        wp_clear_scheduled_hook('graphcomment_cron_task_sync_comments_action');
      }

      if (!isset($this->post['gc_seo_activated']) || $this->post['gc_seo_activated'] != true) {
        GcParamsService::getInstance()->graphcommentUpdateSEOFriendly(false);
      } else {
        GcParamsService::getInstance()->graphcommentUpdateSEOFriendly(true);
      }

      if (!isset($this->post['gc_sso_activated']) || $this->post['gc_sso_activated'] != true) {
        GcParamsService::getInstance()->graphcommentUpdateSSO(false);
      } else {
        GcParamsService::getInstance()->graphcommentUpdateSSO(true);
      }

      if (!isset($this->post['gc_overlay_activated']) || $this->post['gc_overlay_activated'] != true) {
        GcParamsService::getInstance()->graphcommentUpdateOverlay(false, array());
      } else {
        GcParamsService::getInstance()->graphcommentUpdateOverlay(true, array(
          'overlay_visible' => $this->post['gc_overlay_visible'],
          'overlay_bubble' => $this->post['gc_overlay_bubble'],
          'overlay_width' => $this->post['gc_overlay_width'],
          'overlay_fixed_header_height' => $this->post['gc_overlay_fixed_header_height'],
          'overlay_button_label' => $this->post['gc_overlay_button_label'],
          'overlay_button_color' => $this->post['gc_overlay_button_color'],
          'overlay_button_background' => $this->post['gc_overlay_button_background'],
        ));
      }

      if (!isset($this->post['gc_readonly_activated']) || $this->post['gc_readonly_activated'] != true) {
        GcParamsService::getInstance()->graphcommentUpdateReadonly(false, array());
      } else {
        GcParamsService::getInstance()->graphcommentUpdateReadonly(true, array(
          'readonly_who' => $this->post['gc_readonly_who'],
          'readonly_roles' => $this->post['gc_readonly_roles'],
        ));
      }

      // In every situation, redirect to this page
      return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));

    }
  }
}
