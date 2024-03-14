<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<style>
    .wdr-major-release-message-content + p {
        display: none;
    }
</style>

<hr style="margin: 15px -12px; border: 1px solid orange;" />
<div class="wdr-major-release-message-content" style=" margin-bottom: 5px; max-width: 1000px; display: flex;">
    <div style="font-size: 17px; margin-right: 9px; margin-left: 2px;">
        <svg height="18" viewBox="0 0 48 48" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h48v48h-48z" fill="none"/><path d="M22 34h4v-12h-4v12zm2-30c-11.05 0-20 8.95-20 20s8.95 20 20 20 20-8.95 20-20-8.95-20-20-20zm0 36c-8.82 0-16-7.18-16-16s7.18-16 16-16 16 7.18 16 16-7.18 16-16 16zm-2-22h4v-4h-4v4z"/></svg>
    </div>
    <div>
        <div style="font-weight: 600;">
            <?php _e('Heads up, Please backup before upgrade!','woo-discount-rules'); ?>
        </div>
        <div style=" margin: 10px;">
            <?php _e('The latest update includes some substantial changes across different areas of the plugin. We highly recommend you backup your site before upgrading, and make sure you first update in a staging environment','woo-discount-rules'); ?>
        </div>
    </div>
</div>