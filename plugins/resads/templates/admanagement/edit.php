<?php if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.'); ?>

<div class="wrap" id="resads-admanagement">
    <h2><?php if(isset($this->data['ad_name'])) : ?><?php printf(__('Edit Ad "%s"', RESADS_ADMIN_TEXTDOMAIN), $this->data['ad_name']); ?><?php endif; ?></h2>
    <?php if(file_exists(RESADS_TEMPLATE_DIR . '/admanagement/form.php')) : ?>
        <?php require_once RESADS_TEMPLATE_DIR . '/admanagement/form.php'; ?>
    <?php endif; ?>
</div>