<?php
$plugin_name = DSAMM_PLUGIN_NAME;
$plugin_version = DSAMM_PLUGINPRO_VERSION;
$plugin_ver_type = DSAMM_PLUGIN_VERSION_TYPE;
?>

<div class="amm-main-table res-cl">
    <h2><?php esc_html_e('Quick info', 'advance-menu-manager'); ?></h2>
    <table class="table-outer">
        <tbody>
            <tr>
                <td class="fr-1"><?php esc_html_e('Product Type', 'advance-menu-manager'); ?></td>
                <td class="fr-2"><?php esc_html_e('WordPress Plugin', 'advance-menu-manager'); ?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php esc_html_e('Product Name', 'advance-menu-manager'); ?></td>
                <td class="fr-2"><?php esc_html_e($plugin_name, 'advance-menu-manager'); ?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php esc_html_e('Installed Version', 'advance-menu-manager'); ?></td>
                <td class="fr-2"><?php echo esc_html($plugin_ver_type); ?> <?php echo esc_html($plugin_version); ?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php esc_html_e('License & Terms of use', 'advance-menu-manager'); ?></td>
                <td class="fr-2"><a target="_blank"  href="<?php echo esc_url('https://www.thedotstore.com/terms-and-conditions/'); ?>"><?php esc_html_e('Click here', 'advance-menu-manager'); ?></a><?php esc_html_e(' to view license and terms of use.', 'advance-menu-manager'); ?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php esc_html_e('Help & Support', 'advance-menu-manager'); ?></td>
                <td class="fr-2">
                    <ul style="margin: 0px;">
                        <li><a target="_blank" href="<?php echo esc_url(site_url('wp-admin/admin.php?page=advance-menu-manager-pro&tab=menu_advance_manager_get_started_method')); ?>"><?php esc_html_e('Quick Start', 'advance-menu-manager'); ?></a></li>
                        <li><a target="_blank" href="<?php echo esc_url('https://www.thedotstore.com/support/'); ?>"><?php esc_html_e('Support Forum', 'advance-menu-manager'); ?></a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="fr-1"><?php esc_html_e('Localization', 'advance-menu-manager'); ?></td>
                <td class="fr-2"><?php esc_html_e('English, Spanish', 'advance-menu-manager'); ?></td>
            </tr>

        </tbody>
    </table>
</div>
<?php require_once(plugin_dir_path(__FILE__).'header/plugin-sidebar.php'); ?>
