<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/* Weaver Xtreme - admin Save/Restore
 *  __ added - 12/10/14
 * This will come after the Options form has been closed, and is used for non-SAPI options
 *
 */

function weaverx_ts_admin_saverestore4(): void
{

    $func = WEAVER_GET_OPTION;

    $saved = $func(apply_filters('weaverx_options', 'weaverx_settings_backup'), array());


    $style_date = '';
    if (!empty($saved)) {
        $style_date = $saved['style_date'];
    }

    if (!$style_date) {
        $style_date = esc_html__('No saved settings', 'weaver-xtreme' /*adm*/);
    }
    ?>

    <div class="atw-option-header"
         style="clear:both;"><?php esc_html_e('Save/Restore Theme Settings', 'weaver-xtreme' /*adm*/);
        weaverx_help_link('help.html#SaveRestore', esc_html__('Help on Save/Restore Themes', 'weaver-xtreme' /*adm*/)); ?></div>
    <p>
        <?php esc_html_e('Note: if you have Weaver Xtreme Plus installed, then options marked with &#9733;Plus will be included in saves and restores.', 'weaver-xtreme' /*adm*/); ?>
    </p>
    <div class="atw-option-subheader"><?php esc_html_e('Save/Restore Current Theme Settings using WordPress Database', 'weaver-xtreme' /*adm*/); ?></div>
    <?php echo wp_kses_post(__('<p>This option allows you to save and restore all current theme settings using your host\'s WordPress database. Your options will be preserved across Weaver Xtreme theme upgrades, as well when you change to different themes. There is only one saved backup available. You can also download your setting to your computer with the options below.</p>
<p>Note: This save option saves <strong>all</strong> settings, including those marked with &diams;.</p>', 'weaver-xtreme' /*adm*/)); ?>
    <form name="save_mysave_form" method="post">
        <input class="button-primary" type="submit" name="save_mytheme"
               value="<?php esc_html_e('Save Current Theme Settings', 'weaver-xtreme' /*adm*/); ?>"/>
        <strong><?php esc_html_e('Backup all current theme settings using the WordPress database.', 'weaver-xtreme' /*adm*/); ?></strong>
        <?php weaverx_nonce_field('save_mytheme'); ?>
        <br/><br/>
        <input class="button-primary" type="submit" name="restore_mytheme"
               value="<?php esc_html_e('Restore Settings', 'weaver-xtreme' /*adm*/); ?>"/>
        <strong><?php esc_html_e('Restore from saved settings.', 'weaver-xtreme' /*adm*/); ?></strong>
        <em><?php esc_html_e('Last save date:', 'weaver-xtreme' /*adm*/); ?><?php echo $style_date; ?></em>
        <?php
        weaverx_nonce_field('restore_mytheme');

        if (version_compare(WEAVERX_VERSION, '4.9.0', '<') && get_option('weaverx5_settings', false)) {
            ?>
            <hr>
            <input class="button-primary" type="submit" name="remove_v5_settings"
                   value="<?php esc_html_e('Delete V5 Beta Settings', 'weaver-xtreme' /*adm*/); ?>"/>
            <strong><?php esc_html_e('You have Weaver Xtreme V5 Beta settings. Click to remove them and associated style files.', 'weaver-xtreme' /*adm*/); ?></strong>
            <?php weaverx_nonce_field('remove_v5_settings'); ?>
            <hr>
            <?php
        }
        do_action('weaverxplus_admin', 'save_restore');
        ?>
    </form>

    <?php

    weaverx_saverestore();      // download/upload to computer

    do_action('weaverx_child_saverestore');    // allow additional save/restore in child

    do_action('weaverx_child_update');
    ?>
    <div class="atw-option-subheader"><?php esc_html_e('Reset Current Settings to Default', 'weaver-xtreme' /*adm*/); ?></div>
    <br/>
    <form name="resetweaverx_form" method="post"
          onSubmit="return confirm('<?php esc_html_e('Are you sure you want to reset all Weaver Xtreme settings? This will include the [Saved Current Settings using WordPress Database].', 'weaver-xtreme' /*adm*/); ?>');">
        <strong><?php esc_html_e('Click the Clear button to reset all Weaver Xtreme settings, including &diams;, &#9733;Plus, and Weaver Xtreme Plus shortcode settings, to the default values.', 'weaver-xtreme' /*adm*/); ?></strong><br>
        <em style="color:red;"><?php esc_html_e('Warning: You will lose all current settings, including settings from "Save Settings using the WordPress Database".', 'weaver-xtreme' /*adm*/); ?></em><br/>
        <?php esc_html_e('You should use the "Download Current Settings To Your Computer" option above to save a copy of your current settings before clearing!
If you have Weaver Xtreme Plus installed, you should also save shortcode settings from the Xtreme Plus Save/Restore tab.', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <input class="button-primary" type="submit" name="reset_weaverx"
               value="<?php esc_html_e('Clear All Weaver Xtreme Settings', 'weaver-xtreme' /*adm*/); ?>"/>&nbsp;&nbsp
        <?php
        esc_html_e('Note: after clearing, settings will be reset to the default subtheme. This is required by WordPress.org standards.', 'weaver-xtreme');
        weaverx_nonce_field('reset_weaverx'); ?>
    </form> <!-- resetweaverx_form -->
    <br/>
    <hr/>

    <?php

}

function weaverx_process_options_admin_standard($processed)
{
    if (weaverx_submitted('weaverx_clear_messages')) {
        return true;
    }
    if (weaverx_submitted('reset_weaverx')) {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have the capability to do that.', 'weaver-xtreme' /*adm*/));
        }
        // delete everything!
        weaverx_save_msg(__('All Weaver Xtreme settings have been reset to the defaults.', 'weaver-xtreme'));
        delete_option(apply_filters('weaverx_options', WEAVER_SETTINGS_NAME));
        global $weaverx_opts_cache;
        $weaverx_opts_cache = false;    // clear the cache
        weaverx_init_opts('reset_weaverx');
        set_theme_mod('_options_level', 0);
        delete_option(apply_filters('weaverx_options', 'weaverx_settings_backup'));

        do_action('weaverxplus_admin', 'reset_weaverxplus');

        update_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice', 0);     // reset the dismiss on the plugin loader


        return true;
    }

    if (weaverx_submitted('uploadtheme') && function_exists('weaverx_loadtheme')) {
        weaverx_loadtheme();

        return true;
    }

    return $processed;
}

function weaverx_saverestore(): void
{
    /* admin tab for saving and restoring theme */
    $weaverx_theme_dir = esc_url(weaverx_f_uploads_base_dir() . 'weaverx-theme/');
    $download_path = esc_url(weaverx_relative_url('includes/download.php'));
    $download_img_path = esc_url(weaverx_relative_url('assets/images/download.png'));
    $nonce = wp_create_nonce('weaverx_download');
    $a_pro = (function_exists('weaverxplus_plugin_installed')) ? '-plus' : '';

    ?>
    <h3 class="atw-option-subheader" style="color:blue;">
        <?php esc_html_e('Save/Restore Current Theme Settings using Your Computer', 'weaver-xtreme' /*adm*/); ?>
    </h3>
    <p>
        <?php esc_html_e('This option allows you to save and restore all current theme settings by uploading and downloading to your own computer.', 'weaver-xtreme' /*adm*/); ?>
    </p>

    <h3><?php esc_html_e('Download Current Settings To Your Computer', 'weaver-xtreme' /*adm*/); ?></h3>

    <a href="<?php echo $download_path . '?_wpnonce=' . $nonce; ?>"><img
                src="<?php echo esc_url($download_img_path); ?>" alt='download'/>
        &nbsp; <strong><?php esc_html_e('Download', 'weaver-xtreme' /*adm*/); ?></strong>&nbsp;</a> -
    <?php echo wp_kses_post(__('<strong>Save all</strong> current settings to file on your computer.
(Full settings backup, including those marked with &diams;.) <em>File:</em>', 'weaver-xtreme' /*adm*/)); ?>
    <strong>weaverx-backup-settings<?php echo $a_pro; ?>.wxb</strong>
    <br/>
    <br/>
    <a href="<?php echo $download_path . '?_wpnoncet=' . $nonce; ?>"><img
                src="<?php echo esc_url($download_img_path); ?>" alt='download'/>
        &nbsp;<strong><?php esc_html_e('Download', 'weaver-xtreme' /*adm*/); ?></strong></a>&nbsp; -
    <?php echo wp_kses_post(__('<strong><em>Save only theme related</em></strong> current settings to file on your computer. <em>File:</em>
<strong>weaverx-theme-settings<?php echo $a_pro;?>.wxt</strong>', 'weaver-xtreme' /*adm*/)); ?>
    <?php
    if (function_exists('weaverxplus_plugin_installed')) {
        echo '<p>' .
            wp_kses_post(__('Note: Downloaded settings include <em>Weaver Xtreme Plus</em> settings.
Setting files from Weaver Xtreme Plus can be uploaded to the Free Weaver Xtreme version, but will not be used or saved by the free version. If you get error messages while downloading or uploading (a very rare host related issue), try the Customizer save/restore options instead.', 'weaver-xtreme' /*adm*/))
            . '</p>';
    }
    ?>

    <form enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
        <table>
            <tr>
                <td>
                    <h3><?php esc_html_e('Upload settings from file saved on your computer', 'weaver-xtreme' /*adm*/); ?></h3>
                </td>
            </tr>
            <?php
            if (weaverx_ts_allow_file_read()) {
                ?>

                <tr>
                    <td><?php esc_html_e('Select theme/backup file to upload:', 'weaver-xtreme' /*adm*/); ?>
                        <span style="border:1px solid black;padding:2px;"><input name="uploaded" type="file"/></span>
                        <input type="hidden" name="uploadit"
                               value="yes"/>&nbsp;<?php esc_html_e('(Restores settings in file to current settings.)', 'weaver-xtreme' /*adm*/); ?>
                    </td>
                </tr>

                <tr>
                    <td><span class='submit'>
		<input class="button-primary" name="uploadtheme" type="submit"
               value="<?php esc_html_e('Upload theme/backup', 'weaver-xtreme' /*adm*/); ?>"/></span>
                        &nbsp;<small><?php echo wp_kses_post(__('<strong>Upload and Restore</strong> a theme/backup from file on your computer. Will become current settings.', 'weaver-xtreme' /*adm*/)); ?>
                        </small></td>
                </tr>

                <tr>
                    <td>
                        <?php if (!function_exists('weaverxplus_plugin_installed')) {
                            echo '<small>' .
                                wp_kses_post(__('Note: Any Weaver Xtreme Plus settings will <em>not</em> be restored for Weaver Xtreme Free version.', 'weaver-xtreme' /*adm*/)) . '</small>';
                        }
                        ?>&nbsp;
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td>
                        <span style="font-weight: bold; color:red;">File Upload Access Restriction: </span>
                        Sorry, you must be a Multi-Site Super Admin or have the <em>install_plugins</em> capability set
                        for your account by a Super Admin to read settings files. (e.g., with the <em>User Role Editor</em>
                        plugin.)</strong>

                    </td>
                </tr>
                <?php
            }
            ?>


        </table>
        <?php weaverx_nonce_field('uploadtheme'); ?>
    </form>

    <h3 class="atw-option-header" style="color:blue;">
        <?php esc_html_e('Save/Restore Current Settings in files on your site\'s host file system. (&#9733;Plus)', 'weaver-xtreme' /*adm*/); ?>
    </h3>
    <p>
        <?php esc_html_e('This option allows you to save and restore current subtheme settings on the file system of your site\'s host.', 'weaver-xtreme' /*adm*/); echo '</br>';
        esc_html_e('This option is NOT available in the Customizer interface.', 'weaver-xtreme' /*adm*/)
        ?>
    </p>
    <p>
    <?php
    if ( !weaverx_ts_allow_file_read()) {
        echo '<span style="font-weight: bold; color:red;">File Access Restriction for Weaver Xtreme Plus: </span>
        Sorry, you must be a Multi-Site Super Admin or have the <em>install_plugins</em> capability set
        for your account by a Super Admin. (e.g., with the <em>User Role Editor</em> plugin.) to access Plus Save/Restore options.';
    } else {
        if (!function_exists('weaverxplus_plugin_installed') || !version_compare(WEAVER_XPLUS_VERSION, '3.1', '>=')) {
            echo '<strong>';
            _e('This option requires that you have installed Weaver Xtreme Plus version greater or equal to ', 'weaver-xtreme');
            echo '3.1</strong>';
        } else {
            do_action('weaverxplus_admin', 'save_restore_files');
        }
    }
    echo '</p>';
}

