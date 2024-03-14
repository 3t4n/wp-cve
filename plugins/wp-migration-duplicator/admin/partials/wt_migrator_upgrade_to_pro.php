<?php
if (!defined('ABSPATH')) {
    exit;
}
$image_path = (isset($view_params['image_path']) ? $view_params['image_path'] : '');
$pro_width = (empty($_GET['page']) || $_GET['page'] == 'wp-migration-duplicator-settings') ? 'width:94.5%;float:left' : 'width:93.5%;';
?>
<style type="text/css">
    .wt_wt_mgdp_to_pro{ margin-top: 50px;padding:30px; background: #fcffff;border-radius: 15px; }
    .wt_wt_mgdp_to_pro table{padding-right: 15px;}
    .wt_wt_mgdp_to_pro_head{ font-size:26px; line-height:46px; font-weight:bold; text-align:center; }
    .wt_wt_mgdp_to_pro_head img{ float:left; border-radius:5px; margin-right:10px; }
    /*.wt_wt_mgdp_to_pro table{margin-top:10px;}*/
    /*.wt_wt_mgdp_to_pro table td{vertical-align:top;}*/
    .wt_wt_mgdp_pro_features li{ padding:5px 0px; font-weight:500; float:left; }
    .wt_wt_mgdp_pro_features li b{ font-weight:900;}
    .wt_wt_mgdp_pro_features .wt_sc_icon_box{ float:left; width:30px; height:20px;}
    .wt_wt_mgdp_pro_features .dashicons{ background:#fff; color:#6ABE45; border-radius:20px; margin-right:5px; }
    .wt-wt_mgdp-upgrade-to-pro-btn{ color:#fff; display:inline-block; text-transform:uppercase; text-decoration:none; text-align:center; font-size:13px; font-weight:bold; line-height:38px; padding:4px 15px; background:linear-gradient(90.67deg, #182BB4 -34.86%, #37B0FF 115.74%);box-shadow: 0px 26px 36px rgba(25, 44, 180, 0.26); border-radius:5px;width: 195px;margin-top: 15px}
    .wt-wt_mgdp-upgrade-to-pro-btn  img{ border:none; margin-right:5px;margin-top: 10px; }
    .wt-wt_mgdp-upgrade-to-pro-btn:hover{ color:#fff; }
    .wt-wt_mgdp-upgrade-to-pro-btn:active, .wt-wt_mgdp-upgrade-to-pro-btn:focus{ color:#fff; }
</style>
<div class="wt_wt_mgdp_to_pro" style="<?php echo esc_attr($pro_width); ?> ">
    <table>
        <tr>
            <td style="width: 10%;padding-left: 15px;">
                <img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/pro.svg') ?>" style="width: 100px;">
            </td> 
            <td class="wt_wt_mgdp_to_pro_head" style="width: 26%;text-align: left;padding-left: 20px">
                <?php _e('WordPress Backup and Migration Pro', 'wp-migration-duplicator'); ?>
            </td> 
            <td style="width: 22%">
                <ul class="wt_wt_mgdp_pro_features">
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('One-click backup and restore', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Multiple storage locations', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Url import supported', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Email notifications support', 'wp-migration-duplicator'); ?>
                    </li>
                </ul>
            </td> 
            <td style="width: 22%">
                <ul class="wt_wt_mgdp_pro_features">
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Multiple formats supported', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Auto delete backups', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Exclude specific DB tables', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <span class="dashicons dashicons-yes-alt"></span>
                        </span>
                        <b><?php _e('Premium support', 'wp-migration-duplicator'); ?>
                    </li>
                </ul>
            </td>
            <td style="width: 20%">
                <ul>
                    <li>
                        <span class="wt_sc_icon_box">
                            <img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/banner1.svg') ?>" style="width: 18px;margin-top: 10px">
                        </span>
                        <b><?php _e('30 Day Money Back Guarantee', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <span class="wt_sc_icon_box">
                            <img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/banner.svg') ?>" style="width: 18px;margin-top: 5px">
                        </span>
                        <b><?php _e('Fast and Superior Support', 'wp-migration-duplicator'); ?>
                    </li>
                    <li>
                        <a href="https://www.webtoffee.com/product/wordpress-backup-and-migration/?utm_source=free_plugin_sidebar&utm_medium=Migration_free&utm_campaign=WordPress_Backup&utm_content=<?php echo esc_attr(WP_MIGRATION_DUPLICATOR_VERSION);?>" class="wt-wt_mgdp-upgrade-to-pro-btn" target="_blank">
                            <img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/pro_icon.svg') ?>"><?php _e('UPGRADE TO PREMIUM', 'wp-migration-duplicator'); ?>
                        </a>  </li>
                </ul>
            </td>
        </tr>
    </table>

</div>