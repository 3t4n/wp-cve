<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="ms-main-up-wrapper">
<?php
if (majesticsupport::$_config['offline'] == 2) {
        MJTC_message::MJTC_getMessage();
        include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
        <div class="ms-visitor-message-wrapper" >
            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/ms-support-icon.png'; ?>" />
            <span class="ms-visitor-message" >
                <?php echo wp_kses(majesticsupport::$_config['visitor_message'], MJTC_ALLOWED_TAGS)?>
            </span>
        </div>
<?php
} else { // System is offline
    MJTC_layout::MJTC_getSystemOffline();
}
?>
</div>
