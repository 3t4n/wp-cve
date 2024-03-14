<?php

if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="notice notice-success is-dismissible shop-ready-admin-notice-remote">
    <div class="notice-content">
        <?php 
            echo wp_kses_post(base64_decode($_data['msg']));
        ?>
    </div>
    <button type="button" class="notice-dismiss">
        <span
            class="screen-reader-text"><?php echo esc_html__('Dismiss this notice.','shopready-elementor-addon'); ?></span>
    </button>
</div>