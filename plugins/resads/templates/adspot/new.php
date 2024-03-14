<?php if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.'); ?>

<div class="wrap" id="resads-adspot">
    <h2><?php _e('Add New AdSpot', RESADS_ADMIN_TEXTDOMAIN); ?></h2>
    <?php if(file_exists(RESADS_TEMPLATE_DIR . '/adspot/form.php')) : ?>
    <?php require_once RESADS_TEMPLATE_DIR . '/adspot/form.php'; ?>
    <?php endif; ?>
</div>