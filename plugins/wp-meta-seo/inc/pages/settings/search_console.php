<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
if (is_plugin_active(WPMSEO_ADDON_FILENAME)) {
    if (isset($_SESSION['_metaseo_settings_search_console']) && $_SESSION['_metaseo_settings_search_console']) {
        echo '<div class="save-settings-mess top_bar ju-notice-success"><strong>' . esc_html__('Setting saved successfully', 'wp-meta-seo') . '</strong>
        <button type="button" class="wpms-settings-dismiss notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        unset($_SESSION['_metaseo_settings_search_console']);
    }
    // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in 'wp-meta-seo-addon/inc/page/local_business.php' file
    echo $search_console_html;
}
