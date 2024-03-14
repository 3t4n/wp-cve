<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="lsow-banner-wrap">

    <div id="lsow-banner" class="lsow-banner-sticky">
        <h2><span><?php echo __('Livemesh SiteOrigin Widgets', 'livemesh-so-widgets'); ?></span><?php echo __('Plugin Settings', 'livemesh-so-widgets') ?></h2>
        <div id="lsow-buttons-wrap">
            <a class="lsow-button" data-action="lsow_save_settings" id="lsow_settings_save"><i
                    class="dashicons dashicons-yes"></i><?php echo __('Save Settings', 'livemesh-so-widgets') ?></a>
            <a class="lsow-button reset" data-action="lsow_reset_settings" id="lsow_settings_reset"><i
                    class="dashicons dashicons-update"></i><?php echo __('Reset', 'livemesh-so-widgets') ?></a>
        </div>
    </div>

</div>