<?php
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

$plugin_name = MPPC_PLUGIN_NAME;
$plugin_version = MPPC_PLUGIN_VERSION;

require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php');
?>

<div class="mmqw-section-left">
    <div class="mmqw-main-table res-cl">
        <h2><?php esc_html_e('Quick info', 'mass-pages-posts-creator'); ?></h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <td class="fr-1"><?php esc_html_e('Product Type', 'mass-pages-posts-creator'); ?></td>
                    <td class="fr-2"><?php esc_html_e('WordPress Plugin', 'mass-pages-posts-creator'); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e('Product Name', 'mass-pages-posts-creator'); ?></td>
                    <td class="fr-2"><?php esc_html_e($plugin_name, 'mass-pages-posts-creator'); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e('Installed Version', 'mass-pages-posts-creator'); ?></td>
                    <td class="fr-2"><?php esc_html_e( MPPC_PLUGIN_VERSION_LABEL, 'mass-pages-posts-creator' ); ?> <?php echo esc_html_e($plugin_version, 'mass-pages-posts-creator'); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e('License & Terms of use', 'mass-pages-posts-creator'); ?></td>
                    <td class="fr-2"><a target="_blank"  href="<?php echo esc_url('www.thedotstore.com/terms-and-conditions'); ?>"><?php esc_html_e('Click here', 'mass-pages-posts-creator'); ?></a><?php esc_html_e(' to view license and terms of use.', 'mass-pages-posts-creator'); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e('Help & Support', 'mass-pages-posts-creator'); ?></td>
                    <td class="fr-2">
                        <ul>
                            <li><a href="<?php echo esc_url(add_query_arg(array('page' => 'mppc-get-started'), admin_url('admin.php'))); ?>"><?php esc_html_e('Quick Start', 'mass-pages-posts-creator'); ?></a></li>
                            <li><a target="_blank" href="<?php echo esc_url('https://docs.thedotstore.com/article/272-introduction-of-mass-pages-posts-creator-for-wordpress'); ?>"><?php esc_html_e('Guide Documentation', 'mass-pages-posts-creator');
                            ?></a></li>
                            <li><a target="_blank" href="<?php echo esc_url('www.thedotstore.com/support'); ?>"><?php esc_html_e('Support Forum', 'mass-pages-posts-creator'); ?></a></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e('Localization', 'mass-pages-posts-creator'); ?></td>
                    <td class="fr-2"><?php esc_html_e('German, French, Polish, Spanish', 'mass-pages-posts-creator'); ?></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
<?php
require_once(plugin_dir_path( __FILE__ ).'header/plugin-sidebar.php');