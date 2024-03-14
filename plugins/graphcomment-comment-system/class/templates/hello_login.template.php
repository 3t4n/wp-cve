<?php

require_once(__DIR__.'/../services/gc_params_service.class.php');

function hello_login_template() {
 ?>

  <div class="row gc-hello-tag">
    <div class="pull-right">
      <span><?php _e('Hello Message', 'graphcomment-comment-system'); ?> <b><?php echo GcParamsService::getInstance()->getUserField('username'); ?></b></span>
      <img src="<?php echo GcParamsService::getInstance()->getUserField('picture'); ?>" alt="<?php echo GcParamsService::getInstance()->getUserField('username'); ?>" />
      <div class="gc-disconnect gc_sub_action">
        <label><?php _e('Not You Message', 'graphcomment-comment-system'); ?> </label>
        <button id="graphcomment-disconnect-button" class="gc_button_link"> <?php _e('Change Profile Message', 'graphcomment-comment-system'); ?></button>
      </div>
    </div>
  </div>

 <?php
}