<?php

require_once(__DIR__.'/../services/gc_params_service.class.php');

function header_template() {
?>

<div class="gc-header">
  <div class="gc-logo">
    <img src="<?php echo plugins_url('../../theme/images/graphcomment-logo.png', __FILE__); ?>">
    <h3>GraphComment <?php echo constant('GRAPHCOMMENT_VERSION'); ?></h3>
    <p><?php _e('header graphcomment description', 'graphcomment-comment-system'); ?></p>
  </div>
  <div>
    <div class="gc-user" style="<?php echo GcParamsService::getInstance()->getUserField('_id') ? '' : 'display: none'; ?>">
      <img src="<?php echo GcParamsService::getInstance()->getUserField('picture'); ?>" alt="<?php echo GcParamsService::getInstance()->getUserField('username'); ?>" />
      <span class="gc-user-username">
        <?php _e('Hello Message', 'graphcomment-comment-system'); ?>
        <b><?php echo GcParamsService::getInstance()->getUserField('username'); ?></b>
      </span>
      <span class="gc-user-disconnect">
        <button id="graphcomment-disconnect-button" class="gc_button_link"><?php _e('Logout Message', 'graphcomment-comment-system'); ?></button>
      </span>
    </div>
  </div>
</div>

<?php
}
