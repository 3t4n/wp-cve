<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://wployalty.net/
 * */
defined('ABSPATH') or die();
?>
<div id="wlpe-main">
    <div class="wlpe-main-header">
        <h1><?php echo esc_html(WLPE_PLUGIN_NAME); ?> </h1>
        <div><b><?php echo esc_html('v' . WLPE_PLUGIN_VERSION); ?></b></div>
    </div>
    <div class="wlpe-tabs">
        <a class="<?php echo (isset($current_view) && $current_view == "expire_points") ? 'nav-tab-active' : ''; ?>"
           href="<?php echo esc_url(admin_url('admin.php?' . http_build_query(array('page' => WLPE_PLUGIN_SLUG, 'view' => 'expire_points')))); ?>"
        ><i class="wlr wlrf-customers"></i><?php esc_html_e('Manage Points Expiry', 'wp-loyalty-rules') ?></a>
        <a class="<?php echo (isset($current_view) && $current_view == "settings") ? 'nav-tab-active' : ''; ?>"
           href="<?php echo esc_url(admin_url('admin.php?' . http_build_query(array('page' => WLPE_PLUGIN_SLUG, 'view' => 'settings')))) ?>"
        ><i class="wlr wlrf-settings"></i><?php esc_html_e('Settings', 'wp-loyalty-rules') ?></a>
    </div>
    <div>
        <?php echo apply_filters('wlpe_extra_content', (isset($extra) ? $extra : NULL)); ?>
        <?php echo isset($tab_content) ? $tab_content : NULL ?>
    </div>
</div>
