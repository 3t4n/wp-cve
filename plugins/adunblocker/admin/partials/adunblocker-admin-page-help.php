<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://digitalapps.com
 * @since      1.0.0
 *
 * @package    Adblock Detect
 * @subpackage Adblock Detect/admin/partials
 */

?>
<div class="wrap">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content" class="daabd-admin-body">
                <h1><?php _ex( 'Support', 'Get help from the community', 'adunblocker' ); ?></h1>

                <div class="notice notice-success is-dismissible"> 
                    <p><strong>Adblockers must be disabled for the Help UI to work correctly.</strong></p>
                </div>

                <p><?php _e( 'As this is a free plugin, we do not provide prompt support.', 'adunblocker' ); ?></p>

                <p><?php printf( __( 'You may ask the WordPress community for help by posting to the <a href="%s">WordPress.org support forum</a>. Response time can range from a few days to a few weeks and will likely be from a non-developer.', 'adunblocker' ), 'http://wordpress.org/support/plugin/adunblocker' ); ?></p>

                <p class="upgrade-to-pro"><?php printf( __( 'If you want a <strong>timely response via email from a developer</strong> who works on this plugin, <a href="%s">upgrade to AdUnblocker Pro</a> and send us an email.', 'adunblocker' ), 'https://digitalapps.com/adunblocker-pro/?utm_source=insideplugin&utm_medium=web&utm_content=help-tab&utm_campaign=freeplugin' ); ?></p>

                <p><?php printf( __( 'If you\'ve found a bug, please <a href="%s">submit an issue at Github</a>.', 'adunblocker' ), 'https://github.com/DigitalApps/adunblocker/issues' ); ?></p>

                <h3>Diagnostic Info & Error Log</h3>
                <textarea class="debug-log-textarea" autocomplete="off" readonly></textarea>
                <a href="<?php echo network_admin_url( 'admin.php?page=' . $this->plugin_name . '-help' . '&nonce=' . wp_create_nonce( 'daabd-download-log' ) . '&daabd-download-log=1' ); ?>" class="button"><?php _ex( 'Download', 'Download to your computer', 'adunblocker' ); ?></a>
                <a class="button clear-log js-action-link"><?php _e( 'Clear Error Log', 'adunblocker' ); ?></a>

            </div>
            <?php $this->page_sidebar(); ?>
        <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>