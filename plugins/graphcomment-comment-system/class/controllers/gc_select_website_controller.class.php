<?php

require_once('gc_abstract_controller.class.php');

/**
 * @controller SelectWebsiteController
 *
 * Handle the selection of a website
 */

if (!class_exists('GcSelectWebsiteController')) {
  class GcSelectWebsiteController extends GcController {

    public function handleOptionForm() {
      GcLogger::getLogger()->debug('GcSelectWebsiteController::handleOptionForm()');

      // The user has some website, but didn't choose his
      if (!GcParamsService::getInstance()->graphcommentIsWebsiteChoosen() && $_POST['gc-create-website'] === 'true') {
        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - User want to create a new website');

        update_option('gc_create_website', 'true');
        wp_redirect(admin_url('admin.php?page=graphcomment&debug=chooseWebsite'));
        exit ;
      }

      if (isset($this->post['gc_website_id'])) {
        $ret = null;
        if (($ret = GcParamsService::getInstance()->graphcommentSetWebsite($this->post['gc_website_id'])) !== true) {
          GcLogger::getLogger()->error('GcGeneralController::handleOptionForm() - Set website error, ret: '.$ret);

          update_option('gc-msg', json_encode(array('type' => 'danger', 'content' => $ret)));
        }

        GcLogger::getLogger()->debug('GcGeneralController::handleOptionForm() - Set website success: '.$this->post['gc_website_id']);

        update_option('gc-msg', json_encode(array('type' => 'success', 'content' => __('GraphComment Website Selected', 'graphcomment-comment-system') . ': <i>'.$this->post['gc_website_id'].'</i>', 'active_tab' => 'general')));

        return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
      }
    }
  }
}
