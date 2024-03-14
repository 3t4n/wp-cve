<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="bea-banner-wrap">

    <div id="bea-banner" class="bea-banner-sticky">
        <h2><span><?php echo esc_html(__('Better Addons for Elementor', 'better-el-addons')); ?></span><?php echo esc_html(__('Plugin Settings', 'better-el-addons')); ?></h2>
        <div id="bea-buttons-wrap">
            <a class="bea-button" data-action="bea_save_settings" id="bea_settings_save"><i
                    class="dashicons dashicons-yes"></i><?php echo esc_html(__('Save Settings', 'better-el-addons')); ?></a>
            <a class="bea-button reset" data-action="bea_reset_settings" id="bea_settings_reset"><i
                    class="dashicons dashicons-update"></i><?php echo esc_html(__('Reset', 'better-el-addons')); ?></a>
        </div>
    </div>

</div>
