<?php
$uninstall = isset( $settings['uninstall'] ) ? sanitize_text_field( $settings['uninstall'] ) : 'no';
$debub = isset( $settings['debug'] ) ? sanitize_text_field( $settings['debug'] ) : 'no';
$block_bad_robots = isset( $settings['block']['block_bad_robots'] ) ? sanitize_text_field( $settings['block']['block_bad_robots'] ) : 'yes';
$robots_txt_enabled = isset( $settings['robots']['enabled'] ) ? sanitize_text_field( $settings['robots']['enabled'] ) : 'no';
$url_block_enabled = isset( $settings['block']['enabled'] ) ? sanitize_text_field( $settings['block']['enabled'] ) : 'no';
$rate_limit_enabled = isset( $settings['rate_limit']['enabled'] ) ? sanitize_text_field( $settings['rate_limit']['enabled'] ) : 'yes';
$log_404_enabled = isset( $settings['log_404']['enabled'] ) ? sanitize_text_field( $settings['log_404']['enabled'] ) : 'yes';
?>

<p class="notice notice-info">
    <span aria-hidden="true" class="dashicons dashicons-editor-help"></span>
	<?php esc_html_e( 'Not sure where to start?', 'booter' ); ?>
    <a href="#booter-help" aria-controls="booter-reject" class="js-booter-tab">
	    <?php esc_html_e( 'View the help page', 'booter' ); ?>
    </a>
</p>

<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row"><label for="booter-block-block_bad_robots"><?php esc_html_e( 'Block Bad Robots', 'booter' ); ?></label></th>
            <td>
                <booter-switch id="booter-block-block_bad_robots" name="booter_settings[block][block_bad_robots]" value="<?php echo $block_bad_robots; ?>" data-toggle-off=".js-bad-bots-tab"></booter-switch>
                <p class="description">
		            <?php esc_html_e( 'Block bots we identified as malicious, which are causing high server loads from very frequent page crawls, or are used as part of a vulnerability/security breach scans.', 'booter' ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="booter-robots-enabled"><?php esc_html_e( 'robots.txt Management', 'booter' ); ?></label></th>
            <td>
                <booter-switch id="booter-robots-enabled" name="booter_settings[robots][enabled]" value="<?php echo $robots_txt_enabled; ?>" data-toggle-off=".js-robots-tab"></booter-switch>
                <p class="description">
		            <?php esc_html_e( 'Allow Booter - Crawlers Manager to manage your robots.txt file. Existing file will be renamed as a backup, but Booter robots.txt file will be overwritten automatically when saving the settings.', 'booter' ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="booter-block-enabled"><?php esc_html_e( 'Reject Links', 'booter' ); ?></label></th>
            <td>
                <booter-switch id="booter-block-enabled" name="booter_settings[block][enabled]" value="<?php echo $url_block_enabled; ?>" data-toggle-off=".js-block-tab"></booter-switch>
                <p class="description">
		            <?php esc_html_e( 'Block access to predefined URLs, or cleanup old spam URLs by setting a corresponding HTTP status code.', 'booter' ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="booter-rate_limit-enabled">
                    <?php esc_html_e( 'Rate Limiting', 'booter' ); ?><br>
                    <span class="badge" style="background-color: #2873A9; margin: 0 0.25em;"><?php esc_html_e( 'Recommended', 'booter' ); ?></span>
                </label>
            </th>
            <td>
                <booter-switch id="booter-rate_limit-enabled" name="booter_settings[rate_limit][enabled]" value="<?php echo $rate_limit_enabled; ?>" data-toggle-off=".js-rate-tab"></booter-switch>
                <p class="description">
		            <?php esc_html_e( 'Throttle excessive access from bots, crawlers, and malicious users.', 'booter' ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="booter-log_404-enabled"><?php esc_html_e( '404 Logging', 'booter' ); ?></label></th>
            <td>
                <booter-switch id="booter-log_404-enabled" name="booter_settings[log_404][enabled]" value="<?php echo $log_404_enabled; ?>" data-toggle-off=".js-404-tab"></booter-switch>
                <p class="description">
		            <?php esc_html_e( 'Logging 404 errors allows you to detect bad or spam URLs search engines trying to crawl.', 'booter' ); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>

<toggle-panel>
    <template slot="title">
        <span class="dashicons dashicons-admin-settings" aria-hidden="true"></span>
        <?php esc_html_e( 'Advanced', 'booter' ); ?>
    </template>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label for="booter-uninstall"><?php esc_html_e( 'Uninstall', 'booter' ); ?></label></th>
            <td>
                <booter-switch id="booter-uninstall" name="booter_settings[uninstall]" size="small" type="warning" value="<?php echo $uninstall; ?>"></booter-switch>
                <p class="description">
					<?php esc_attr_e( 'Delete all settings when deactivating the plugin.', 'booter' ); ?>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="booter-debug-switch"><?php esc_html_e( 'Debug Mode', 'booter' ); ?></label></th>
            <td>
                <booter-switch id="booter-debug-switch" name="booter_settings[debug]" size="small" type="warning" value="<?php echo $debub; ?>"></booter-switch>
                <p class="description">
					<?php esc_attr_e( 'Log all block events with the rule which caused it and help you find rules causing false-positives.', 'booter' ); ?>
                </p>
            </td>
        </tr>
    </table>
</toggle-panel>


<?php submit_button(); ?>
